from django.template import RequestContext
from django.shortcuts import render_to_response, get_object_or_404, get_list_or_404
from django.contrib.auth import login
from django.http import HttpResponse, HttpResponseRedirect

from mealadvisor.restaurant.models import Restaurant, MenuItemImage, MenuItem
from mealadvisor.common import profiles

class Search:
    RESTAURANT_BY_NAME            = 1
    LOCATION_IN_PLACE             = 2
    RESTAURANT_BY_NAME_IN_PLACE   = 3
    LOCATION_NEAR_PLACE           = 4
    RESTAURANT_BY_NAME_NEAR_PLACE = 5

    def __init__(self, query):
        self.query       = query
        self.search_type = self.RESTAURANT_BY_NAME
        self.name        = None
        self.result_type = 'Restaurant'
        
        import re
        r = re.compile(r'(?:(.*)\b(in|near)\b\W*(.*)|(.*))')
        (restaurant_name, in_or_near, place, name) = r.match(query).groups()
        
        # we are RESTAURANT_BY_NAME if "\bnear[: ]"
        if name <> None:
            self.name = name.strip()
        else:
            self.place       = place.strip()
            self.result_type = 'Location'
            
            if restaurant_name <> None:
                self.name  = restaurant_name.strip()

                # this can either be 
                # RESTAURANT_BY_NAME_IN_PLACE   
                # RESTAURANT_BY_NAME_NEAR_PLACE             
                self.search_type = self.RESTAURANT_BY_NAME_IN_PLACE \
                if in_or_near == 'in' else self.RESTAURANT_BY_NAME_NEAR_PLACE
            
            else:
                self.search_type = self.LOCATION_IN_PLACE \
                if in_or_near == 'in' else self.LOCATION_NEAR_PLACE
            
        
        

def home(request):
    # load n pictures   
    images = MenuItemImage.objects.select_related(depth=2).order_by('?')[:6]
    return render_to_response("common/index.html", {"images": images}, context_instance=RequestContext(request))
  

def search(request):
    # determine the type of search
    query = request.GET.get('q', '')
    s     = Search(query)
    
    #get_search_type(query)
    # - name of restaurant
    # - restaurant near location
    # - location
    
    # get results from model
    restaurants = Restaurant.objects.search(query)
    context     = { 'query' : query, 'restaurants' : restaurants }
    
    return render_to_response("common/search.html", context, context_instance=RequestContext(request))
  
def menuitem(request, slug, item_slug):
    # get results from model
    restaurant = get_object_or_404(Restaurant, stripped_title__exact=slug)
    menu_item  = get_object_or_404(MenuItem, restaurant__stripped_title__exact=slug, slug__exact=item_slug)
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
