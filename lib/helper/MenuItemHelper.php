<?php
function tags_for_menuitem($menu_item, $max = 5) 
{ 
	$tags = array(); 
	foreach($menu_item->getPopularTags($max) as $tag => $count) 
	{ 
		$tags[] = link_to($tag, '@tag?tag='.$tag); 
	} 
	return implode(' + ', $tags); 
} 

function tags_for_menuitem_from_user($menu_item, Profile $u) 
{ 
	use_helper('Javascript');
	$tags = array(); 
	foreach($menu_item->getTagsFromUser($u) as $tag) 
	{ 
		$tags[] = link_to($tag, '@tag?tag='.$tag) . link_to_remote(image_tag('minus.png','class=mini_action alt=-'), array('url'=>'@tag_remove?menuitem_hash='. $menu_item->getHash() . '&tag='.$tag, 'update' => $menu_item->getUrl().'_tags'),
		"confirm='Are you sure you want to remove this tag, $tag?'"); 
	} 
	return implode(' ', $tags); 
}
?>