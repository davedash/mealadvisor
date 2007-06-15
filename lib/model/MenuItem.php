<?php

require_once 'lib/model/om/BaseMenuItem.php';


/**
 * Skeleton subclass for representing a row from the 'menu_item' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package model
 */	
class MenuItem extends BaseMenuItem {
	private $newVersion;
	public function getTitle ()
	{
		return $this->getName();
	}
	
	public function getVisibleImage (Profile $p = null)
	{
		$c = new Criteria();
		$c->add(MenuItemImagePeer::MENU_ITEM_ID, $this->getId());
		if ($p instanceof Profile) {
			$c2 = clone $c;
			$c2->add(MenuItemImagePeer::USER_ID, $p->getId());
			$image = MenuItemImagePeer::doSelectOne($c);
			if ($image instanceof MenuItemImage) {
				return $image;
			}
		}
		
		$images = MenuItemImagePeer::doSelect($c);
		
		if (count($images)) {
			shuffle($images);
			return $images[0];
		}
	}
	
	public function __toString ()
	{
		$st = 'NOT FOUND';
		$s = $this->getName();
		
		return $st;
	}
	
	public function getUserRating(Profile $u)
	{
		$c = new Criteria();
		$c->add(MenuItemRatingPeer::MENU_ITEM_ID, $this->getId());
		$c->add(MenuItemRatingPeer::USER_ID, $u->getId());
		$rating = MenuItemRatingPeer::doSelectOne($c);
		
		if ($rating instanceof MenuItemRating) return $rating->getValue();
		return;
	}
	
	public function getFeedRestaurant()
	{
		return $this->getRestaurant()->getStrippedTitle();
	}
	
	public function getFeedStrippedTitle()
	{
		$st = $this->getStrippedTitle();
		if ($st === null) return 'NOT FOUND';
		return $st;
	}
	
	public function getStrippedTitle()
	{
		return $this->getUrl();
	}
	public function getHashedId()
	{
		return $this->getHash();
	}
	
	public function getPrice()
	{
		if ($this->getMenuitemVersion())
		{
			return $this->getMenuitemVersion()->getPrice();
		}
	}

	public function setPrice($v)
	{
		$version = $this->getNewVersion();
		$version->setPrice($v);
	}
		
	public function getDescription($length = null)
	{
		$desc ='';
		if ($this->getMenuitemVersion()) 
		{
			$desc = $this->getMenuitemVersion()->getDescription();
		}
		if ($length) {
			
			$desc = myTools::truncate_text($desc, $length);
		}
		
		return $desc;
	}
	
	public function getHtmlDescription()
	{
		if ($this->getMenuitemVersion()) 
		{
			return $this->getMenuitemVersion()->getHtmlDescription();
		}
	}
		
	public function setDescription($v)
	{
		$version = $this->getNewVersion();
		$version->setDescription($v);
	}
	
	public function getNewVersion()
	{
		if (!($this->newVersion instanceof MenuitemVersion)) {
			if ($this->getMenuitemVersion()) {
				$this->newVersion = $this->getMenuitemVersion()->copy();
			} else {
				$this->newVersion = new MenuitemVersion();
				$this->newVersion->setMenuItem($this);
			}
		}
		
		return $this->newVersion;
	}
	public function addTagsForUser($phrase, $userId) 
	{ 
		// split phrase into individual tags 
		$tags = Tag::splitPhrase($phrase); 
		// add tags 
		foreach($tags as $tag) 
		{ 
			$c = new Criteria();
			$c->add(MenuitemTagPeer::MENU_ITEM_ID, $this->getId());
			$c->add(MenuitemTagPeer::USER_ID,$userId);
			$c->add(MenuitemTagPeer::TAG, $tag);
			$menuitemTag = MenuitemTagPeer::doSelectOne($c);

			if (!($menuitemTag instanceof MenuitemTag)) {
			
				$menuitemTag = new MenuitemTag(); 
				$menuitemTag->setMenuItemId($this->getId()); 
				$menuitemTag->setUserId($userId); 
				$menuitemTag->setTag($tag); 
				$menuitemTag->save(); 
			}
		} 
	}
	public function removeTagForUser($tag, $userId) 
	{ 
		$c = new Criteria();
		$c->add(MenuitemTagPeer::MENU_ITEM_ID, $this->getId());
		$c->add(MenuitemTagPeer::USER_ID,$userId);
		$c->add(MenuitemTagPeer::NORMALIZED_TAG, $tag);

		MenuitemTagPeer::doDelete($c);
		
	}	
	
	public function setName($v)
	{
		parent::setName($v);
		$this->setUrl(myTools::stripText($v));
	}
	
