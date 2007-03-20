<?php

/**
* user actions.
*
* @package    ##PROJECT_NAME##
* @subpackage user
* @author     Your name here
* @version    SVN: $Id: actions.class.php 500 2006-01-23 09:15:57Z fabien $
*/
class userActions extends sfActions
{
	public function isPost()
	{
		return ($this->getRequest()->getMethod() == sfRequest::POST);
	}

	public function executeProfile()
	{
		$this->user = ProfilePeer::retrieveByUsername($this->getRequestParameter('username'));
		$this->getResponse()->setTitle($this->user->getUsername() . ' &raquo; ' . sfConfig::get('app_title', 'rbu'), false);
		$this->restaurants = $this->user->getAssociatedRestaurants(10);
		$this->moreRestaurants = $this->user->getAssociatedRestaurants(null,10);
	}
	
	public function executeAjaxRegister()
	{
		$u = new Profile();
		$u->setUsername($this->getRequestParameter('username'));
		$u->setPassword($this->getRequestParameter('password'));
		$u->save();
		$this->getUser()->loginAs($u);	
	}
	
	public function executeRegister()
	{
		if (!$this->isPost()) {
			return sfView::SUCCESS;
		}
		$u = new Profile();
		$u->setUsername($this->getRequestParameter('username'));
		$u->setPassword($this->getRequestParameter('password'));
		$u->save();
		$this->getUser()->loginAs($u);
		return 'Complete';
		
	}
	public function handleErrorRegister()
	{
		return sfView::SUCCESS;
	}

	public function executeShow ()
	{
		$this->user = ProfilePeer::retrieveByPk($this->getRequestParameter('id'));

		$this->forward404Unless($this->user instanceof Profile);
	}

	private function getUserOrCreate ($id = 'id')
	{
		if (!$this->getRequestParameter($id, 0))
		{
			$user = new Profile();
		}
		else
		{
			$user = ProfilePeer::retrieveByPk($this->getRequestParameter($id));

			$this->forward404Unless($user instanceof Profile);
		}

		return $user;
	}

}