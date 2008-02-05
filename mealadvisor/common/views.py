from django.shortcuts import render_to_response, get_object_or_404

def home(request):
  return render_to_response("common/index.html")
  
def search(request):
  # get results from model
  return render_to_response("common/search.html")