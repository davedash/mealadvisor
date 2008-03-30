from django.template import RequestContext
from django.shortcuts import render_to_response, get_object_or_404, get_list_or_404
from models import Restaurant, RestaurantRating, MenuItem, MenuitemRating
from django.contrib.auth.decorators import login_required

def restaurant(request, slug):
    restaurant                = get_object_or_404(Restaurant.objects.all().select_related(depth=1), stripped_title__exact=slug)

    if request.user.is_authenticated():
        try:
            rating                    = RestaurantRating.objects.get(restaurant = restaurant.id, user = request.user.get_profile().id)
            restaurant.current_rating = rating.value
        except:
          pass
    locations     = list(restaurant.location_set.all())
    main_location = locations.pop(0)
    num_locations = len(locations)
    dishes        = restaurant.menuitem_set.with_ratings(request.user)
    
    return render_to_response("restaurant/restaurant_detail.html", locals(), context_instance=RequestContext(request))

    
def menu(request, slug):
    restaurant = get_object_or_404(Restaurant.objects.all().select_related(depth=1), stripped_title__exact=slug)
    dishes     = restaurant.menuitem_set.with_ratings(request.user)

    return render_to_response("restaurant/menu.html", locals(), context_instance=RequestContext(request))

    
    
@login_required    
def rate(request, slug):
    
    restaurant = get_object_or_404(Restaurant.objects.all().select_related(depth=1), stripped_title__exact=slug)
    value      = request['value']

    rating, created = RestaurantRating.objects.get_or_create(restaurant=restaurant, user=request.user.get_profile())
    rating.value = value
    rating.save()

    restaurant.current_rating = rating.value
    return render_to_response("restaurant/rating.html", locals(), context_instance=RequestContext(request))
    
    
@login_required    
def menuitem_rate(request, slug, item_slug):
    restaurant = get_object_or_404(Restaurant, stripped_title__exact=slug)
    menu_item  = get_object_or_404(MenuItem, restaurant__stripped_title__exact=slug, slug__exact=item_slug)
    value      = request['value']

    rating, created = MenuitemRating.objects.get_or_create(menu_item=menu_item, user=request.user.get_profile())
    rating.value = value
    rating.save()

    menu_item.current_rating = rating.value
    return render_to_response("restaurant/menuitem_rating.html", locals(), context_instance=RequestContext(request))
