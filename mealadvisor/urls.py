from django.conf.urls.defaults import *
from django.views.generic import list_detail
from mealadvisor.feeds import LatestRestaurants, MenuItems
from mealadvisor.restaurant.models import Restaurant

from os import path
import settings

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
    # feedback
    (r'^feedback/?', 'feedback'),
)

urlpatterns += patterns('mealadvisor.restaurant.views',
    # Home
    (r'^$', 'home'),

    # Menu Item
    (r'^restaurant/(?P<slug>[^/]+)/menu/(?P<item_slug>[^/]+)$', 'menuitem'),

    # Search
    (r'^search$', 'search'),
    

    # Restaurant    
    (r'^restaurant/(?P<slug>[^/]+)/?$', 'restaurant'),
    
    # Location
    (r'^restaurant/(?P<slug>[^/]+)/location/(?P<location_slug>[^/]+)$', 'location'),
    
    # Full Menu
    (r'^restaurant/(?P<slug>[^/]+)/menu$', 'menu'),

    # Full Menu
    (r'^restaurant/(?P<slug>[^/]+)/add/item$', 'menuitem_add'),

    # single menu page 
    (r'^restaurant/(?P<slug>[^/]+)/menu/page/(?P<page>\d+)$', 'menu_page'),
    
    # Restaurant/rating
    (r'^restaurant/(?P<slug>[^/]+)/rate/$', 'rate'),
    
    # Menu Item rating
    # Restaurant/rating
    (r'^restaurant/(?P<slug>[^/]+)/menu/(?P<item_slug>[^/]+)/rate/$', 'menuitem_rate'),

    # Restaurant Review
    (r'^restaurant/(?P<slug>[^/]+)/review$', 'review'),
    
    # Tag page
    (r'^tag/(?P<tag>[^/]+)/?', 'tag'),
    
    # Add Restaurant
    (r'^add/restaurant/?$', 'add'),
    
    (r'^profile/(?P<username>[^/]+)/?', 'profile'),

    # add image
    (r'^restaurant/(?P<slug>[^/]+)/menu/(?P<item_slug>[^/]+)/add/image$', 'menuitem_add_image'),
    
)

# registration
urlpatterns += patterns('',
    (r'^register/?$', 'registration.views.register'),
    (r'^accounts/', include('registration.urls')),
)

# static pages
urlpatterns += patterns('django.views.generic.simple',
    (r'^about$', 'direct_to_template', {'template': 'common/about.html'}),
    (r'^contact/thanks/$', 'direct_to_template', {'template': 'contact_thanks.html'}),

)

urlpatterns += patterns('',
    # static content... leave this out on production
    (
        r'^static/(?P<path>.*)$', 
        'django.views.static.serve', 
        {'document_root': settings.MEDIA_ROOT}
    ),
)

urlpatterns += patterns('restaurant.ajax_views',
    (r'^ajax/tag_ac$', 'tags'),
    (r'^ajax/tag_add_restaurant$', 'tag_add'),
    (r'^ajax/tag_add_menu_item$', 'tag_add_menuitem'),
    (r'^ajax/tag_rm$', 'tag_remove'),
)
