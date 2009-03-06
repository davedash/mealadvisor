from django.template import RequestContext
from django.shortcuts import render_to_response, get_object_or_404, get_list_or_404
from django.contrib.auth import login
from django.http import HttpResponse, HttpResponseRedirect
from django.core.mail import EmailMessage

from mealadvisor.common import profiles

from forms import *

import settings 



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

def feedback(request):
    if request.method == 'POST':
        form = ContactForm(request.POST)
    
        if form.is_valid():
            topic   = form.cleaned_data['topic']
            sender  = form.cleaned_data.get('email')
            message = form.cleaned_data['message']
            message += "\n\n\nFROM: %s" % sender

            email = EmailMessage(
                'Feedback from your site, topic: %s' % topic,
                message, sender,
                (settings.ADMINS[0][1],)
            )
            email.send()
            return HttpResponseRedirect('/contact/thanks/')
    
    else:
        form = ContactForm()
    
    return render_to_response('contact.html', locals(), context_instance=RequestContext(request))
    
