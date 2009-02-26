from django.core.management import setup_environ
import sys,os

sys.path.append('../mealadvisor')

import mealadvisor.settings

setup_environ(mealadvisor.settings)

class MenuItemImage(models.Model):
    user      = models.ForeignKey(Profile, null=True, blank=True)
    menu_item = models.ForeignKey(MenuItem)
    data      = models.TextField(blank=True)
    md5sum    = models.CharField(max_length=96, blank=True)
    height    = models.IntegerField(null=True, blank=True)
    width     = models.IntegerField(null=True, blank=True)
    objects   = RandomManager()

    class Meta:
        db_table = u'menu_item_image'

    def is_portrait(self):
        return (self.height > self.width);
        
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
