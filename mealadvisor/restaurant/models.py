from django.db import models
from mealadvisor.common.models import Profile, Country
from mealadvisor.tools import stem_phrase, extract_numbers
from mealadvisor.geocoder import Geocoder

class LocationManager(models.Manager):
    def in_country(self, country):
        c = Country.objects.retrieve_magically(country)
        return self.filter(country=c)
        
    def anyin(self, place):
        # let's geocode this first...
        # then, let's break it down and understand how "zoomed in we are"
        # using that we can get the appropriate sql query and get our propper 
        # set of objects
        g = Geocoder(place)
        accuracy = g.accuracy()
        
        if accuracy == g.COUNTRY:
            return self.in_country(g.country)
        
        
        # $c->addDescendingOrderByColumn(RestaurantPeer::NUM_RATINGS);
        # $c->addJoin(LocationPeer::RESTAURANT_ID, RestaurantPeer::ID);
        # if ($countryStr = $this->getRequestParameter('country'))
        # {
        #   $countryStr = str_replace('%2b',' ', $countryStr);
        #   $country = CountryPeer::retrieveByMagic($countryStr);
        #   if ($country instanceof Country) 
        #   {
        #       $c->add(LocationPeer::COUNTRY_ID, $country->getIso());
        #   }
        # 
        #   $this->in = link_to_geo($country->getPrintableName());
        # }
            # 
            # if ($stateStr = $this->getRequestParameter('state'))
            # {
            #   $state = StatePeer::retrieveByMagic($stateStr);
            #   if ($state instanceof State)
            #   {
            #       $cton1 = $c->getNewCriterion(LocationPeer::STATE, $state->getUsps());
            #       $cton2 = $c->getNewCriterion(LocationPeer::STATE, $state->getName());
            #       $cton1->addOr($cton2);
            #       $c->add($cton1);
            #       $this->in = $this->in = link_to_geo($country->getPrintableName(), $state->getName()) . ', ' . $this->in; 
            #       $stateStr = $state->getName();
            #   }
            #   else
            #   {
            #       $c->add(LocationPeer::STATE, $stateStr);
            # 
            #       $this->in = ucwords($stateStr) . ', ' . $this->in;
            #   }
            # }
            # 
            # if ($cityStr = $this->getRequestParameter('city'))
            # {
            #   $cityStr = strtr($cityStr, '_', ' ');
            #   $cc = new Criteria();
            #   $c->add(LocationPeer::CITY, $cityStr);
            # 
            #   $this->in = link_to_geo($country->getPrintableName(), $stateStr, ucwords($cityStr)) . ', ' . $this->in; 
            # 
            # }
            # 
            # $pager->setCriteria($c);
            # $pager->setPage($page);
            # $pager->init();
            # 
            # $this->pager = $pager;
            # $this->nav_url = '@locations_in?country=' . $countryStr;
            # if ($state = $this->getRequestParameter('state'))
            # {
            #   $this->nav_url .= '&state='.$state;
            # 
            # }
            # 
            # if ($cityStr)
            # {
            #           $this->nav_url .= '&city='.$cityStr;
            # }
            # $this->nav_url .= '&page=';
            # 
            # 
            # $this->prependTitle('Restaurants in '. strip_tags($this->in));
        
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


class Restaurant(models.Model):
    name           = models.CharField(max_length=765, blank=True)
    stripped_title = models.CharField(max_length=384, blank=True)
    approved       = models.IntegerField(null=True, blank=True)
    average_rating = models.FloatField(null=True, blank=True)
    num_ratings    = models.IntegerField(null=True, blank=True)
    version        = models.ForeignKey('RestaurantVersion', related_name="the_restaurant", null=True, blank=True)
    updated_at     = models.DateTimeField(null=True, blank=True)
    created_at     = models.DateTimeField(null=True, blank=True)
    objects        = RestaurantManager()

    class Meta:
        db_table     = u'restaurant'

    def get_absolute_url(self):
        return "/restaurant/%s" % (self.stripped_title,)

    def slug(self):
        return self.stripped_title;

    def __unicode__(self):
        return self.name

        
class RestaurantVersion(models.Model):
    chain            = models.IntegerField(null=True, blank=True)
    description      = models.TextField(blank=True)
    url              = models.CharField(max_length=765, blank=True)
    created_at       = models.DateTimeField(null=True, blank=True)
    restaurant       = models.ForeignKey(Restaurant, null=True, blank=True)
    user             = models.ForeignKey(Profile, null=True, blank=True)
    html_description = models.TextField(blank=True)

    class Meta:
        db_table = u'restaurant_version'

        
class Location(models.Model):
    restaurant      = models.ForeignKey(Restaurant, null=True, blank=True)
    data_source     = models.CharField(max_length=96, blank=True)
    data_source_key = models.CharField(max_length=765, blank=True)
    name            = models.CharField(max_length=765, blank=True)
    stripped_title  = models.CharField(max_length=765, blank=True)
    address         = models.CharField(max_length=765, blank=True)
    city            = models.CharField(max_length=384, blank=True)
    state           = models.CharField(max_length=48, blank=True)
    zip             = models.CharField(max_length=30, blank=True)
    country         = models.ForeignKey(Country, null=True, blank=True)
    latitude        = models.FloatField(null=True, blank=True)
    longitude       = models.FloatField(null=True, blank=True)
    phone           = models.CharField(max_length=48, blank=True)
    approved        = models.IntegerField(null=True, blank=True)
    updated_at      = models.DateTimeField(null=True, blank=True)
    created_at      = models.DateTimeField(null=True, blank=True)
    objects         = LocationManager()

    class Meta:
        db_table = u'location'
        unique_together = (("data_source", "data_source_key"),)


class MenuItem(models.Model):
    name           = models.CharField(max_length=765, blank=True)
    slug           = models.CharField(db_column='url', max_length=765, blank=True)
    version        = models.ForeignKey('MenuitemVersion', related_name="the_menuitem", null=True, blank=True)
    restaurant     = models.ForeignKey(Restaurant)
    approved       = models.IntegerField(null=True, blank=True)
    average_rating = models.FloatField(null=True, blank=True)
    num_ratings    = models.IntegerField(null=True, blank=True)
    updated_at     = models.DateTimeField(null=True, blank=True)
    created_at     = models.DateTimeField(null=True, blank=True)

    def __unicode__(self):
        return self.name

    def get_absolute_url(self):
        "http://prod.rbu.sf/frontend_dev.php/restaurant/hobees/menu/special-traditional-eggs-benedict"
        return "%s/menu/%s" % (self.restaurant.get_absolute_url(), self.slug)

    class Meta:
        db_table = u'menu_item'


class MenuitemVersion(models.Model):
    description      = models.TextField(blank=True)
    html_description = models.TextField(blank=True)
    location         = models.ForeignKey(Location, null=True, blank=True)
    menuitem         = models.ForeignKey(MenuItem, null=True, blank=True)
    user             = models.ForeignKey(Profile, null=True, blank=True)
    price            = models.CharField(max_length=48, blank=True)
    created_at       = models.DateTimeField(null=True, blank=True)
    class Meta:
        db_table = u'menuitem_version'


class MenuItemImage(models.Model):
    user      = models.ForeignKey(Profile, null=True, blank=True)
    menu_item = models.ForeignKey(MenuItem)
    data      = models.TextField(blank=True)
    md5sum    = models.CharField(max_length=96, blank=True)
    height    = models.IntegerField(null=True, blank=True)
    width     = models.IntegerField(null=True, blank=True)


    class Meta:
        db_table = u'menu_item_image'

    def is_portrait(self):
        return (self.height > self.width);
