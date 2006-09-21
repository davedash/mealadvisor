<?php

  // include base peer class
  require_once 'lib/model/om/BaseMenuItemPeer.php';
  
  // include object class
  include_once 'lib/model/MenuItem.php';


/**
 * Skeleton subclass for performing query and update operations on the 'menu_item' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package model
 */	
class MenuItemPeer extends BaseMenuItemPeer {
	
	public static function search($phrase, $exact = false, $offset = 0, $max = 10)
	{
	  $words    = array_values(myTools::stemPhrase($phrase));
	  $nb_words = count($words);

	  if (!$words)
	  {
	    return array();
	  }

	  $con = Propel::getConnection('propel');

	  // define the base query
	  $query = '
	      SELECT DISTINCT '.MenuitemSearchIndexPeer::MENUITEM_ID.', COUNT(*) AS nb, SUM('.MenuitemSearchIndexPeer::WEIGHT.') AS total_weight
	      FROM '.MenuitemSearchIndexPeer::TABLE_NAME;



	  $query .= ' WHERE 
	      ('.implode(' OR ', array_fill(0, $nb_words, MenuitemSearchIndexPeer::WORD.' = ?')).')
	      GROUP BY '.MenuitemSearchIndexPeer::MENUITEM_ID;

	  // AND query?
	  if ($exact)
	  {
	    $query .= '
	      HAVING nb = '.$nb_words;
	  }

	  $query .= '
	      ORDER BY nb DESC, total_weight DESC';

	  // prepare the statement
	  $stmt = $con->prepareStatement($query);
	  $stmt->setOffset($offset);
	  $stmt->setLimit($max);
	  $placeholder_offset = 1;

	  for ($i = 0; $i < $nb_words; $i++)
	  {
	    $stmt->setString($i + $placeholder_offset, $words[$i]);
	  }
	  $rs = $stmt->executeQuery(ResultSet::FETCHMODE_NUM);

	  // Manage the results
	  $items = array();
	  while ($rs->next())
	  {
	    $items[] = self::retrieveByPK($rs->getInt(1));
	  }

	  return $items;
	}
		
	public static function getPopularByTag($tag, $page = 0) 
	{ 
		$c = new Criteria(); 
		$c->add(MenuitemTagPeer::NORMALIZED_TAG, $tag); 
		$c->addJoin(MenuitemTagPeer::MENU_ITEM_ID, MenuItemPeer::ID, Criteria::LEFT_JOIN); 
		return MenuItemPeer::doSelect($c);
		
	} 
	public static function retrieveByHash($h)
	{
		$con = Propel::getConnection();

		// if not using a driver that supports sub-selects
		// you must do a cross join (left join w/ NULL)
		$sql = 'SELECT menu_item.* FROM menu_item WHERE '.
		"MD5(CONCAT('menuitem:',id)) = '$h'";

		$stmt = $con->createStatement();
		$rs = $stmt->executeQuery($sql, ResultSet::FETCHMODE_NUM);

		$object = parent::populateObjects($rs);     
		if ($object)
		{
			return $object[0];
		}   
		return null;
	}
	
	public static function retrieveByStrippedTitle ($restaurant, $stripped_title)
	{
		$c = new Criteria();
		if (!$restaurant instanceof Restaurant) {
			$restaurant = RestaurantPeer::retrieveByStrippedTitle($restaurant);
		}
		$c->add(MenuItemPeer::RESTAURANT_ID, $restaurant->getId());
		$c->add(MenuItemPeer::URL, $stripped_title);
		return MenuItemPeer::doSelectOne($c);
	}
}
// MenuItemPeer
