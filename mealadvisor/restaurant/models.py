# This Python file uses the following encoding: utf-8
u"""
Test stemming
>>> sorted(stem_phrase("the big red dog jumped the fence fence car"))
['big', 'car', 'dog', 'fenc', 'fenc', 'jump', 'red']


Test tag normalizing

>>> normalize(u'cafe')
u'cafe'
>>> normalize(u'caf e')
u'cafe'
>>> normalize(u' cafe ')
u'cafe'

>>> normalize(u' café ')
u'cafe'

>>> normalize(u'cAFe')
u'cafe'    
>>> normalize(u'$sss$s')
u'ssss'
"""

import random

from markdown import markdown

from django.conf import settings
from django.contrib.redirects.models import Redirect
from django.db import models, transaction, connection
from django.db.models import Q
from django.template.defaultfilters import slugify
from django.utils.html import strip_tags

from mealadvisor.common.models import Profile, Country, State
from mealadvisor.geocoder import geocode, Geocoder
from mealadvisor.tools import *

from managers import RestaurantManager, TagManager, RandomManager
from utils import *

class Restaurant(models.Model):
    name           = models.CharField(max_length=765)
    stripped_title = models.CharField(max_length=384)
    approved       = models.IntegerField(null=True)
    current_rating = None
    average_rating = models.FloatField(null=True)
    num_ratings    = models.IntegerField(null=True)
    version        = models.ForeignKey('RestaurantVersion', related_name="the_restaurant")
    updated_at     = models.DateTimeField(auto_now=True)
    created_at     = models.DateTimeField(auto_now_add=True)
    objects        = RestaurantManager()
    new_version    = None

    def save(self, force_insert=False, force_update=False, reindex=True):

        if self.pk:
            old_version = Restaurant.objects.get(pk=self.pk)
            if old_version.stripped_title != self.stripped_title:
                Redirect(site_id=1, old_path=old_version.get_absolute_url(), new_path=self.get_absolute_url()).save()

        if not self.stripped_title:
            self.stripped_title = slugify(self.name)

        super(Restaurant, self).save(force_insert, force_update)
        if self.new_version:
            self.new_version.restaurant = self
            self.new_version.save()
            self.version = self.new_version
            super(Restaurant, self).save(force_insert, force_update)

        if reindex:
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
            elif name == 'html_description':
                return self.version.html_description
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
        raw_text = ''

        if self.description:
            raw_text += ' '.join([self.description]*settings.SEARCH_WEIGHT_BODY)

        name     = self.name.replace("'", '')
        raw_text += ' '.join([name]*settings.SEARCH_WEIGHT_TITLE)

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


class RestaurantVersion(models.Model):
    chain            = models.IntegerField(null=True)
    description      = models.TextField(null=True)
    url              = models.CharField(max_length=765, null=True)
    created_at       = models.DateTimeField(auto_now_add=True)
    restaurant       = models.ForeignKey('Restaurant')
    user             = models.ForeignKey(Profile, null=True)
    html_description = models.TextField(null=True)
    
    def save(self, force_insert=False, force_update=False):
        self.html_description = markdown(self.description)
        super(RestaurantVersion, self).save(force_insert, force_update)
        
    class Meta:
        db_table = u'restaurant_version'


class RestaurantRating(models.Model):
    restaurant = models.ForeignKey(Restaurant)
    value      = models.IntegerField(null=True)
    location   = models.ForeignKey('Location', null=True)
    user       = models.ForeignKey(Profile)
    created_at = models.DateTimeField(auto_now_add=True)
    
    class Meta:
        db_table = u'restaurant_rating'
    
    def save(self, force_insert=False, force_update=False):
        try:
            super(RestaurantRating, self).save(force_insert, force_update)
            cursor  = connection.cursor()
            query   = "SELECT count(value), avg(value) FROM restaurant_rating WHERE restaurant_id = %s"
            results = cursor.execute(query, (self.restaurant.id,))
            
            for row in cursor.fetchall():
                self.restaurant.num_ratings    = row[0]
                self.restaurant.average_rating = row[1]
                self.restaurant.save(reindex=False)
                
        except:
              transaction.rollback()
        else:
              transaction.commit()


