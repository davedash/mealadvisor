from django.contrib.auth.decorators import login_required
from django.http import HttpResponse
from django.shortcuts import render_to_response
from django.template import RequestContext

from tagging import utils

from models import RestaurantTag, MenuitemTag, MenuItem
from forms import TagAddForm, MenuitemTagAddForm
from views import get_restaurant


@login_required    
def tags(request):
    q = ''
    if 'q' in request.REQUEST:
        q = request.REQUEST['q']
    
    # RestaurantTag gets for both menu_item and restaurant  
    tags = RestaurantTag.objects.get_tags_for_user(request.user.get_profile(), q)
    
    return HttpResponse("\n".join(tags), mimetype="text/plain")
    

@login_required
def tag_add(request):
    form       = TagAddForm(request.POST)
    restaurant = get_restaurant(request.REQUEST['restaurant'])

    if form.is_valid():
        tags = utils.parse_tag_input(request.REQUEST['tag'])
        
        for t in tags:
            tag, created = RestaurantTag.objects.get_or_create(restaurant=restaurant, user=request.user.get_profile(), tag=t)
            tag.save()
    
    
    return render_to_response("restaurant/tags.html", locals(), context_instance=RequestContext(request))

@login_required
def tag_add_menuitem(request):
    form     = MenuitemTagAddForm(request.POST)
    
    menuitem = MenuItem.objects.get(id=request.REQUEST['menu_item'])

    if form.is_valid():
        tags = utils.parse_tag_input(request.REQUEST['tag'])
        
        for t in tags:
            tag, created = MenuitemTag.objects.get_or_create(menu_item=menuitem, user=request.user.get_profile(), tag=t)
            tag.save()


    menu_item = menuitem
    return render_to_response("menuitem/tags.html", locals(), context_instance=RequestContext(request))
    
    
@login_required
def tag_remove(request):
    id = request.REQUEST['id']

    if 't' in request.REQUEST and request.REQUEST['t'] == 'menu_item':
        tag       = MenuitemTag.objects.get(id=id)
        menu_item = tag.menu_item
        tag.delete()
        return render_to_response("menuitem/tags.html", locals(), context_instance=RequestContext(request))
        
        
    tag        = RestaurantTag.objects.get(id=id)
    restaurant = tag.restaurant
    tag.delete()

    return render_to_response("restaurant/tags.html", locals(), context_instance=RequestContext(request))

