from markdown import markdown

from django.db import models, transaction, connection
from django.conf import settings
from django.template.defaultfilters import slugify

from mealadvisor.common.models import Profile
from mealadvisor.tools import *

from tags import TagManager
from utils import *

class RestaurantManager(models.Manager):
    
    def search(self, phrase, offset=0, max=10):
        # we want to stem the words AND extract any numbers
        words = stem_phrase(phrase) + extract_numbers(phrase)
        
        num_words = len(words)
        if num_words == 0:
            return []
        
        
        # mysql specifc
        # e.g. longhorn steakhouse
        # produces
        # SELECT DISTINCT restaurant_search_index.RESTAURANT_ID, COUNT(*) AS nb,
        # SUM(restaurant_search_index.WEIGHT) AS total_weight FROM
        # restaurant_search_index WHERE (restaurant_search_index.WORD LIKE 'longhorn'
        # OR restaurant_search_index.WORD LIKE 'steakhous') GROUP BY
        # restaurant_search_index.RESTAURANT_ID ORDER BY nb DESC, total_weight DESC
        # LIMIT 10
        
        query = """
        SELECT DISTINCT 
            `restaurant_search_index`.`restaurant_id`, 
            COUNT(*) AS nb,
            SUM(`restaurant_search_index`.`weight`) AS total_weight
        FROM 
            `restaurant_search_index`
        WHERE
            (%s)
        GROUP BY
            `restaurant_search_index`.`restaurant_id`
        ORDER BY
            nb DESC,
            total_weight DESC
        LIMIT %%s
        OFFSET %%s
        """ \
        % " OR ".join(['`restaurant_search_index`.`word` LIKE ?'] * num_words)
        
        query   = query.replace('?', '%s')
        cursor  = connection.cursor()
        results = cursor.execute(query, words + [max, offset])
        
        restaurants = []
        for row in cursor.fetchall():
            restaurant        = self.get(pk=row[0])
            restaurant.count  = row[1]
            restaurant.weight = row[2]
            restaurants.append(restaurant)
        
        return restaurants
    

    def get_tagged(self, tag):
        restaurants = []
        tags = RestaurantTag.objects.select_related('restaurant').filter(tag=tag)
        [restaurants.append(tag.restaurant) for tag in tags]
        return restaurants

class RestaurantVersion(models.Model):
    chain            = models.IntegerField(null=True, blank=True)
    description      = models.TextField(blank=True)
    url              = models.CharField(max_length=765, blank=True)
    created_at       = models.DateTimeField(auto_now_add=True)
    restaurant       = models.ForeignKey('Restaurant', null=True, blank=True)
    user             = models.ForeignKey(Profile, null=True, blank=True)
    html_description = models.TextField(blank=True)
    
    def save(self, force_insert=False, force_update=False):
        self.html_description = markdown(self.description)
        super(RestaurantVersion, self).save(force_insert, force_update)
        
    class Meta:
        db_table = u'restaurant_version'
    


class Restaurant(models.Model):
    name           = models.CharField(max_length=765, blank=True)
    stripped_title = models.CharField(max_length=384, blank=True)
    approved       = models.IntegerField(null=True, blank=True)
    current_rating = None
    average_rating = models.FloatField(null=True, blank=True)
    num_ratings    = models.IntegerField(null=True, blank=True)
    version        = models.ForeignKey(RestaurantVersion, related_name="the_restaurant")
    updated_at     = models.DateTimeField(auto_now=True)
    created_at     = models.DateTimeField(auto_now_add=True)
    objects        = RestaurantManager()
    new_version    = None
    
    def save(self, force_insert=False, force_update=False):
        if not self.stripped_title:
            self.stripped_title = slugify(self.name)
        
        super(Restaurant, self).save(force_insert, force_update)
        if self.new_version:
            self.new_version.restaurant = self
            self.new_version.save()
            self.version = self.new_version
            super(Restaurant, self).save(force_insert, force_update)
        
        self.reindex()
        
        
    def __setattr__(self, name, value):
        if name == 'description':
            self.get_new_version().description = value
        
        elif name == 'url':
            self.get_new_version().url = value
        
        else:
            object.__setattr__(self, name, value)

    def __getattr__(self, name):
        try:
            if name == 'description':
                return self.version.description

            elif name == 'url':
                return self.version.url
        except RestaurantVersion.DoesNotExist:
            return ''
            
        models.Model.__getattribute__(self, name)
    
    def get_new_version(self):
        if self.new_version == None:
            try:
                rv    = self.version
                rv.id = None
            except RestaurantVersion.DoesNotExist:
                rv = RestaurantVersion()
            
            self.new_version = rv

        return self.new_version
        
    def get_absolute_url(self):
        return "/restaurant/%s" % (self.stripped_title,)

    def get_rating_url(self):
        return self.get_absolute_url()+"/rate/"
        
    def slug(self):
        return self.stripped_title;

    def __unicode__(self):
        return self.name
        
    def get_popular_tags(self, max = 10):
        
        query = """
        SELECT `normalized_tag` AS tag, COUNT(`normalized_tag`) AS count
        FROM `restaurant_tag`
        WHERE `restaurant_id` = %s
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
    
    def get_tags_from_user(self, profile):
        # given a profile return all the tags that said profile has for this particular item
        tags = RestaurantTag.objects.filter(restaurant = self, user = profile)
        return tags

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
        RestaurantSearchIndex.objects.filter(restaurant=self).delete()

        for word, weight in self.get_words().iteritems():
            RestaurantSearchIndex(restaurant=self, word=word, weight=weight).save()
    
    class Meta:
        db_table     = u'restaurant'


class RestaurantTag(models.Model):
    """
    Django doesn't support multi column keys... so we need to unique our PK and create a dummy one (id automatically gets created)
    """
    restaurant     = models.ForeignKey(Restaurant)
    user           = models.ForeignKey(Profile)
    created_at     = models.DateTimeField(auto_now_add=True)
    tag            = models.CharField(max_length=300)
    normalized_tag = models.CharField(max_length=300)

    unique_together = ("user", "restaurant", "normalized_tag")

    objects        = TagManager()

    def __unicode__(self):
        return self.normalized_tag

    class Meta:
        db_table = u'restaurant_tag'

    def delete(self):
        super(RestaurantTag, self).delete()
        self.restaurant.reindex()
        
    def save(self, force_insert=False, force_update=False):
        if not self.normalized_tag:
            self.normalized_tag = normalize(tag)

        super(RestaurantTag, self).save(force_insert, force_update)
        self.restaurant.reindex()


class RestaurantSearchIndex(models.Model):
    restaurant = models.ForeignKey(Restaurant)
    word = models.CharField(max_length=768)
    weight = models.IntegerField()

    unique_together = ("user", "restaurant")

    class Meta:
        db_table = u'restaurant_search_index'


class RestaurantNote(models.Model):
    profile    = models.ForeignKey(Profile, db_column='user_id')
    note       = models.TextField(blank=True)
    restaurant = models.ForeignKey(Restaurant)
    location   = models.ForeignKey('Location')
    updated_at = models.DateTimeField(auto_now=True)
    created_at = models.DateTimeField(auto_now_add=True)
    html_note  = models.TextField(blank=True)

    def save(self, force_insert=False, force_update=False):
        self.html_note = markdown(self.note)
        super(RestaurantNote, self).save(force_insert, force_update) # Call the "real" save() method.


    def author(self):
        return self.profile.user
        #return self.profile.user

    class Meta:
        db_table = u'restaurant_note'

