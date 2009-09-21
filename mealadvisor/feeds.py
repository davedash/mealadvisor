from django.contrib.syndication.feeds import FeedDoesNotExist, Feed
from mealadvisor.restaurant.models import Restaurant, MenuItem


class LatestRestaurants(Feed):
    title       = "Freshest Restaurants"
    link        = "/"
    description = "A list of the freshest restaurants posted to Meal Advisor"

    def items(self):
        return Restaurant.objects.order_by('-created_at')[:10]


class MenuItems(Feed):
    """
    e.g. /restaurant/hobees/feed

    We need the equivalent of
    select menuitems from menuitems, restaurants where restaurant.slug = slug
    """

    def get_object(self, bits):
        return Restaurant.objects.get(stripped_title__exact=bits[0])

    def title(self, obj):
        return "Menu items at %s" % obj.name

    def link(self, obj):
        return obj.get_absolute_url()

    def description(self, obj):
        return "A list of menu items served at %s" % obj.name

    def items(self, obj):
        items = MenuItem.objects.filter(restaurant__id__exact=obj.id)
        return items.order_by('-updated_at')[:10]
