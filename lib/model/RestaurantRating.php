<?php

require_once 'lib/model/om/BaseRestaurantRating.php';


/**
 * Skeleton subclass for representing a row from the 'restaurant_rating' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package model
 */	
class RestaurantRating extends BaseRestaurantRating {

	public function save($con = null)
	{
		$ret = parent::save();

		$con = Propel::getConnection();
		$con->begin();
		$query = 'SELECT count(%s), avg(%s) FROM %s WHERE %s = ?';
		$query = sprintf($query, RestaurantRatingPeer::VALUE,RestaurantRatingPeer::VALUE,RestaurantRatingPeer::TABLE_NAME, RestaurantRatingPeer::RESTAURANT_ID);
		
		$stmt = $con->prepareStatement($query);
		$stmt->setInt(1, $this->getRestaurantId());
		$rs = $stmt->executeQuery(ResultSet::FETCHMODE_NUM);
		while($rs->next()) 
		{
			$this->getRestaurant()->setNumRatings($rs->getInt(1));
			$this->getRestaurant()->setAverageRating($rs->getFloat(2));
			$this->getRestaurant()->save();
		}
		$con->commit();
		
	}
} // RestaurantRating
