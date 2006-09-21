

<?php
/**
* Require the OpenID consumer code.
*/
require_once "PHP-openid-0.9.2/Auth/OpenID/Consumer.php";

/**
* Require the "file store" module, which we'll need to store OpenID
* information.
*/
require_once "PHP-openid-0.9.2/Auth/OpenID/FileStore.php";

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
		$c->add(UserPeer::USERID, $this->getRequestParameter('user'));
		$this->user = UserPeer::doSelectOne($c);
		$this->restaurants = $this->user->getAssociatedRestaurants(10);
		$this->moreRestaurants = $this->user->getAssociatedRestaurants(null,10);
	}
	
	public function executeRegister()
	{
		if (!$this->isPost()) {
			return sfView::SUCCESS;
		}
		$u = new User();
		$u->setUserid($this->getRequestParameter('username'));
		$u->setPassword($this->getRequestParameter('password'));
		$u->save();
		$this->getUser()->loginAs($u);
		return 'Complete';
		
	}
	public function handleErrorRegister()
	{
		return sfView::SUCCESS;
	}
	public function executeLogout()
	{
		
		$this->getUser()->setAuthenticated(false);
		$this->getUser()->clearCredentials();

		$this->getUser()->getAttributeHolder()->removeNamespace('subscriber');
		//$cookie_result = setCookie('remember_me', null, 0, '/');
		
		$this->redirect($this->getRequest()->getReferer());
	}

	public function executeLogin()
	{
		
		if ($this->getRequest()->getMethod() != sfRequest::POST)
		{
			if ($this->getUser()->isLoggedIn())
			{
				$this->redirect('@homepage');
			}
			$this->forward('default', 'login');
		}
		else
		{

			$consumer = $this->getConsumer();
			$openid = $this->getRequestParameter('openid_url');

			$process_url = $this->getController()->genUrl('user/finishAuth', true);

			$trust_root = $this->getController()->genUrl('@homepage', true);
			// Begin the OpenID authentication process.
			list($status, $info) = @$consumer->beginAuth($openid);
			
			// Handle failure status return values.
			if ($status != Auth_OpenID_SUCCESS) {
				echo "Authentication error.";

				exit(0);
			}

			// Redirect the user to the OpenID server for authentication.  Store
			// the token for this authentication so we can verify the response.
			$this->getUser()->setAttribute('token', $info->token, 'openid');
			
			$redirect = $this->getRequestParameter('referer', '@homepage');


			$this->getUser()->setAttribute('redirect', $redirect, 'openid');
			$this->getUser()->setAttribute('url', $openid, 'openid');
			
			$redirect_url = @$consumer->constructRedirect($info, $process_url, $trust_root);
		
			return $this->redirect($redirect_url);

		}
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

	public function getConsumer()
	{
		/**
		* This is where the example will store its OpenID information.  You
		* should change this path if you want the example store to be created
		* elsewhere.  After you're done playing with the example script,
		* you'll have to remove this directory manually.
		*/
		$store_path = "/tmp/_php_consumer_test";
		if (!file_exists($store_path) &&
		!mkdir($store_path)) {
			print "Could not create the FileStore directory '$store_path'. ".
			" Please check the effective permissions.";
			return;
		}

		$store = @new Auth_OpenID_FileStore($store_path);
		/**
		* Create a consumer object using the store object created earlier.
		*/
		return @new Auth_OpenID_Consumer($store);


	}
	public function executeFinishAuth()
	{
		$consumer = $this->getConsumer();
		$token = $this->getUser()->getAttribute('token', null, 'openid');

		// Complete the authentication process using the server's response.
		list($status, $info) = @$consumer->completeAuth($token, $_GET);

		$openid = null;

		// React to the server's response.  $info is the OpenID that was
		// tried.
		if ($status != Auth_OpenID_SUCCESS) {
			$msg = sprintf("Verification of %s failed.", $info);
		} 
		else {
			if ($info) {
				// This means the authentication succeeded.
				$openid = $info;
				$success = sprintf("You have successfully verified %s as your identity.",
				$openid);
				$this->getUser()->loginAs($this->getUser()->getAttribute('url', null, 'openid'), true);
			} 
			else {
				// This means the authentication was cancelled.
				$msg = 'Verification cancelled.';
			}
		}

		$redirect = $this->getUser()->getAttribute('redirect', '@homepage', 'openid');


		return $this->redirect($redirect);
	}


	public function executeShow ()
	{
		$this->user = UserPeer::retrieveByPk($this->getRequestParameter('id'));

		$this->forward404Unless($this->user instanceof User);
	}

	private function getUserOrCreate ($id = 'id')
	{
		if (!$this->getRequestParameter($id, 0))
		{
			$user = new User();
		}
		else
		{
			$user = UserPeer::retrieveByPk($this->getRequestParameter($id));

			$this->forward404Unless($user instanceof User);
		}

		return $user;
	}

}

?>