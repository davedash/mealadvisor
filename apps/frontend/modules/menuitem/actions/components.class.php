<?php
class menuitemComponents extends sfComponents
{
	public function executeFeature()
	{
		
		$mis = MenuItemImagePeer::doSelectJoinAll(new Criteria());
		shuffle($mis);
		$mi = new MenuItemImage();
		if (isset($mis[0])) $mi = $mis[0];
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
}
