<?php
require_once('myActions.class.php');
/**
* menuitem_image actions.
*
* @package    reviewsby.us
* @subpackage menuitem_image
* @author     Your name here
* @version    SVN: $Id: actions.class.php 1415 2006-06-11 08:33:51Z fabien $
*/
class menuitem_imageActions extends myActions
{
	/**
	* Executes index action
	*
	*/
	public function executeShowImageForItem()
	{
		$menu_item = MenuItemPeer::retrieveByHash($this->getRequestParameter('hashed_id'));
		if (!$menu_item instanceof MenuItem)
		{
			$this->notFound();
		}
		
		$c = new Criteria();
		$c->add(MenuItemImagePeer::MENU_ITEM_ID, $menu_item->getId());
		
		$this->image = MenuItemImagePeer::doSelectOne($c);
		
		if (!$this->image instanceof MenuItemImage) {
			$this->notFound();
		}
	}

	public function notFound()
	{
		$url = 'http'.($this->getRequest()->isSecure() ? 's' : '') . '://' . $this->getRequest()->getHost() . '/images/photoNotAvailable.gif';
		$this->redirect($url);
	}

	public function executeAdd ()
	{
		$this->restaurant = RestaurantPeer::retrieveByStrippedTitle($this->getRequestParameter('restaurant'));
		$this->menu_item = MenuItemPeer::retrieveByStrippedTitle($this->restaurant, $this->getRequestParameter('stripped_title'));
		$this->forward404Unless($this->menu_item instanceof MenuItem);
		
		if ($this->isPost()) 
		{
			// figure out if they gave us an URL or an image
			// get the input into a string somehow
			// resize the image
			// save the image in the database
		
			//if ($_FILES['image_file']['size']) {
				$md5 =  md5_file($_FILES['image_file']['tmp_name']);
				$imageAsString = file_get_contents($_FILES['image_file']['tmp_name']);
			//}
			
			$image = new MenuItemImage();
			$image->setUserId($this->getUser()->getId());
			$image->setMenuItemId($this->menu_item->getId());
			$image->setData($imageAsString);
			$image->setMd5Sum($md5);
			$image->save();
			$this->redirect('@menu_item?restaurant='.$this->restaurant->getStrippedTitle(). '&stripped_title='.$this->menu_item->getStrippedTitle());
			/*
				<column name="data" type="blob" />
				<column name="md5sum" type="varchar" size="32"/>
			*/
		}
		
	}

	public function handleErrorAdd()
	{
			$this->restaurant = RestaurantPeer::retrieveByStrippedTitle($this->getRequestParameter('restaurant'));
			$this->menu_item = MenuItemPeer::retrieveByStrippedTitle($this->restaurant, $this->getRequestParameter('stripped_title'));
			$this->forward404Unless($this->menu_item instanceof MenuItem);
		return sfView::SUCCESS; 
	}
}
