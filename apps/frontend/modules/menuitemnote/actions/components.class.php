<?php

class menuitemnoteComponents extends sfComponents
{

	public function executeLatest ()
	{
		$c = new Criteria();
		
		$c->addDescendingOrderByColumn(MenuItemNotePeer::CREATED_AT);
		$c->setLimit(4);
		$this->notes = MenuItemNotePeer::doSelectJoinAll($c);
	}
}