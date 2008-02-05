from django.conf.urls.defaults import *
from django.views.generic import list_detail
from mealadvisor.feeds import LatestRestaurants
from mealadvisor.common.models import Restaurant
from os import path

feeds = {
    'latest'        : LatestRestaurants,
}

restaurant_info_dict = {
    'queryset'   : Restaurant.objects.all(),
    'slug_field' : 'stripped_title',
}

urlpatterns = patterns('',
    # feed urls
    (
        r'^feed/(?P<url>.*)/$',
        'django.contrib.syndication.views.feed',
        {'feed_dict': feeds}
    ),
)

urlpatterns += patterns('mealadvisor.common.views',
    # Restaurant
    (r'^restaurant/(?P<slug>.+)$', list_detail.object_detail, restaurant_info_dict),
    
    # Search
    (r'^search$', 'search'),

    # Home
    (r'^$', 'home'),
)

urlpatterns += patterns('',
    # static content... leave this out on production
    (
        r'^static/(?P<path>.*)$', 
        'django.views.static.serve', 
        {'document_root': path.join(path.dirname(__file__), "static")}
    ),
)