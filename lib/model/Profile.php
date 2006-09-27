<?php

require_once 'lib/model/om/BaseProfile.php';


/**
* Skeleton subclass for representing a row from the 'user' table.
*
* 
*
* You should add additional methods to this class to meet the
* application requirements.  This class will only be generated as
* long as it does not already exist in the output directory.
*
* @package model
*/	
class Profile extends BaseProfile {
	protected $reindex = false, $sfGuardUser = null, $save_user = false;

	public function getsfGuardUser ()
	{

		if ($this->sfGuardUser === null)
		{
			if ($this->getUserid())
			{
				$this->sfGuardUser = sfGuardUserPeer::retrieveByPK($this->getUserid());
			} 
			else {
				$this->sfGuardUser = new sfGuardUser();
			}
		}
		return $this->sfGuardUser;
	}
	
	public function getUser ()
	{
		return $this->getsfGuardUser();
	}


	public function setUsername ( $v )
	{
		$u = $this->getUser();
		$u->setUsername($v);
		$this->save_user = true;
		$this->reindex = true;
	}


	public function getUsername() 
	{
		return $this->getUser()->getUsername();
	}
	
	public function setPassword ( $v )
	{
		$u = $this->getUser();
		$u->setPassword($v);
		$this->save_user = true;
		$this->reindex = true;
	}

	public function save ($con = null)
	{
		if ($this->save_user) {
			$u = $this->getUser();
			$u->save();
			$this->setUserid($u->getId());
		}
		parent::save($con);

	}

		public function getAssociatedRestaurants($max = null, $offset = 0)
		{
			$con = Propel::getConnection();
			$restaurant_ids = array();

			// get restaurants that this user rated
			$query = 'SELECT DISTINCT %s FROM %s WHERE %s = ? ORDER BY %s DESC';
			$query = sprintf($query, RestaurantRatingPeer::RESTAURANT_ID, RestaurantRatingPeer::TABLE_NAME, RestaurantRatingPeer::USER_ID, RestaurantRatingPeer::VALUE);

			// prepare the statement
			$stmt = $con->prepareStatement($query);
			$stmt->setInt(1, $this->getId()); 

			$rs = $stmt->executeQuery(ResultSet::FETCHMODE_NUM);

			// Manage the results
			while ($rs->next())
			{
				$restaurant_ids[] = $rs->getInt(1);
			}

			// get restaurants that this user commented on
			$query = 'SELECT DISTINCT %s FROM %s WHERE %s = ?';
			$query = sprintf($query, RestaurantNotePeer::RESTAURANT_ID, RestaurantNotePeer::TABLE_NAME, RestaurantNotePeer::USER_ID);

			// prepare the statement
			$stmt = $con->prepareStatement($query);
			$stmt->setInt(1, $this->getId()); 

			$rs = $stmt->executeQuery(ResultSet::FETCHMODE_NUM);

			// Manage the results
			while ($rs->next())
			{
				$restaurant_ids[] = $rs->getInt(1);
			}		

			// remove dupes

			$restaurant_ids = array_unique($restaurant_ids);
			if ($max) {
				$restaurant_ids = array_splice($restaurant_ids, $offset, $max);
				} else if($offset) {
					$restaurant_ids = array_splice($restaurant_ids, $offset);
				}

				$restaurant = array();


				foreach($restaurant_ids AS $id) {
					$restaurant[] = RestaurantPeer::retrieveByPk($id);
				}

				return $restaurant;
			}
			public function __toString()
			{
				return $this->getUsername();
			}

			} // Profile
