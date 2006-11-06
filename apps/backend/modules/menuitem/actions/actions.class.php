<?php

/**
 * menuitem actions.
 *
 * @package    reviewsby.us
 * @subpackage menuitem
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2288 2006-10-02 15:22:13Z fabien $
 */
class menuitemActions extends automenuitemActions
{
	public function executeBatch()
	{
		if ($this->isPost()) {
			$restaurant = RestaurantPeer::retrieveByPK($this->getRequestParameter('restaurant_id'));
			$string = $this->getRequestParameter('batch');
			$lines = preg_split('/[\n\r]+/',$string);
			foreach ($lines AS $line) {
				$price = null;
				@list($item, $description, $price) = preg_split('/\t/', $line);
				if (empty($item)) {
					continue;
				}
				$item = ucwords(strtolower($item));
				// lookup group and determine if this is new or replace
				$c = new Criteria();
				$c->add(MenuItemPeer::NAME, $item);
				$c->add(MenuItemPeer::RESTAURANT_ID, $restaurant->getId());
				$m = MenuItemPeer::doSelectOne($c);
				
				if (!$m instanceof MenuItem) {
					$m = new MenuItem();
					$m->setName($item);
					
				}
				
				$m->setRestaurant($restaurant);
				$m->setDescription($description);
				
				if ($price) {
					$m->setPrice($price);
				}
				$m->save();
				// determine if private
			}
			$this->redirect('menuitem');
		}

	}
	
	public function isPost()
	{
		return ($this->getRequest()->getMethod() == sfRequest::POST);
	}

}
