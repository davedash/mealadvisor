<?php
class menuitemComponents extends sfComponents
{
	public function executeFeature()
	{
		$c = new Criteria();
		$c->addDescendingOrderByColumn("rand()");
		$c->setLimit(7);
		$this->images = MenuItemImagePeer::doSelectJoinAll($c);
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
