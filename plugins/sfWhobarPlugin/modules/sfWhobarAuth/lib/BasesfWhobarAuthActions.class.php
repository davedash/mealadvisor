<?php
define('WHOBAR', 1);

$e = error_reporting('E_ALL');
ini_set('include_path', ini_get('include_path') . ':'.dirname(__FILE__).'/../../../lib/whobar');
require_once 'whobar/identity-functions.php';
require_once 'whobar/mapping-functions.php';
require_once 'whobar/misc-functions.php';
require_once 'HTTP/Client.php';

error_reporting($e);
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
class BasesfWhobarAuthActions extends sfActions
{
	/* This is the form... the guts in the template file can 	be copied and pasted without much worry. */
	public function executeIndex()
	{
		if ($this->isPost()) {
			$result = whobar_discover($_POST, array(
				'openid' => whobar_get_script("whobar_action=verify&whobar_proto=openid")
			));
		}
	}
	
	public function isPost()
	{
		return ($this->getRequest()->getMethod() == sfRequest::POST);
	}
	
}
