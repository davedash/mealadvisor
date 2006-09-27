<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 *
 * @package    symfony
 * @subpackage plugin
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfGuardSecurityUser.class.php 1949 2006-09-05 14:40:20Z fabien $
 */
class sfGuardSecurityUser extends sfBasicSecurityUser
{
  private $user = null;

  public function hasCredential($credential)
  {
    // a superadmin has all credentials
    if ($this->getGuardUser()->getIsSuperAdmin())
    {
      return true;
    }
    else
    {
      return parent::hasCredential($credential);
    }
  }

  public function isAnonymous()
  {
    return $this->getAttribute('user_id', null, 'sfGuardSecurityUser') ? false : true;
  }

  public function signIn($user, $remember = false)
  {
    // signin
    $this->setAttribute('user_id', $user->getId(), 'sfGuardSecurityUser');
    $this->setAuthenticated(true);
    $this->addCredentials($user->getAllPermissionNames());

    // save last login
    $user->setLastLogin(time());
    $user->save();

	// remember?
	if ($remember)
	{
		// remove old keys
		$c = new Criteria();
		$expiration_age = sfConfig::get('app_sf_guard_plugin_remember_key_expiration_age', 15 * 24 * 3600);
		$c->add(sfGuardRememberKeyPeer::CREATED_AT, time()-$expiration_age, Criteria::LESS_THAN);
		sfGuardRememberKeyPeer::doDelete($c);
		// generate new keys
		$key = $this->generate_random_key();
		// save key
		$rk = new sfGuardRememberKey();
		$rk->setRememberKey($key);
		$rk->setSfGuardUser($user);
		$rk->setIpAddress($_SERVER['REMOTE_ADDR']);
		$rk->save();
		// make key as a cookie
		sfContext::getInstance()->getResponse()->setCookie('remember', $key, time()+$expiration_age);
	}
  }

  protected function generate_random_key ($len = 20)
  {
	$string = '';
	$pool   = 'abcdefghijklmnopqrstuvwzyzABCDEFGHIJKLMNOPQRSTUVWZYZ0123456789';
	for ($i = 1; $i <= $len; $i++) {
		$string .= substr($pool, rand(0,61), 1);
	}

  	return md5($string);
  }
  public function signOut()
  {
    $this->getAttributeHolder()->removeNamespace('sfGuardSecurityUser');
    $this->user = null;
    $this->clearCredentials();
    $this->setAuthenticated(false);
	sfContext::getInstance()->getResponse()->setCookie('remember', '', time()-$expiration_age);

  }

  public function getGuardUser()
  {
    if (!$this->user && $id = $this->getAttribute('user_id', null, 'sfGuardSecurityUser'))
    {
      $this->user = sfGuardUserPeer::retrieveByPk($id);
    }

    if (!$this->user)
    {
      // the user does not exist anymore in the database
      $this->signOut();

      throw new sfException('The user does exist anymore in the database.');
    }

    return $this->user;
  }

  // add some proxy method to the sfGuardUser instance

  public function __toString()
  {
    return $this->getGuardUser()->__toString();
  }

  public function getUsername()
  {
    return $this->getGuardUser()->getUsername();
  }

  public function getEmail()
  {
    return $this->getGuardUser()->getEmail();
  }

  public function setPassword($password)
  {
    $this->getGuardUser()->setPassword($password);
  }

  public function checkPassword($password)
  {
    return $this->getGuardUser()->checkPassword($password);
  }

  public function hasGroup($name)
  {
    return $this->getGuardUser()->hasGroup($name);
  }

  public function getGroups()
  {
    return $this->getGuardUser()->getGroups();
  }

  public function getGroupNames()
  {
    return $this->getGuardUser()->getGroupNames();
  }

  public function hasPermission($name)
  {
    return $this->getGuardUser()->hasPermission($name);
  }

  public function getPermissions()
  {
    return $this->getGuardUser()->getPermissions();
  }

  public function getPermissionNames()
  {
    return $this->getGuardUser()->getPermissionNames();
  }

  public function getAllPermissions()
  {
    return $this->getGuardUser()->getAllPermissions();
  }

  public function getAllPermissionNames()
  {
    return $this->getGuardUser()->getAllPermissionNames();
  }

  public function getProfile()
  {
    return $this->getGuardUser()->getProfile();
  }

  public function addGroupByName($name)
  {
    return $this->getGuardUser()->addGroupByName($name);
  }

  public function addPermissionByName($name)
  {
    return $this->getGuardUser()->addPermissionByName($name);
  }
}
