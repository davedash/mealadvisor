from django import forms
from django.template.defaultfilters import slugify
from django.db.models import Q
from models import Restaurant, MenuItem
from mealadvisor.common.models import State
from django.forms.util import ErrorList

from django.contrib.localflavor.us.forms import USZipCodeField, USPhoneNumberField


STARS = TOPIC_CHOICES = (
    ('',''),
    ('1', '*'),
    ('2', '**'),
    ('3', '***'),
    ('4', '****'),
    ('5', '*****'),
)

class StateField(forms.ModelChoiceField):
    def label_from_instance(self, obj):
        return obj.name

class ReviewForm(forms.Form):
    # rating = forms.ChoiceField(choices=STARS, error_messages={'required': 'Please choose a star rating'})
    note = forms.CharField(widget=forms.Textarea(),error_messages={'required': 'Please type in your message'})
    

class TagAddForm(forms.Form):
    restaurant = forms.CharField(widget=forms.HiddenInput())
    tag        = forms.CharField()

class MenuitemTagAddForm(forms.Form):
    menu_item = forms.CharField(widget=forms.HiddenInput())
    tag         = forms.CharField()


class NewMealForm(forms.Form):
    name        = forms.CharField(error_messages={'required': 'Please enter the name of your dish'})
    description = forms.CharField(help_text="Note: This is not a review.", widget=forms.Textarea, required=False)
    price       = forms.CharField(required=False)
    restaurant  = None
    
    def clean_name(self):
        # see if this dish exist or a dish with the same slug exists
        name = self.cleaned_data.get('name', '')
        
        if name:
            qset = (Q(name=name) | Q(slug=slugify(name))) & Q(restaurant=self.restaurant)
            
            results = MenuItem.objects.filter(qset)
            
            if results:
                raise forms.ValidationError("""This dish <a href="%s">already exists</a>""" % results[0].get_absolute_url())
                
        return name
            
            
class NewRestaurantForm(forms.Form):
	
    restaurant_name = forms.CharField(error_messages={'required': 'Please enter the name of the restaurant'})
    description     = forms.CharField(help_text="Note: This is not a review.", widget=forms.Textarea)
    url             = forms.URLField(required=False)
    
    location_name = forms.CharField(help_text="<em>Optional</em>: Nickname for location if not <em>City, State</em> (e.g. Santana Mall)", \
	required=False)
    address       = forms.CharField()
    city          = forms.CharField(required=False)
    state         = StateField(queryset=State.objects.all() , required=False)
    zipcode       = USZipCodeField(required=False)
    phone         = USPhoneNumberField(required=False)

    review = forms.CharField(widget=forms.Textarea, required=False)
    
    def clean_restaurant_name(self):
        name = self.cleaned_data.get('restaurant_name', '')
        
        # see if this restaurant exists or a restaurant with the same slug exists
        if name:
            qset = (
                Q(name=name) |
                Q(stripped_title=slugify(name))
            )
            results = Restaurant.objects.filter(qset)

            if results:
                raise forms.ValidationError("""This restaurant <a href="%s">already exists</a>.""" % results[0].get_absolute_url())
        
        return name
    
		
    def clean_address(self):
        address = self.cleaned_data.get('address','')
        return address.strip()
        
    def clean(self):
        cleaned_data  = self.cleaned_data
        location_name = cleaned_data.get("location_name")
        address       = cleaned_data.get("address")
        city          = cleaned_data.get("city")
        state         = cleaned_data.get("state")
        zipcode       = cleaned_data.get("zipcode")
        
        if (location_name or address or city or state or zipcode) and \
        not (address and city and state and zipcode):
            msg = 'Please give us address, city, state and zip for the location.'
            self._errors["location_name"] = ErrorList([msg])
        
        return cleaned_data
