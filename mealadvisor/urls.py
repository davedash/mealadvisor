from django.conf.urls.defaults import *

urlpatterns = patterns('',
  (r'^static/(?P<path>.*)$', 'django.views.static.serve', 
    {'document_root': os.path.join(os.path.dirname(__file__), "static")}
  ),
  (r'^$', 'mealadvisor.common.views.home'),
)
