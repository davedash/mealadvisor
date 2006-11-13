<?php

/**
* api actions.
*
* @package    reviewsby.us
* @subpackage api
* @author     Your name here
* @version    SVN: $Id: actions.class.php 500 2006-01-23 09:15:57Z fabien $
*/
class apiActions extends sfActions
{
	public function preExecute()
	{
		sfConfig::set('sf_web_debug', false);
	}

	/**
	* Executes index action
	*
	*/
	public function executeGoogleCoop()
	{
		$this->tags = MenuitemTagPeer::getPopularTags(30);
		$this->restaurants = RestaurantPeer::doSelect(new Criteria());
	}
}
