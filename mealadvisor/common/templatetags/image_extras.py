from django import template

register = template.Library()

@register.simple_tag
def menuitem_image(image, longest_side=None):
    # <img alt="{{image.menu_item}} from {{image.menu_item.restaurant}}"
    #     height="{{image.height}}" width="{{image.width}}" 
    #     src="/menuitem_image/{{image.md5sum}}"/>
    #     
    
    height = image.height
    width  = image.width
    
    if longest_side:
        if image.is_portrait():
            height = longest_side
            width  = int(float(image.width)/float(image.height) * longest_side)
        else:
            width  = longest_side
            height = int(float(image.height)/float(image.width) * longest_side)
    
    
    return '<img alt="%s from %s" height="%d" width="%d" src="/menuitem_image/%s" />' \
    % (
        str(image.menu_item), 
        str(image.menu_item.restaurant), 
        height, width, 
        str(image.md5sum),
        );
