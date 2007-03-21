<?php

/**
 * sfNifty class.
 *
 * @package    symfony.sfNiftyPlugin
 * @author     Alban Creton <acreton@gmail.com>
 * @version    1.1.0
 */

class sfNifty
{
  
  private static $elements=array();
  
  /**
   * Add an element in rounded list.
   *
   * @param string id of the html element
   *
   * @return Boolean A sfController implementation instance
   */  
  public static function addId( $id )
  {
    if( isset(self::$elements[$id]) )
    {
      return false;
    }
    else
    {
    	self::$elements[$id] = true;
    	return true;
    }
  }
  
  
}

?>