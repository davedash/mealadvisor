# coding=utf-8
# This is an auto-generated Django model module.
# You'll have to do the following manually to clean this up:
#     * Rearrange models' order
#     * Make sure each model has one field with primary_key=True
# Feel free to rename the models, but don't rename db_table values or field names.
#
# Also note: You'll have to insert the output of 'django-admin.py sqlcustom [appname]'
# into your database.

from django.db import models
from django.db.models import Q
from django.contrib.auth.models import User


class CountryManager(models.Manager):
    
    def retrieve_magically(self, country):
        
        return self.get(Q(name = country) \
        | Q(iso = country) | Q(iso3 = country))

class StateManager(models.Manager):

    def retrieve_magically(self, state):

        return self.get(Q(name = state) | Q(usps = state))


class Profile(models.Model):
    user        = models.ForeignKey(User, db_column="userid", unique=True)
    email       = models.CharField(max_length=384, blank=True)
    openid      = models.BooleanField(null=True, blank=True)
    preferences = models.TextField(blank=True)
    about_text  = models.TextField(blank=True)
    updated_at = models.DateTimeField(auto_now=True)
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        db_table = u'profile'


class Country(models.Model):
    iso            = models.CharField(max_length=6, primary_key=True)
    name           = models.CharField(max_length=240)
    printable_name = models.CharField(max_length=240)
    iso3           = models.CharField(max_length=9, blank=True)
    numcode        = models.IntegerField(null=True, blank=True)
    objects        = CountryManager()
    class Meta:
        db_table = u'country'
		

class State(models.Model):
    usps    = models.CharField(max_length=6, primary_key=True)
    name    = models.CharField(max_length=240, blank=True)
    objects = StateManager()
    
    class Meta:
        db_table = u'state'


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
# 
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
# 
# class YahooLocalCategory(models.Model):
#     yid = models.IntegerField(primary_key=True)
#     description = models.CharField(max_length=192, blank=True)
#     class Meta:
#         db_table = u'yahoo_local_category'
# 
