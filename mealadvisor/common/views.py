from django.template import RequestContext
from django.shortcuts import render_to_response, get_object_or_404
from django.contrib.auth import login
from django.http import HttpResponseRedirect

from mealadvisor.common.models import Restaurant
from mealadvisor.common import profiles


def home(request):
    return render_to_response("common/index.html", context_instance=RequestContext(request))
  
def search(request):
    # get results from model
    query   = request.GET.get('q', '')
    context = { 'query' : query }
    
    return render_to_response("common/search.html", context, context_instance=RequestContext(request))
  
def menuitem(request, slug, item_slug):
    # get results from model
    restaurant = Restaurant.objects.get(stripped_title__exact=slug)
    context = {'restaurant':restaurant}
    
    return render_to_response("common/menuitem.html", context, context_instance=RequestContext(request))
    

def openid_success(request, results):
    context = results
    
    # results["url"] contains the validated URL
    profile = None
    
    # let's 1 determine if a user exists
    try:
        profile = profiles.get_by_openid(results["url"])
    # if they don't autocreate a user for them
    except:
        profile = profiles.create_by_openid(results["url"])
    
    # if they do automatically log them in
    # ### TODO: we need to write a propper backend for OpenID
    profile.user.backend='django.contrib.auth.backends.ModelBackend' 
    login(request, profile.user)

    return HttpResponseRedirect("/")
