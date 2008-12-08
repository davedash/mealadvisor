from django import template

register = template.Library()

@register.simple_tag
def restaurant_tags(user, restaurant):
    pop_tags = restaurant.get_popular_tags(10)
    
    user_tags = []
    
    if user.is_authenticated:
        user_tags = restaurant.get_tags_from_user(user.get_profile())
        for tag in user_tags:
            if not tag in pop_tags:
                pop_tags[tag] = 1
        

	tags = {}

    # not a limit, but a query of what the max count we had
    if pop_tags:
        max_count = max(pop_tags.values())

    NUM_SIZES = 7
    
    for tag in pop_tags:
        size = 1 if (max_count == 1) else pop_tags[tag] / max_count * NUM_SIZES
    
        class_ = 'tag_size_%d'%size
        extras = ''
        
        if tag in user_tags:
            # we want to show that we can remove
            #       $tags[$tag] = link_to($tag, '@tag?tag='.$tag, "class=my tag_size_$size") . link_to_remote(image_tag('minus.png','class=mini_action alt=-'), array('url'=>'@restaurant_tag_remove?restaurant='. $this->restaurant->getStrippedTitle() . '&tag='.$tag, 'update' => $this->restaurant->getStrippedTitle().'_tags'),
            #       "confirm='Are you sure you want to remove this tag, $tag?'");
            class_ = 'my '+class_
            extras = '-'

        tags[tag] = link_to(tag, '/tag/%s'%tag, {'class': class_}) + extras
        
    output = "\n".join(["<li>%s</li>"%tags[tag] for tag in sorted(tags.keys())])

    return "<ul>\n%s\n</ul>" %output
    
@register.simple_tag
def link_to(text, url, args = {}):
    extras = ' '.join(['%s="%s"'%(key,args[key]) for key in args.keys()])
    output = """<a href="%s"%s>%s</a> """ % (url, extras.strip(), text)
    return output


@register.simple_tag
def post_to_delicious(request, title=None, text=None):
    from urllib import urlencode
    if text == None:
        text = 'post to del.icio.us'
    
    if title == None:
        title = 'meal advisor'
    
    url = request.build_absolute_uri()
    del_url = 'http://del.icio.us/post?%s' % urlencode({'url': url,'title': title}).replace('&', '&amp;')
    
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
    		<input type="submit" class="submit" value="rate it" name="rate"/>
		</fieldset>
	</form>
    """ % (path, id_string, meta,stars)
    if spanfree:
        return html
    else:
        return """<div class="joint_star_rater">%s</div>""" % html
    return html
