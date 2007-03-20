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

	public function getUsernameForURL()
	{
		// normalize it by putting it in lowercase
		return strtolower($this->getUsername());
	}
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

	public function save ($con = null)
	{
		if ($this->save_user) {
			$u = $this->getUser();
			$u->save();
			$this->setUserid($u->getId());
		}
		parent::save($con);

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



		public function getAssociatedRestaurants($max = null, $offset = 0)
		{
			// get restaurants that user rated or commented on...
			// we can do this cleaner
			// select everything from the restaurant table
			// left join it with RRating
			// left join it with RComments
			// where either RC.userid or RR.userid = the userid we want...
			
			$c = new Criteria();
			
			$c->addJoin(RestaurantPeer::ID, RestaurantRatingPeer::RESTAURANT_ID, Criteria::LEFT_JOIN);
			$c->addJoin(RestaurantPeer::ID, RestaurantNotePeer::RESTAURANT_ID, Criteria::LEFT_JOIN);
			$cton1 = $c->getNewCriterion(RestaurantRatingPeer::USER_ID, $this->getId());
			$cton2 = $c->getNewCriterion(RestaurantNotePeer::USER_ID, $this->getId());
			$cton1->addOr($cton2);
			$c->add($cton1);
			$c->setOffset($offset);
			$c->setLimit($max);
			$c->addDescendingOrderByColumn(RestaurantRatingPeer::VALUE);
			$c->setDistinct();
			$restaurants = RestaurantPeer::doSelect($c);
			return $restaurants;
			
		}
			public function __toString()
			{
				return $this->getUsername();
			}

			} // Profile
