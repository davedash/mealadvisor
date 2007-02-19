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
	
	public static function getNear($location, $query = null, $page = 1, $options = array()) 
	{
		$options = is_array($options) ? $options : sfToolkit::stringToArray($options);
		
		$max = sfConfig::get('app_search_results_max');
		
		if (isset($options['limit']))
		{
			$max = $options['limit'];
			unset($options['limit']);
		}
		
		list($search_location, $near, $radius) = myTools::getNearness($location);	
		if (!$near) {
			return array();
		}
		return LocationPeer::search($query, $near,$radius, false, ($page - 1) * sfConfig::get('app_search_results_max'), $max, $options);
	}
	
	public static function allNear($geo_info, $radius, $exact = false, $offset = 0, $max = 10, $options = array())
	{
		
		$options = is_array($options) ? $options : sfToolkit::stringToArray($options);
		$use_distance = (isset($options['min_distance']) && $options['min_distance'] = 'off') ? false : true;
		$use_gradients = (isset($options['gradients']) && $options['gradients'] = 'on') ? true : false;
		// we can divide the results into radial circles based on the value of radius if gradients are on
		$order = isset($options['order']) ? $options['order'] : 'distance ASC';

		$con = Propel::getConnection('propel');
		$lat = $geo_info['Latitude'];
		$lng = $geo_info['Longitude'];

		$query = '
		SELECT DISTINCT '.LocationPeer::RESTAURANT_ID.', '.LocationPeer::ID.',
		'.LocationPeer::LATITUDE.','.LocationPeer::LONGITUDE.'
		,';
		
		$math = "
		(
			(
				(
					acos(sin(($lat*pi()/180)) * sin((latitude*pi()/180)) 
					+ 
					cos(($lat*pi()/180)) * cos((latitude*pi()/180)) 
					* 
					cos((($lng - longitude)*pi()/180)))
				)
				* 180/pi()
			)
			*60*1.1515
		)";
		
		if ($use_gradients) {
			$query .= " FLOOR($math/$radius) AS distance ";
		} else {
			$query .= " $math AS distance ";
		}
		
		$query .= '
		FROM '.LocationPeer::TABLE_NAME
		.', '.RestaurantPeer::TABLE_NAME;

		$query .= '
		WHERE '.LocationPeer::RESTAURANT_ID.'='.RestaurantPeer::ID;

		if (array_key_exists('restaurant', $options))
		{
			$query .= '
			AND '.LocationPeer::RESTAURANT_ID.' = '.$options['restaurant']->getId();
		}		
//		$query .= '
//		('.implode(' OR ', array_fill(0, $nb_words, RestaurantSearchIndexPeer::WORD.' LIKE ?')).')
		$query .= '
		GROUP BY '.LocationPeer::RESTAURANT_ID.','.LocationPeer::LATITUDE.','.LocationPeer::LONGITUDE;
		
		if ($use_distance) {
			$query .= '
			HAVING distance < '.$radius;
		} else {
			$query .= '
			HAVING distance IS NOT NULL';
		}
		$query .= "
		ORDER BY $order";

		// prepare the statement
		$stmt = $con->prepareStatement($query);
		$stmt->setOffset($offset);
		$stmt->setLimit($max);
		$placeholder_offset = 1;

		/*for ($i = 0; $i < $nb_words; $i++)
		{
			$stmt->setString($i + $placeholder_offset, $words[$i] . '%'); // experiment with % for LIKE
		}*/
		$rs = $stmt->executeQuery(ResultSet::FETCHMODE_NUM);

		// Manage the results
		$locations = array();
		while ($rs->next())
		{
			$loc = self::retrieveByPK($rs->getInt(2));
			$loc->search_distance = $rs->getFloat(5);
			$locations[] = $loc;
		}

		return $locations;
	}

	public static function search($phrase, $geo_info, $radius, $exact = false, $offset = 0, $max = 10, $options)
	{
		$options = is_array($options) ? $options : sfToolkit::stringToArray($options);
		
		$words    = array_values(myTools::stemPhrase($phrase));
		$nb_words = count($words);

		if (!$words)
		{
			return LocationPeer::allNear($geo_info, $radius, $exact, $offset, $max, $options);
		}

		$con = Propel::getConnection('propel');
		$lat = $geo_info['Latitude'];
		$lng = $geo_info['Longitude'];


		$query = '
		SELECT DISTINCT '.RestaurantSearchIndexPeer::RESTAURANT_ID.', '.LocationPeer::ID.',
		COUNT(*) AS nb, SUM('.RestaurantSearchIndexPeer::WEIGHT.") AS total_weight,
		
		(
			(
				(
					acos(sin(($lat*pi()/180)) * sin((latitude*pi()/180)) 
					+ 
					cos(($lat*pi()/180)) * cos((latitude*pi()/180)) 
					* 
					cos((($lng - longitude)*pi()/180)))
				)
				* 180/pi()
			)
			*60*1.1515
		) AS distance, " . LocationPeer::LATITUDE .','. LocationPeer::LONGITUDE .'
		
		FROM '.RestaurantSearchIndexPeer::TABLE_NAME.', '.LocationPeer::TABLE_NAME;
/*            
SELECT id,name,    
(
	(
		(
			acos(sin(($lat*pi()/180)) * sin((latitude*pi()/180)) + cos(($lat*pi()/180)) * cos((latitude*pi()/180)) * cos((($lng - longitude)*pi()/180))))*180/pi())*60*1.1515) as distance FROM companies HAVING distance <= $miles ORDER BY distance ASC LIMIT xx
*/
		$query .= '
		WHERE '.LocationPeer::RESTAURANT_ID.
		'='.RestaurantSearchIndexPeer::RESTAURANT_ID.' AND';

		
		$query .= '
		('.implode(' OR ', array_fill(0, $nb_words, RestaurantSearchIndexPeer::WORD.' LIKE ?')).')
		GROUP BY '.RestaurantSearchIndexPeer::RESTAURANT_ID . ', '
		 . LocationPeer::LATITUDE .','. LocationPeer::LONGITUDE;


		$query .= '
		HAVING distance < '.$radius;
		if ($exact)
		{
			$query .= '
			AND nb = '.$nb_words;
		}

		$query .= '
		ORDER BY distance ASC, nb DESC, total_weight DESC';

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

} // LocationPeer