class LocationManager(models.Manager):
    def in_country(self, country):
        c = Country.objects.retrieve_magically(country)
        return self.filter(country__exact=c)
    
    def in_state(self, country, state):
        s = State.objects.retrieve_magically(state)
        return self.in_country(country).filter(Q(state__exact=s.usps)|Q(state__exact=s.name))
    
    def in_city(self, country, state, city):
        return self.in_state(country, state).filter(city__exact=city)
    
    def in_zip(self, zip):
        return self.filter(zip__startswith=zip)
    
    def anyin(self, place = None, geocoder = None):
        # let's geocode this first...
        # then, let's break it down and understand how "zoomed in we are"
        # using that we can get the appropriate sql query and get our propper 
        # set of objects
        
        g = None
        
        if place != None:
            g = Geocoder(place)
        elif geocoder != None:
            g = geocoder
        else:
            return []
        
        accuracy = g.location.accuracy
        
        if accuracy == g.COUNTRY:
            return self.in_country(g.location.country).select_related(depth=1)
        if accuracy == g.STATE:
            return self.in_state(g.location.country, g.location.state).select_related(depth=1)
        if accuracy == g.CITY:
            return self.in_city(g.location.country, g.location.state, g.location.city).select_related(depth=1)
        if accuracy >= g.ZIP:
            return self.in_zip(g.location.zip).select_related(depth=1)
    
    def search_in(self, phrase, place = None, offset=0, max=10, geocoder = None):
        g = None
        if place != None:
            g = Geocoder(place)
        elif geocoder != None:
            g = geocoder
        else:
            return []
        
        accuracy = g.location.accuracy
        
        where  = []
        inputs = []
        
        if (accuracy != g.ZIP):
            if accuracy >= g.COUNTRY:
                c = Country.objects.retrieve_magically(g.location.country)
                where.append("l.country_id LIKE '%s'" % c.iso)
            if accuracy >= g.STATE:
                s = State.objects.retrieve_magically(g.location.state)
                where.append("l.state IN ('%s', '%s' ) " % (s.usps, s.name))
            if accuracy >= g.CITY:
                where.append("l.city LIKE '%s'" % g.location.city)
        else:
            where.append("l.zip LIKE %s")
            inputs.append(g.location.zip+"%")
        # we want to stem the words AND extract any numbers
        words = stem_phrase(phrase) + extract_numbers(phrase)
        
        num_words = len(words)
        if num_words == 0:
            return []
        
        query = """
        SELECT DISTINCT 
            l.`id`, 
            COUNT(*) AS nb,
            SUM(rsi.`weight`) AS total_weight
        FROM 
            `restaurant_search_index` rsi,
            location l
        WHERE
            l.restaurant_id = rsi.restaurant_id AND
            (%s)
        """ \
        % " OR ".join(["rsi.`word` LIKE '%s'" % k for k in words]) 
        
        if where != []:
            query = query + "AND (%s) " % " AND ".join(where)
        
        query = query + """
        GROUP BY
            l.id
        ORDER BY
            nb DESC,
            total_weight DESC
        LIMIT %s
        OFFSET %s
        """ 
        
        cursor  = connection.cursor()
        results = cursor.execute(query, inputs + [max, offset])
        
        locations = []
        for row in cursor.fetchall():
            location = self.get(pk=row[0])
            locations.append(location)
            
        return locations
    
    def near(self, place, phrase = None):
        g        = Geocoder(place)
        accuracy = g.location.accuracy
        
        # we aren't interested in searching near countries, or states
        # just cities... so deflect everything to anyin
        if accuracy < g.CITY:
            if phrase:
                return self.search_in(phrase, geocoder = g)
            else:
                return self.anyin(geocoder = g)
      
        lat = g.location.latitude
        lng = g.location.longitude
        
        distance = """
        (
            (
                (
                    acos(sin((%f*pi()/180)) * sin((latitude*pi()/180)) 
                    + 
                    cos((%f*pi()/180)) * cos((latitude*pi()/180)) 
                    * 
                    cos(((%f - longitude)*pi()/180)))
                )
                * 180/pi()
            )
            *60*1.1515
        ) AS distance
        """ % (lat, lat, lng)
        
        select   = [distance, 'location.latitude', 'location.longitude']
        group_by = ['location.latitude', 'location.longitude']
        having   = ['distance < %d' % settings.SEARCH_DEFAULT_RADIUS ]
        order_by = ['distance']
        
        results   = self.raw_search_query(select=select, group_by=group_by, having=having, order_by=order_by, phrase=phrase)
        locations = []
        
        for row in results:
            location = self.get(pk=row[0])
            locations.append(location)
        
        return locations
    
    def raw_search_query(self, select=[], where=[], group_by=[], having=[], order_by=[], phrase=None):
        
        # we're getting locations
        select   = ['location.id'] + select
        tables   = ['location']
        group_by = ['location.id'] + group_by
        
        if phrase:
            words     = stem_phrase(phrase) + extract_numbers(phrase)
            num_words = len(words)
            tables    = ['restaurant_search_index rsi'] + tables
            where     = [
                'rsi.restaurant_id = location.restaurant_id', 
                " OR ".join(["rsi.`word` LIKE '%s'" % k for k in words])
            ]
            group_by
            
        max    = 10
        offset = 0
        
        query = "SELECT DISTINCT " + ",".join(select)
        query = query + " FROM " + ",".join(tables)
        
        if where != []:
            query = query + " WHERE " + " AND ".join(where)
        
        if group_by != []:
            query = query + " GROUP BY " + ",".join(group_by)
        
        if having != []:
            query = query + " HAVING " + " AND ".join(having)
            
        if order_by != []:
            query = query + " ORDER BY " + ",".join(order_by)
        
        cursor  = connection.cursor()
        results = cursor.execute(query)
        return cursor.fetchall()
    

