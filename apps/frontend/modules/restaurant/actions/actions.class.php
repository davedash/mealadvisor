<?php

class restaurantActions extends myActions
{
	public function executeAdminAjax()
	{
		$command = $this->getRequestParameter('command');
		$restaurant = $this->getRestaurant();
		switch($command) 
		{
			case 'save_name':
				$ost = $restaurant->getStrippedTitle();
				$restaurant->setName($this->getRequestParameter('value'));
				$restaurant->save();
				$nst = $restaurant->getStrippedTitle();
				if ($ost != $nst) // the url has changed
				{
					$this->redirect_to = $this->getController()->genUrl('@restaurant?stripped_title='.$nst);
				}
				$this->message = $restaurant->getName();
			break;
		}
	}
	
	private function getRestaurant()
	{
		$st = $this->getRequestParameter('stripped_title');
		$restaurant = RestaurantPeer::retrieveByStrippedTitle($st);
		if (!$restaurant instanceof Restaurant) {
			// we need to find the URL that this should be
			$rr = RestaurantRedirectPeer::retrieveByPK($st);
			
			$this->forwardIf($rr instanceof RestaurantRedirect, 'restaurant', 'moved');
			$this->forward404();
		}
		return $restaurant;
	}
	
	public function executeMoved()
	{
		$st = $this->getRequestParameter('stripped_title');
		
		$this->restaurantRedirect = RestaurantRedirectPeer::retrieveByPK($st);
		
	}
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
			$this->getUser()->setAttribute('referer', 'restaurant/rate?object=' .$hash .'&rating=' .$rating);
			if(!$ajax) return $this->redirect('@sf_guard_signin');
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
		if ($joint) return 'Joint';
		return sfView::SUCCESS;
	}

	
	public function executeSearch ()
	{
		$query    = $this->getRequestParameter('search');
		$location = $this->getRequestParameter('location');
		$page     = $this->getRequestParameter('page',1);
		
		// remove default anything/anywhere terms
		if (strtolower(trim($query))    == 'anything') $query    = null; 
		if (strtolower(trim($location)) == 'anywhere') $location = null;

		// determine the type of search
		// each needs to set an appropriate title and fetch appropriate results

		// 3 query near location
		if ($query && $location)
		{
			$geo = sfGeocoder::getGeocoder();
			$geo->query($location);
      
      // first determine if this is a search for something *IN* something else
			// or a search for something NEAR something else...
			switch ($geo->getPrecision())
			{
				case YahooGeo::COUNTRY:
				$this->locations = LocationPeer::searchIn($query, $geo, $page);
				$this->in = true;
				$country = CountryPeer::retrieveByMagic($geo->getCountry());
  			
				$this->search_location = $country->getPrintableName();
				return 'LocationSuccess';
				break;
				
				case YahooGeo::STATE:
				$this->locations = LocationPeer::searchIn($query, $geo, $page);
				$this->in = true;
				$this->search_location = $geo->getShortString();
				
				return 'LocationSuccess';
				break;
				
				case YahooGeo::CITY:
				case YahooGeo::ZIP:
				$this->locations = LocationPeer::searchNear($query, $geo, $page);
				$this->in = false;
				$this->search_location = $geo->getShortString();

				// near search
				return 'LocationSuccess';
				
				default:
				break;
			}			
		} 
		
		// 1 $query only
		elseif ($query)
		{
			$this->debugMessage('search: (QUERY_ONLY): ' . $query);
			$exact             = $this->getRequestParameter('search_all', false);
			$offset            = ($page - 1) * sfConfig::get('app_search_results_max');
			$limit             = sfConfig::get('app_search_results_max');
			$this->restaurants = RestaurantPeer::search($query, $exact, $offset, $limit);
			$this->prependTitle("Search for '".$query."'");
			return sfView::SUCCESS;
		} 

		// 2 location only
		elseif ($location)
		{
			$geo = sfGeocoder::getGeocoder();
			$geo->query($location);

			switch ($geo->getPrecision())
			{
				case sfGeocoder::COUNTRY:
				case sfGeocoder::STATE:
				case sfGeocoder::CITY:
				case sfGeocoder::ZIP:
					$this->redirect('@locations_in?' . $geo->getQueryString());
				default:
				break;
			}
		}

		// search for nothing .. later this can include an error message, etc
		$this->redirect('@homepage');
		
		
		$this->getResponse()->setTitle('Search for \'' . $this->getRequestParameter('search') . '\' &laquo; ' . sfConfig::get('app_title'), false);
		
		if ($query||$location)
		{
			if ($location) {
				$this->locations = LocationPeer::getNear($location, $query, $page);
				
				$this->getUser()->setPreference('location', $location);
			
				list($this->search_location, $this->near, $this->radius) = myTools::getNearness($location);
				return 'LocationSuccess';
			}
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

		$this->restaurant = $this->getRestaurant();

		$this->addFeed('@menu_item_feed?stripped_title=' . $this->restaurant->getStrippedTitle(), 'Menu items at ' . $this->restaurant->__toString());

		$this->locations = $this->restaurant->getLocations();
		$this->num_locations = count($this->locations);
		$this->getResponse()->setTitle($this->restaurant->__toString() . ' &laquo; ' . sfConfig::get('app_title'), false);
		$this->rating = null;

		if ($this->getUser()->isLoggedIn()) {
			$c = new Criteria();
			$c->add(RestaurantRatingPeer::RESTAURANT_ID, $this->restaurant->getId());
			$c->add(RestaurantRatingPeer::USER_ID, $this->getUser()->getId());
			$rating = RestaurantRatingPeer::doSelectOne($c);
			if ($rating instanceof RestaurantRating) $this->rating = $rating->getValue();
		}
		
		/* map */
		
		// need to get a location
		if ($this->num_locations)
		{
			if ($this->getUser()->hasLocation()) {
				$this->location = LocationPeer::getNearestForRestaurant($this->restaurant, $this->getUser()->getLocation(), null, 1, 'min_distance=off gradients=on order=distance,restaurant.updated_at DESC limit=8');
			} else {
				$this->location = $this->locations[0];
			}
		}
	}

	public function executeEdit()
	{
		if(!$this->getUser()->isLoggedIn())
		{
			// build the url where we need to get redirected...
			$this->setFlash('post_login', $this->getController()->genUrl('@restaurant_add',1));
			if(empty($ajax)) return $this->redirect('@sf_guard_signin');
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

	public function executeReindex ()
	{
		RestaurantPeer::reindex();
	}
}

