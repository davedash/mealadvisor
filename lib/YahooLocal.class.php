<?php

	// api into the yahoo local api
	
	#doc
	#	classname:	YahooLocal
	#	scope:		PUBLIC
	#
	#/doc

	class YahooLocal extends YahooREST
	{
		#	internal variables
		private $data;
		private $totalResultsAvailable = 0, 
		$totalResultsReturned = 0, $firstResultPosition = 0, 
		$lastResultPosition = 0, $resultsPerPage = 10, $page = 1, $lastPage = 1;
		private $options = array();
		
		#	Constructor
		function __construct ($query, $options = array())
		{
			$this->url = 'http://local.yahooapis.com/LocalSearchService/V2/localSearch';
			$this->registerAppId();
			$this->doQuery($query, $options);
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
			
			if (array_key_exists('page', $options))
			{
				$this->page = $options['page'];
				unset($options['page']);
				$options['start'] = ($this->page-1) * $this->resultsPerPage + 1;
			}
			$this->executeQuery($options);
			
			
			$data                        = $this->getRawData();

			$this->totalResultsAvailable = $data['ResultSet']['totalResultsAvailable'];
			$this->totalResultsReturned  = $data['ResultSet']['totalResultsReturned'];
			$this->firstResultPosition   = $data['ResultSet']['firstResultPosition'];
			$this->lastResultPosition    = $this->firstResultPosition + $this->totalResultsReturned - 1;
			$this->lastPage              = floor($this->totalResultsAvailable/$this->resultsPerPage) + 1;
			//print_r($data);
			if (!array_key_exists('Result', $data['ResultSet']))
			{
				throw new sfException('No results found');
			}
			$this->data                  = $data['ResultSet']['Result'];
			
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
