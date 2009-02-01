from django.db import models, transaction, connection
from restaurant import Restaurant
from mealadvisor.common.models import Profile, Country, State
from django.template.defaultfilters import slugify
from mealadvisor.geocoder import geocode

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
    data_source     = models.CharField(max_length=96, blank=True, null=True)
    data_source_key = models.CharField(max_length=765, blank=True, null=True)
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
            (place, self.latitude, self.longitude) = geocode(self.get_full_address())

        super(Location, self).save(force_insert, force_update)


        #   $geo = new YahooGeo($this->getFullAddress('%a, %c, %s'));
        # 
        #       $this->setCountryByName($country);
        #       if ($state = $geo->getState());
        #       {
        #           $this->setState($state);
        #       }
        #       if ($city = $geo->getCity());
        #       {
        #           $this->setCity($city);
        #       }
        #       if ($zip = $geo->getZip());
        #       {
        #           $this->setZip($zip);
        #       }
        #       
        #       $this->setLatitude($geo->getLatitude());
        #       $this->setLongitude($geo->getLongitude());
    class Meta:
        db_table = u'location'
        unique_together = (("data_source", "data_source_key"),)

