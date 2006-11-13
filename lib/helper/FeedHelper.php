<?php

function include_feeds()
{
	$type = 'rss';
	$already_seen = array();
	foreach (get_feeds() as $feeds)
	{
		if (!is_array($feeds) || is_associative($feeds))
		{
			$feeds = array($feeds);
		}

		foreach ($feeds as $feed)
		{
			if (is_array($feed)) {
				$file = $feed['url'];
				$title = empty($feed['title']) ? $type : $feed['title'];
			} else {
				$file = $feed;
				$title = $type;
			}

			if (isset($already_seen[$file])) continue;

			$already_seen[$file] = 1;
			echo tag('link', array('rel' => 'alternate', 'type' => 'application/'.$type.'+xml', 'title' => $title, 'href' => url_for($file, true)));
		}
	}
}

function get_feeds()
{
	return sfContext::getInstance()->getRequest()->getAttributeHolder()->getAll('helper/asset/auto/feed');
}

?>