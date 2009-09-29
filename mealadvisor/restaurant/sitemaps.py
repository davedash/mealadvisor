from django.contrib.sitemaps import Sitemap
from models import Restaurant, MenuItem as Dish, Location

class RestaurantSitemap(Sitemap):
    changefreq = "daily"
    priority = 0.5

    def items(self):
        return Restaurant.objects.all()

    def lastmod(self, obj):
        return obj.updated_at

#
class DishSitemap(Sitemap):
    changefreq = "daily"
    priority = 0.6

    def items(self):
        return Dish.objects.all()

    def lastmod(self, obj):
        return obj.updated_at

#
class LocationSitemap(Sitemap):
    changefreq = "never"
    priority = 0.4

    def items(self):
        return Location.objects.all()

    def lastmod(self, obj):
        return obj.updated_at
