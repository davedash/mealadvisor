<?php

class myUsernameValidator extends sfValidator
{
	public function initialize($context, $parameters = null) {
		parent::initialize($context, $parameters);

		$this->getParameterHolder()->set('username_error', 'Username is already in use.');    
		$this->getParameterHolder()->set('username_error_dots', 'Username can not have a period (<q>.</q>).');    
		return true;
	}

	public function execute (&$value, &$error)
	{

		$user = sfGuardUserPeer::retrieveByUsername($value);

		if ($user instanceof sfGuardUser)
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