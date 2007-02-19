<?php

class tagComponents extends sfComponents
{
	public function executePopularCloud()
	{
		$this->tags = MenuitemTagPeer::getPopularTags(40);
	}
	public function executePopularRestaurantCloud()
	{
		$this->tags = RestaurantTagPeer::getPopularTags(40);
		return "hi";
	}
	
	public function executeRestaurant()
	{
	
			$pop_tags = $this->restaurant->getPopularTags(10);
			$user_tags = array();
			if ($this->getUser()->isLoggedIn())
			{
				$user_tags = $this->restaurant->getTagsFromUser($this->getUser()->getProfile());
			}
			
			$tags = array();
			
			$max_count = isset($pop_tags[0]) ? max($pop_tags) : 0;
			
			
			foreach($pop_tags as $tag => $count) 
			{ 
				$num_sizes = 7;
				
				$size = ($max_count == 1) ? 1 : $count / $max_count * $num_sizes;
				
				if ($this->getUser()->isLoggedIn() && in_array($tag, $user_tags)) {
					
					$key = array_search($tag, $user_tags);
					require_once 'symfony/helper/JavascriptHelper.php';
					$tags[$tag] = link_to($tag, '@tag?tag='.$tag, "class=my tag_size_$size") . link_to_remote(image_tag('minus.png','class=mini_action alt=-'), array('url'=>'@restaurant_tag_remove?restaurant='. $this->restaurant->getStrippedTitle() . '&tag='.$tag, 'update' => $this->restaurant->getStrippedTitle().'_tags'),
					"confirm='Are you sure you want to remove this tag, $tag?'");
					unset($user_tags[$key]);
					
				} else {
					
					$tags[$tag] = link_to($tag, '@tag?tag='.$tag, "class=tag_size_$size"); 
				
				}
			} 
			
			foreach($user_tags as $tag) 
			{ 
				$tags[$tag] = link_to($tag, '@tag?tag='.$tag, "class=my tag_size_1") . link_to_remote(image_tag('minus.png','class=mini_action alt=-'), array('url'=>'@restaurant_tag_remove?restaurant='. $this->restaurant->getStrippedTitle() . '&tag='.$tag, 'update' => $this->restaurant->getStrippedTitle().'_tags'),
				"confirm='Are you sure you want to remove this tag, $tag?'"); 
			} 
			
			ksort($tags);
			$this->tags = $tags;
	}
}