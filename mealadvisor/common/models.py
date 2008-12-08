# coding=utf-8

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
# 
# 
# class YahooLocalCategory(models.Model):
#     yid = models.IntegerField(primary_key=True)
#     description = models.CharField(max_length=192, blank=True)
#     class Meta:
#         db_table = u'yahoo_local_category'
# 
