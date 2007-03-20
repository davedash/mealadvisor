<?php

/*
 * This file is part of the symfony package.
 * (c) 2007 Dave Dash <dave.dash@spindrop.us>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 *
 * @package    symfony
 * @subpackage plugin
 * @author     Dave Dash <dave.dash@spindrop.us>
 * @version    SVN: $Id: actions.class.php $
 */
class BasesfOpenIDAuthActions extends sfActions
{
	/* This is the form... the guts in the template file can 	be copied and pasted without much worry. */
	public function executeSignin()
	{
		if ($this->isPost()) {
			$this->getUser()->setAttribute('referer', $this->getRequestParameter('referer', '@homepage'));
			$openid = new sfOpenID();
			$openid->setIdentity($this->getRequestParameter('openid_url'));
			
			$process_url = $this->getController()->genUrl('@openid_finishauth', true);
			$openid->setApprovedURL($process_url); // Script which handles a response from OpenID Server
			
			$trust_root = $this->getController()->genUrl('@homepage', true);
			$openid->SetTrustRoot($trust_root);

			$this->redirect($openid->getRedirectURL());
			 
		}
	}
	
	public function executeFinish()
	{
		$mode = $this->getRequestParameter('openid_mode');
		if ($mode == 'id_res') 
		{
			$openid = new sfOpenID();
			$openid->setIdentity($this->getRequestParameter('openid_identity'));
			$openid_validation_result = $openid->validateWithServer();

			if ($openid_validation_result) $this->openIDCallback();
			else if ($openid->hasError()) {
        $this->error = $openid->getError();
			}
			else {                                            // Signature Verification Failed
				$this->error = 'INVALID AUTHORIZATION';
			}
			return sfView::ERROR;
		}
	}
	
	public function isPost()
	{
		return ($this->getRequest()->getMethod() == sfRequest::POST);
	}

	public function openIDCallback()
	{
		
	}	
}
