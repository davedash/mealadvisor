<?php

/**
 * yahoolocal actions.
 *
 * @package    reviewsby.us
 * @subpackage yahoolocal
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class yahoolocalActions extends myActions
{
	
  public function executeIndex()
  {
		$this->local = null;
		$this->sort = 'distance';
		$page = $this->getRequestParameter('page');
		$this->query = '*';
		$this->location = '55408';
		$this->category = 96926236;
		
		$this->radius = 1;
		// we start the search here...
		if ($this->isPost() || $page || $this->getUser()->hasAttribute('query', 'yLocal'))
		{
			if (!$page)
			{
				$page = 1;
			}
			$options = array();
			$query = null;
			
			if (!$this->getRequestParameter('category'))
			{
				$options = $this->getUser()->getAttribute('options',null,'yLocal');
				//var_dump($options);
				$query =  $this->getUser()->getAttribute('query',null,'yLocal');
			} 
			else 
			{
				$options = array('sort' => $this->getRequestParameter('sort'), 
					'location' => $this->getRequestParameter('location'), 
					'radius' => $this->getRequestParameter('radius',null), 
					'page' => $page,
					'category' => $this->getRequestParameter('category',null)
					);

				$query = $this->getRequestParameter('query');
				if (empty($query))
				{
					$query = '*';
				}
			}

			try 
			{
				$local = new YahooLocal($query, $options);
				$this->results = $local->getResults();
				$this->local = $local;
				$this->route = 'yahoolocal?' . $local->getQueryString() . '&page=';
			}
			catch (sfException $e)
			{
				return;
			}
			
			// store these
			$this->getUser()->setAttribute('options',$options,'yLocal');
			
			$this->getUser()->setAttribute('query',$query,'yLocal');

			$this->category = $options['category'];
			$this->query    = $query;
			$this->location = $options['location'];
			$this->sort     = $options['sort'];
			$this->radius   = $options['radius'];
		}
		
  }

	public function executeAddLocation()
	{
		if ($id = $this->getRequestParameter('restaurant'))
		{
			$this->saveLocation($id);
			$this->restaurant = RestaurantPeer::retrieveByPK($id);
			$this->yid = $this->getRequestParameter('yid');
			
		}
	}

	public function executeReplaceLocation()
	{
		if ($id = $this->getRequestParameter('location'))
		{
			$location = LocationPeer::retrieveByPK($id);
			$this->saveLocationFromRequest($location);
			$this->restaurant = $location->getRestaurant();
			$this->yid = $this->getRequestParameter('yid');
		}
	}


	public function executeAdd()
	{
		
		$local = new YahooLocal('*', array('listing_id'=>$this->getRequestParameter('yid')));
		$this->result = $local->getResults();
		// search for similar restaurant names
		$title = str_ireplace(array('restaurant','cafe','sandwiches','bistro'), '',$this->result->Title);
		$this->restaurants = RestaurantPeer::search($title);
		$this->locations = LocationPeer::findNearLatLng((float) $this->result->Latitude, (float) $this->result->Longitude);
	}
	
	// adds all restaurants as new... dangerous ;)
	public function executeAddAll()
	{
		$yids = $this->getRequestParameter('yid[]');
	
		foreach( $yids as $yid )
		{
			$result = YahooLocal::getOneResult($yid);
			$r = new Restaurant();
			$r->setName(YahooLocal::sanitizeText($result->Title));
			$r->setUrl($result->BusinessUrl);
			$r->save();
			$location = new Location();
			$location->updateFromYahooLocalId($yid);
			$location->setRestaurant($r);
			$location->save();
		}
		return $this->redirect('yahoolocal/index');
		
	}
	
	public function saveLocation($r_id)
	{
		$location = new Location();
		$location->setRestaurantId($r_id);
		$this->saveLocationFromRequest($location);
	}
	
	public function saveLocationFromRequest(Location $location)
	{
		$yid = $this->getRequestParameter('yid');
		$result = YahooLocal::getOneResult($yid);
		
		$location->setYahooLocalID($yid);
		$address = $this->getRequestParameter('address');
		if (!empty($address))
		{
			$location->setAddress($address);
			$location->setCity($this->getRequestParameter('city'));
			$location->setState($this->getRequestParameter('state'));
			$location->setPhone($this->getRequestParameter('phone'));
			
			if ( $this->getRequestParameter('location_name') )
			{
				$location->setName($this->getRequestParameter('location_name'));
			}
		}
		else 
		{
			$location->setAddress($result->Address);
			$location->setCity($result->City);
			$location->setState($result->State);
			$location->setPhone($result->Phone);
			
		}	
		$location->save();
		if ($result->Categories)
		{
			foreach ($result->Categories->Category AS $cat)
			{
				YahooLocalCategoryPeer::add((int) $cat['id'], (string) $cat);
			}
		}

	}
	
	public function executeAddRestaurant()
	{
		
		$restaurant = new Restaurant();

		$restaurant->setName($this->getRequestParameter('name'));
		$restaurant->setChain($this->getRequestParameter('chain', 0));
		$restaurant->setDescription($this->getRequestParameter('description'));
		$restaurant->setUrl($this->getRequestParameter('url'));
		
		$restaurant->save();

		if ($this->getRequestParameter('address') || $this->getRequestParameter('city') || $this->getRequestParameter('state') || $this->getRequestParameter('zip') || $this->getRequestParameter('phone')) 
		{
			$this->saveLocation($restaurant->getId());
		}
		
		return $this->redirect('yahoolocal/index');
	}
}
