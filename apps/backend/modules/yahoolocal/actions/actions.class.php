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
  /**
   * Executes index action
   *
   */
  public function executeIndex()
  {
		$this->local = null;
		$page = $this->getRequestParameter('page');
		$this->query = 'restaurant';
		$this->location = '55408';
		
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
			
			if (!$this->getRequestParameter('query'))
			{
				$options = $this->getUser()->getAttribute('options',null,'yLocal');
			//var_dump($options);
				$query =  $this->getUser()->getAttribute('query',null,'yLocal');
			} 
			else 
			{
			
				$options = array('sort' => $this->getRequestParameter('sort'), 
					'location' => $this->getRequestParameter('location'), 
					'radius' => $this->getRequestParameter('radius',null), 'page' => $page );

				$query = $this->getRequestParameter('query');
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

			$this->query = $query;
			$this->location = $options['location'];
			
			$this->radius = $options['radius'];
		}
		
  }

	public function executeAddLocation()
	{
		if ($id = $this->getRequestParameter('restaurant'))
		{
			$this->saveLocation($id);
			$this->restaurant = RestaurantPeer::retrieveByPK($id);
		}
	}
	public function executeAdd()
	{
		// search for similar restaurant names
		$this->restaurants = RestaurantPeer::search($this->getRequestParameter('title'));
		$this->locations = LocationPeer::findNearLatLng($this->getRequestParameter('latitude'), $this->getRequestParameter('longitude'));
	}
	
	public function saveLocation($r_id)
	{
		$location = new Location();
		$location->setRestaurantId($r_id);
		$location->setAddress($this->getRequestParameter('address'));
		
		$location->setCity($this->getRequestParameter('city'));
		
		$location->setState($this->getRequestParameter('state'));
					
		$location->setPhone($this->getRequestParameter('phone'));
		
		if ( $this->getRequestParameter('location_name') )
		{
			$location->setName($this->getRequestParameter('location_name'));
		}
	
		$location->save();
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
			$this->saveLocation($restaurant->getId(), $this->getRequestParameter('address'), $this->getRequestParameter('city'), $this->getRequestParameter('state'), $this->getRequestParameter('phone'));
		}
		
		return $this->redirect('yahoolocal/index');
	}
}
