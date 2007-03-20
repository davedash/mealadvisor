<?php

/**
 * sfGuardAuth actions.
 *
 * @package    reviewsby.us
 * @subpackage sfGuardAuth
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 1814 2006-08-24 12:20:11Z fabien $
 */

require_once(sfConfig::get('sf_root_dir').'/plugins/sfGuardPlugin/modules/sfGuardAuth/lib/BasesfGuardAuthActions.class.php');

class sfGuardAuthActions extends BasesfGuardAuthActions
{
	public function executeAjaxSignin()
	{

	}
  public function executeSignout()
  {
    $this->getUser()->signOut();
    $this->redirect($this->getRequest()->getReferer());
  }
}
