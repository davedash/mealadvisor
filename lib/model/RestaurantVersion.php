<?php

require_once 'lib/model/om/BaseRestaurantVersion.php';
require_once 'markdown.php';

/**
 * Skeleton subclass for representing a row from the 'restaurant_version' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package model
 */	
class RestaurantVersion extends BaseRestaurantVersion {
	public function setDescription($v)
	{
	  parent::setDescription($v);

	  require_once('markdown.php');

	  $this->setHtmlDescription(markdown($v));
	}
} // RestaurantVersion
