<?php

/**
* location actions.
*
* @package    ##PROJECT_NAME##
* @subpackage location
* @author     Your name here
* @version    SVN: $Id: actions.class.php 500 2006-01-23 09:15:57Z fabien $
*/
class locationActions extends sfActions
{

	public function executeShow ()
	{
		$this->location = LocationPeer::retrieveByStrippedTitles($this->getRequestParameter('restaurant'), $this->getRequestParameter('location'));
		$this->getResponse()->setTitle(strip_tags($this->location->__toString()) . ' &laquo; ' .$this->location->getRestaurant()->__toString() . ' &laquo; ' . sfConfig::get('app_title'), true);

		$this->forward404Unless($this->location instanceof Location);
	}

	public function executeEdit ()
	{
		if(!$this->getUser()->isLoggedIn())
		{
			// build the url where we need to get redirected...
			$this->setFlash('post_login', $this->getController()->genUrl('@location_add?restaurant=' . $this->getRequestParameter('restaurant'),1));
			return $this->redirect('@sf_guard_signin');
		}
		$this->location = $this->getLocationOrCreate();
		$c = new Criteria();
		$c->add(RestaurantPeer::STRIPPED_TITLE, $this->getRequestParameter('restaurant'));
		$this->restaurant = RestaurantPeer::doSelectOne($c);
		if ($this->getRequest()->getMethod() != sfRequest::POST)
		{
			// display the form
			return sfView::SUCCESS;
		}	
		$this->location->setRestaurant($this->restaurant);
		if ( $this->getRequestParameter('name') )
		{
			$this->location->setName($this->getRequestParameter('name'));
		}
		$this->location->setAddress($this->getRequestParameter('address'));
		$this->location->setCity($this->getRequestParameter('city'));
		$this->location->setState($this->getRequestParameter('state'));
		if ($this->getRequestParameter('country')) {
			$this->location->setCountryId($this->getRequestParameter('country'));
		}
		$zip = preg_replace('/\D/','', $this->getRequestParameter('zip'));

		if ($zip) {
			$this->location->setZip($zip);
		}
		$phone = $this->getRequestParameter('phone');
		$phone = preg_replace('/\D/', '', $phone);

		if ($phone) {
			$this->location->setPhone($phone);
		}
		$this->location->save();
		// still need to work on this...
		return $this->redirect('@restaurant?stripped_title='.$this->restaurant->getStrippedTitle());
	}


	private function getLocationOrCreate ($id = 'id')
	{
		if (!$this->getRequestParameter($id, 0))
		{
			$location = new Location();
			// set the US to be default country

		}
		else
		{
			$location = LocationPeer::retrieveByPk($this->getRequestParameter($id));

			$this->forward404Unless($location instanceof Location);
		}

		return $location;
	}

	public function executeIndex()
	{
	  // limit somehow to just restaurants near a given location
		if ($this->getUser()->hasLocation()) {
			$this->locations = LocationPeer::getNear($this->getUser()->getLocation(), null, 1, 'min_distance=off gradients=on order=distance,restaurant.updated_at DESC');
		} 
		else {
			$c = new Criteria();
			$c->addDescendingOrderByColumn(RestaurantPeer::UPDATED_AT);
			$this->locations = LocationPeer::doSelectJoinRestaurant($c);
		}
	}
	
}

