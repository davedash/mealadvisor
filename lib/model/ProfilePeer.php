<?php

  // include base peer class
  require_once 'lib/model/om/BaseProfilePeer.php';
  
  // include object class
  include_once 'lib/model/Profile.php';


/**
 * Skeleton subclass for performing query and update operations on the 'user' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package model
 */	
class ProfilePeer extends BaseProfilePeer {
	public static function retrieveByUsername($v)
	{		
			$c = new Criteria();
			$c->add(sfGuardUserPeer::USERNAME, $v);
			$sfgu = sfGuardUserPeer::doSelectOne($c);
			return $sfgu->getProfile();
	}
} // ProfilePeer
