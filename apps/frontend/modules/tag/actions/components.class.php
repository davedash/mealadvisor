<?php

class tagComponents extends sfComponents
{
	public function executePopularCloud()
	{
		$this->tags = MenuitemTagPeer::getPopularTags(40);
	}
	public function executePopularRestaurantCloud()
	{
		$this->tags = RestaurantTagPeer::getPopularTags(40);
		return "hi";
	}
}

?>