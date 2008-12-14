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

from django.db import models, transaction, connection
from django.db.models import Q
from django.conf import settings
from django.utils.html import strip_tags

from mealadvisor.common.models import Profile
from mealadvisor.tools import *
from mealadvisor.geocoder import Geocoder

from markdown import markdown
from utils import *
from restaurant import *
from menuitem import *
from location import *
    
class RandomManager(models.Manager):
    def random(self):
        try:
            return self.all().order_by('?')[0]
        except:
            return None


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
                self.restaurant.save()
                
        except:
              transaction.rollback()
        else:
              transaction.commit()



