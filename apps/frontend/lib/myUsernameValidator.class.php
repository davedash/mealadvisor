<?php

#doc
#	classname:	myUsernameValidator
#	scope:		PUBLIC
#
#/doc

class myUsernameValidator extends sfValidator
{
	#	internal variables
	public function initialize($context, $parameters = null) {
		// initialize parent
		parent::initialize($context, $parameters);
		$this->getParameterHolder()->set('username_error', 'Username is already in use.');    
		$this->getParameterHolder()->set('username_error_dots', 'Username can not have a period (<q>.</q>).');    
		
		
		return true;

	}
	###
	public function execute (&$value, &$error)
	{
		$c = new Criteria();
		$c->add(UserPeer::USERID, $value);
		$user = UserPeer::doSelectOne($c);
		
		if ($user instanceof User)
		{
			$error = $this->getParameterHolder()->get('username_error');
			return false;
		}
		elseif (strpos($value, '.')) 
		{
			$error = $this->getParameterHolder()->get('username_error_dots');
			return false;
			
		}

		return true;
	}
}
?>