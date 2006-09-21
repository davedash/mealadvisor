<?php

class myUser extends sfBasicSecurityUser
{
	public function loginAs($user, $openid = false)
	{
		// find a matching user
		// if none exists... create one
		$username = null;
		if (!($user instanceof User)) {
			$username = $user;
			$c = new Criteria();
			$c->add(UserPeer::USERID, $user);
			$user = UserPeer::doSelectOne($c);
		}
		if (!($user instanceof User)) {
			$user = new User();
			$user->setUserid($username);
			$user->setOpenId($openid);
			$user->save();
		} 
		$this->setAuthenticated(true);
		$this->addCredential('subscriber');

		$this->setAttribute('subscriber_id', $user->getId(), 'subscriber');
		$this->setAttribute('nickname', $user->getUserid(), 'subscriber');
		/*
	                $userGroupRels = $user->getUserGroupRelsJoinUserGroup();
	                foreach ($userGroupRels AS $ugr) {

	                        $this->addCredential($ugr->getUserGroup()->getName());
	                }
	 	*/
		
	}

	public function getUsername()
	{
		return $this->getAttribute('nickname', null, 'subscriber');
	}
	
	public function getUser()
	{
		return UserPeer::retrieveByPK($this->getAttribute('subscriber_id', '', 'subscriber'));                                                                      
	}
	public function getId()
	{
		return $this->getAttribute('subscriber_id', null, 'subscriber');
		
	}
    public function isLoggedIn() 
    {
		return $this->hasAttribute('nickname', 'subscriber') &&
			$this->hasCredential('subscriber');
	}	
}

?>