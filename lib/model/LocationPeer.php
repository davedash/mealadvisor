<?php

  // include base peer class
  require_once 'lib/model/om/BaseLocationPeer.php';
  
  // include object class
  include_once 'lib/model/Location.php';


/**
 * Skeleton subclass for performing query and update operations on the 'location' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package model
 */	
class LocationPeer extends BaseLocationPeer {
	public static function retrieveByStrippedTitles($r, $l)
	{
		$c = new Criteria();
		$c->add(RestaurantPeer::STRIPPED_TITLE, $r);
		$restaurant = RestaurantPeer::doSelectOne($c);
		//echo $restaurant;
		$c->add(LocationPeer::RESTAURANT_ID, $restaurant->getId());
		$c->add(LocationPeer::STRIPPED_TITLE, $l);
		return LocationPeer::doSelectOne($c);
		
	}
} // LocationPeer
