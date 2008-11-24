from django.contrib.auth.models import User
from spindrop.django.openid.util import normalize_openid
from mealadvisor.common.models import Profile 

def get_by_openid(openid):
    openid = normalize_openid(openid)
    return Profile.objects.get(user__username__exact=openid)


def create_by_openid(openid):
    openid = normalize_openid(openid)
    user   = User(username = openid)
    user.save()

    profile        = Profile()
    profile.user   = user
    profile.openid = True
    profile.save()

    return profile
