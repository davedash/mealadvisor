<?php

	function markdown_enabled_link()
	{
		return '(' . link_to('Markdown', 'http://daringfireball.net/projects/markdown/syntax') . ' enabled)';
	}
	
	function link_to_location(Location $l)
	{
		return link_to($l->__toString(), url_for_location($l));
	}
	
	function url_for_location(Location $l)
	{
		return '@location?restaurant=' . $l->getRestaurant()->getStrippedTitle() . '&location=' . $l->getStrippedTitle();
	}
	
	function link_to_restaurant(Restaurant $r, $absolute = false)
	{
		return link_to($r->__toString(), '@restaurant?stripped_title=' . $r->getStrippedTitle(), 'absolute_url='.$absolute);
	}
	function link_to_menuitem(MenuItem $i)
	{
		return link_to(htmlentities($i->getName()), url_for_menuitem($i));
	}
	function url_for_menuitem(MenuItem $i)
	{
		return '@menu_item?stripped_title='.$i->getUrl().'&restaurant='.$i->getRestaurant()->getStrippedTitle();
	}
	
	function link_to_menuitem_edit(MenuItem $i, $text)
	{
		return link_to($text, url_for_menuitem_edit($i));
	}
	function url_for_menuitem_edit(MenuItem $i)
	{
		return '@menu_item_edit?stripped_title='.$i->getUrl().'&restaurant='.$i->getRestaurant()->getStrippedTitle();
	}
	
	

	function format_phone($phone)
	{
		 
		return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1.$2.$3", $phone); 
		
	}
	function is_associative($array)
	{
	  if (!is_array($array) || empty($array))
	   return false;

	  $keys = array_keys($array);
	  return array_keys($keys) !== $keys;
	}
	
	function link_to_user($mixed) 
	{
		if ($mixed instanceof User) {
			$user = $mixed;
		} else if ($mixed instanceof myUser) {
			$user = $mixed->getUser();
		} else {
			// user isn't there
			return "Anonymous Diner";
		}
		
		return link_to($user->__toString(), '@profile?user='. $user->getUserId());
	}
?>