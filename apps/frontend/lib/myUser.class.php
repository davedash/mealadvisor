<?php

class myUser extends myCommonUser 
{
	
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
		return $this->getProfile() ? $this->getProfile()->getId() : null;
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
