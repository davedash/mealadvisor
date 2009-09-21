import Image
import StringIO
import hashlib
import os
import django.core.files

from django.template import RequestContext
from django.shortcuts import render_to_response, get_object_or_404, \
get_list_or_404

from django.http import HttpResponseRedirect
from django.contrib.auth.decorators import login_required
from django.contrib.auth.models import User
from django.core.paginator import Paginator, InvalidPage

from models import *
from forms import *
from search import Search


def get_restaurant(slug):
    return get_object_or_404(Restaurant.objects.all().select_related(depth=1),
    stripped_title__exact=slug)


def restaurant(request, slug):
    restaurant = get_restaurant(slug)

    if request.user.is_authenticated():
        try:
            rating = RestaurantRating.objects.get(restaurant = restaurant,
            user = request.user.get_profile())
            restaurant.current_rating = rating.value
        except:
            pass

    locations = list(restaurant.location_set.all().order_by('state', 'city'))

    if locations:
        main_location = locations.pop(0)

    num_locations = len(locations)

    paginator = Paginator(restaurant.menuitem_set.with_ratings(request.user),
    8)
    page      = paginator.page(1)
    dishes    = page.object_list

    reviews = restaurant.restaurantnote_set.all()\
    .select_related('profile__user')

    # tagbox data
    tags_template    = 'restaurant/tags.html'
    tag_type         = 'restaurant'
    tagged_object_id = restaurant.slug()

    return render_to_response("restaurant/restaurant_detail.html", locals(),
    context_instance=RequestContext(request))


def menu(request, slug):
    restaurant = get_restaurant(slug)
    dishes     = restaurant.menuitem_set.with_ratings(request.user)

    return render_to_response("restaurant/menu.html", locals(),
    context_instance=RequestContext(request))


def menu_page(request, slug, page):
    restaurant = get_restaurant(slug)
    paginator  = Paginator(restaurant.menuitem_set.with_ratings(request.user),
    8)
    page       = paginator.page(page)
    dishes     = page.object_list

    return render_to_response("restaurant/menu_page.html", locals(),
    context_instance=RequestContext(request))


@login_required
def rate(request, slug):
    restaurant = get_restaurant(slug)
    value      = request.REQUEST['value']

    rating, created = RestaurantRating.objects.get_or_create(
    restaurant=restaurant, user=request.user.get_profile())
    rating.value = value
    rating.save()

    restaurant.current_rating = rating.value
    return render_to_response("restaurant/rating.html", locals(),
    context_instance=RequestContext(request))


@login_required
def menuitem_rate(request, slug, item_slug):
    restaurant = get_restaurant(slug)
    menu_item  = get_object_or_404(MenuItem,
    restaurant__stripped_title__exact=slug, slug__exact=item_slug)
    value      = request.REQUEST['value']

    rating, created = MenuitemRating.objects.get_or_create(
    menu_item=menu_item, user=request.user.get_profile())
    rating.value = value
    rating.save()

    menu_item.current_rating = rating.value
    return render_to_response("restaurant/menuitem_rating.html", locals(),
    context_instance=RequestContext(request))


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

            # Redirect after POST
            return HttpResponseRedirect(restaurant.get_absolute_url())
    else:
        form = ReviewForm() # An unbound form

    review_form = form
    return render_to_response('restaurant/review.html', locals(),
    context_instance=RequestContext(request))


def tag(request, tag):
    # get restaurants
    restaurants = Restaurant.objects.get_tagged(tag)
    # get menuitems
    menuitems = MenuItem.objects.get_tagged(tag=tag)
    return render_to_response('restaurant/tag.html', locals(),
    context_instance=RequestContext(request))


@login_required
def menuitem_add(request, slug):
    restaurant = get_restaurant(slug)

    if request.method == 'POST':
        form            = NewMealForm(request.POST)
        form.restaurant = restaurant

        if form.is_valid():
            name        = form.cleaned_data['name']
            description = form.cleaned_data['description']
            price       = form.cleaned_data['price']

            i             = MenuItem(name=name)
            i.restaurant  = restaurant
            i.description = description
            i.price       = price
            i.save()

            # Redirect after POST
            return HttpResponseRedirect(i.get_absolute_url())

    else:
        form = NewMealForm()

    return render_to_response('menuitem/add.html', locals(),
    context_instance=RequestContext(request))


@login_required
def add(request):
    if request.method == 'POST': # If the form has been submitted...
        form = NewRestaurantForm(request.POST) # A form bound to the POST data
        if form.is_valid(): # All validation rules pass
            # create the restaurant

            name        = form.cleaned_data['restaurant_name']
            description = form.cleaned_data['description']
            url         = form.cleaned_data['url']

            r             = Restaurant(name=name)
            r.description = description
            r.url         = url
            r.save()

            address = form.cleaned_data['address']
            city    = form.cleaned_data['city']
            state   = form.cleaned_data['state'].usps
            zipcode = form.cleaned_data['zipcode']
            phone   = form.cleaned_data['phone']

            if (address or phone):
                location = Location(restaurant=r, address=address, city=city,
                state=state, zip=zipcode, phone=phone)

                name = form.cleaned_data['location_name']
                if name:
                    location.name = name

                location.save()

            review = form.cleaned_data['review']

            if review:
                note = RestaurantNote(restaurant=r,
                profile=request.user.get_profile(), note=review)
                note.save()

            # Redirect after POST
            return HttpResponseRedirect(r.get_absolute_url())

    else:
        form = NewRestaurantForm()

    return render_to_response('restaurant/add.html', locals(),
    context_instance=RequestContext(request))


