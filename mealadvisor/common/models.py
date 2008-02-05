# This is an auto-generated Django model module.
# You'll have to do the following manually to clean this up:
#     * Rearrange models' order
#     * Make sure each model has one field with primary_key=True
# Feel free to rename the models, but don't rename db_table values or field names.
#
# Also note: You'll have to insert the output of 'django-admin.py sqlcustom [appname]'
# into your database.

from django.db import models

class Profile(models.Model):
  id           = models.IntegerField(primary_key=True)
  userid       = models.IntegerField(unique=True, null=True, blank=True)
  email        = models.CharField(max_length=384, blank=True)
  openid       = models.IntegerField(null=True, blank=True)
  preferences  = models.TextField(blank=True)
  about_text   = models.TextField(blank=True)
  updated_at   = models.DateTimeField(null=True, blank=True)
  created_at   = models.DateTimeField(null=True, blank=True)
  class Meta:
    db_table = u'profile'

class Restaurant(models.Model):
  id             = models.IntegerField(primary_key=True)
  name           = models.CharField(max_length=765, blank=True)
  stripped_title = models.CharField(max_length=384, blank=True)
  approved       = models.IntegerField(null=True, blank=True)
  average_rating = models.FloatField(null=True, blank=True)
  num_ratings    = models.IntegerField(null=True, blank=True)
  version        = models.ForeignKey('RestaurantVersion', null=True, blank=True)
  updated_at     = models.DateTimeField(null=True, blank=True)
  created_at     = models.DateTimeField(null=True, blank=True)
  class Meta:
    db_table = u'restaurant'
  def get_absolute_url(self):
    return "/restaurant/%s" % (self.stripped_title,)

class RestaurantVersion(models.Model):
  id               = models.IntegerField(primary_key=True)
  chain            = models.IntegerField(null=True, blank=True)
  description      = models.TextField(blank=True)
  url              = models.CharField(max_length=765, blank=True)
  created_at       = models.DateTimeField(null=True, blank=True)
  restaurant       = models.ForeignKey(Restaurant, null=True, blank=True)
  user             = models.ForeignKey(Profile, null=True, blank=True)
  html_description = models.TextField(blank=True)
  class Meta:
    db_table = u'restaurant_version'

class Country(models.Model):
    iso            = models.CharField(max_length=6, primary_key=True)
    name           = models.CharField(max_length=240)
    printable_name = models.CharField(max_length=240)
    iso3           = models.CharField(max_length=9, blank=True)
    numcode        = models.IntegerField(null=True, blank=True)
    class Meta:
        db_table = u'country'

class Location(models.Model):
    id              = models.IntegerField(primary_key=True)
    restaurant      = models.ForeignKey(Restaurant, null=True, blank=True)
    data_source     = models.CharField(unique=True, max_length=96, blank=True)
    data_source_key = models.CharField(unique=True, max_length=765, blank=True)
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
    class Meta:
        db_table = u'location'


class MenuItem(models.Model):
    id             = models.IntegerField(primary_key=True)
    name           = models.CharField(max_length=765, blank=True)
    url            = models.CharField(max_length=765, blank=True)
    version        = models.ForeignKey('MenuitemVersion', null=True, blank=True)
    restaurant     = models.ForeignKey(Restaurant, null=True, blank=True)
    approved       = models.IntegerField(null=True, blank=True)
    average_rating = models.FloatField(null=True, blank=True)
    num_ratings    = models.IntegerField(null=True, blank=True)
    updated_at     = models.DateTimeField(null=True, blank=True)
    created_at     = models.DateTimeField(null=True, blank=True)

    class Meta:
        db_table = u'menu_item'

    def __unicode__(self):
        return self.name

    def get_absolute_url(self):
        "http://prod.rbu.sf/frontend_dev.php/restaurant/hobees/menu/special-traditional-eggs-benedict"
        return "%s/menu/%s" % (self.restaurant.get_absolute_url(), self.url)


class MenuitemVersion(models.Model):
    id               = models.IntegerField(primary_key=True)
    description      = models.TextField(blank=True)
    html_description = models.TextField(blank=True)
    location         = models.ForeignKey(Location, null=True, blank=True)
    menuitem         = models.ForeignKey(MenuItem, null=True, blank=True)
    user             = models.ForeignKey(Profile, null=True, blank=True)
    price            = models.CharField(max_length=48, blank=True)
    created_at       = models.DateTimeField(null=True, blank=True)
    class Meta:
        db_table = u'menuitem_version'

