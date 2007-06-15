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

require_once(sfConfig::get('sf_plugins_dir').'/sfOpenIDPlugin/modules/sfOpenIDAuth/lib/BasesfOpenIDAuthActions.class.php');
class sfOpenIDAuthActions extends BasesfOpenIDAuthActions
{
	public function openIDCallback()
	{
		// if we've gotten here then the authentication was a success.
		// id
		$openid = sfOpenID::simplifyURL($this->getRequestParameter('openid_identity'));
		
		$user = $this->getUser();
		$user->loginAs($openid, true);
		$referer = $this->getUser()->getAttribute('referer');
		$this->getUser()->getAttributeHolder()->remove('referer');
		return $this->redirect($referer);
	}
}
