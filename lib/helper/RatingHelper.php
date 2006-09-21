<?php
	use_helper('Javascript','Asset', 'MyText');
	function rater($options=array())
	{
		// $field is what we replace, ala ajax.
		$options = _parse_attributes($options);
		$value = $options['rating'];
		if (!array_key_exists('id', $options))
		{
			$options['id'] = 'rater';
		}
		if (!array_key_exists('class', $options))
		{
			$options['class'] = 'star_rating';
		}
		if (!array_key_exists('message', $options))
		{
			$options['message'] = 'Recommend this:';
		}
	  
		$stars = array();
		$meanings = array('no', 'maybe not', 'maybe', 'definitely', 'most definitely');
		
		$starstr ='';
		for ($i = 1; $i <=5; $i++)
		{
			$class = "star_$i";
			$id = "{$options['id']}_$class";
			$starstr .= content_tag('li',_rating_star($i, $id, $meanings[$i-1], $options));
		}
		
		
		if ($value)
		{
			$width = $value * 20;
			$id = "{$options['id']}_star_current";
			
			$starstr .= content_tag('li',link_to('&nbsp;', '#', "id=$id class=current_rating style=width:{$width}px;"));
			
		}
		
		$js = '
			function raterOn(e)
			{
				// set the message
				$("'.$options['id'].'_message").innerHTML = Event.element(e).title;
				// clear the other thing
				if ($("'.$options['id'].'_star_current")) {
				  $("'.$options['id'].'_star_current").widthRestore = $("'.$options['id'].'_star_current").style.width;
				  $("'.$options['id'].'_star_current").style.width = 0;
				}	
			}
			function clearRaterMessage(e)
			{
				$("'.$options['id'].'_message").innerHTML = "'.$options['message'].'";
				// restore current
				if ($("'.$options['id'].'_star_current"))
					$("'.$options['id'].'_star_current").style.width = $("'.$options['id'].'_star_current").widthRestore;	
					
			}
		
			function init_rater_hovering() {
				
				Event.observe("'.$options['id'].'_star_1", "mouseover", raterOn, false);
				Event.observe("'.$options['id'].'_star_2", "mouseover", raterOn, false);
				Event.observe("'.$options['id'].'_star_3", "mouseover", raterOn, false);
				Event.observe("'.$options['id'].'_star_4", "mouseover", raterOn, false);
				Event.observe("'.$options['id'].'_star_5", "mouseover", raterOn, false);
				Event.observe("'.$options['id'].'", "mouseout", clearRaterMessage, false);
			}
			init_rater_hovering();
		';

		// unset the unnecessary
		unset($options['user']);
		unset($options['object']);
		unset($options['update']);
		unset($options['rating']);
		// we need each link on hover somehow to tell the rater_message area what their message is
		return content_tag('div',$options['message'],"id={$options['id']}_message class=head") 
		. content_tag('ul',$starstr, $options)
		. content_tag('script', javascript_cdata_section($js), 'type=text/javascript');
		
	}

	function _rating_star($value, $id, $meaning, $options)
	{
		$options = _parse_attributes($options);
		if (!array_key_exists('module', $options))
		{
			$module = 'restaurant';
		} else {
			$module = $options['module'];
			unset($options['module']);
		}
		
		$user = $options['user'];
		$object = $options['object'];
		$class= "star_$value";
		$html_options = array('id'=>$id, 'title'=>$meaning, 'class'=>$class);
		$url = $module . '/rate?object=' .$object->getHashedId() ."&rating=$value";
		
		if (array_key_exists('joint', $options))
		{
			$url .= '&joint=true';
		}
		if ($user->isLoggedIn()) {
			$options = array(
				'url' => $url.'&mode=ajax', 
				'update' => $options['update'],
				'complete' => "init_rater_hovering()" );
			return link_to_remote('&nbsp;',$options, $html_options);
		} else {
			return link_to('&nbsp;', $url, $html_options);
		}
	}

	function average_rating($options)
	{
		$options = _parse_attributes($options);
		$votes = $options['votes'];
		$infoString = "Average ($votes " . pluralize($votes, 'vote') . ')';
		
		$head = isset($options['omit_heading']) ? '' : content_tag('div', $infoString . ':' ,'class=head');
		unset($options['omit_heading']);
		
		unset($options['votes']);
		$value = $options['rating'];
		unset($options['rating']);
		if (!array_key_exists('id', $options))
		{
			$options['id'] = 'average_rating';
		}
		if (!array_key_exists('id', $options))
		{
			$options['id'] = 'average_rating';
		}
		if (!array_key_exists('class', $options))
		{
			$options['class'] = 'star_rating';
		}		
		$starstr ='';
		
		if (!array_key_exists('show_zero', $options))
		{
			$options['show_zero'] = false;
		}
		
		if ($value || $options['show_zero'])
		{
			unset($options['show_zero']);
			
			$width = $value * 20;
			$id = "{$options['id']}_star_current";
			
			$starstr = content_tag('li',content_tag('span', '&nbsp;', "id=$id class=current_rating style=width:{$width}px;"));
			
			$options['title'] = $infoString;
			return $head. content_tag('ul',$starstr, $options);	
		}
		// we don't want an empty ul
		return '';
	}

	function joint_rater($options=array())
	{
		// $field is what we replace, ala ajax.
		$options = _parse_attributes($options);
		$options['joint'] = true;
		// first let's get the object
		
		$object = $options['object'];
		$user = $options['user'];
		// let's see if the user set a rating
		if ($user->isLoggedIn() && $value = $object->getUserRating($user->getUser())) {
			$is_user_rating = true;
		} else {
			$value = $object->getAverageRating();
			$is_user_rating = false;
		}

		if (!array_key_exists('id', $options))
		{
			$options['id'] = $object->getStrippedTitle().'_rater';
		}
		if (!array_key_exists('class', $options))
		{
			$options['class'] = 'star_rating';
		}
			  
		$stars = array();
		$meanings = array('no', 'maybe not', 'maybe', 'definitely', 'most definitely');
		
		$starstr ='';
		for ($i = 1; $i <=5; $i++)
		{
			$class = "star_$i";
		
			$id = "{$options['id']}_$class";
			$starstr .= content_tag('li',_rating_star($i, $id, $meanings[$i-1], $options));
		}
		
		
		if ($value)
		{
			$width = $value * 20;
			$id = "{$options['id']}_star_current";
			
			$class = $is_user_rating ? 'current_rating' : 'joint_rating';
			$starstr .= content_tag('li',link_to('&nbsp;', '#', "id=$id class=$class style=width:{$width}px;"));
			
		}
		
		$js = '
			function raterOn(e)
			{
				// set the message
				//$("'.$options['id'].'_message").innerHTML = Event.element(e).title;
				// clear the other thing
				if ($("'.$options['id'].'_star_current")) {
				  $("'.$options['id'].'_star_current").widthRestore = $("'.$options['id'].'_star_current").style.width;
				  $("'.$options['id'].'_star_current").style.width = 0;
				}	
			}
			function clearRaterMessage(e)
			{
				//$("'.$options['id'].'_message").innerHTML = "'.$options['message'].'";
				// restore current
				if ($("'.$options['id'].'_star_current"))
					$("'.$options['id'].'_star_current").style.width = $("'.$options['id'].'_star_current").widthRestore;	
					
			}
		
			function init_rater_hovering() {
				
				Event.observe("'.$options['id'].'_star_1", "mouseover", raterOn, false);
				Event.observe("'.$options['id'].'_star_2", "mouseover", raterOn, false);
				Event.observe("'.$options['id'].'_star_3", "mouseover", raterOn, false);
				Event.observe("'.$options['id'].'_star_4", "mouseover", raterOn, false);
				Event.observe("'.$options['id'].'_star_5", "mouseover", raterOn, false);
				Event.observe("'.$options['id'].'", "mouseout", clearRaterMessage, false);
			}
			init_rater_hovering();
		';

		// unset the unnecessary
		unset($options['user']);
		unset($options['object']);
		unset($options['update']);
		unset($options['rating']);
		// we need each link on hover somehow to tell the rater_message area what their message is
		return content_tag('div',$options['message'],"id={$options['id']}_message class=head") 
		. content_tag('ul',$starstr, $options)
		. content_tag('script', javascript_cdata_section($js), 'type=text/javascript');
		
	}


?>