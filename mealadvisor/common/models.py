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
    email       = models.CharField(max_length=384, blank=True, null=True)
    openid      = models.NullBooleanField(null=True, blank=True)
    preferences = models.TextField(null=True)
    about_text  = models.TextField(null=True)
    updated_at = models.DateTimeField(auto_now=True)
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        db_table = u'profile'


class Country(models.Model):
    iso            = models.CharField(max_length=6, primary_key=True)
    name           = models.CharField(max_length=240)
    printable_name = models.CharField(max_length=240)
    iso3           = models.CharField(max_length=9, null=True)
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

# class YahooLocalCategory(models.Model):
#     yid = models.IntegerField(primary_key=True)
#     description = models.CharField(max_length=192, blank=True)
#     class Meta:
#         db_table = u'yahoo_local_category'
# 
