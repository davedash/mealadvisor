<?php

class myUser extends sfGuardSecurityUser {
		
	public function loginAs($user, $openid = false)
	{
		// find a matching user
		// if none exists... create one
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
		if ($this->isAnonymous())
		{
			$prefs = base64_decode(sfContext::getInstance()->getRequest()->getCookie('preferences'));
			
		} else {
			$prefs = $this->getProfile()->getPreferences();
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
		$this->getContext()->getResponse()->setCookie('preferences', base64_encode(serialize($prefs)),time()+$expiration_age);
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
		// if there's no lat longitude... um, return Anywhere
		
		// if nothing yet... then use anywhere
		
		return 'Anywhere';
	}
	
	public function hasLocation ()
	{
		$location = $this->getLocation();
		return ($location != 'Anywhere') ? true : false;
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
		return !$this->isAnonymous();
	}	
	
	public function getProfile()
	{
		if ($this->isLoggedIn()) {
			return parent::getProfile();
		}
	}
}

?>
