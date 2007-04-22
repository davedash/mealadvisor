<?php

  // include base peer class
  require_once 'lib/model/om/BaseLocationPeer.php';
  
  // include object class
  include_once 'lib/model/Location.php';


/**
 * Skeleton subclass for performing query and update operations on the 'location' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package model
 */	
class LocationPeer extends BaseLocationPeer {
	public static function retrieveByStrippedTitles($r, $l)
	{
		$c = new Criteria();
		$c->add(RestaurantPeer::STRIPPED_TITLE, $r);
		$restaurant = RestaurantPeer::doSelectOne($c);
		//echo $restaurant;
		$c->add(LocationPeer::RESTAURANT_ID, $restaurant->getId());
		$c->add(LocationPeer::STRIPPED_TITLE, $l);
		return LocationPeer::doSelectOne($c);
		
	}
	
	public static function searchIn($query, YahooGeo $geo, $page = 1)
	{
		$country = CountryPeer::retrieveByMagic($geo->getCountry());
		
		$where = array();
		
		$where[] = LocationPeer::COUNTRY_ID.'=\''.$country->getIso()."'";

		if ($stateStr = $geo->getState())
		{
			$state = StatePeer::retrieveByMagic($stateStr);
			
			if ($state instanceof State)
			{
				$where[] = '(' . LocationPeer::STATE.'=\''.$state->getUsps()."' OR "
					. LocationPeer::STATE.'=\''.$state->getName()."')";
			}
			else
			{
				$where[] = LocationPeer::STATE.'=\''.stateStr."'";
			}
		}
		
		$rs = self::getSearchQuery($query, $page, array('where' => $where));
		$locations = array();
		
		while ($rs->next())
		{
			$locations[] = self::retrieveByPK($rs->getInt(2));
		}

		return $locations;
	}
	
	
	
	public static function getSearchQuery($phrase, $page, $options = array())
	{
		$con = Propel::getConnection('propel');
		
		$words    = array_values(myTools::stemPhrase($phrase));
		$nb_words = count($words);
		$max      = sfConfig::get('app_search_results_max',10);
		$offset   = $max * ($page - 1);

		$query = '
		SELECT DISTINCT '.RestaurantSearchIndexPeer::RESTAURANT_ID.', '.LocationPeer::ID.',
		COUNT(*) AS nb, SUM('.RestaurantSearchIndexPeer::WEIGHT.') AS total_weight';
		
		if (array_key_exists('select',$options))
		{
			$query .= ','.implode(',', $options['select']); // geo fields can go here
		}
		
		$query .= '
		FROM '.RestaurantSearchIndexPeer::TABLE_NAME.', '.LocationPeer::TABLE_NAME;

		$query .= '
		WHERE '.LocationPeer::RESTAURANT_ID.
		'='.RestaurantSearchIndexPeer::RESTAURANT_ID.' AND';

		
		$query .= '
		('.implode(' OR ', array_fill(0, $nb_words, RestaurantSearchIndexPeer::WORD.' LIKE ?')).') 
		';
		
		
		if (array_key_exists('where',$options))
		{
			$query .= ' AND '.implode(' AND ', $options['where']); // geo fields can go here
		}
		
		$query .= '
			GROUP BY '.RestaurantSearchIndexPeer::RESTAURANT_ID;

		if (array_key_exists('group_by',$options))
		{
			$query .= ', '.implode(',', $options['group_by']); // geo fields can go here
		}


		if (array_key_exists('exact',$options) && $options['exact'])
		{
			$options['having'][] = 'nb = '.$nb_words;
		}

		if (array_key_exists('having',$options))
		{
			$query .= '
			HAVING ' .implode(' AND ', $options['having']);
		}
		
		$query .= '
		ORDER BY ';
		
		if (array_key_exists('order',$options))
		{
			$query .= implode(',', $options['order']) . ','; // geo fields can go here
		}
		
		
		$query .= ' nb DESC, total_weight DESC';
		
		// prepare the statement
		$stmt = $con->prepareStatement($query);
		$stmt->setOffset($offset);
		$stmt->setLimit($max);
		$placeholder_offset = 1;

		for ($i = 0; $i < $nb_words; $i++)
		{
			$stmt->setString($i + $placeholder_offset, $words[$i] . '%'); // experiment with % for LIKE
		}
		return $stmt->executeQuery(ResultSet::FETCHMODE_NUM);
		
	}
	
	public static function searchNear($query, YahooGeo $geo, $page = 1)
	{
		$lat = $geo->getLatitude();
		$lng = $geo->getLongitude();
		
		$select = array();
		$select[] = '(
			(
				(
					acos(sin((' . $lat . '*pi()/180)) * sin((latitude*pi()/180)) 
					+ 
					cos(('.$lat.'*pi()/180)) * cos((latitude*pi()/180)) 
					* 
					cos((('.$lng.' - longitude)*pi()/180)))
				)
				* 180/pi()
			)
			*60*1.1515
		) AS distance';
		
		$select[] = LocationPeer::LATITUDE;
		$select[] = LocationPeer::LONGITUDE;
		
		$group_by = array();
		$group_by[] = LocationPeer::LATITUDE;
		$group_by[] = LocationPeer::LONGITUDE;
		
		$having = array();
		$having[] = 'distance < '.sfConfig::get('app_search_city_radius',25);
		
		$order = array();
		$order[] = 'distance';
		
		$options = array('select' => $select, 'group_by' => $group_by, 'having' => $having, 'order' => $order);
		
		$rs = self::getSearchQuery($query, $page, $options);
		$locations = array();
		
		while ($rs->next())
		{
			$loc = self::retrieveByPK($rs->getInt(2));
			$loc->search_distance = $rs->getFloat(5);
			$locations[] = $loc;
		}

		return $locations;
	}
	
	public static function getNearestForRestaurant(Restaurant $restaurant, $location, $page = 1, $options = array()) 
	{
		list($search_location, $near, $radius) = myTools::getNearness($location);	
		
		$locations = LocationPeer::allNear($near, $radius, $exact = false, $offset = 0, $max = 10, array('restaurant' => $restaurant, 'min_distance' => 'off'  ));
		if (count($locations))
		{
			return $locations[0];
		}
	}

	public static function getBounds($number, $scale)
	{
		$number *= pow(10,$scale);
		$lower = floor($number) / pow(10,$scale);
		$upper = ceil($number) / pow(10,$scale);
		return array($lower, $upper);
	}
	
	public static function findNearLatLng($lat, $lng)
	{
		// do a query like:
		$c = new Criteria();
		// round lat/lng to 2 decimals...
		list($l, $u) = self::getBounds($lat, 3);

		$c->add(LocationPeer::LATITUDE, 'latitude BETWEEN '.$l.' AND '.$u, Criteria::CUSTOM);
		list($l, $u) = self::getBounds($lng, 3);

		$c->add(LocationPeer::LONGITUDE, 'longitude BETWEEN '.$l.' AND '.$u, Criteria::CUSTOM);
		// SELECT * FROM `location` 
		// 
		// 		WHERE latitude BETWEEN 44.95 AND 44.96
		// 		AND longitude BETWEEN -93.28 AND -93.27
		// 		 ORDER BY `latitude`
		return self::doSelect($c);
	}
} // LocationPeer
