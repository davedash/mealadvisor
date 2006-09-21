<?php

/**
* default actions.
*
* @package    eatw.us
* @subpackage default
* @author     Your name here
* @version    SVN: $Id: actions.class.php 500 2006-01-23 09:15:57Z fabien $
*/
class defaultActions extends sfActions
{
	public function executeStatic()
  {
        $this->page = $this->getRequestParameter('page');
  }
	public function executeTest()
	{
		$c = new Criteria();
		$c->add(LocationPeer::LATITUDE, null);
		$ls = LocationPeer::doSelect($c);
		foreach ($ls AS $l)
		{
			echo $l;
			$l->save();
		}
		
		exit;
	}
	
	public function isPost()
	{
		return ($this->getRequest()->getMethod() == sfRequest::POST);
	}

	public function executeUpdateIndex()
	{

		$restaurants = RestaurantPeer::doSelect(new Criteria());
		foreach ($restaurants AS $restaurant) {
			$restaurant->setChain($restaurant->getOldChain());
			$restaurant->save();
		}
		
		echo 'done';
		exit;
	}

	/**
	* Executes index action
	*
	*/
	public function executeIndex()
	{
		$this->getResponse()->addJavascript(sfConfig::get('SF_PROTOTYPE_WEB_DIR').'/js/prototype.js');
		$this->getResponse()->addJavascript('map');
		$this->getResponse()->addJavascript('latestMap');
		
		$c = new Criteria();
		$this->num_restaurants = RestaurantPeer::doCount($c);

		$c->addDescendingOrderByColumn(RestaurantPeer::UPDATED_AT);
		$c->setLimit(7);
		$this->restaurants = RestaurantPeer::doSelect($c);
		$this->footerLogoOff = true;
	}

	public function executeError404()
	{}

	public function executeSecure()
	{

	}

	public function executeLogin()
	{
		if ($this->hasFlash('post_login')) {
			$this->getRequest()->getParameterHolder()->set('referer', $this->getFlash('post_login'));
		} else {
			$this->getRequest()->getParameterHolder()->set('referer', $this->getRequest()->getReferer());
		}
		
	}
}

?>