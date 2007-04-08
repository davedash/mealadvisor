<?php

	// api into the yahoo geo rss api
	
	#doc
	#	classname:	YahooGeo
	#	scope:		PUBLIC
	#
	#/doc

	class YahooGeo 
	{
		#	internal variables
		private $rawLocation;
		private $rawData = array();
		private $precision;
		private $single = null;
		private $country = null, $state = null, $city = null, $zip = null;
		
		const COUNTRY = 'country';
		const STATE = 'state';
		const CITY = 'city';
		const ZIP = 'zip';
		#	Constructor
		function __construct ( $locationString )
		{
			$this->rawLocation = $locationString;
			$this->queryGIS();
		}
		
		public function getPrecision()
		{
			if ($this->precision === null)
			{
				$s = $this->getSingle();
				$this->precision = $s['precision'];
			}
			return $this->precision;
		}
		
		public function getCountry()
		{
			if ($this->country === null)
			{
				$s = $this->getSingle();
				
				
				if ($s['Country'] == 'US')
				{
					$this->country = 'US';
				} 
				else 
				{
					$this->country = ucwords(strtolower($s['Country']));
				}
			}
			return $this->country;
		}

		public function getState()
		{
			if ($this->state === null)
			{
				$s = $this->getSingle();
				
				$this->state = (strlen($s['State']) == 2) ? $s['State']: ucwords(strtolower($s['State']));
			}
			return $this->state;
		}
		
		public function getCity()
		{
			if ($this->city === null)
			{
				$s = $this->getSingle();
				$this->city = ucwords(strtolower($s['City']));
			}
			return $this->city;
		}
		
		public function getZip()
		{
			if ($this->zip === null)
			{
				$s = $this->getSingle();
				$this->zip = $s['Zip'];
			}
			return $this->zip;
		}		
		
		public function getShortString()
		{
			// builds a string that we can use to describe where we are ;)
			
			$s = $this->getCSZAsArray();
			
			return implode(', ', array_reverse($s));
		}
		
		public function getCSZAsArray($options = array())
		{
			$convert_spaces = false;
			if (array_key_exists('convert_spaces', $options))
			{
				$convert_spaces = $options['convert_spaces'];
			}
						
			$q = array();
			if ($c = $this->getCountry())
			{
				$q['country'] = $c;
			}

			if ($c = $this->getState())
			{
				$q['state'] = $c;
			}

			if ($c = $this->getCity())
			{
				if ($convert_spaces)
				{
					$c = strtr($c, ' ', '_');
				}
				
				$q['city'] = $c;
			}
			
			return $q;
		}
		
		public function getQueryString()
		{
			//builds a query string
			// country=$c&state=$s&city=$c&zip=$z
			
			
			$q = $this->getCSZAsArray(array('convert_spaces'=>true));
			
			return strtolower(http_build_query($q,null,'&'));
			
		}
		
		public function getLatitude()
		{
			$s = $this->getSingle();
			return strtolower($s['Latitude']);
		}
		
		public function getLongitude()
		{
			$s = $this->getSingle();
			return strtolower($s['Longitude']);
		}
		// get the first element of the resultset
		private function getSingle()
		{
			if (!$this->single) 
			{
				$result = $this->rawData['ResultSet']['Result'];
				if (myTools::is_associative_array($result)) 
				{
					$this->single = $result;
				} 
				elseif (array_key_exists(0, $result))
				{
					$this->single = $result[0];
				}
				
			}
			
			return $this->single;
		}
		public function getRawData()
		{
			return $this->rawData;
		}
		
	  protected function queryGIS()
		{
			$function_cache_dir = sfConfig::get('sf_cache_dir').'/function';
			$cache = new sfFunctionCache($function_cache_dir);
			$this->rawData = $cache->call(array('YahooGeo','doQueryGIS'), $this->rawLocation);
			return $this->rawData;
			
		}
		
		public static function doQueryGIS($location)
		{
			$url               = sfConfig::get('app_yahoo_geocode');

			$query             = array();
			$query['appid']    = sfConfig::get('app_yahoo_app_id');
			$query['location'] = $location;
			$query['output']   = 'php';

			$url .= '?' . http_build_query($query,null,'&');	

			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($curl);
		

			if ($response != 'Array') 
			{
				return unserialize($response);
			} 
			else 
			{
				throw new sfException('Yahoo! GeoCoder does not understand: "'. $location . "\"\n");
			}
			return false;			
			
		}
	}
	###
