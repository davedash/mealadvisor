<?php
function tags_for_restaurant($restaurant, $max = 5) 
{ 
	$tags = array(); 
	foreach($restaurant->getPopularTags($max) as $tag => $count) 
	{ 
		$tags[] = link_to($tag, '@tag?tag='.$tag); 
	} 
	return implode(' + ', $tags); 
} 

function tags_for_restaurant_from_user($restaurant, Profile $u) 
{ 
	use_helper('Javascript');
	$tags = array(); 
	foreach($restaurant->getTagsFromUser($u) as $tag) 
	{ 
		$tags[] = link_to($tag, '@tag?tag='.$tag) . link_to_remote(image_tag('minus.png','class=mini_action alt=-'), array('url'=>'@restaurant_tag_remove?restaurant='. $restaurant->getStrippedTitle() . '&tag='.$tag, 'update' => $restaurant->getStrippedTitle().'_tags'),
		"confirm='Are you sure you want to remove this tag, $tag?'"); 
	} 
	return implode(' ', $tags); 
}
?>