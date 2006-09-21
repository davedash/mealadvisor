<?php

  // include base peer class
  require_once 'lib/model/om/BaseRestaurantPeer.php';
  
  // include object class
  include_once 'lib/model/Restaurant.php';


/**
 * Skeleton subclass for performing query and update operations on the 'restaurant' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package model
 */	
class RestaurantPeer extends BaseRestaurantPeer {
	public static function getPopularByTag($tag, $page = 0) 
	{ 
		$c = new Criteria(); 
		$c->add(RestaurantTagPeer::NORMALIZED_TAG, $tag); 
		$c->addJoin(RestaurantTagPeer::RESTAURANT_ID, RestaurantPeer::ID, Criteria::LEFT_JOIN); 
		return RestaurantPeer::doSelect($c);
		
	}	
	public static function retrieveByStrippedTitle($v)
	{
		$c = new Criteria();
		$c->add(RestaurantPeer::STRIPPED_TITLE, $v);
		$restaurants =  RestaurantPeer::doSelectJoinAll($c);
		if (count($restaurants)) return $restaurants[0];
		return null;
	}
	
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
	      SELECT DISTINCT '.RestaurantSearchIndexPeer::RESTAURANT_ID.', COUNT(*) AS nb, SUM('.RestaurantSearchIndexPeer::WEIGHT.') AS total_weight
	      FROM '.RestaurantSearchIndexPeer::TABLE_NAME;

	    $query .= '
	      WHERE ';

	  $query .= '
	      ('.implode(' OR ', array_fill(0, $nb_words, RestaurantSearchIndexPeer::WORD.' LIKE ?')).')
	      GROUP BY '.RestaurantSearchIndexPeer::RESTAURANT_ID;
		// experimenting with LIKE ^^^ vs =
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
	    $stmt->setString($i + $placeholder_offset, $words[$i] . '%'); // experiment with % for LIKE
	  }
	  $rs = $stmt->executeQuery(ResultSet::FETCHMODE_NUM);

	  // Manage the results
	  $restaurants = array();
	  while ($rs->next())
	  {
	    $restaurants[] = self::retrieveByPK($rs->getInt(1));
	  }

	  return $restaurants;
	}

	public static function retrieveByHash($h)
	{
		$con = Propel::getConnection();

		// if not using a driver that supports sub-selects
		// you must do a cross join (left join w/ NULL)
		$query = 'SELECT %s.* FROM %s WHERE MD5(CONCAT("restaurant:",%s)) = ?';
		
		$query = sprintf($query,self::TABLE_NAME,self::TABLE_NAME,self::ID);
		$stmt = $con->prepareStatement($query);
		$stmt->setString(1, $h);
		$rs = $stmt->executeQuery(ResultSet::FETCHMODE_NUM);

		$object = parent::populateObjects($rs);     
		if ($object)
		{
			return $object[0];
		}   
		return null;
	}

	public static function retrieveByMenuItemTag($tag)
	{
		$query = 'SELECT DISTINCT %s FROM %s, %s WHERE %s = ? AND %s = %s';
		$query = sprintf($query, 
			MenuItemPeer::RESTAURANT_ID, 
			MenuitemTagPeer::TABLE_NAME, 
			MenuItemPeer::TABLE_NAME, 
			MenuitemTagPeer::NORMALIZED_TAG, 
			MenuitemTagPeer::MENU_ITEM_ID,
			MenuItemPeer::ID);
		$con = Propel::getConnection('propel');
		$stmt = $con->prepareStatement($query);
		$stmt->setString(1, $tag);
		$rs = $stmt->executeQuery(ResultSet::FETCHMODE_NUM);

		// Manage the results
		$restaurants = array();
		while ($rs->next())
		{
			$restaurants[] = self::retrieveByPK($rs->getInt(1));
		}

		return $restaurants;
	}
} // RestaurantPeer
