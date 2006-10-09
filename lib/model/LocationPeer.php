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
	
	
	public static function search($phrase, $geo_info, $radius, $exact = false, $offset = 0, $max = 10)
	{
		$words    = array_values(myTools::stemPhrase($phrase));
		$nb_words = count($words);

		if (!$words)
		{
			return array();
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
		) AS distance
		
		FROM ".RestaurantSearchIndexPeer::TABLE_NAME.', '.LocationPeer::TABLE_NAME;

		$query .= '
		WHERE '.LocationPeer::RESTAURANT_ID.
		'='.RestaurantSearchIndexPeer::RESTAURANT_ID.' AND';

		$query .= '
		('.implode(' OR ', array_fill(0, $nb_words, RestaurantSearchIndexPeer::WORD.' LIKE ?')).')
		GROUP BY '.RestaurantSearchIndexPeer::RESTAURANT_ID;


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
} // LocationPeer
