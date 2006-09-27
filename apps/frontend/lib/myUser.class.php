<?php

class myUser extends sfGuardSecurityUser {
	
	public function loginAs($user, $openid = false)
	{
		// find a matching user
		// if none exists... create one
		$username = null;
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
}

?>