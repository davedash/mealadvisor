from django import forms

STARS = TOPIC_CHOICES = (
    ('',''),
    ('1', '*'),
    ('2', '**'),
    ('3', '***'),
    ('4', '****'),
    ('5', '*****'),
)

class ReviewForm(forms.Form):
    # rating = forms.ChoiceField(choices=STARS, error_messages={'required': 'Please choose a star rating'})
    note = forms.CharField(widget=forms.Textarea(),error_messages={'required': 'Please type in your message'})
    

class TagAddForm(forms.Form):
    restaurant = forms.CharField(widget=forms.HiddenInput())
    tag        = forms.CharField()
