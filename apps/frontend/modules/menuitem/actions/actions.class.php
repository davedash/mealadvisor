<?php

/**
* menuitem actions.
*
* @package    ##PROJECT_NAME##
* @subpackage menuitem
* @author     Your name here
* @version    SVN: $Id: actions.class.php 500 2006-01-23 09:15:57Z fabien $
*/
class menuitemActions extends myActions
{
	public function executeRate()
	{
		sfConfig::set('sf_web_debug', false);
		$hash = $this->getRequestParameter('object');
		$rating = $this->getRequestParameter('rating');
		$ajax = ($this->getRequestParameter('mode')=='ajax');
		$menu_item = MenuItemPeer::retrieveByHash($hash);
		$joint = $this->getRequestParameter('joint', false);

		$this->rating = null;

		if(!$this->getUser()->isLoggedIn())
		{
			// build the url where we need to get redirected...
			$this->getUser()->setAttribute('post_login', 'menuitem/rate?object=' .$hash .'&rating=' .$rating);
			$this->setFlash('use_post_login', true);
			if(!$ajax) return $this->redirect('user/login');
		} 
		else {
			$c = new Criteria();

			$c->add(MenuItemRatingPeer::MENU_ITEM_ID, $menu_item->getId());
			$c->add(MenuItemRatingPeer::USER_ID, $this->getUser()->getId());

			$itemRating = MenuItemRatingPeer::doSelectOne($c);
			if ($itemRating instanceof MenuItemRating) $itemRating->delete();
			$itemRating = new MenuItemRating();
			$itemRating->setMenuItem($menu_item);
			$itemRating->setProfile($this->getUser()->getProfile());
			$itemRating->setValue($rating);
			$itemRating->save();
			$this->rating = $rating;

			require_once('helper/GlobalHelper.php');
			if(!$ajax) return $this->redirect(url_for_menuitem($menu_item));
		}
		$this->menu_item = $menu_item;
		if ($joint) return 'Joint';
		return sfView::SUCCESS;
	}

	public function getMenuItem()
	{
		$c = new Criteria();
		$c->add(RestaurantPeer::STRIPPED_TITLE, $this->getRequestParameter('restaurant'));
		$this->restaurant = RestaurantPeer::doSelectOne($c);
		$c = new Criteria();
		$c->add(MenuItemPeer::RESTAURANT_ID, $this->restaurant->getId());
		$c->add(MenuItemPeer::URL, $this->getRequestParameter('stripped_title'));
		return MenuItemPeer::doSelectOne($c);

	}

	public function executeShow()
	{
		$this->getResponse()->addJavascript(sfConfig::get('SF_PROTOTYPE_WEB_DIR').'/js/prototype');
		$this->getResponse()->addJavascript(sfConfig::get('SF_PROTOTYPE_WEB_DIR').'/js/controls');
		$this->getResponse()->addJavascript('comment');

		$this->menu_item = $this->getMenuItem();

		// display any notes/reviews about this product


		$this->forward404Unless($this->menu_item instanceof MenuItem);
		$this->getResponse()->setTitle($this->menu_item->getName() . ' &laquo; ' .$this->menu_item->getRestaurant()->__toString() . ' &laquo; ' . sfConfig::get('app_title'), true);

		$this->rating = null;

		if ($this->getUser()->isLoggedIn()) {
			$c = new Criteria();
			$c->add(MenuItemRatingPeer::MENU_ITEM_ID, $this->menu_item->getId());
			$c->add(MenuItemRatingPeer::USER_ID, $this->getUser()->getId());
			$rating = MenuItemRatingPeer::doSelectOne($c);
			if ($rating instanceof MenuItemRating) $this->rating = $rating->getValue();
		}

	}

	public function executeAddComment()
	{
	
		$this->menu_item = $this->getMenuItem();
		//var_dump($this->menu_item);
		$note = new MenuItemNote();
		$note->setMenuItem($this->menu_item);

		$note->setUserId($this->getUser()->getId());
		$note->setNote($this->getRequestParameter('body'));
		// clear this so it doesn't pre-populate the form.
		$this->getRequest()->getParameterHolder()->set('body',null);
		$note->save();
		$this->comment = $note;


	}

	public function executeEdit ()
	{
		$this->menu_item = $this->getMenuItemOrCreate();
	
		$c = new Criteria();
		$c->add(RestaurantPeer::STRIPPED_TITLE, $this->getRequestParameter('restaurant'));
		$this->restaurant = RestaurantPeer::doSelectOne($c);
		$this->menu_item->setRestaurant($this->restaurant);
			
		if (!$this->isPost())
		{
			// display the form
		
			$this->getResponse()->setTitle('adding a new dish' . ' &laquo; ' .$this->menu_item->getRestaurant()->__toString() . ' &laquo; ' . sfConfig::get('app_title'), true);
				
			return sfView::SUCCESS;
		}	
		if (!$this->menu_item->getId()) {
			$this->menu_item->setName($this->getRequestParameter('name'));
		}
		$this->menu_item->setDescription($this->getRequestParameter('description'));
		$this->menu_item->setPrice($this->getRequestParameter('price'));
		$this->menu_item->save();
		return $this->redirect(UrlHelper::url_for_menuitem($this->menu_item));
	}

	private function getMenuItemOrCreate ()
	{
		if (!$this->getRequestParameter('stripped_title', false))
		{
			$menu_item = new MenuItem();
		}
		else
		{
			
			$menu_item = MenuItemPeer::retrieveByStrippedTitle($this->getRequestParameter('restaurant'), $this->getRequestParameter('stripped_title'));

			$this->forward404Unless($menu_item instanceof MenuItem);
		}

		return $menu_item;
	}

}

?>