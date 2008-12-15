from django.template import RequestContext
from django.shortcuts import render_to_response, get_object_or_404, get_list_or_404
from django.http import HttpResponseRedirect
from models import *
from django.contrib.auth.decorators import login_required
from django.core.paginator import Paginator, InvalidPage
from forms import ReviewForm


def get_restaurant(slug):
    return get_object_or_404(Restaurant.objects.all().select_related(depth=1), stripped_title__exact=slug)
    
def restaurant(request, slug):
    restaurant = get_restaurant(slug)

    if request.user.is_authenticated():
        try:
            rating                    = RestaurantRating.objects.get(restaurant = restaurant.id, user = request.user.get_profile().id)
            restaurant.current_rating = rating.value
        except:
            pass
    
    locations     = list(restaurant.location_set.all())
    main_location = locations.pop(0)
    num_locations = len(locations)

    paginator = Paginator(restaurant.menuitem_set.with_ratings(request.user), 8)
    page      = paginator.page(1)
    dishes    = page.object_list
    
    reviews = restaurant.restaurantnote_set.all().select_related('profile__user')

    # tagbox data
    tags_template    = 'restaurant/tags.html'
    tag_type         = 'restaurant'
    tagged_object_id = restaurant.slug()
    
    return render_to_response("restaurant/restaurant_detail.html", locals(), context_instance=RequestContext(request))

    
def menu(request, slug):
    restaurant = get_restaurant(slug)
    dishes     = restaurant.menuitem_set.with_ratings(request.user)

    return render_to_response("restaurant/menu.html", locals(), context_instance=RequestContext(request))


def menu_page(request, slug, page):
    restaurant = get_restaurant(slug)
    paginator  = Paginator(restaurant.menuitem_set.with_ratings(request.user), 8)
    page       = paginator.page(page)
    dishes     = page.object_list
    
    return render_to_response("restaurant/menu_page.html", locals(), context_instance=RequestContext(request))
    
    
    
@login_required    
def rate(request, slug):
    restaurant = get_restaurant(slug)
    value      = request.REQUEST['value']

    rating, created = RestaurantRating.objects.get_or_create(restaurant=restaurant, user=request.user.get_profile())
    rating.value = value
    rating.save()

    restaurant.current_rating = rating.value
    return render_to_response("restaurant/rating.html", locals(), context_instance=RequestContext(request))
    
    
@login_required    
def menuitem_rate(request, slug, item_slug):
    restaurant = get_restaurant(slug)
    menu_item  = get_object_or_404(MenuItem, restaurant__stripped_title__exact=slug, slug__exact=item_slug)
    value      = request.REQUEST['value']

    rating, created = MenuitemRating.objects.get_or_create(menu_item=menu_item, user=request.user.get_profile())
    rating.value = value
    rating.save()

    menu_item.current_rating = rating.value
    return render_to_response("restaurant/menuitem_rating.html", locals(), context_instance=RequestContext(request))

@login_required
def review(request, slug):    
    restaurant = get_restaurant(slug)
    
    if request.method == 'POST': # If the form has been submitted...
        form = ReviewForm(request.POST) # A form bound to the POST data
        if form.is_valid(): # All validation rules pass
            note = RestaurantNote()
            note.profile = request.user.get_profile()
            note.note = form.cleaned_data['note']
            note.restaurant = restaurant
            note.save()
            
            return HttpResponseRedirect(restaurant.get_absolute_url()) # Redirect after POST
    else:
        form = ReviewForm() # An unbound form

    review_form = form
    return render_to_response('restaurant/review.html', locals(), context_instance=RequestContext(request))
    
    
