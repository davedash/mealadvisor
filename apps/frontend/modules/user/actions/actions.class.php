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
		$c = new Criteria();
		$c->add(ProfilePeer::USERID, $this->getRequestParameter('user'));
		$this->user = ProfilePeer::doSelectOne($c);
		$this->restaurants = $this->user->getAssociatedRestaurants(10);
		$this->moreRestaurants = $this->user->getAssociatedRestaurants(null,10);
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

	public function executeLogin()
	{

	}

	public function handleErrorLocalLogin()
	{
	
		$this->getRequest()->getAttributeHolder()->set('referer', $this->getRequestParameter('referer'));
		return sfView::SUCCESS;
	}

	
	public function executeLocalLogin()
	{
		if ($this->getRequest()->getMethod() != sfRequest::POST)
		{
			
			$this->forward('default', 'login');
		}
		else
		{

			$redirect = $this->getRequestParameter('referer', '@homepage');
			
			return $this->redirect($redirect);
		
		}
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

?>