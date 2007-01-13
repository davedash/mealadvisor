<?php

/**
* feed actions.
*
* @package    eatw.us
* @subpackage feed
* @author     Your name here
* @version    SVN: $Id: actions.class.php 500 2006-01-23 09:15:57Z fabien $
*/
class feedActions extends sfActions
{
	public function preExecute()
	{
		sfConfig::set('sf_web_debug', false);                           
	}
	
	public function executeMenuItems()
	{
		//...
		$restaurant = RestaurantPeer::retrieveByStrippedTitle($this->getRequestParameter('stripped_title'));
		$this->forward404Unless($restaurant instanceof Restaurant);
		
		$c = new Criteria();
		$c->addDescendingOrderByColumn(MenuItemPeer::CREATED_AT);
		$c->add(MenuItemPeer::RESTAURANT_ID, $restaurant->getId());
		
		$items = MenuItemPeer::doSelect($c);
		$feed = sfFeed::newInstance('rss201rev2');
		$feed->setTitle('Menu items at ' . $restaurant->__toString());
		$feed->setLink('@restaurant?stripped_title=' . $restaurant->getStrippedTitle());
		$feed->setDescription('A list of the menu items served at '.$restaurant->__toString());

		$feed->setFeedItemsRouteName('@menu_item');
		$feed->setItems($items);
		$this->feed = $feed;
	}
	
	public function executeLatest()
	{
		// questions 
		$c = new Criteria(); 
		$c->addDescendingOrderByColumn(RestaurantPeer::CREATED_AT); 
		$c->setLimit(sfConfig::get('app_feed_max_restaurants')); 
		$restaurants = RestaurantPeer::doSelect($c); 
		$feed = sfFeed::newInstance('rss201rev2'); 
		// channel 
		$feed->setTitle('Latest restaurants'); 
		$feed->setLink('@homepage'); 
		$feed->setDescription('A list of the latest restaurants posted to my reviewsby.us');
		$feed->setFeedItemsRouteName('@restaurant'); 
		$feed->setItems($restaurants); 
		$this->feed = $feed; 
	}
	
	public function executeFreshest()
	{
		$near = $this->getRequestParameter('near');
		// limit somehow to just restaurants near a given location
		if ($near) {
			$locations = LocationPeer::getNear($near, null, 1, 'min_distance=off gradients=on order=distance,restaurant.updated_at DESC limit=' . sfConfig::get('app_feed_max_restaurants'));
		} 
		else {
			$c = new Criteria();
			$c->addDescendingOrderByColumn(RestaurantPeer::UPDATED_AT);
			$c->setLimit(sfConfig::get('app_feed_max_restaurants'));
			$locations = LocationPeer::doSelectJoinRestaurant($c);
		}
		
		$feed = sfFeed::newInstance('geoRss');
		$feed->setTitle('Freshest restaurant locations'); 
		$feed->setLink('@homepage'); 
		$feed->setDescription('A geocoded list of the freshest restaurant locations posted to reviewsby.us');
		$feed->setItems($locations); 
		$this->feed = $feed;		
	}
	
	/* This will eventually serve the geo+rss feed that'll power the front page map */
	public function executeLatestRestaurantLocations()
	{
		/* we want the locations of the latest restaurants 
		 * so we first get the restaurant and then their locations
		 */
		$c = new Criteria();
		$c->addDescendingOrderByColumn(RestaurantPeer::UPDATED_AT);
		$c->setLimit(sfConfig::get('app_feed_max_restaurants'));
		$restaurants = RestaurantPeer::doSelect($c);
		
		$locations = array();
		
		foreach ($restaurants AS $r)
		{
			$locations = array_merge($locations, $r->getLocations());
		}
		//$feed = sfFeed::newInstance('rss201rev2');
		$feed = sfFeed::newInstance('geoRss');
		
		// channel 
		$feed->setTitle('Latest restaurants\' locations'); 
		$feed->setLink('@homepage'); 
		$feed->setDescription('A geocoded list of the latest restaurants\' locations posted to reviewsby.us');
		$feed->setItems($locations); 
		$this->feed = $feed;
	}
	
	
	
	/* This will eventually serve the geo+rss feed that'll power the tag pages */
	public function executeTagGeoRSS()
	{
		/* we want the locations of the latest restaurants 
		 * so we first get the restaurant and then their locations
		 */
		$tag = $this->getRequestParameter('tag');
		// get restaurants by tag
		
		$restaurants = RestaurantPeer::retrieveByMenuItemTag($tag);
		$locations = array();
		foreach ($restaurants AS $r)
		{
			$locations = array_merge($locations, $r->getLocations());
		}	

		$feed = sfFeed::newInstance('geoRss');

		// channel 
		$feed->setTitle("Restaurants serving $tag (GeoRSS)"); 
		$feed->setLink('@homepage'); 
		$feed->setDescription("A geocoded list of restaurants serving dishes tagged with '$tag'.");
		//		$feed->setFeedItemsRouteName('@location'); 
		$feed->setItems($locations); 
		$this->feed = $feed;
		
	}
}

?>