<?php

class myTools
{
	public static function removeStopWordsFromArray($words)
	{
		$stop_words 
		= 
		array(
			'i', 'me', 'my', 'myself', 'we', 'our', 'ours', 'ourselves', 'you', 'your', 'yours', 
			'yourself', 'yourselves', 'he', 'him', 'his', 'himself', 'she', 'her', 'hers', 
			'herself', 'it', 'its', 'itself', 'they', 'them', 'their', 'theirs', 'themselves', 
			'what', 'which', 'who', 'whom', 'this', 'that', 'these', 'those', 'am', 'is', 'are', 
			'was', 'were', 'be', 'been', 'being', 'have', 'has', 'had', 'having', 'do', 'does', 
			'did', 'doing', 'a', 'an', 'the', 'and', 'but', 'if', 'or', 'because', 'as', 'until', 
			'while', 'of', 'at', 'by', 'for', 'with', 'about', 'against', 'between', 'into', 
			'through', 'during', 'before', 'after', 'above', 'below', 'to', 'from', 'up', 'down', 
			'in', 'out', 'on', 'off', 'over', 'under', 'again', 'further', 'then', 'once', 'here', 
			'there', 'when', 'where', 'why', 'how', 'all', 'any', 'both', 'each', 'few', 'more', 
			'most', 'other', 'some', 'such', 'no', 'nor', 'not', 'only', 'own', 'same', 'so', 
			'than', 'too', 'very'
		);

		return array_diff($words, $stop_words);
	}      

	public static function stemPhrase($phrase)
	{
		// split into words
		$words = str_word_count(strtolower($phrase), 1);

		// ignore stop words
		$words = myTools::removeStopWordsFromArray($words);

		// stem words
		$stemmed_words = array();
		foreach ($words as $word)
		{
			// ignore 1 and 2 letter words
			if (strlen($word) <= 2)
			{
				continue;
			}

			$stemmed_words[] = PorterStemmer::stem($word, true);
		}

		return $stemmed_words;
	}
	
	public static function stripText($text)
	{
		$text = strtolower($text);

		//remove apostrophes
		$text = preg_replace('/\'/', '', $text);

		// strip all non word chars
		$text = preg_replace('/\W/', ' ', $text);

		// replace all white space sections with a dash
		$text = preg_replace('/\ +/', '-', $text);

		// trim dashes
		$text = preg_replace('/\-$/', '', $text);
		$text = preg_replace('/^\-/', '', $text);

		return $text;
	} 

	public static function getLatLng($address, $city = null, $state = null, $zip = null, $country = null)
	{
		$url = sfConfig::get('app_yahoo_geocode');
		
		$query['appid'] = sfConfig::get('app_yahoo_app_id');
	
		$query['location'] = "$address, $city, $state, $zip, $country";
		$query['output'] = 'php'; 
	
		ini_set("arg_separator.output", "&");
		$url .= '?' . http_build_query($query);	
		
		$response = @file_get_contents($url);

		if ($response) {
			$response = unserialize($response);
			return $response['ResultSet']['Result'];
		}
		return null;
	}
	
	public static function getLatLngOne($address, $city = null, $state = null, $zip = null, $country = null)
	{
		$latLngs = myTools::getLatLng($address, $city, $state, $zip, $country);
		
		if (!$latLngs) {
			return;
		}
		if (myTools::is_associative_array($latLngs)) {
			return $latLngs;
		} 
		else if (array_key_exists(0, $latLngs)){
			return $latLngs[0];
		}
	}
	
	public static function getNearness($location)
	{
		$near = myTools::getLatLngOne($location);

		list($precision, $search_location) = myTools::getNormalizedLocation($near);

		$radius = sfConfig::get('app_search_default_radius');

		switch($precision)
		{
			case 'zip':
				$radius = sfConfig::get('app_search_zip_radius',20);
				break;
			case 'city':
				$radius = sfConfig::get('app_search_city_radius',25);
				break;
			case 'address':
				$radius = sfConfig::get('app_search_address_radius',15);
				break;
		}
		return array($search_location, $near, $radius);
	}
	
	public static function getNormalizedLocation($assoc)
	{
		$location = null;
		switch($assoc['precision']) {
			case 'city':
				$location = "{$assoc['City']}, {$assoc['State']}";
				break;
			default:
				$a = array();
				array_push($a,$assoc['City']);
				
				array_push($a,$assoc['State']);
				array_push($a,$assoc['Zip']);
				
				array_push($a,$assoc['Country']);
				$location = join(' ', $a);
				break;
		}
		
		return array($assoc['precision'], $location);
	}
	public static function is_associative_array( $var ) {

	   return is_array( $var ) && !is_numeric( implode( array_keys( $var ) ) );
	}
	
	
	public static function _or($a,$b)
	{
		return empty($a) ? $b:$a;
	}
}