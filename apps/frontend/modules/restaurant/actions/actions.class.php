<?php
// auto-generated by sfPropelCrud
// date: 02/19/2006 01:45:15
?>
<?php

/**
* restaurant actions.
*
* @package    ##PROJECT_NAME##
* @subpackage restaurant
* @author     Your name here
* @version    SVN: $Id: actions.class.php 500 2006-01-23 09:15:57Z fabien $
*/

require_once('myActions.class.php');

class restaurantActions extends myActions
{
	public function executeRate()
	{
		sfConfig::set('sf_web_debug', false);
		$hash = $this->getRequestParameter('object');
		$rating = $this->getRequestParameter('rating');
		$ajax = ($this->getRequestParameter('mode')=='ajax');
		$restaurant = RestaurantPeer::retrieveByHash($hash);
		$joint = $this->getRequestParameter('joint', false);
		$this->rating = null;
		if(!$this->getUser()->isLoggedIn())
		{
			// build the url where we need to get redirected...
			$this->getUser()->setAttribute('post_login', 'restaurant/rate?object=' .$hash .'&rating=' .$rating);
			$this->setFlash('use_post_login', true);
			if(!$ajax) return $this->redirect('user/login');
		} 
		else {
			$c = new Criteria();

			$c->add(RestaurantRatingPeer::RESTAURANT_ID, $restaurant->getId());
			$c->add(RestaurantRatingPeer::USER_ID, $this->getUser()->getId());

			$restaurantRating = RestaurantRatingPeer::doSelectOne($c);
			if ($restaurantRating instanceof RestaurantRating) $restaurantRating->delete();
			$restaurantRating = new RestaurantRating();
			$restaurantRating->setRestaurant($restaurant);
			$restaurantRating->setProfile($this->getUser()->getUser());
			$restaurantRating->setValue($rating);
			$restaurantRating->save();
			$this->rating = $rating;
			if(!$ajax) return $this->redirect('@restaurant?stripped_title='.$restaurant->getStrippedTitle());
		}
		$this->restaurant = $restaurant;
		//if ($joint) return 'joint';
		return sfView::SUCCESS;
	}

	
	public function executeSearch ()
	{
		$this->getResponse()->setTitle('Search for \'' . $this->getRequestParameter('search') . '\' &laquo; ' . sfConfig::get('app_title'), true);
		
		if ($this->getRequestParameter('search'))
		{
			$this->restaurants = RestaurantPeer::search($this->getRequestParameter('search'), $this->getRequestParameter('search_all', false), ($this->getRequestParameter('page', 1) - 1) * sfConfig::get('app_search_results_max'), sfConfig::get('app_search_results_max'));
			
			$this->items = MenuItemPeer::search($this->getRequestParameter('search'), $this->getRequestParameter('search_all', false), ($this->getRequestParameter('page', 1) - 1) * sfConfig::get('app_search_results_max'), sfConfig::get('app_search_results_max'));
			
		}
		else
		{
			$this->redirect('@homepage');
		}
	}
	
	public function executeAddComment()
	{
		$c = new Criteria();
		$c->add(RestaurantPeer::STRIPPED_TITLE, $this->getRequestParameter('stripped_title'));
		$restaurant = RestaurantPeer::doSelectOne($c);

		$note = new RestaurantNote();
		$note->setRestaurant($restaurant);
		if ($this->getUser()) $note->setUserId($this->getUser()->getId());
		$note->setNote($this->getRequestParameter('body'));
		// clear this so it doesn't pre-populate the form.
		$this->getRequest()->getParameterHolder()->set('body',null);
		$note->save();
		$this->comment = $note;
		
				
	}
	public function executeIndex ()
	{
		return $this->redirect('@homepage');
	}

	public function executeLatest() 
	{
		$c = new Criteria(); 
		$c->addDescendingOrderByColumn(RestaurantPeer::UPDATED_AT);
		
		$pager = new sfPropelPager('Restaurant', 10); 
		$pager->setCriteria($c); 
		$pager->setPage($this->getRequestParameter('page', 1)); 
		$pager->init(); 
		$this->pager = $pager; 
		 
		
		
//		$this->restaurants = RestaurantPeer::doSelect($c);
	}

