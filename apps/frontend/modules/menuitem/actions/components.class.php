<?php
class menuitemComponents extends sfComponents
{
	public function executeFeature()
	{
		$c = new Criteria();
		$c->addDescendingOrderByColumn("rand()");
		$c->setLimit(1);
		$mis = MenuItemImagePeer::doSelectJoinAll($c);
		$mi = $mis[0];
				
		$this->item = $mi->getMenuItem();
		$this->image = $mi;
		$this->rating = null;

		if ($this->getUser()->isLoggedIn()) {
			$c = new Criteria();
			$c->add(MenuItemRatingPeer::MENU_ITEM_ID, $this->item->getId());
			$c->add(MenuItemRatingPeer::USER_ID, $this->getUser()->getId());
			$rating = MenuItemRatingPeer::doSelectOne($c);
			if ($rating instanceof MenuItemRating) $this->rating = $rating->getValue();
		}
	}
	
	public function executeInRestaurant()
	{
		$c = new Criteria();
		$c->add(MenuItemPeer::RESTAURANT_ID, $this->restaurant->getId());
		$c->addDescendingOrderByColumn(MenuItemPeer::NUM_RATINGS);
		$pager = new sfPropelPager('MenuItem', sfConfig::get('app_restaurant_max_menuitems'));
		$pager->setCriteria($c);
		$pager->setPage(myTools::_or($this->page, 1));
		$pager->init();
		$this->pager = $pager;
		$this->nav_url = '@menuitems_in_restaurant?restaurant='.$this->restaurant->getStrippedTitle().'&scope='.$this->scope.'&page=';
	}
}
