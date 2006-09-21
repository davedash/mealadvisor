<?php

require_once 'lib/model/om/BaseMenuitemTag.php';


/**
* Skeleton subclass for representing a row from the 'menuitem_tag' table.
*
* 
*
* You should add additional methods to this class to meet the
* application requirements.  This class will only be generated as
* long as it does not already exist in the output directory.
*
* @package model
*/	
class MenuitemTag extends BaseMenuitemTag {
	public function setTag($v) 
	{ 
		parent::setTag($v); 
		$this->setNormalizedTag(Tag::normalize($v)); 
	} 

	public function save($con = null)
	{
	  $con = Propel::getConnection('propel');
	  try
	  {
	    $con->begin();

	    $ret = parent::save($con);
	    $this->getMenuitem()->updateSearchIndex();

	    $con->commit();

	    return $ret;
	  }
	  catch (Exception $e)
	  {
	    $con->rollback();
	    throw $e;
	  }
	}
} 
// MenuitemTag