class Location(models.Model):
    restaurant      = models.ForeignKey(Restaurant)
    data_source     = models.CharField(max_length=96, null=True)
    data_source_key = models.CharField(max_length=765, null=True)
    name            = models.CharField(max_length=765, null=True)
    stripped_title  = models.CharField(max_length=765)
    address         = models.CharField(max_length=765, null=True)
    city            = models.CharField(max_length=384, null=True)
    state           = models.CharField(max_length=48, null=True)
    zip             = models.CharField(max_length=30, null=True)
    country         = models.ForeignKey(Country, null=True)
    latitude        = models.FloatField(null=True)
    longitude       = models.FloatField(null=True)
    phone           = models.CharField(max_length=48, null=True)
    approved        = models.IntegerField(null=True)
    updated_at      = models.DateTimeField(auto_now=True)
    created_at      = models.DateTimeField(auto_now_add=True)
    objects         = LocationManager()

    def __unicode__(self):
        loc     = [self.city, self.state]
        loc     = [elem for elem in loc if elem]
        loc_str = ', '.join(loc)
        
        if self.name:
            return "%s <em>(%s)</em>" % (self.name, loc_str)
        elif loc_str:
            return loc_str
        else:
            return '-'
        
    def generate_slug(self):
        if self.name:
            return slugify(self.name)
        else:
            return slugify(" ".join([self.address, self.city, self.state]))
        
    def get_full_address(self, format = "%(address)s, %(city)s, %(state)s, %(zipcode)s"):
        str = format % {'address': self.address, 'city': self.city, 'state': self.state, 'zipcode': self.zip }
        return str.strip()
    
    def save(self, force_insert=False, force_update=False):
        if not self.stripped_title:
            self.stripped_title = self.generate_slug()

        if not (self.latitude and self.longitude):
            try:
                (place, self.latitude, self.longitude) = geocode(self.get_full_address())
            except:
                pass
                
        super(Location, self).save(force_insert, force_update)

    def get_absolute_url(self):
        "http://prod.rbu.sf/frontend_dev.php/restaurant/hobees/menu/special-traditional-eggs-benedict"
        return "%s/location/%s" % (self.restaurant.get_absolute_url(), self.stripped_title)

    class Meta:
        db_table = u'location'
        unique_together = (("data_source", "data_source_key"),)


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
    
    
    def get_tagged(self, tag):
        items = []
        tags = MenuitemTag.objects.select_related('menu_item').filter(tag=tag)
        [items.append(tag.menu_item) for tag in tags]
        return items


