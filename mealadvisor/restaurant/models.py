from django.db import models, transaction, connection
from django.db.models import Q
from django.conf import settings
from mealadvisor.common.models import Profile, Country, State
from mealadvisor.tools import stem_phrase, extract_numbers
from mealadvisor.geocoder import Geocoder


class RandomManager(models.Manager):
    def random(self):
        try:
            return self.all().order_by('?')[0]
        except:
            return None


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

        from django.db import connection
        cursor  = connection.cursor()
        results = cursor.execute(query)
        return cursor.fetchall()

            
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

        query = query.replace('?', '%s')
        from django.db import connection
        cursor  = connection.cursor()
        results = cursor.execute(query, words + [max, offset])

        restaurants = []
        for row in cursor.fetchall():
            restaurant        = self.get(pk=row[0])
            restaurant.count  = row[1]
            restaurant.weight = row[2]
            restaurants.append(restaurant)
            
        return restaurants


class RestaurantVersion(models.Model):
    chain            = models.IntegerField(null=True, blank=True)
    description      = models.TextField(blank=True)
    url              = models.CharField(max_length=765, blank=True)
    created_at       = models.DateTimeField(null=True, blank=True)
    restaurant       = models.ForeignKey('Restaurant', null=True, blank=True)
    user             = models.ForeignKey(Profile, null=True, blank=True)
    html_description = models.TextField(blank=True)

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
    updated_at     = models.DateTimeField(null=True, blank=True)
    created_at     = models.DateTimeField(null=True, blank=True)
    objects        = RestaurantManager()
    
    def __getattr__(self, name):
        if name == 'description':
            return self.version.description

        elif name == 'url':
            return self.version.url
            
        models.Model.__getattr__(self, name)

    class Meta:
        db_table     = u'restaurant'

    def get_absolute_url(self):
        return "/restaurant/%s" % (self.stripped_title,)

    def get_rating_url(self):
        return self.get_absolute_url()+"/rate/"
        
    def slug(self):
        return self.stripped_title;

    def __unicode__(self):
        return self.name

        
class Location(models.Model):
    restaurant      = models.ForeignKey(Restaurant)
    data_source     = models.CharField(max_length=96, blank=True)
    data_source_key = models.CharField(max_length=765, blank=True)
    name            = models.CharField(max_length=765, blank=True)
    stripped_title  = models.CharField(max_length=765, blank=True)
    address         = models.CharField(max_length=765, blank=True)
    city            = models.CharField(max_length=384, blank=True)
    state           = models.CharField(max_length=48, blank=True)
    zip             = models.CharField(max_length=30, blank=True)
    country         = models.ForeignKey(Country)
    latitude        = models.FloatField(null=True, blank=True)
    longitude       = models.FloatField(null=True, blank=True)
    phone           = models.CharField(max_length=48, blank=True)
    approved        = models.IntegerField(null=True, blank=True)
    updated_at      = models.DateTimeField(null=True, blank=True)
    created_at      = models.DateTimeField(null=True, blank=True)
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
        
    class Meta:
        db_table = u'location'
        unique_together = (("data_source", "data_source_key"),)


class MenuItem(models.Model):
    name           = models.CharField(max_length=765, blank=True)
    slug           = models.CharField(db_column='url', max_length=765, blank=True)
    version        = models.ForeignKey('MenuitemVersion', related_name="the_menuitem")
    restaurant     = models.ForeignKey(Restaurant)
    approved       = models.IntegerField(null=True, blank=True)
    average_rating = models.FloatField(null=True, blank=True)
    num_ratings    = models.IntegerField(null=True, blank=True)
    updated_at     = models.DateTimeField(null=True, blank=True)
    created_at     = models.DateTimeField(null=True, blank=True)
    objects        = MenuItemManager()
    current_rating = None


    def __getattr__(self, name):
        if name == 'description':
            return self.version.description

        models.Model.__getattr__(self, name)

    def __unicode__(self):
        return self.name

    def get_absolute_url(self):
        "http://prod.rbu.sf/frontend_dev.php/restaurant/hobees/menu/special-traditional-eggs-benedict"
        return "%s/menu/%s" % (self.restaurant.get_absolute_url(), self.slug)


    def get_rating_url(self):
        return self.get_absolute_url()+"/rate/"

    class Meta:
        db_table = u'menu_item'


class MenuitemVersion(models.Model):
    description      = models.TextField(blank=True)
    html_description = models.TextField(blank=True)
    location         = models.ForeignKey(Location, null=True, blank=True)
    menuitem         = models.ForeignKey(MenuItem)
    user             = models.ForeignKey(Profile, null=True, blank=True)
    price            = models.CharField(max_length=48, blank=True)
    created_at       = models.DateTimeField(null=True, blank=True)
    class Meta:
        db_table         = u'menuitem_version'


class MenuItemImage(models.Model):
    user      = models.ForeignKey(Profile, null=True, blank=True)
    menu_item = models.ForeignKey(MenuItem)
    data      = models.TextField(blank=True)
    md5sum    = models.CharField(max_length=96, blank=True)
    height    = models.IntegerField(null=True, blank=True)
    width     = models.IntegerField(null=True, blank=True)
    objects   = RandomManager()

    class Meta:
        db_table = u'menu_item_image'

    def is_portrait(self):
        return (self.height > self.width);

class RestaurantRating(models.Model):
    restaurant = models.ForeignKey(Restaurant)
    value      = models.IntegerField(null=True, blank=True)
    location   = models.ForeignKey(Location, null=True, blank=True)
    user       = models.ForeignKey(Profile)
    created_at = models.DateTimeField(null=True, blank=True)

    class Meta:
        db_table = u'restaurant_rating'
    
    def save(self, raw=False):
        try:
            models.Model.save(self, raw)
            cursor  = connection.cursor()
            query   = "SELECT count(value), avg(value) FROM restaurant_rating WHERE restaurant_id = %s"
            results = cursor.execute(query, (self.restaurant.id,))

            for row in cursor.fetchall():
                self.restaurant.num_ratings    = row[0]
                self.restaurant.average_rating = row[1]
                self.restaurant.save()

        except:
              transaction.rollback()
        else:
              transaction.commit()
      
      
class MenuitemRating(models.Model):
    menu_item  = models.ForeignKey(MenuItem, null=True, blank=True)
    user       = models.ForeignKey(Profile, null=True, blank=True)
    value      = models.IntegerField(null=True, blank=True)
    created_at = models.DateTimeField(null=True, blank=True)

    class Meta:
        db_table = u'menuitem_rating'

    def save(self, raw=False):
        try:
            models.Model.save(self, raw)
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


class RestaurantNote(models.Model):
    profile    = models.ForeignKey(Profile, db_column='user_id')
    note       = models.TextField(blank=True)
    restaurant = models.ForeignKey(Restaurant)
    location   = models.ForeignKey(Location, null=True, blank=True)
    updated_at = models.DateTimeField()
    created_at = models.DateTimeField()
    html_note  = models.TextField(blank=True)
    
    def author(self):
        return self.profile.user
        #return self.profile.user
        
    class Meta:
        db_table = u'restaurant_note'
