<?php

require_once 'lib/model/om/BaseRestaurantTag.php';


/**
 * Skeleton subclass for representing a row from the 'restaurant_tag' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package model
 */	
class RestaurantTag extends BaseRestaurantTag {
	public function setTag($v)
	{
	  parent::setTag($v);

	  $this->setNormalizedTag(Tag::normalize($v));
	}
} // RestaurantTag