class MenuItem(models.Model):
    name           = models.CharField(max_length=765)
    slug           = models.CharField(db_column='url', max_length=765)
    version        = models.ForeignKey('MenuitemVersion', related_name="the_menuitem")
    restaurant     = models.ForeignKey(Restaurant)
    approved       = models.IntegerField(null=True)
    average_rating = models.FloatField(null=True)
    num_ratings    = models.IntegerField(null=True)
    updated_at     = models.DateTimeField(auto_now=True)
    created_at     = models.DateTimeField(auto_now_add=True)
    objects        = MenuItemManager()
    current_rating = None
    new_version    = None
    
    def save(self, force_insert=False, force_update=False):
        if not self.slug:
            self.slug = slugify(self.name)
        
        super(MenuItem, self).save(force_insert, force_update)
        
        if self.new_version:
            self.new_version.restaurant = self
            self.new_version.save()
            self.version = self.new_version
            super(MenuItem, self).save(force_insert, force_update)
        
        self.reindex()
    
    
    def __setattr__(self, name, value):
        if name == 'description':
            self.get_new_version().description = value
        
        elif name == 'price':
            self.get_new_version().price = value
        
        else:
            object.__setattr__(self, name, value)
    
    def __getattr__(self, name):
        
        try:
            if name == 'description':
                return self.version.description
            
            elif name == 'html_description':
                return self.version.html_description
            
            elif name == 'price':
                return self.version.price
        except RestaurantVersion.DoesNotExist:
            return ''
            
        models.Model.__getattribute__(self, name)
    
    def get_new_version(self):
        if self.new_version == None:
            try:
                v    = self.version
                v.id = None
            except MenuitemVersion.DoesNotExist:
                v = MenuitemVersion()
            
            self.new_version = v
        
        return self.new_version
    
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
    menuitem = models.ForeignKey(MenuItem, null=True)
    word = models.CharField(max_length=765)
    weight = models.IntegerField(null=True)
    class Meta:
        db_table = u'menuitem_search_index'

class MenuitemVersion(models.Model):
    description      = models.TextField(null=True)
    html_description = models.TextField(null=True)
    location         = models.ForeignKey(Location, null=True)
    menuitem         = models.ForeignKey(MenuItem)
    user             = models.ForeignKey(Profile, null=True)
    price            = models.CharField(max_length=48, null=True)
    created_at       = models.DateTimeField(auto_now_add=True)

    def save(self, force_insert=False, force_update=False):
        self.html_description = markdown(self.description)
        super(MenuitemVersion, self).save(force_insert, force_update)
        
    class Meta:
        db_table         = u'menuitem_version'
        

      
class MenuitemRating(models.Model):
    menu_item  = models.ForeignKey(MenuItem)
    user       = models.ForeignKey(Profile, null=True)
    value      = models.IntegerField(null=True)
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
    

class MenuItemImage(models.Model):
    user      = models.ForeignKey(Profile, null=True)
    menu_item = models.ForeignKey(MenuItem)
    image     = models.ImageField(upload_to='images/menuitems', height_field='height', width_field='width', max_length=240)
    height    = models.IntegerField(null=True)
    width     = models.IntegerField(null=True)
    objects   = RandomManager()

    class Meta:
        db_table = u'menu_item_image'

    def is_portrait(self):
        return (self.height > self.width)
                    
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
    note       = models.TextField()
    restaurant = models.ForeignKey(Restaurant)
    location   = models.ForeignKey(Location, null=True)
    updated_at = models.DateTimeField(auto_now=True)
    created_at = models.DateTimeField(auto_now_add=True)
    html_note  = models.TextField()

    def save(self, force_insert=False, force_update=False):
        self.html_note = markdown(self.note)
        super(RestaurantNote, self).save(force_insert, force_update) # Call the "real" save() method.


    def author(self):
        return self.profile.user
        #return self.profile.user

    class Meta:
        db_table = u'restaurant_note'