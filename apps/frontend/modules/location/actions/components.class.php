<?php

class locationComponents extends sfComponents
{

	public function executeFreshest ()
	{
		// limit somehow to just restaurants near a given location
		if ($this->getUser()->hasLocation()) {
			$this->locations = LocationPeer::getNear($this->near, null, 1, 'min_distance=off gradients=on order=distance,restaurant.updated_at DESC limit=8');
		} 
		else {

			$c = new Criteria();
			$c->addDescendingOrderByColumn(RestaurantPeer::UPDATED_AT);
			$c->setLimit(8);
			$this->locations = LocationPeer::doSelectJoinRestaurant($c);
		}
	}
}