<?php

  // include base peer class
  require_once 'lib/model/om/BaseMenuItemImagePeer.php';
  
  // include object class
  include_once 'lib/model/MenuItemImage.php';


/**
 * Skeleton subclass for performing query and update operations on the 'menu_item_image' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package model
 */	
class MenuItemImagePeer extends BaseMenuItemImagePeer 
{
  public static function doSelectJoinItemAndRestaurant(Criteria $c, $con = null)
  {
    $c = clone $c;

    // Set the correct dbName if it has not been overridden
    if ($c->getDbName() == Propel::getDefaultDB()) {
      $c->setDbName(self::DATABASE_NAME);
    }

    // Add select columns for MII
    MenuItemImagePeer::addSelectColumns($c);
    $startcol2 = (MenuItemImagePeer::NUM_COLUMNS - MenuItemImagePeer::NUM_LAZY_LOAD_COLUMNS) + 1;

    // Add select columns for R
    RestaurantPeer::addSelectColumns($c);
    $startcol3 = $startcol2 + RestaurantPeer::NUM_COLUMNS ;

    // Add select columns for MI
    MenuItemPeer::addSelectColumns($c);

    // [NOTE 1]
    $c->addJoin(MenuItemImagePeer::MENU_ITEM_ID, MenuItemPeer::ID);
    $c->addJoin(MenuItemPeer::RESTAURANT_ID, RestaurantPeer::ID);

    $rs = BasePeer::doSelect($c, $con);
    $results = array();

    while($rs->next())
    {
      // Hydrate the Article object
      $omClass = MenuItemImagePeer::getOMClass();

      $cls = Propel::import($omClass);
      $obj1 = new $cls();
      $obj1->hydrate($rs);

      // Hydrate the Book object
      $omClass = RestaurantPeer::getOMClass();

      $cls = Propel::import($omClass);
      $obj2 = new $cls();
      $obj2->hydrate($rs, $startcol2);

      // Hydrate the Category object
      $omClass = MenuItemPeer::getOMClass();

      $cls = Propel::import($omClass);
      $obj3 = new $cls();
      $obj3->hydrate($rs, $startcol3);

      // [NOTE 2]
      $obj1->setMenuItem($obj3);
      $obj3->setDefaultMenuItemImage($obj1);
      $obj3->setRestaurant($obj2); 
      $results[] = $obj1;
    }

    return $results;
  }
} // MenuItemImagePeer
