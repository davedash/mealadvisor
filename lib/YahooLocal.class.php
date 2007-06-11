<?php

	// api into the yahoo local api
	
	#doc
	#	classname:	YahooLocal
	#	scope:		PUBLIC
	#
	#/doc

	class YahooLocal extends YahooREST
	{
		const CATEGORY_RESTAURANTS = '96926236';
		
		public static function sanitizeText($input)
		{
			$bad = array('/ llc$/i','/ incorporated$/i');
			$input = preg_replace($bad,null,$input);
			$bad  = array('a(C)');
			$good = array('é');
			return str_replace($bad, $good, $input);
		}
		#	internal variables
		private $data;
		private $totalResultsAvailable = 0, 
		$totalResultsReturned = 0, $firstResultPosition = 0, 
		$lastResultPosition = 0, $resultsPerPage = 10, $page = 1, $lastPage = 1;
		private $options = array();
		
		#	Constructor
		function __construct ($query, $options = array())
		{
			$this->url = 'http://local.yahooapis.com/LocalSearchService/V3/localSearch';
			$this->registerAppId();
			$this->doQuery($query, $options);
		}
		
		public static function getOneResult($yid)
		{
			$local = new YahooLocal('*', array('listing_id'=>$yid));
			return $local->getResults();
			
		}
		public function getPage()
		{
			return $this->page;
		}
		
		public function getLastPage()
		{
			return $this->lastPage;
		}

		public function getQueryString()
		{
			return http_build_query($this->options, null, '&');
		}
		
		private function doQuery($query, $options=array())
		{
			$options['query'] = $query;
			
			$this->options = $options;
			
			if (array_key_exists('results',$options))
			{
				$this->resultsPerPage = $options['results'];
			}
			
			if (array_key_exists('page', $options))
			{
				$this->page = $options['page'];
				unset($options['page']);
				$options['start'] = ($this->page-1) * $this->resultsPerPage + 1;
			}
			$this->executeQuery($options);
			
			$xml                         = new SimpleXMLElement($this->getRawData());
			$this->totalResultsAvailable = $xml['totalResultsAvailable'];
			$this->totalResultsReturned  = $xml['totalResultsReturned'];
			$this->firstResultPosition   = $xml['firstResultPosition'];
			$this->lastResultPosition    = $this->firstResultPosition + $this->totalResultsReturned - 1;
			$this->lastPage              = ceil($this->totalResultsAvailable/$this->resultsPerPage);
			//print_r($data);
			if (empty($xml->Result))
			{
				throw new sfException('No results found: ' .$this->query_url);
			}
			// we want data to be an array of results always... so we test for 
			// $results[0], if it doesn't exist that means we were returned a 
			// single result which we need to turn into an array
			$this->data = $xml->Result;
			
		}
		
		public function getFirst()
		{
			return $this->firstResultPosition;
		}
		
		public function getLast()
		{
			return $this->lastResultPosition;
		}
		
		public function getTotal()
		{
			return $this->totalResultsAvailable;
		}
		
		public function getResults()
		{
			return $this->data;
		}
	}
	###
