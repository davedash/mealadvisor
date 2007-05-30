<?php

require_once 'lib/model/om/BaseRestaurant.php';


/**
 * Skeleton subclass for representing a row from the 'restaurant' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package model
 */	
class Restaurant extends BaseRestaurant {
	private $newVersion;
 	private $reindex = false;

	public function getFeedDescription ()
	{
		return $this->getHtmlDescription();
	}

	public function getUserRating(Profile $u)
	{
		$c = new Criteria();
		$c->add(RestaurantRatingPeer::RESTAURANT_ID, $this->getId());
		$c->add(RestaurantRatingPeer::USER_ID, $u->getId());
		$rating = RestaurantRatingPeer::doSelectOne($c);
		
		if ($rating instanceof RestaurantRating) return $rating->getValue();
		return;
	}
		
	public function getAverageRating() {
		$rating = parent::getAverageRating();
		
		return $rating ? $rating : 0;
	}
	
	public function getHashedId()
	{
		return md5('restaurant:'.$this->getId());
	}
	
	public function getReviews()
	{
		return $this->getRestaurantNotes();
	}
	
	public function setName($v)
	{
		parent::setName($v);
		$this->setStrippedTitle(myTools::stripText($v));
		$this->reindex = true;
	}
	
	public function setStrippedTitle($v)
	{
		$old_st = parent::getStrippedTitle();
		if (!empty($old_st) && $this->getId() && $old_st != $v) {
			RestaurantRedirectPeer::create($old_st, $this);
		}
		parent::setStrippedTitle($v);
	}
	
	public function getStripped_Title()
	{
		return $this->getStrippedTitle();
	}
	
	public function __toString()
	{
		return $this->getName();
	}
	
	public function getDescription()
	{
		if ($this->getRestaurantVersion()) {
			return $this->getRestaurantVersion()->getDescription();
		}
		return null;
	}
	
	public function getHtmlDescription()
	{
		if ($this->getRestaurantVersion()) {
			return $this->getRestaurantVersion()->getHtmlDescription();
		}
		return null;
	}
	
	public function getChain()
	{
		if ($this->getRestaurantVersion())
		{
			return $this->getRestaurantVersion()->getChain();
		}
		return null;
	}
	
	public function setChain($v)
	{
		$version = $this->getNewVersion();
		$version->setChain($v);
	}
	
	public function setDescription($v)
	{
		$version = $this->getNewVersion();
		$version->setDescription($v);
	}
	
	public function getNewVersion()
	{
		if (!($this->newVersion instanceof RestaurantVersion)) {
			if ($this->getRestaurantVersion()) {
				$this->newVersion = $this->getRestaurantVersion()->copy();
			} else {
				$this->newVersion = new RestaurantVersion();
				$this->newVersion->setRestaurant($this);
			}
		}
		
		return $this->newVersion;
	}
	
	public function getUrl()
	{
		if ($this->getRestaurantVersion()) {
			return $this->getRestaurantVersion()->getUrl();
		}
		return null;
	}
	
	public function setUrl($v)
	{
		$version = $this->getNewVersion();
		$version->setUrl($v);
	}

	public function save($con = null)
	{
		$ret = null;
	  $con = Propel::getConnection('propel');
		try
		{
			$con->begin();

			if ($this->newVersion instanceof RestaurantVersion) 
			{
				$this->newVersion->save();
				$this->setRestaurantVersion($this->newVersion);
			}

			$ret = parent::save($con);

			$con->commit();

		}
		catch (Exception $e)
		{
			$con->rollback();
			throw $e;
		}
		$this->updateSearchIndex();
		
		return $ret;

	}

	public function index()
	{
		require_once 'Zend/Search/Lucene.php';
		$index = new Zend_Search_Lucene(sfConfig::get('app_search_restaurant_index_file'));
		// first find any references to this user and delete them
		$hits = $index->find('restaurantid:'. $this->getId());
		foreach ($hits AS $hit) {
			$index->delete($hit->id);
		}

		$doc = $this->generateZSLDocument();
		$index->addDocument($doc);
		$index->commit();
	}
	
	
	
