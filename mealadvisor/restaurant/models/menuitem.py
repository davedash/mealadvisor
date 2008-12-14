from django.db import models, transaction, connection
from django.conf import settings

from mealadvisor.common.models import Profile 
from mealadvisor.tools import *

from restaurant import Restaurant, TagManager
from location import Location
from utils import *
from markdown import markdown

class MenuItemManager(models.Manager):
    def with_ratings(self, user=None):

        items = self.all().select_related(depth=1)

        if user != None and user.is_authenticated() and len(items) > 0:

            r_id = items[0].restaurant_id

            sql = """
            SELECT r.menu_item_id, r.value
            FROM menuitem_rating r, menu_item m
            WHERE r.menu_item_id = m.id 
            AND restaurant_id = %s
            AND user_id = %s
            """

            cursor = connection.cursor()

            cursor.execute(sql, [r_id, user.get_profile().id])
            result_dict = {}

            for row in cursor.fetchall():
                result_dict[row[0]] = row[1]

                for item in items:
                    if item.id in result_dict:
                        item.current_rating = result_dict[item.id]

        return items

class MenuItem(models.Model):
    name           = models.CharField(max_length=765, blank=True)
    slug           = models.CharField(db_column='url', max_length=765, blank=True)
    version        = models.ForeignKey('MenuitemVersion', related_name="the_menuitem")
    restaurant     = models.ForeignKey(Restaurant)
    approved       = models.IntegerField(null=True, blank=True)
    average_rating = models.FloatField(null=True, blank=True)
    num_ratings    = models.IntegerField(null=True, blank=True)
    updated_at     = models.DateTimeField(auto_now=True)
    created_at     = models.DateTimeField(auto_now_add=True)
    objects        = MenuItemManager()
    current_rating = None

    def get_tags_from_user(self, profile):
        # given a profile return all the tags that said profile has for this particular item
        tags = MenuitemTag.objects.filter(menu_item = self, user = profile)
        return tags
    
    def get_popular_tags(self, max = 10):
        
        query = """
        SELECT `normalized_tag` AS tag, COUNT(`normalized_tag`) AS count
        FROM `menuitem_tag`
        WHERE `menu_item_id` = %s
        GROUP BY `normalized_tag`
        ORDER BY count DESC
        LIMIT %s
        """

        cursor  = connection.cursor()
        results = cursor.execute(query, [self.id, max])

        tags   = {}
        
        for row in cursor.fetchall():
            # tags[tag_name] = tag_count
            tags[row[0]] = row[1] 

        return tags
        
    def __getattr__(self, name):
        if name == 'description':
            return self.version.description

        if name == 'price':
            return self.version.price

        
        models.Model.__getattribute__(self, name)
    
    def __unicode__(self):
        return self.name
    
    def get_absolute_url(self):
        "http://prod.rbu.sf/frontend_dev.php/restaurant/hobees/menu/special-traditional-eggs-benedict"
        return "%s/menu/%s" % (self.restaurant.get_absolute_url(), self.slug)
    
    
    def get_rating_url(self):
        return self.get_absolute_url()+"/rate/"

    def get_words(self):
        """
        Get stemmed words that make up this entry
        """
        raw_text      = ' '.join([self.description]*settings.SEARCH_WEIGHT_BODY)
        name          = self.name.replace("'", '')
        raw_text      += ' '.join([name]*settings.SEARCH_WEIGHT_TITLE)

        raw_text = rePunctuation.sub(' ', raw_text)

        stemmed_words = stem_phrase(raw_text) + extract_numbers(raw_text)
        words         = list_count_values(stemmed_words)

        max = 1
        pop_tags = self.get_popular_tags(50)

        for tag, count in pop_tags.iteritems():
            if max < count:
                max = count

            stemmed_tag = stem(tag)

            if not stemmed_tag in words:
                words[stemmed_tag] = 0

            words[stemmed_tag] += math.ceil(count/max) * settings.SEARCH_WEIGHT_TAG

        return words
    def reindex(self):
        # Remove search_index entries for this restaurant:
        MenuitemSearchIndex.objects.filter(menuitem=self).delete()

        for word, weight in self.get_words().iteritems():
            MenuitemSearchIndex(menuitem=self, word=word, weight=weight).save()        
        
    class Meta:
        db_table = u'menu_item'
    
class MenuitemSearchIndex(models.Model):
    menuitem = models.ForeignKey(MenuItem, null=True, blank=True)
    word = models.CharField(max_length=765, blank=True)
    weight = models.IntegerField(null=True, blank=True)
    class Meta:
        db_table = u'menuitem_search_index'

class MenuitemVersion(models.Model):
    description      = models.TextField(blank=True)
    html_description = models.TextField(blank=True)
    location         = models.ForeignKey(Location, null=True, blank=True)
    menuitem         = models.ForeignKey(MenuItem)
    user             = models.ForeignKey(Profile, null=True, blank=True)
    price            = models.CharField(max_length=48, blank=True)
    created_at       = models.DateTimeField(auto_now_add=True)
    class Meta:
        db_table         = u'menuitem_version'

      
class MenuitemRating(models.Model):
    menu_item  = models.ForeignKey(MenuItem)
    user       = models.ForeignKey(Profile, null=True, blank=True)
    value      = models.IntegerField(null=True, blank=True)
    created_at = models.DateTimeField(auto_now_add=True)
    
    class Meta:
        db_table = u'menuitem_rating'
    
    def save(self, force_insert=False, force_update=False):
        try:
            super(MenuitemRating, self).save(force_insert, force_update)
            cursor  = connection.cursor()
            query   = "SELECT count(value), avg(value) FROM menuitem_rating WHERE menu_item_id = %s"
            results = cursor.execute(query, (self.menu_item.id,))

            for row in cursor.fetchall():
                self.menu_item.num_ratings    = row[0]
                self.menu_item.average_rating = row[1]
                self.menu_item.save()

        except:
            transaction.rollback()
        else:
            transaction.commit()
    


class MenuitemTag(models.Model):
    menu_item       = models.ForeignKey(MenuItem)
    user            = models.ForeignKey(Profile)
    tag             = models.CharField(max_length=300)
    normalized_tag  = models.CharField(max_length=300)
    created_at      = models.DateTimeField(auto_now_add=True)
    unique_together = ("user", "menuitem", "normalized_tag")

    objects        = TagManager()
    
    def __unicode__(self):
        return self.normalized_tag
    
    class Meta:
        db_table = u'restaurant_tag'
    
    def delete(self):
        super(MenuitemTag, self).delete()
        self.menu_item.reindex()
    
    def save(self, force_insert=False, force_update=False):
        if not self.normalized_tag:
            self.normalized_tag = normalize(tag)
    
        super(MenuitemTag, self).save(force_insert, force_update)
        self.menu_item.reindex()

    class Meta:
        db_table = u'menuitem_tag'


class MenuitemNote(models.Model):
    menu_item  = models.ForeignKey(MenuItem)
    profile    = models.ForeignKey(Profile, db_column='user_id')
    note       = models.TextField()
    html_note  = models.TextField()
    updated_at = models.DateTimeField(auto_now=True)
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        db_table = u'menuitem_note'

    def save(self, force_insert=False, force_update=False):
        self.html_note = markdown(self.note)
        super(MenuitemNote, self).save(force_insert, force_update)


    def author(self):
        return self.profile.user