	public function executeShow()
	{
		$this->getResponse()->addJavascript(sfConfig::get('SF_PROTOTYPE_WEB_DIR').'/js/prototype');
		$this->getResponse()->addJavascript(sfConfig::get('SF_PROTOTYPE_WEB_DIR').'/js/controls');
		$this->getResponse()->addJavascript('comment');
		
		$this->restaurant = RestaurantPeer::retrieveByStrippedTitle($this->getRequestParameter('stripped_title'));
		$this->forward404Unless($this->restaurant instanceof Restaurant);

		$this->addFeed('@menu_item_feed?stripped_title=' . $this->restaurant->getStrippedTitle(), 'Menu items at ' . $this->restaurant->__toString());

		$this->num_locations = count($this->restaurant->getLocations());
		$this->getResponse()->setTitle($this->restaurant->__toString() . ' &laquo; ' . sfConfig::get('app_title'), true);
		$this->rating = null;

		if ($this->getUser()->isLoggedIn()) {
			$c = new Criteria();
			$c->add(RestaurantRatingPeer::RESTAURANT_ID, $this->restaurant->getId());
			$c->add(RestaurantRatingPeer::USER_ID, $this->getUser()->getId());
			$rating = RestaurantRatingPeer::doSelectOne($c);
			if ($rating instanceof RestaurantRating) $this->rating = $rating->getValue();
		}
	}

	public function executeEdit()
	{
		if(!$this->getUser()->isLoggedIn())
		{
			// build the url where we need to get redirected...
			$this->setFlash('post_login', $this->getController()->genUrl('@restaurant_add',1));
			if(!$ajax) return $this->redirect('user/login');
		}
		$this->restaurant = $this->getRestaurantOrCreate();
	}



	public function executeEditInformation()
	{
		
		$this->restaurant = RestaurantPeer::retrieveByStrippedTitle($this->getRequestParameter('stripped_title'));
		if ($this->getRequest()->getMethod() != sfRequest::POST)
		{
			// display the form
			return sfView::SUCCESS;
		}
		if ($this->restaurant->getRestaurantVersion()) {
			$version = $this->restaurant->getRestaurantVersion()->copy();			
		} else {
			$version = new RestaurantVersion();
			$version->setRestaurantId($this->restaurant->getId());
			
		}
		
		$version->setDescription($this->getRequestParameter('description'));
		$version->setUrl($this->getRequestParameter('url'));
		$version->setChain($this->getRequestParameter('chain'));
		$version->save();
		$this->restaurant->setRestaurantVersion($version);
		$this->restaurant->save();
		
		return $this->redirect('@restaurant?stripped_title='.$this->restaurant->getStrippedTitle());
		
	}

	public function executeUpdate ()
	{


		$restaurant = $this->getRestaurantOrCreate();

		$restaurant->setId($this->getRequestParameter('id'));
		$restaurant->setName($this->getRequestParameter('name'));
		$restaurant->setChain($this->getRequestParameter('chain', 0));
		$restaurant->setDescription($this->getRequestParameter('description'));
		$restaurant->setUrl($this->getRequestParameter('url'));
		
		$restaurant->save();

		if ($this->getRequestParameter('address') || $this->getRequestParameter('city') || $this->getRequestParameter('state') || $this->getRequestParameter('zip') || $this->getRequestParameter('phone')) {
			
			$location = new Location();
			$location->setRestaurant($restaurant);
			$location->setAddress($this->getRequestParameter('address'));
			
			$location->setCity($this->getRequestParameter('city'));
			
			$location->setState($this->getRequestParameter('state'));
			
			$location->setZip($this->getRequestParameter('zip'));
			
			$location->setPhone($this->getRequestParameter('phone'));
			if ($this->getRequestParameter('country')) {
				$location->setCountryId($this->getRequestParameter('country'));
			}
		
			if ( $this->getRequestParameter('location_name') )
		    {
				$location->setName($this->getRequestParameter('location_name'));
		    }
		
			$location->save();
		}
		
		if ($this->getRequestParameter('review')) {
			$note = new RestaurantNote();
			$note->setRestaurantId($restaurant->getId());
			$note->setUserId($this->getUser()->getId());
			$note->setNote($this->getRequestParameter('review'));
			$note->save();
		}
		return $this->redirect('@restaurant?stripped_title='.$restaurant->getStrippedTitle());
	}
	
	public function handleErrorUpdate()
	{
		return $this->forward('restaurant', 'edit');
	}

	public function executeDelete ()
	{
		$restaurant = RestaurantPeer::retrieveByPk($this->getRequestParameter('id'));

		$this->forward404Unless($restaurant instanceof Restaurant);

		$restaurant->delete();

		return $this->redirect('restaurant/list');
	}

	private function getRestaurantOrCreate ($id = 'id')
	{
		if (!$this->getRequestParameter($id, 0))
		{
			$restaurant = new Restaurant();
		}
		else
		{
			$restaurant = RestaurantPeer::retrieveByPk($this->getRequestParameter($id));

			$this->forward404Unless($restaurant instanceof Restaurant);
		}

		return $restaurant;
	}


}

?>