<?php

	#doc
	#	classname:	sfGuardSecurityFilter
	#	scope:		PUBLIC
	#
	#/doc

	class sfGuardBasicSecurityFilter extends sfBasicSecurityFilter
	{
		public function execute ($filterChain)
		{
			if ($this->isFirstCall()) 
			{
				if ($cookie = $this->getContext()->getRequest()->getCookie('remember'))
				{
					$c = new Criteria();
					$c->add(sfGuardRememberKeyPeer::REMEMBER_KEY, $cookie);
					$rk = sfGuardRememberKeyPeer::doSelectOne($c);
					if ($rk && $rk->getSfGuardUser())
					{
						$this->getContext()->getUser()->signIn($rk->getSfGuardUser());
					}
				}
			}
			parent::execute($filterChain);
		}
	}
	###

?>