<?php

/**
 * restaurant actions.
 *
 * @package    reviewsby.us
 * @subpackage restaurant
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2288 2006-10-02 15:22:13Z fabien $
 */
class restaurantActions extends autorestaurantActions
{
	protected function updateRestaurantFromRequest()
	{
		$yids = $this->getRequestParameter('yid[]', array());
		foreach( $yids as $yid )
		{
			$l = new Location();
			$l->updateFromYahooLocalId($yid);
			$l->setRestaurant($this->restaurant);
			$l->save();
		}
		// Let symfony handle the other fields
		parent::updateRestaurantFromRequest();
	}
	
	public function executeYl_results()
	{
		$this->loc    = $this->getRequestParameter('l', 'United States');
	  $this->restaurant  = $this->getRequestParameter('q');
		$this->page        = $this->getRequestParameter('p');
		try
		{
			$local = new YahooLocal($this->restaurant, array('results' => '20','category' => YahooLocal::CATEGORY_RESTAURANTS, 'page' => $this->page,'location'=>$this->loc,'radius' => '4000', ));
			$this->results = $local->getResults();
		}
		catch (Exception $e)
		{
			return sfView::ERROR;
		}
	}
}
