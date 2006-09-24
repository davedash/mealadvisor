<?php

	#doc
	#	classname:	UrlHelper
	#	scope:		PUBLIC
	#
	#/doc

	class UrlHelper
	{
		
		public static function url_for_menuitem (MenuItem $i)
		{
			return '@menu_item?stripped_title='.$i->getUrl().'&restaurant='.$i->getRestaurant()->getStrippedTitle();
		}

	}
	###

?>