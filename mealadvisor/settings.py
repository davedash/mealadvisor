# Django settings for mealadvisor project.
import os

ENVIRONMENT = os.environ.get('DJANGO_ENVIRONMENT','dev')

# CACHE

CACHE_BACKEND                   = 'memcached://127.0.0.1:11211/'
CACHE_MIDDLEWARE_SECONDS        = 300
CACHE_MIDDLEWARE_KEY_PREFIX     = 'ma'
CACHE_MIDDLEWARE_ANONYMOUS_ONLY = True

LOGIN_HOST     = 'http://127.0.0.1:8000'
CACHE_TIMEOUTS = {'home_images': 43200}
DEBUG          = False

if ENVIRONMENT == 'staging':
    DATABASE_ENGINE   = 'mysql'       
    DATABASE_NAME     = 'ma_staging'
    DATABASE_USER     = 'ma_staging'   
    DATABASE_PASSWORD = 'f3nne7'         
    DATABASE_HOST     = 'localhost'
    DATABASE_PORT     = ''
    
    LOGIN_HOST = 'http://wallace.mealadvisor.us'
else:
    DEBUG          = True
    TEMPLATE_DEBUG = DEBUG

    DATABASE_ENGINE   = 'mysql'      # 'postgresql_psycopg2', 'postgresql', 'mysql', 'sqlite3' or 'oracle'.
    DATABASE_NAME     = 'rbu'        # Or path to database file if using sqlite3.
    DATABASE_USER     = 'root'       # Not used with sqlite3.
    DATABASE_PASSWORD = ''           # Not used with sqlite3.
    DATABASE_HOST     = 'localhost'  # Set to empty string for localhost. Not used with sqlite3.
    DATABASE_PORT     = ''           # Set to empty string for default. Not used with sqlite3.
    
    EMAIL_HOST          = 'smtp.gmail.com'
    EMAIL_HOST_USER     = 'catchall@davedash.com'
    EMAIL_HOST_PASSWORD = 'manipul8'
    EMAIL_PORT          = 587
    EMAIL_USE_TLS       = True

DEFAULT_FROM_MAIL = 'hungry.robot@mealadvsor.us'

ADMINS = (
  ('Dave Dash', 'dave.dash@mealadvisor.us'),
)

MANAGERS = ADMINS


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
MEDIA_ROOT = os.path.realpath(os.path.join(os.path.dirname(__file__),os.path.pardir, "static"))

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
    'django.middleware.cache.UpdateCacheMiddleware',
    'django.middleware.common.CommonMiddleware',
    'django.contrib.sessions.middleware.SessionMiddleware',
    'django.contrib.auth.middleware.AuthenticationMiddleware',
    'django.middleware.doc.XViewMiddleware',
    'django.middleware.transaction.TransactionMiddleware',
    'django.contrib.redirects.middleware.RedirectFallbackMiddleware',
    'django.middleware.cache.FetchFromCacheMiddleware',
)

ROOT_URLCONF = 'mealadvisor.urls'

TEMPLATE_DIRS = (
    os.path.join(os.path.dirname(__file__), "templates"),
)

LOGIN_URL          = '/login'
LOGIN_REDIRECT_URL = '/'

OPENID_SUCCESS = "mealadvisor.common.views.openid_success"

INSTALLED_APPS = (
    'django.contrib.redirects',
    'django.contrib.auth',
    'django.contrib.sites',
    'django.contrib.contenttypes',
    'django.contrib.sessions',
    'spindrop.django.openid.consumer',
    'mealadvisor.common',
    'mealadvisor.restaurant',
    'registration',
    'django_cpserver',
)

if DEBUG:
    MIDDLEWARE_CLASSES += (
    'debug_toolbar.middleware.DebugToolbarMiddleware',
    )
    INSTALLED_APPS += ('debug_toolbar',)


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
