from django.shortcuts import render_to_response, get_object_or_404
from mealadvisor.common.models import Restaurant

def home(request):
    return render_to_response("common/index.html")
  
def search(request):
    # get results from model
    query   = request.GET.get('q', '')
    context = { 'query' : query }
    
    return render_to_response("common/search.html", context)
  
def menuitem(request, slug, item_slug):
    # get results from model
    restaurant = Restaurant.objects.get(stripped_title__exact=slug)
    
    
    return render_to_response("common/menuitem.html", {'restaurant':restaurant})
    
