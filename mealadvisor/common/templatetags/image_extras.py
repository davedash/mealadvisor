from django import template
from mealadvisor.restaurant.models import MenuItemImage

import settings

register = template.Library()

@register.simple_tag
def menuitem_image(image, longest_side = None, alt=None, menu_item=None):

    height    = 146
    width     = 240
    image_url = '/static/images/ui/logo/bowl_240x146.png'
    portrait  = False
    
    if isinstance(image, MenuItemImage):
        height    = image.height
        width     = image.width
        image_url = image.image.url
        portrait  = image.is_portrait()
        
    if longest_side and height and width:
        if portrait:
            width  = int(float(width)/float(height) * longest_side)
            height = longest_side
        else:
            height = int(float(height)/float(width) * longest_side)
            width  = longest_side

    
    if menu_item == None:
        menu_item = image.menu_item
    
    if alt == None:
        alt = u"%s from %s" % (unicode(menu_item), unicode(menu_item.restaurant))

    return image_link(menu_item.get_absolute_url(), image_url, alt, height, width)
    
def image_link(url, image_url, alt, height, width):
    return """<a href="%s"><img alt="%s" height="%d" width="%d" src="%s" /></a>""" \
        % ( url, alt, height, width, image_url );
    
@register.simple_tag
def random_menuitem_image(item, longest_side=None):
    image = item.menuitemimage_set.random()
    return menuitem_image(image, longest_side, alt=item.name, menu_item=item)
