from django import template

register = template.Library()

@register.simple_tag
def post_to_delicious(request, title=None, text=None):
    from urllib import urlencode
    if text == None:
        text = 'post to del.icio.us'
    
    if title == None:
        title = 'meal advisor'
    
    url = request.build_absolute_uri()
    del_url = 'http://del.icio.us/post?%s' % urlencode({'url': url,'title': title})
    
    return '<a href="%s">%s</a>' % (del_url, text)