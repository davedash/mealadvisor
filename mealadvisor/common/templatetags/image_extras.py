from django import template

register = template.Library()

@register.simple_tag
def menuitem_image(image, longest_side=None, alt=None, menu_item=None):
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
    
    if alt == None:
        alt = "%s from %s" % (str(image.menu_item), str(image.menu_item.restaurant))

    if menu_item == None:
        menu_item = image.menu_item
        
    return """<a href="%s">
          <img alt="%s" height="%d" width="%d" src="/menuitem_image/%s" />
      </a>"""\
      % ( menu_item.get_absolute_url().encode('utf-8'),
          alt, 
          height, width, 
          str(image.md5sum),
          );


@register.simple_tag
def random_menuitem_image(item, longest_side=None):
    image = item.menuitemimage_set.random()
    if image:
        return menuitem_image(image, longest_side, alt=item.name, menu_item=item)
    
    return '&nbsp;'