	public function updateSearchIndex()
	{
	  // delete existing SearchIndex entries about the current question
	  $c = new Criteria();
	  $c->add(RestaurantSearchIndexPeer::RESTAURANT_ID, $this->getId());
	  RestaurantSearchIndexPeer::doDelete($c);
	  // create a new entry for each of the words of the question
		//var_dump ($this->getWords());
	  foreach ($this->getWords() as $word => $weight)
	  {
	    $index = new RestaurantSearchIndex();
	    $index->setRestaurantId($this->getId());
	    $index->setWord($word);
	    $index->setWeight($weight);
	    $index->save();
	  }
	}

	public function getWords()
	{
		// body
		$raw_text =  str_repeat(' '.strip_tags($this->getDescription()), sfConfig::get('app_search_body_weight'));
		// title
		$name = str_replace('\'', '', $this->getName());
		//echo $name, "\n";
		$raw_text .= str_repeat(' '.$name, sfConfig::get('app_search_title_weight'));
		//var_dump ($raw_text);

	  // title and body stemming
	  $stemmed_words = myTools::stemPhrase($raw_text);
		var_dump ($stemmed_words);

	  // unique words with weight
	  $words = array_count_values($stemmed_words);

	  
	  return $words;
	}
	
	public function getTags()
	{
		$c = new Criteria();
		$c->clearSelectColumns();
		$c->addSelectColumn(RestaurantTagPeer::NORMALIZED_TAG);
		$c->add(RestaurantTagPeer::RESTAURANT_ID, $this->getId());
		$c->setDistinct();
		$c->addAscendingOrderByColumn(RestaurantTagPeer::NORMALIZED_TAG);

		$tags = array();
		$rs = RestaurantTagPeer::doSelectRS($c);
		while ($rs->next())
		{
			$tags[] = $rs->getString(1);
		}

		return $tags;
	}

	public function getPopularTags($max = 10) 
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
			RestaurantTagPeer::NORMALIZED_TAG, 
			RestaurantTagPeer::NORMALIZED_TAG, 
			RestaurantTagPeer::TABLE_NAME, 
			RestaurantTagPeer::RESTAURANT_ID, 
			RestaurantTagPeer::NORMALIZED_TAG 
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
	public function getTagsFromUser(Profile $u)
	{
		$c = new Criteria();
		$c->add(RestaurantTagPeer::RESTAURANT_ID, $this->getId()); 
		$c->addGroupByColumn(RestaurantTagPeer::NORMALIZED_TAG); 
		$c->setDistinct(); 
		$c->add(RestaurantTagPeer::USER_ID,$u->getId());
		$c->addAscendingOrderByColumn(RestaurantTagPeer::NORMALIZED_TAG); 
		$tags = array(); 
		foreach(RestaurantTagPeer::doSelect($c) as $tag) 
		{ 
			$tags[] = $tag->getNormalizedTag(); 
		} 
		
		return $tags;	
	}
	public function removeTagForUser($tag, $userId) 
	{ 
		$c = new Criteria();
		$c->add(RestaurantTagPeer::RESTAURANT_ID, $this->getId());
		$c->add(RestaurantTagPeer::USER_ID,$userId);
		$c->add(RestaurantTagPeer::NORMALIZED_TAG, $tag);

		RestaurantTagPeer::doDelete($c);
		
	}
	public function addTagsForUser($phrase, $userId) 
	{ 
		// split phrase into individual tags 
		$tags = Tag::splitPhrase($phrase); 
		// add tags 
		foreach($tags as $tag) 
		{ 
			$c = new Criteria();
			$c->add(RestaurantTagPeer::RESTAURANT_ID, $this->getId());
			$c->add(RestaurantTagPeer::USER_ID,$userId);
			$c->add(RestaurantTagPeer::TAG, $tag);
			$restaurantTag = RestaurantTagPeer::doSelectOne($c);

			if (!($restaurantTag instanceof RestaurantTag)) {
			
				$restaurantTag = new RestaurantTag(); 
				$restaurantTag->setRestaurantId($this->getId()); 
				$restaurantTag->setUserId($userId); 
				$restaurantTag->setTag($tag); 
				$restaurantTag->save(); 
			}
		} 
	}
	
} // Restaurant
