# Django settings for mealadvisor project.
import os

DEBUG = True
TEMPLATE_DEBUG = DEBUG

EMAIL_HOST = 'smtp.comcast.net'
EMAIL_PORT = 25
DEFAULT_FROM_MAIL = 'hungry.robot@mealadvsor.us'

ADMINS = (
  ('Dave Dash', 'dave.dash@mealadvisor.us'),
)

MANAGERS = ADMINS

# DATABASE_OPTIONS = {
#    "init_command": "SET storage_engine=INNODB",
# }

DATABASE_ENGINE   = 'mysql'           # 'postgresql_psycopg2', 'postgresql', 'mysql', 'sqlite3' or 'oracle'.
DATABASE_NAME     = 'rbu'             # Or path to database file if using sqlite3.
DATABASE_USER     = 'root'             # Not used with sqlite3.
DATABASE_PASSWORD = ''         # Not used with sqlite3.
DATABASE_HOST     = 'localhost'             # Set to empty string for localhost. Not used with sqlite3.
DATABASE_PORT     = ''             # Set to empty string for default. Not used with sqlite3.

DEFAULT_ENCODING = 'utf-8'
# Local time zone for this installation. Choices can be found here:
# http://en.wikipedia.org/wiki/List_of_tz_zones_by_name
# although not all choices may be avilable on all operating systems.
# If running in a Windows environment this must be set to the same as your
# system time zone.
TIME_ZONE = 'America/Los_Angeles'

# Language code for this installation. All choices can be found here:
# http://www.i18nguy.com/unicode/language-identifiers.html
LANGUAGE_CODE = 'en-us'

SITE_ID = 1

# If you set this to False, Django will make some optimizations so as not
# to load the internationalization machinery.
USE_I18N = True

# Absolute path to the directory that holds media.
# Example: "/home/media/media.lawrence.com/"
MEDIA_ROOT = os.path.join(os.path.dirname(__file__), "static")

# URL that handles the media served from MEDIA_ROOT. Make sure to use a
# trailing slash if there is a path component (optional in other cases).
# Examples: "http://media.lawrence.com", "http://example.com/media/"
MEDIA_URL = '/static/'

# URL prefix for admin media -- CSS, JavaScript and images. Make sure to use a
# trailing slash.
# Examples: "http://foo.com/media/", "/media/".
ADMIN_MEDIA_PREFIX = '/media/'

# Make this unique, and don't share it with anybody.
SECRET_KEY = '$*th1o)%73c$o#!4f0cjg=*968tao8@(*5+y9^_-ru!_hx#a)y'

# List of callables that know how to import templates from various sources.
TEMPLATE_LOADERS = (
    'django.template.loaders.filesystem.load_template_source',
    'django.template.loaders.app_directories.load_template_source',
#     'django.template.loaders.eggs.load_template_source',
)

MIDDLEWARE_CLASSES = (
    'django.middleware.common.CommonMiddleware',
    'django.contrib.sessions.middleware.SessionMiddleware',
    'django.contrib.auth.middleware.AuthenticationMiddleware',
    'django.middleware.doc.XViewMiddleware',
    'django.middleware.transaction.TransactionMiddleware',
)

ROOT_URLCONF = 'mealadvisor.urls'

TEMPLATE_DIRS = (
    os.path.join(os.path.dirname(__file__), "templates"),
)

LOGIN_URL          = '/login'
LOGIN_REDIRECT_URL = '/'

OPENID_SUCCESS = "mealadvisor.common.views.openid_success"

INSTALLED_APPS = (
    'django.contrib.auth',
    'django.contrib.sites',
    'django.contrib.contenttypes',
    'django.contrib.sessions',
    'spindrop.django.openid.consumer',
    'mealadvisor.common',
    'mealadvisor.restaurant',
    'registration'
)

ACCOUNT_ACTIVATION_DAYS = 30

TEMPLATE_CONTEXT_PROCESSORS = (
    'django.core.context_processors.request',
    "django.core.context_processors.auth",
    "django.core.context_processors.debug",
    "django.core.context_processors.i18n",
    "django.core.context_processors.media"
)

AUTH_PROFILE_MODULE = 'common.profile'

INTERNAL_IPS = ('127.0.0.1',)

GOOGLE_API_KEY = 'ABQIAAAAyAAmLPzn6NjNWlSghNqTKxTTEM7e85ZdOLxclOTP3CThLJ0yaxTCLs4phoI67W6KqIr1j4bqwaMTUQ'

SEARCH_DEFAULT_RADIUS = 25
SEARCH_WEIGHT_BODY    = 1
SEARCH_WEIGHT_TITLE   = 2
SEARCH_WEIGHT_TAG     = 3
