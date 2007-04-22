<?php

	// api into the yahoo rest api
	
	#doc
	#	classname:	YahooRest
	#	scope:		PUBLIC
	#
	#/doc

	abstract class YahooREST
	{
		#	internal variables
		protected $url;
	  protected $standard_args = array();
		public $query_url;
		
		public function getRawData()
		{
			return $this->rawData;
		}
		
		protected function registerAppId()
		{
			$appid = sfConfig::get('app_yahoo_app_id');
			if (!$appid)
			{
				throw new Exception('app_yahoo_app_id not defined');
			}
			$this->standard_args['appid'] = $appid;
			
		}
		
		public function executeQuery($args = array())
		{
			$url = $this->url;
			
			$this->standard_args['output'] = 'php';
			$encoded_params = array();
			
			foreach ($this->standard_args as $k => $v)
			{
				$encoded_params[] = urlencode($k).'='.urlencode($v);
			}
			
			foreach ($args as $k => $v)
			{
				$encoded_params[] = urlencode($k).'='.urlencode($v);
			}
			
			$url .= '?' . implode('&', $encoded_params);
			$this->query_url = $url;
			
			$function_cache_dir = sfConfig::get('sf_cache_dir').'/function';
			$cache = new sfFunctionCache($function_cache_dir);
			$this->rawData = $cache->call(array('YahooRest','doExecuteQuery'), $url);
			return $this->rawData;
			
		}
		
		public static function doExecuteQuery($url)
		{
			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($curl);
			return unserialize($response);
		}
	}
	###
