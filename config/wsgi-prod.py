import os
import site
import sys

site.addsitedir('/a/mealadvisor.us/lib/python2.6/site-packages/')
site.addsitedir('/a/mealadvisor.us/release/')
site.addsitedir('/a/mealadvisor.us/release/mealadvisor')

os.environ['DJANGO_SETTINGS_MODULE'] = 'mealadvisor.settings'
os.environ['DJANGO_ENVIRONMENT']     = 'prod'

sys.stdout = sys.stderr

import django.core.handlers.wsgi

application = django.core.handlers.wsgi.WSGIHandler()