	public function getTags() 
	{ 
		$c = new Criteria(); 
		$c->add(MenuitemTagPeer::MENU_ITEM_ID, $this->getId()); 
		$c->addGroupByColumn(MenuitemTagPeer::NORMALIZED_TAG); 
		$c->setDistinct(); 
		$c->addAscendingOrderByColumn(MenuitemTagPeer::NORMALIZED_TAG); 
		$tags = array(); 
		foreach(MenuitemTagPeer::doSelect($c) as $tag) 
		{ 
			$tags[] = $tag->getNormalizedTag(); 
		} 
		return $tags; 
	}
	
	public function getTagsFromUser(Profile $u)
	{
		$c = new Criteria();
		$c->add(MenuitemTagPeer::MENU_ITEM_ID, $this->getId()); 
		$c->addGroupByColumn(MenuitemTagPeer::NORMALIZED_TAG); 
		$c->setDistinct(); 
		$c->add(MenuitemTagPeer::USER_ID,$u->getId());
		$c->addAscendingOrderByColumn(MenuitemTagPeer::NORMALIZED_TAG); 
		$tags = array(); 
		foreach(MenuitemTagPeer::doSelect($c) as $tag) 
		{ 
			$tags[] = $tag->getNormalizedTag(); 
		} 
		
		return $tags;	
	}
	public function getHash()
	{
		return md5('menuitem:'.$this->getId());
	}
	public function getPopularTags($max = 5) 
	{ 
		$tags = array(); 
		$con = Propel::getConnection(); 
		$query = ' 
		SELECT %s AS tag, COUNT(%s) AS count 
		FROM %s 
		WHERE %s = ? 
		GROUP BY %s 
		ORDER BY count DESC 
		'; 
		$query = sprintf($query, 
			MenuitemTagPeer::NORMALIZED_TAG, 
			MenuitemTagPeer::NORMALIZED_TAG, 
			MenuitemTagPeer::TABLE_NAME, 
			MenuitemTagPeer::MENU_ITEM_ID, 
			MenuitemTagPeer::NORMALIZED_TAG 
		); 
		$stmt = $con->prepareStatement($query); 
		$stmt->setInt(1, $this->getId()); 
		$stmt->setLimit($max); 
		$rs = $stmt->executeQuery(); 
		while($rs->next()) 
		{ 
			$tags[$rs->getString('tag')] = $rs->getInt('count'); 
		} 
		return $tags; 
	} 
	
	public function save($con = null)
	{
	  $con = Propel::getConnection('propel');
	  try
	  {
	    $con->begin();
		if ($this->newVersion instanceof MenuitemVersion) {
			$this->newVersion->save();
			$this->setMenuitemVersion($this->newVersion);
		}

	    $ret = parent::save($con);
	    $this->updateSearchIndex();

	    $con->commit();

	    return $ret;
	  }
	  catch (Exception $e)
	  {
	    $con->rollback();
	    throw $e;
	  }
	}
	
	public function updateSearchIndex()
	{
	  // delete existing SearchIndex entries about the current question
	  $c = new Criteria();
	  $c->add(MenuitemSearchIndexPeer::MENUITEM_ID, $this->getId());
	  MenuitemSearchIndexPeer::doDelete($c);

	  // create a new entry for each of the words of the question
	  foreach ($this->getWords() as $word => $weight)
	  {
	    $index = new MenuitemSearchIndex();
	    $index->setMenuitemId($this->getId());
	    $index->setWord($word);
	    $index->setWeight($weight);
	    $index->save();
	  }
	}

	public function getWords()
	{
	  // body
	  $raw_text =  str_repeat(' '.strip_tags($this->getHtmlDescription()), sfConfig::get('app_search_body_weight'));

	  // title
	  $raw_text .= str_repeat(' '.$this->getName(), sfConfig::get('app_search_title_weight'));

	  // title and body stemming
	  $stemmed_words = myTools::stemPhrase($raw_text);

	  // unique words with weight
	  $words = array_count_values($stemmed_words);

	  // add tags
	  $max = 0;
	  foreach ($this->getPopularTags(20) as $tag => $count)
	  {
	    if (!$max)
	    {
	      $max = $count;
	    }

	    $stemmed_tag = PorterStemmer::stem($tag);

	    if (!isset($words[$stemmed_tag]))
	    {
	      $words[$stemmed_tag] = 0;
	    }
	    $words[$stemmed_tag] += ceil(($count / $max) * sfConfig::get('app_search_tag_weight'));
	  }

	  return $words;
	}
	
	public function getNumReviews()
	{
		$c = new Criteria();
		$c->add(MenuItemNotePeer::MENU_ITEM_ID, $this->getId());
		return MenuItemNotePeer::doCount($c);
	}
} // MenuItem
