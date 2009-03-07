import sys
sys.path.append('..')

from mealadvisor.settings import *

# Override the settings we need to...

ADMIN_MEDIA_PREFIX = '/media/'

ROOT_URLCONF = 'mealadvisor_admin.urls'



TEMPLATE_DIRS = (
    os.path.join(os.path.dirname(__file__), "templates"),
)


INSTALLED_APPS = (
    'django.contrib.redirects',
    'django.contrib.admin',
    'django.contrib.auth',
    'django.contrib.sites',
    'django.contrib.contenttypes',
    'django.contrib.sessions',
    'spindrop.django.openid.consumer',
    'mealadvisor.common',
    'mealadvisor.restaurant',
    'registration'
)

