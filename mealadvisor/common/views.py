from django.template import RequestContext
from django.shortcuts import render_to_response, get_object_or_404, get_list_or_404
from django.contrib.auth import login
from django.http import HttpResponse, HttpResponseRedirect

from mealadvisor.restaurant.models import Restaurant, MenuItemImage, MenuItem, MenuitemRating
from mealadvisor.common import profiles
from mealadvisor.common.search import Search

def home(request):
    # load n pictures   
    images = MenuItemImage.objects.select_related(depth=2).order_by('?')[:6]
    return render_to_response("common/index.html", {"images": images}, context_instance=RequestContext(request))
  

def search(request):
    # determine the type of search
    query = request.GET.get('q', '')
    s     = Search(query)
    
    context     = { 'query' : query }
    
    results = s.get_results()
    
    if s.result_type == 'Restaurant':
        context['restaurants'] = results
    else:
        context['locations'] = results
            
    return render_to_response("common/search.html", context, context_instance=RequestContext(request))
  
def menuitem(request, slug, item_slug):
    # get results from model
    restaurant = get_object_or_404(Restaurant, stripped_title__exact=slug)
    menu_item  = get_object_or_404(MenuItem, restaurant__stripped_title__exact=slug, slug__exact=item_slug)
    
    if request.user.is_authenticated():
        try:
            rating                   = MenuitemRating.objects.get(menu_item = menu_item, user = request.user.get_profile())
            menu_item.current_rating = rating.value
        except:
            pass

    context    = {'restaurant':restaurant, 'menu_item':menu_item}

    return render_to_response("common/menuitem.html", context, context_instance=RequestContext(request))


def menuitem_image(request, md5):
    image = get_list_or_404(MenuItemImage, md5sum=md5)[0]
    return HttpResponse(image.data, mimetype="image/jpeg")


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
