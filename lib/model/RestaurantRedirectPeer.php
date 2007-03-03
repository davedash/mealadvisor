<?php

/**
 * Subclass for performing query and update operations on the 'restaurant_redirect' table.
 *
 * 
 *
 * @package lib.model
 */ 
class RestaurantRedirectPeer extends BaseRestaurantRedirectPeer
{
	public static function create ($old, Restaurant $r)
	{
		$rr = self::retrieveByPK($old);
		if (!$rr instanceof RestaurantRedirect) {
			$rr = new RestaurantRedirect();
			$rr->setOldStrippedTitle($old);
		}
		$rr->setRestaurant($r);
		$rr->save();
	}
}
