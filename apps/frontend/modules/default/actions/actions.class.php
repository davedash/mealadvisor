<?php

/**
* default actions.
*
* @package    eatw.us
* @subpackage default
* @author     Your name here
* @version    SVN: $Id: actions.class.php 500 2006-01-23 09:15:57Z fabien $
*/
class defaultActions extends sfActions
{
	public function executeStatic()
	{
		$this->page = $this->getRequestParameter('page');
	}
	public function executeTest()
	{
		$c = new Criteria();
		$c->add(LocationPeer::LATITUDE, null);
		$ls = LocationPeer::doSelect($c);
		foreach ($ls AS $l)
		{
			echo $l;
			$l->save();
		}

		exit;
	}

	public function isPost()
	{
		return ($this->getRequest()->getMethod() == sfRequest::POST);
	}

	public function executeUpdateIndex()
	{

		$c = new Criteria();
		$c->add(MenuItemImage::HEIGHT, null, Criteria::ISNULL);
		$menu_item_image = MenuItemImagePeer::doSelect($c);
		
		foreach ($menu_item_image AS $img) {
			$data = $img->getData()->getContents();
			$gdimg = imagecreatefromstring($data);
			$img->setWidth(imagesx($gdimg));
			$img->setHeight(imagesy($gdimg));
			$img->save();
			echo $img->getWidth();
			echo "," . $img->getHeight() . "<Br/>";
		}

		echo 'done';
		exit;
	}

	/**
	* Executes index action
	*
	*/
	public function executeIndex()
	{
		

	$this->getResponse()->addJavascript(sfConfig::get('SF_PROTOTYPE_WEB_DIR').'/js/prototype.js');


		$this->footerLogoOff = true;
	}

	public function executeError404()
	{}

	
	public function executeLogin()
	{
		if ($this->hasFlash('post_login')) {
			$this->getRequest()->getParameterHolder()->set('referer', $this->getFlash('post_login'));
			} else {
				$this->getRequest()->getParameterHolder()->set('referer', $this->getRequest()->getReferer());
			}

		}
	}

	?>
