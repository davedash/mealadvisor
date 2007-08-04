<?php

  #doc
  # classname:  myCommonUser
  # scope:    PUBLIC
  #
  #/doc

  class myCommonUser extends sfGuardSecurityUser
  {
  	protected function getPreferences()
  	{
  		$prefs = null;
  		if (!$this->isAnonymous())
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

  	public function getPreference($key, $default)
  	{
  		$prefs = $this->getPreferences();
  		if (array_key_exists($key, $prefs)) {
  			return $prefs[$key];
  		}
      return $default;
  	}

  }
  