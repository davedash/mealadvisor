<?php

/**
	* Subclass for performing query and update operations on the 'state' table.
	*
	* 
	*
	* @package lib.model
	*/ 
class StatePeer extends BaseStatePeer
{
	public static function retrieveByMagic($stateString)
	{

		$sc = new Criteria();
		$cton1 = $sc->getNewCriterion(StatePeer::USPS, $stateString);
		$cton2 = $sc->getNewCriterion(StatePeer::NAME,  $stateString);
		$cton1->addOr($cton2);
		$sc->add($cton1);
		return StatePeer::doSelectOne($sc);
	}

}
