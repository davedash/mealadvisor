<?php

/**
 * location actions.
 *
 * @package    reviewsby.us
 * @subpackage location
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2288 2006-10-02 15:22:13Z fabien $
 */
class locationActions extends autolocationActions
{
	public function executeBatch()
	{
		if ($this->isPost()) {
			$restaurant = RestaurantPeer::retrieveByPK($this->getRequestParameter('restaurant_id'));
			$string = $this->getRequestParameter('batch');
			$lines = preg_split('/[\n\r]+/',$string);
			$ls = $restaurant->getLocations();
			foreach ($ls AS $l) {
				$l->delete();
			} 
			foreach ($lines AS $line) {
				$price = null;
				@list($name, $address, $csz, $phone) = preg_split('/\t/', $line);
				if (empty($address)) {
					continue;
				}

				$l = null;
				if (!$l instanceof Location) {
					$l = new Location();
					$l->setName($name);
				}
				
				$l->setRestaurant($restaurant);
				$l->setAddress($address);
				
				list($city, $sz) = split(',', $csz);
				$matches = array();
				preg_match('/^(.*) ([0-9]{5})$/',$sz, $matches);
				list($blah, $state, $zip) = $matches;
				
				$l->setCity($city);
				$l->setState($state);
				$l->setZip($zip);
				$l->setPhone($phone);
				
				$l->save();
				// determine if private
			}
			$this->redirect('location');
		}
	}
	
	public function isPost()
	{
		return ($this->getRequest()->getMethod() == sfRequest::POST);
	}
}

