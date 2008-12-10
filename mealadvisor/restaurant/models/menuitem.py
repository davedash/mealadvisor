from django.db import models, transaction, connection

from mealadvisor.common.models import Profile 
from restaurant import Restaurant
from location import Location

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
    
    
    def __getattr__(self, name):
        if name == 'description':
            return self.version.description

        if name == 'price':
            return self.version.price

        
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
    created_at       = models.DateTimeField(auto_now_add=True)
    class Meta:
        db_table         = u'menuitem_version'

      
class MenuitemRating(models.Model):
    menu_item  = models.ForeignKey(MenuItem, null=True, blank=True)
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


