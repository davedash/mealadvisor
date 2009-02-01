from django.core.management import setup_environ
import sys,os

sys.path.append('../mealadvisor')

import mealadvisor.settings

setup_environ(mealadvisor.settings)

from mealadvisor.restaurant.models import Location

locations = Location.objects.all()

counter = 1

for location in locations:
    print counter, "of", len(locations)
    print location.restaurant_id, location.stripped_title
    print "Checking for dupes"
    matches = Location.objects.filter(restaurant = location.restaurant, stripped_title = location.stripped_title)
    if len(matches) > 1:
        new_slug = location.generate_slug()
        print "Setting slug to: %s" %new_slug
        location.stripped_title = new_slug
        location.save()
    counter += 1
    
print "SAFE to do the following: '%s'" % "ALTER TABLE location ADD constraint unique (restaurant_id, stripped_title)"