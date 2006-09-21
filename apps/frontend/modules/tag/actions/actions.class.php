<?php

/**
* tag actions.
*
* @package    eatw.us
* @subpackage tag
* @author     Your name here
* @version    SVN: $Id: actions.class.php 500 2006-01-23 09:15:57Z fabien $
*/

require_once('myActions.class.php');

class tagActions extends myActions
{
	public function executePopular()
	{
	}
	/**
	* Executes index action
	*
	*/
	public function executeShow() 
	{ 
		$tag = $this->getRequestParameter('tag');
		$this->getResponse()->setTitle( $tag . ' &laquo; tags &laquo; ' . sfConfig::get('app_title'));

		$this->items = MenuItemPeer::getPopularByTag($tag);
		$this->addFeed('@feed_tag_georss?tag=' . $tag, "Restaurants serving $tag (GeoRSS)");
		
		$this->restaurants = RestaurantPeer::getPopularByTag($tag);
		$this->tag = $tag;
	}

	public function executeAdd() 
	{ 
		sfConfig::set('sf_web_debug', false);                           

		$this->menu_item = MenuItemPeer::retrieveByHash($this->getRequestParameter('menuitem_hash')); 
		$this->forward404Unless($this->menu_item); 
		$userId = $this->getUser()->getId(); 
		$phrase = $this->getRequestParameter('tag'); 
		$this->menu_item->addTagsForUser($phrase, $userId); 
		//$this->tags = $this->menu_item->getTags(); 
	}

	public function executeAddRestaurant()
	{
		sfConfig::set('sf_web_debug', false);                           

		$this->restaurant = RestaurantPeer::retrieveByStrippedTitle($this->getRequestParameter('restaurant')); 
		$this->forward404Unless($this->restaurant instanceof Restaurant); 
		$userId = $this->getUser()->getId(); 
		$phrase = $this->getRequestParameter('tag'); 
		$this->restaurant->addTagsForUser($phrase, $userId);
	}
	public function executeRemove()
	{
		sfConfig::set('sf_web_debug', false);
		$this->menu_item = MenuItemPeer::retrieveByHash($this->getRequestParameter('menuitem_hash')); 
		$this->forward404Unless($this->menu_item); 
		$userId = $this->getUser()->getId(); 
		$tag = $this->getRequestParameter('tag'); 
	
		$this->menu_item->removeTagForUser($tag, $userId); 
		//$this->tags = $this->menu_item->getTags(); 
		
	}
	
	public function executeRemoveRestaurant()
	{
		sfConfig::set('sf_web_debug', false);
		$this->restaurant = RestaurantPeer::retrieveByStrippedTitle($this->getRequestParameter('restaurant')); 
		$this->forward404Unless($this->restaurant instanceof Restaurant); 
		$userId = $this->getUser()->getId(); 
		$tag = $this->getRequestParameter('tag'); 
	
		$this->restaurant->removeTagForUser($tag, $userId); 
		//$this->tags = $this->menu_item->getTags(); 
		
	}
	
	
	
	public function executeAutocomplete() 
	{ 
		// disable web debug toolbar 
		sfConfig::set('sf_web_debug', false);                           
		$tags = array(); 
		$con = Propel::getConnection(); 
		$query = ' 
		SELECT DISTINCT %s AS tag 
		FROM %s 
		WHERE %s = ? AND %s LIKE ? 

		UNION

		SELECT DISTINCT %s AS tag 
		FROM %s 
		WHERE %s = ? AND %s LIKE ? 

		ORDER BY tag
		
		
		'; 
		$query = sprintf($query, 
			MenuitemTagPeer::NORMALIZED_TAG, 
			MenuitemTagPeer::TABLE_NAME, 
			MenuitemTagPeer::USER_ID, 
			MenuitemTagPeer::TAG, 
			
			RestaurantTagPeer::NORMALIZED_TAG,
			RestaurantTagPeer::TABLE_NAME,
			RestaurantTagPeer::USER_ID,
			RestaurantTagPeer::TAG
		); 
		$stmt = $con->prepareStatement($query); 
		$stmt->setInt(1, $this->getUser()->getId()); 
		$stmt->setString(2, $this->getRequestParameter('tag').'%'); 
		$stmt->setInt(3, $this->getUser()->getId()); 
		$stmt->setString(4, $this->getRequestParameter('tag').'%'); 
		$stmt->setLimit(10); 
		$rs = $stmt->executeQuery(); 
		while($rs->next()) 
		{ 
			$tags[] = $rs->getString('tag'); 
		} 
		$this->tags = $tags; 
	} 

}

?>