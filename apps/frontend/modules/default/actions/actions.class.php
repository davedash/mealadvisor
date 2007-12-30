<?php

/**
* default actions.
*
* @package    eatw.us
* @subpackage default
* @author     Your name here
* @version    SVN: $Id: actions.class.php 500 2006-01-23 09:15:57Z fabien $
*/
class defaultActions extends myActions
{

	public function executeStatic()
	{
		$this->page = $this->getRequestParameter('page');
	}

	public function executeIndex()
	{
	  
	}

	public function executeError404()
	{}

}
