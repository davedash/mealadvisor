<?php

require_once 'lib/model/om/BaseMenuItemRating.php';


/**
 * Skeleton subclass for representing a row from the 'menuitem_rating' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package model
 */	
class MenuItemRating extends BaseMenuItemRating {
	public function save($con = null)
	{
		$ret = parent::save();

		$con = Propel::getConnection();
		$con->begin();
		$query = 'SELECT count(%s), avg(%s) FROM %s WHERE %s = ?';
		$query = sprintf($query, MenuItemRatingPeer::VALUE,MenuItemRatingPeer::VALUE,MenuItemRatingPeer::TABLE_NAME, MenuItemRatingPeer::MENU_ITEM_ID);
		
		$stmt = $con->prepareStatement($query);
		$stmt->setInt(1, $this->getMenuItemId());
		$rs = $stmt->executeQuery(ResultSet::FETCHMODE_NUM);
		while($rs->next()) 
		{
			$this->getMenuItem()->setNumRatings($rs->getInt(1));
			$this->getMenuItem()->setAverageRating($rs->getFloat(2));
			$this->getMenuItem()->save();
		}
		$con->commit();
		
	}
} // MenuItemRating
