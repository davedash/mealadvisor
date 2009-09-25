from django.contrib import admin # pragma: no cover
from django.forms import models # pragma: no cover
from models import * # pragma: no cover


class RestaurantVersionInline(admin.StackedInline):
    model   = RestaurantVersion
    max_num = 1
    fields  = ('description', 'url', 'user')


class RestaurantAdmin(admin.ModelAdmin):
    fields        = ('name', 'stripped_title')
    inlines       = (RestaurantVersionInline, )
    raw_id_fields = ('version', )


admin.site.register(Restaurant, RestaurantAdmin)
