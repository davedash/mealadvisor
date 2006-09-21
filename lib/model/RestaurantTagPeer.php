<?php

  // include base peer class
  require_once 'lib/model/om/BaseRestaurantTagPeer.php';
  
  // include object class
  include_once 'lib/model/RestaurantTag.php';


/**
 * Skeleton subclass for performing query and update operations on the 'restaurant_tag' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package model
 */	
class RestaurantTagPeer extends BaseRestaurantTagPeer {
	public static function getPopularTags($max = 5)
	{
		$tags = array();

		$con = Propel::getConnection('propel');
		$query = '
			SELECT '.RestaurantTagPeer::NORMALIZED_TAG.' AS tag,
		    COUNT('.RestaurantTagPeer::NORMALIZED_TAG.') AS count
		    FROM '.RestaurantTagPeer::TABLE_NAME.'
		    GROUP BY '.RestaurantTagPeer::NORMALIZED_TAG.'
		    ORDER BY count DESC'
		;

		$stmt = $con->prepareStatement($query);
		$stmt->setLimit($max);
		$rs = $stmt->executeQuery();
		$max_popularity = 0;
		while ($rs->next())
		{
			if (!$max_popularity)
			{
				$max_popularity = $rs->getInt('count');
			}

			$tags[$rs->getString('tag')] = floor(($rs->getInt('count') / $max_popularity * 3) + 1);
		}

		ksort($tags);

		return $tags;
	}
} // RestaurantTagPeer
