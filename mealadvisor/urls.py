from django.conf.urls.defaults import *
from django.views.generic import list_detail
from mealadvisor.feeds import LatestRestaurants, MenuItems
from mealadvisor.restaurant.models import Restaurant

from os import path

feeds = {
    'latest'        : LatestRestaurants,
    'restaurant'    : MenuItems,
}

urlpatterns = patterns('',

    # authentication
    (
        r'^login$', 
        'django.contrib.auth.views.login'
    ),
    
    (
        r'^logout$', 
        'django.contrib.auth.views.logout',
        { 'next_page' : '/' }
    ),
    
    # OpenID    
    (r'^openid/', include('spindrop.django.openid.consumer.urls')),
    

    # feed urls
    (
        r'^feed/(?P<url>.*)/$',
        'django.contrib.syndication.views.feed',
        {'feed_dict': feeds}
    ),
    
    # restaurant feed
    (
        r'^(?P<url>restaurant.*)/feed',
        'django.contrib.syndication.views.feed',
        {'feed_dict': feeds}
    ),
)

urlpatterns += patterns('mealadvisor.common.views',
    # Menu Item Image
    (r'^menuitem_image/(?P<md5>[a-f0-9]{32})$', 'menuitem_image',),

    # Menu Item
    (r'^restaurant/(?P<slug>[^/]+)/menu/(?P<item_slug>[^/]+)$', 'menuitem'),

    # Search
    (r'^search$', 'search'),

    # Home
    (r'^$', 'home'),
)

urlpatterns += patterns('mealadvisor.restaurant.views',
    # Restaurant
    (r'^restaurant/(?P<slug>[^/]+)$', 'restaurant'),
    # Restaurant/rating
    (r'^restaurant/(?P<slug>[^/]+)/rate/$', 'rate'),

)
# static pages
urlpatterns += patterns('django.views.generic.simple',
    (r'^about$', 'direct_to_template', {'template': 'common/about.html'}),
)

urlpatterns += patterns('',
    # static content... leave this out on production
    (
        r'^static/(?P<path>.*)$', 
        'django.views.static.serve', 
        {'document_root': path.join(path.dirname(__file__), "static")}
    ),
)