from django.core.management import setup_environ
import sys,os

sys.path.append('../mealadvisor')
sys.path.append('..')

import mealadvisor.settings

setup_environ(mealadvisor.settings)

from models import MenuItemImage




        
images = MenuItemImage.objects.all()

counter = 1

for img in images:
    print counter, "of", len(images)
    filename = 'mealadvisor/static/images/menuitems/%s.jpg'%img.md5sum
    print filename
    f = open(filename, 'w')
    f.write(img.data)
    f.close()
    counter += 1
    
print "SAFE to do the following: '%s'" % """

alter table menu_item_image drop data;
alter table menu_item_image add column `image` varchar(240) NOT NULL;
update menu_item_image set image = CONCAT('images/menuitems/',md5, '.jpg');
alter table menu_item_image drop md5sum;


"""
