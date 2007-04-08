<?php

// include base peer class
require_once 'lib/model/om/BaseCountryPeer.php';

// include object class
include_once 'lib/model/Country.php';


/**
	* Skeleton subclass for performing query and update operations on the 'country' table.
	*
	* 
	*
	* You should add additional methods to this class to meet the
	* application requirements.  This class will only be generated as
	* long as it does not already exist in the output directory.
	*
	* @package model
	*/	
class CountryPeer extends BaseCountryPeer {

	public static function retrieveByMagic($searchString)
	{
		$cc = new Criteria();
		$cton1 = $cc->getNewCriterion(CountryPeer::NAME, $searchString);
		$cton2 = $cc->getNewCriterion(CountryPeer::ISO,  $searchString);
		$cton3 = $cc->getNewCriterion(CountryPeer::ISO3, $searchString);

		$cton1->addOr($cton2);
		$cton1->addOr($cton3);
		$cc->add($cton1);
		return CountryPeer::doSelectOne($cc);

	}
} // CountryPeer
