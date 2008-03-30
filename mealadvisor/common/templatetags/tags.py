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
    
    
@register.simple_tag
def star(id_string, current, average, path, spanfree=False):
    meta = ""
    if current != None:
        meta = """
        <li class="current meta" title="%d" style="width:%dpx">Current Rating: %d</li>
        """ % (int(current), int(current)*20, int(current))
    elif average != None:
        meta = """
        <li class="average meta" title="%.1f" style="width:%dpx">Average Rating: %.1f</li>
        """ % (float(average), float(average)*20, float(average))
    
    stars   = ''
    ratings = ['Poor', 'Fair', 'Good', 'Very Good', 'Excellent']
    
    for i in range(1,6):
        stars = stars + """
		<li class="star_%d star" title="%s">
			<label for="%s_rating_%d">%s</label>
			<input id="%s_rating_%d" type="radio" value="%d" name="rating"/>
		</li>         
        """ % (i, ratings[i-1],id_string, i, ratings[i-1], id_string, i, i)
    html = """
	<form action="%s" method="post" id="rater_%s">
		<fieldset>
			<legend>Rating</legend>
			<ul>
			%s
			%s
			</ul>
		</fieldset>
		<input type="submit" class="submit" value="rate it" name="rate"/>
	</form>
    """ % (path, id_string, meta,stars)
    if spanfree:
        return html
    else:
        return """<span class="joint_star_rater">%s</span>""" % html
    return html
