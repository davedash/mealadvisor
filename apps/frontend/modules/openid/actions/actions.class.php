<?php


/**
* openid actions.
*
* @package    reviewsby.us
* @subpackage openid
* @author     Your name here
* @version    SVN: $Id: actions.class.php 1814 2006-08-24 12:20:11Z fabien $
*/
class openidActions extends myActions
{
	private $store_path = "/tmp/_open_id_store";

	/**
	* SignIn
	*
	*/
	public function executeSignin()
	{
		// this action doesn't have a template, it just forwards requests to an open id server

		if (!$this->isPost())
		{
			if ($this->getUser()->isLoggedIn())
			{
				$this->redirect('@homepage');
			}
			$this->redirect('@sf_guard_signin');
		}
		else
		{
			//$errorlevel = error_reporting(E_ALL);
			
			$errorlevel = error_reporting(E_ERROR);
			/* Do the OpenID login */
			define('Auth_OpenID_NO_MATH_SUPPORT', true);
			
			$this->init_openid();
			
			$openid = $this->getRequestParameter('openid');

			
			$process_url = $this->getController()->genUrl('@openid_finishauth', true);

			
			$trust_root = $this->getController()->genUrl('@homepage', true);
			// Begin the OpenID authentication process.
			$auth_request = $this->consumer->begin($openid);
			// Handle failure status return values.
			if (!$auth_request) {
			    return sfView::ERROR;
			}
			
			$auth_request->addExtensionArg('sreg', 'optional', 'email');

			
			
			
			// Redirect the user to the OpenID server for authentication.  Store
			// the token for this authentication so we can verify the response.
			//$this->getUser()->setAttribute('token', $info->token, 'openid');

			$redirect = $this->getRequestParameter('referer', '@homepage');

			$this->getUser()->setAttribute('redirect', $redirect, 'openid');
			$this->getUser()->setAttribute('url', $openid, 'openid');

			$redirect_url = $auth_request->redirectURL($trust_root,	$process_url);

	

			error_reporting($errorlevel);
			return $this->redirect($redirect_url);

		}
	}

	protected function init_openid ()
	{
		require_once "Auth/OpenID/Consumer.php";
		require_once "Auth/OpenID/FileStore.php";
		
		if (!file_exists($this->store_path) &&
		!mkdir($this->store_path)) {
			print "Could not create the FileStore directory '$this->store_path'. ".
			" Please check the effective permissions.";
			exit(0);
		}
				
		$this->store = new Auth_OpenID_FileStore($this->store_path);
		$this->consumer = new Auth_OpenID_Consumer($this->store);
	}
	
	public function executeFinishAuth()
	{
		if (!$this->getRequestParameter('nonce')) {
			if ($this->getUser()->isLoggedIn())
			{
				$this->redirect('@homepage');
			}
			$this->redirect('@sf_guard_signin');
		}
		$errorlevel = error_reporting(E_ERROR);
		
		$this->init_openid();
		$response = $this->consumer->complete($_GET);
		
		if ($response->status == Auth_OpenID_CANCEL) {
		    // This means the authentication was cancelled.
		    $msg = 'Verification cancelled.';
			return sfView::ERROR;
		} else if ($response->status == Auth_OpenID_FAILURE) {
		    $msg = "OpenID authentication failed: " . $response->message;
			return sfView::ERROR;
		} else if ($response->status == Auth_OpenID_SUCCESS) {
		    // This means the authentication succeeded.
		    $openid = $response->identity_url;
		    $esc_identity = htmlspecialchars($openid, ENT_QUOTES);
		    $success = sprintf('You have successfully verified ' .
		                       '<a href="%s">%s</a> as your identity.',
		                       $esc_identity, $esc_identity);

		    if ($response->endpoint->canonicalID) {
		        $success .= '  (XRI CanonicalID: '.$response->endpoint->canonicalID.') ';
		    }

		    $sreg = $response->extensionResponse('sreg');

		    if (@$sreg['email']) {
		        $success .= "  You also returned '".$sreg['email']."' as your email.";
		    }
		    if (@$sreg['postcode']) {
		        $success .= "  Your postal code is '".$sreg['postcode']."'";
		    }
		
			$this->getUser()->loginAs($this->getUser()->getAttribute('url', null, 'openid'), true);
		}
			
		$redirect = $this->getUser()->getAttribute('redirect', '@homepage', 'openid');
		error_reporting($errorlevel);
		return $this->redirect($redirect);
	}
}
