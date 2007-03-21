<?php

class sfOpenID {
	const NO_SERVERS_FOUND = 'No OpenID servers found.';

	private $openid_url_identity;
	private $trust_root;
	private $approved_url;
	private $openid_server;
	private $error = array();


	function KVsToArray($kvs) 
	{
		$r = array();
		preg_match_all('|^\s*([^:]+):([^:\n]+)[ ]*$|m', $kvs, $matches);
		for($i = 0; $i < count($matches[0]); $i++) {
			$r[$matches[1][$i]] = $matches[2][$i];
		}
		return $r;
	}
	
	public function __construct(){
		if (!function_exists('curl_exec')) {
			die('Error: Class sfOpenID() requires curl extension to work');
		}
	}

	public function getRedirectURL()
	{
		$params                          = array();
		$params['openid.return_to']      = urlencode($this->approved_url);
		$params['openid.mode']           = 'checkid_setup';
		$params['openid.identity']       = urlencode($this->openid_url_identity);
		$params['openid.trust_root']     = urlencode($this->trust_root);
			
		return $this->getOpenIDServer() .'?'. $this->arrayToQueryString($params);
	}
	
	public function setIdentity($identity)
	{ 	// Set Identity URL
		if (strpos($identity, 'http://') === false) {
			$identity = 'http://'.$identity;
		}
		// if this is a server we want a trailing slash
		// therefore if there isn't a slash somewhere in the url after
		// http:// add one
		if (preg_match('|^http[s]?://[^/]+$|', $identity)) 
		{
			$identity .= '/';
		}
		$this->openid_url_identity = $identity;
	}
	
	public function setApprovedURL($url) 
	{
		$this->approved_url = $url;
	}
	
	public function setTrustRoot($url) 
	{
		$this->trust_root = $url;
	}
		
	public function getOpenIDServer() 
	{
		$response                  = $this->web_request($this->openid_url_identity);
		list($servers, $delegates) = $this->extractOpenIDServerFromHTML($response);

		if (!isset($servers[0]))
		{
			//			$this->setError(self::NO_SERVERS_FOUND);
			throw new sfException(self::NO_SERVERS_FOUND);
		}
		if (!empty($delegates[0]))
		{
			$this->openid_url_identity = $delegates[0];
		}
		
		$this->setOpenIDServer($servers[0]);
		return $servers[0];
	}
	
	public function hasError()
	{
		return isset($this->error[0]);
	}
	
	protected function setError($code)
	{
		$this->error[] = $code;
	}

	function getErrors()
	{
		return $this->error();
	}
		
	public function web_request($url, $method='GET', $params = null) 
	{ 

		if (is_array($params)) $params = $this->arrayToQueryString($params);

		if (!empty($params) && $method == 'GET') $url .= '?' . $params;
	
		
		$curl = curl_init($url);

		if ($method == 'POST') curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPGET, ($method == 'GET'));
		curl_setopt($curl, CURLOPT_POST, ($method == 'POST'));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($curl);
		
		return $response;
	}
	
	// converts associated array to URL Query String
	public function arrayToQueryString($arr)
	{ 
		if (!is_array($arr)) {
			return false;
		}
		
		$query = '';
		foreach ($arr as $key => $value) {
			$query .= $key . '=' . $value . '&';
		}
		
		return $query;
	}

	private function extractOpenIDServerFromHTML($content) 
	{
		$get = array();
		// Get details of their OpenID server and (optional) delegate
		preg_match_all('/<link[^>]*rel="openid.server"[^>]*href="([^"]+)"[^>]*\/?>/i', $content, $matches1);
		preg_match_all('/<link[^>]*href="([^"]+)"[^>]*rel="openid.server"[^>]*\/?>/i', $content, $matches2);
		$servers = array_merge($matches1[1], $matches2[1]);

		preg_match_all('/<link[^>]*rel="openid.delegate"[^>]*href="([^"]+)"[^>]*\/?>/i', $content, $matches1);

		preg_match_all('/<link[^>]*href="([^"]+)"[^>]*rel="openid.delegate"[^>]*\/?>/i', $content, $matches2);

		$delegates = array_merge($matches1[1], $matches2[1]);

		return array($servers, $delegates);

	}

	public function setOpenIDServer($url)
	{
		$this->openid_server = $url;
	}
	
	public function validateWithServer()
	{
		$params = array(
			'openid.assoc_handle' => urlencode($_GET['openid_assoc_handle']),
			'openid.signed' 			=> urlencode($_GET['openid_signed']),
			'openid.sig' 					=> urlencode($_GET['openid_sig']),
			'openid.identity'			=> urlencode($_GET['openid_identity']),
			'openid.return_to'		=> urlencode($_GET['openid_return_to'])
		);
		$params['openid.mode'] = 'check_authentication';

		$openid_server = $this->getOpenIDServer();

		if ($openid_server == false) {
			return false;
		}
		
		$data = $this->KVstoArray($this->web_request($openid_server,'POST',$params));
		if (isset($data['is_valid']) && $data['is_valid'] == 'true') {
			return true;
		}
		
		return false;
		
	}

	public static function simplifyURL($url) 
	{
		$match = array();
		preg_match('|^http[s]?://([^/]+)[/]?$|', $url, $match);
		return isset($match[1]) ? $match[1] : null;
	}
}