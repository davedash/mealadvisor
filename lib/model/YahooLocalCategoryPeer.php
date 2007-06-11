<?php

/**
 * Subclass for performing query and update operations on the 'yahoo_local_category' table.
 *
 * 
 *
 * @package lib.model
 */ 
class YahooLocalCategoryPeer extends BaseYahooLocalCategoryPeer
{
	public static function add($id, $desc)
	{
		$cat = self::retrieveByPK($id);
		if (!$cat instanceof YahooLocalCategory)
		{
			$cat = new YahooLocalCategory();
			$cat->setYid($id);
			$cat->setDescription($desc);
			$cat->save();
		}
	}
	
	public static function retrieveAll()
	{
		$c = new Criteria();
		$c->addAscendingOrderByColumn(YahooLocalCategoryPeer::DESCRIPTION);
		return self::doSelect($c);
	}
}