def profile(request, username):
    user        = User.objects.get(username=username)
    profile     = user.profile_set.all()[0]
    restaurants = Restaurant.objects.rated_or_reviewed_by(profile)
    return render_to_response('profile.html', locals(),
    context_instance=RequestContext(request))


def location(request, slug, location_slug):
    location   = get_object_or_404(Location,
    restaurant__stripped_title__exact=slug,
    stripped_title__exact=location_slug)
    restaurant = location.restaurant

    return render_to_response('restaurant/location.html', locals(),
    context_instance=RequestContext(request))


@login_required
def menuitem_add_image(request, slug, item_slug):
    restaurant = get_restaurant(slug)
    menu_item  = get_object_or_404(MenuItem,
    restaurant__stripped_title__exact=slug, slug__exact=item_slug)

    if request.method == 'POST':
        form = NewMenuItemImageForm(request.POST, request.FILES)

        if form.is_valid():
            handle_uploaded_image(request.FILES['image'], menu_item,
            request.user.get_profile())
            return HttpResponseRedirect(menu_item.get_absolute_url())
    else:
        form       = NewMenuItemImageForm()

    return render_to_response('menuitem/add_image.html', locals(),
    context_instance=RequestContext(request))


def handle_uploaded_image(i, menu_item, user):
    # resize image
    imagefile  = StringIO.StringIO(i.read())
    imageImage = Image.open(imagefile)

    (width, height) = imageImage.size
    (width, height) = scale_dimensions(width, height, longest_side=240)

    resizedImage = imageImage.resize((width, height))

    imagefile = StringIO.StringIO()
    resizedImage.save(imagefile, 'JPEG')
    filename = hashlib.md5(imagefile.getvalue()).hexdigest()+'.jpg'


    # #save to disk
    imagefile = open(os.path.join('/tmp', filename), 'w')
    resizedImage.save(imagefile, 'JPEG')
    imagefile = open(os.path.join('/tmp', filename), 'r')
    content = django.core.files.File(imagefile)

    # create MII
    mii = MenuItemImage(user=user, menu_item=menu_item)
    mii.image.save(filename, content)


def scale_dimensions(width, height, **kwargs):

    if 'longest_side' in kwargs and height and width:
        longest_side = kwargs['longest_side']

        if width < height:
            width  = int(float(width)/float(height) * longest_side)
            height = longest_side
        else:
            height = int(float(height)/float(width) * longest_side)
            width  = longest_side

    return (width, height)


def home(request):
    # load n pictures
    from django.core.cache import cache

    images = cache.get('home_images')
    if not images:
        images = MenuItemImage.objects.cheap_random(6).select_related(
        depth=2)
        cache.set('home_images', images,
        settings.CACHE_TIMEOUTS.get('home_images'))

    return render_to_response("common/index.html", {"images": images},
    context_instance=RequestContext(request))


def search(request):
    # determine the type of search
    query = request.GET.get('q', '')
    s     = Search(query)

    context     = {'query': query}

    results = s.get_results()

    if s.result_type == 'Restaurant':
        context['restaurants'] = results
    else:
        context['locations'] = results

    return render_to_response("common/search.html", context,
    context_instance=RequestContext(request))


def menuitem(request, slug, item_slug):
    # get results from model
    restaurant = get_object_or_404(Restaurant, stripped_title__exact=slug)
    menu_item  = get_object_or_404(MenuItem,
    restaurant__stripped_title__exact=slug, slug__exact=item_slug)

    if request.user.is_authenticated():
        try:
            rating = MenuitemRating.objects.get(menu_item = menu_item,
            user = request.user.get_profile())
            menu_item.current_rating = rating.value
        except:
            pass

    tags_template    = 'menuitem/tags.html'
    tag_type         = 'menu_item'
    tagged_object_id = menu_item.id

    reviews      = menu_item.menuitemnote_set.all().select_related(depth=2)
    review_title = 'Reviews'

    if request.method == 'POST': # If the form has been submitted...
        form = ReviewForm(request.POST) # A form bound to the POST data
        if form.is_valid(): # All validation rules pass
            note           = MenuitemNote()
            note.profile   = request.user.get_profile()
            note.note      = form.cleaned_data['note']
            note.menu_item = menu_item
            note.save()

            return HttpResponseRedirect(menu_item.get_absolute_url())
    else:
        form = ReviewForm() # An unbound form


    review_form = form

    return render_to_response("common/menuitem.html", locals(),
    context_instance=RequestContext(request))
