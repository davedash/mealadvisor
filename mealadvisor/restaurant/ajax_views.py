from django.contrib.auth.decorators import login_required
from django.http import HttpResponse
from django.shortcuts import render_to_response
from django.template import RequestContext

from models import RestaurantTag
from forms import TagAddForm
from views import get_restaurant


@login_required    
def tags(request):
    q = ''
    if 'q' in request.REQUEST:
        q = request.REQUEST['q']
        
    tags = RestaurantTag.objects.get_tags_for_user(request.user.get_profile(), q)
    return HttpResponse("\n".join(tags), mimetype="text/plain")
    

@login_required
def tag_add(request):
    form       = TagAddForm(request.POST)
    restaurant = get_restaurant(request.REQUEST['restaurant'])

    if form.is_valid():
        tag, created = RestaurantTag.objects.get_or_create(restaurant=restaurant, user=request.user.get_profile(), tag=request.REQUEST['tag'])
        
        tag.save()
    
    
    return render_to_response("restaurant/tags.html", locals(), context_instance=RequestContext(request))

@login_required
def tag_remove(request):
    
    id         = request.REQUEST['id']
    tag        = RestaurantTag.objects.get(id=id)
    restaurant = tag.restaurant
    
    tag.delete()

    return render_to_response("restaurant/tags.html", locals(), context_instance=RequestContext(request))

