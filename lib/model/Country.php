<?php

require_once 'lib/model/om/BaseCountry.php';


/**
 * Skeleton subclass for representing a row from the 'country' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package model
 */	
class Country extends BaseCountry {

	public function __toString ()
	{
		return $this->getPrintableName();
	}
} // Country
