import os
import site
import sys

site.addsitedir('/a/mealadvisor.us/lib/python2.5/site-packages/')
site.addsitedir('/a/mealadvisor.us/staging/mealadvisor/')

os.environ['DJANGO_SETTINGS_MODULE'] = 'mealadvisor.settings'
os.environ['DJANGO_ENVIRONMENT']     = 'staging'

sys.stdout = sys.stderr

import django.core.handlers.wsgi

application = django.core.handlers.wsgi.WSGIHandler()