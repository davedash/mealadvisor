<?php

class myUser extends sfGuardSecurityUser {
	
	/**
	 * myUser::loginAs
	 *  
	 * @param mixed Profile/sfGuardUser or string representing a user	 	 
	 * @param boolean if true, will set the "openId" flag to true
	 * 	 
	 * This function will log a user in.  If the user truly does not exist, 	 
	 * a new account will be created -> this is helpful for validated openID 	 
	 * logins
	 *
	 * @return null
	 * @author Dave Dash
	 **/
	public function loginAs($user, $openid = false)
	{
		$username = null;
		if ($user instanceof Profile) 
		{
			$user = $user->getUser();
		}
		if (!($user instanceof sfGuardUser)) {
			$username = $user;
			$user = sfGuardUserPeer::retrieveByUsername($username);
		}
		if (!($user instanceof sfGuardUser)) {
			$p = new Profile();
			$p->setUsername($username);
			$p->setOpenId($openid);
			$p->save();
			$user = $p->getUser();
		} 
		
		$this->signIn($user);
	}
	
	protected function getPreferences()
	{
		$prefs = null;
		if ($this->isLoggedIn())
		{
			$prefs = $this->getProfile()->getPreferences();
		} 
		if (!$prefs) {
			$prefs = base64_decode(sfContext::getInstance()->getRequest()->getCookie('preferences'));
		}
		return $prefs ? unserialize($prefs) : array();
	}
	
	public function setPreference($key, $value)
	{
		$prefs = $this->getPreferences();
		$prefs[$key] = $value;
		if ($this->isAuthenticated()) {
			$p = $this->getProfile();
			$p->setPreferences(serialize($prefs));
		}
		$expiration_age = sfConfig::get('app_preference_cookie_expiration_age', 15 * 24 * 3600);    
		$this->getContext()->getResponse()->setCookie('preferences', 
		base64_encode(serialize($prefs)),time()+$expiration_age);
	}
	
	public function getPreference($key)
	{
		$prefs = $this->getPreferences();
		if (array_key_exists($key, $prefs)) {
			return $prefs[$key];
		}
	}
	
	public function getLocation()
	{
		$location = $this->getPreference('location');
		
		list($search_location, $near, $radius) = myTools::getNearness($location);
		
		if ($location && $near) return $location;
		
		// if nothing yet... then use anywhere
		
		return sfConfig::get('app_location_anywhere', 'Anywhere');
	}
	
	public function hasLocation()
	{
		$location = $this->getLocation();
		return ($location != sfConfig::get('app_location_anywhere', 'Anywhere')) ? true : false;
	}
	
	public function getId()
	{
		return $this->getProfile()->getId();
	}
	
	public function getUser ()
	{
		return $this->getProfile();
	}
	
    public function isLoggedIn() 
    {
		return (!$this->isAnonymous() && $this->isAuthenticated());
	}	
	
	public function getProfile()
	{
		if ($this->isLoggedIn()) {
			return parent::getProfile();
		}
	}
	
	public function isAdmin()
	{
		
		return (!$this->isAnonymous() && $this->hasGroup('administrator'));
	}
}