# 
# 
# 
# 
# class MenuImage(models.Model):
#     id = models.IntegerField(primary_key=True)
#     restaurant = models.ForeignKey(Restaurant, null=True, blank=True)
#     location = models.ForeignKey(Location, null=True, blank=True)
#     filename = models.CharField(max_length=765, blank=True)
#     approved = models.IntegerField(null=True, blank=True)
#     updated_at = models.DateTimeField(null=True, blank=True)
#     created_at = models.DateTimeField(null=True, blank=True)
#     class Meta:
#         db_table = u'menu_image'
# 
# 
# class MenuItemImage(models.Model):
#     id = models.IntegerField(primary_key=True)
#     user = models.ForeignKey(Profile, null=True, blank=True)
#     menu_item = models.ForeignKey(MenuItem, null=True, blank=True)
#     data = models.TextField(blank=True)
#     md5sum = models.CharField(max_length=96, blank=True)
#     height = models.IntegerField(null=True, blank=True)
#     width = models.IntegerField(null=True, blank=True)
#     class Meta:
#         db_table = u'menu_item_image'
# 
# class MenuitemNote(models.Model):
#     id = models.IntegerField(primary_key=True)
#     menu_item = models.ForeignKey(MenuItem, null=True, blank=True)
#     user = models.ForeignKey(Profile, null=True, blank=True)
#     note = models.TextField(blank=True)
#     updated_at = models.DateTimeField(null=True, blank=True)
#     created_at = models.DateTimeField(null=True, blank=True)
#     html_note = models.TextField(blank=True)
#     class Meta:
#         db_table = u'menuitem_note'
# 
# class MenuitemRating(models.Model):
#     id = models.IntegerField(primary_key=True)
#     menu_item = models.ForeignKey(MenuItem, null=True, blank=True)
#     user = models.ForeignKey(Profile, null=True, blank=True)
#     value = models.IntegerField(null=True, blank=True)
#     created_at = models.DateTimeField(null=True, blank=True)
#     class Meta:
#         db_table = u'menuitem_rating'
# 
# class MenuitemSearchIndex(models.Model):
#     menuitem = models.ForeignKey(MenuItem, null=True, blank=True)
#     word = models.CharField(max_length=765, blank=True)
#     weight = models.IntegerField(null=True, blank=True)
#     class Meta:
#         db_table = u'menuitem_search_index'
# 
# class MenuitemTag(models.Model):
#     menu_item = models.ForeignKey(MenuItem, null=True, blank=True)
#     user = models.ForeignKey(Profile, null=True, blank=True)
#     tag = models.CharField(max_length=300, blank=True)
#     normalized_tag = models.CharField(max_length=300, blank=True)
#     created_at = models.DateTimeField(null=True, blank=True)
#     id = models.IntegerField()
#     class Meta:
#         db_table = u'menuitem_tag'
# 
# 
# class FacebookProfileRel(models.Model):
#     fbid = models.IntegerField(primary_key=True)
#     profile = models.ForeignKey(Profile, null=True, blank=True)
#     updated_at = models.DateTimeField(null=True, blank=True)
#     created_at = models.DateTimeField(null=True, blank=True)
#     class Meta:
#         db_table = u'facebook_profile_rel'
# 
# 
# class RestaurantNote(models.Model):
#     id = models.IntegerField(primary_key=True)
#     user = models.ForeignKey(Profile, null=True, blank=True)
#     note = models.TextField(blank=True)
#     restaurant = models.ForeignKey(Restaurant, null=True, blank=True)
#     location = models.ForeignKey(Location, null=True, blank=True)
#     updated_at = models.DateTimeField(null=True, blank=True)
#     created_at = models.DateTimeField(null=True, blank=True)
#     html_note = models.TextField(blank=True)
#     class Meta:
#         db_table = u'restaurant_note'
# 
# class RestaurantRating(models.Model):
#     id = models.IntegerField(primary_key=True)
#     restaurant = models.ForeignKey(Restaurant, null=True, blank=True)
#     value = models.IntegerField(null=True, blank=True)
#     location = models.ForeignKey(Location, null=True, blank=True)
#     user = models.ForeignKey(Profile, null=True, blank=True)
#     created_at = models.DateTimeField(null=True, blank=True)
#     class Meta:
#         db_table = u'restaurant_rating'
# 
# class RestaurantRedirect(models.Model):
#     old_stripped_title = models.CharField(max_length=765, primary_key=True)
#     restaurant = models.ForeignKey(Restaurant, null=True, blank=True)
#     updated_at = models.DateTimeField(null=True, blank=True)
#     created_at = models.DateTimeField(null=True, blank=True)
#     class Meta:
#         db_table = u'restaurant_redirect'
# 
# class RestaurantSearchIndex(models.Model):
#     restaurant = models.ForeignKey(Restaurant, null=True, blank=True)
#     word = models.CharField(max_length=765, blank=True)
#     weight = models.IntegerField(null=True, blank=True)
#     class Meta:
#         db_table = u'restaurant_search_index'
# 
# class RestaurantTag(models.Model):
#     restaurant = models.ForeignKey(Restaurant)
#     user = models.ForeignKey(Profile)
#     created_at = models.DateTimeField(null=True, blank=True)
#     tag = models.CharField(max_length=300, blank=True)
#     normalized_tag = models.CharField(max_length=300)
#     class Meta:
#         db_table = u'restaurant_tag'
# 
# class SfGuardGroup(models.Model):
#     id = models.IntegerField(primary_key=True)
#     name = models.CharField(max_length=765)
#     description = models.TextField()
#     class Meta:
#         db_table = u'sf_guard_group'
# 
# class SfGuardGroupPermission(models.Model):
#     group = models.ForeignKey(SfGuardGroup)
#     permission = models.ForeignKey(SfGuardPermission)
#     class Meta:
#         db_table = u'sf_guard_group_permission'
# 
# class SfGuardModule(models.Model):
#     module_name = models.CharField(max_length=300, primary_key=True)
#     class Meta:
#         db_table = u'sf_guard_module'
# 
# class SfGuardPermission(models.Model):
#     id = models.IntegerField(primary_key=True)
#     name = models.CharField(max_length=765)
#     module_name = models.ForeignKey(SfGuardModule, null=True, db_column='module_name', blank=True)
#     description = models.TextField()
#     action_name = models.CharField(max_length=300, blank=True)
#     class Meta:
#         db_table = u'sf_guard_permission'
# 
# class SfGuardRememberKey(models.Model):
#     user = models.ForeignKey(SfGuardUser)
#     remember_key = models.CharField(max_length=96, blank=True)
#     ip_address = models.CharField(max_length=45, primary_key=True)
#     created_at = models.DateTimeField(null=True, blank=True)
#     class Meta:
#         db_table = u'sf_guard_remember_key'
# 
# class SfGuardUser(models.Model):
#     id = models.IntegerField(primary_key=True)
#     username = models.CharField(max_length=384)
#     algorithm = models.CharField(max_length=384)
#     salt = models.CharField(max_length=384)
#     password = models.CharField(max_length=384)
#     created_at = models.DateTimeField()
#     last_login = models.DateTimeField()
#     is_active = models.IntegerField()
#     is_super_admin = models.IntegerField()
#     class Meta:
#         db_table = u'sf_guard_user'
# 
# class SfGuardUserGroup(models.Model):
#     group = models.ForeignKey(SfGuardGroup)
#     user = models.ForeignKey(SfGuardUser)
#     class Meta:
#         db_table = u'sf_guard_user_group'
# 
# class SfGuardUserPermission(models.Model):
#     user = models.ForeignKey(SfGuardUser)
#     permission = models.ForeignKey(SfGuardPermission)
#     class Meta:
#         db_table = u'sf_guard_user_permission'
# 
# class State(models.Model):
#     usps = models.CharField(max_length=6, primary_key=True)
#     name = models.CharField(max_length=240, blank=True)
#     class Meta:
#         db_table = u'state'
# 
# class YahooLocalCategory(models.Model):
#     yid = models.IntegerField(primary_key=True)
#     description = models.CharField(max_length=192, blank=True)
#     class Meta:
#         db_table = u'yahoo_local_category'
# 
