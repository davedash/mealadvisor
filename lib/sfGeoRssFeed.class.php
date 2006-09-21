<?php

/**
 * sfGeoRSSFeed.
 *
 * based on sfRss201rev2Feed.class.php from Symfony project
 *
 * @author     Dave Dash <dave.dash@spindrop.us>
 */

class sfGeoRssFeed extends sfFeed
{
	public function getFeed()
	{
		$this->getContext()->getResponse()->setContentType('application/rss+xml');

		$xml = array();
		$xml[] = '<?xml version="1.0" encoding="UTF-8" ?>';
		$xml[] = '<rss version="'.$this->getVersion().'" xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#">';
		$xml[] = '  <channel>';
		$xml[] = '  <title>'.$this->getTitle().'</title>';
		$xml[] = '  <link>'.sfContext::getInstance()->getController()->genUrl($this->getLink(), true).'</link>';
		$xml[] = '  <description>'.$this->getDescription().'</description>';
		if ($this->getLanguage())
		{
			$xml[] = '  <language>'.$this->getLanguage().'</language>';
		}
		$xml[] = implode("\n", $this->getFeedElements());
		$xml[] = '  </channel>';
		$xml[] = '</rss>';

		return implode("\n", $xml);
	}
	protected function getFeedElements()
	{
		$xml = array();
		foreach ($this->getItems() as $item)
		{
			$xml[] = '<item>';
			$xml[] = '  <title>'.htmlspecialchars($this->getItemFeedTitle($item), null, 'UTF-8').'</title>';
			if ($this->getItemFeedDescription($item))
			{
				$xml[] = '  <description>'.htmlspecialchars($this->getItemFeedDescription($item)).'</description>';
			}
			$xml[] = '  <link>'.$this->getItemFeedLink($item).'</link>';
			if ($this->getItemFeedUniqueId($item))
			{
				$xml[] = '  <guid isPermalink="false">'.$this->getItemFeedUniqueId($item).'</guid>';
			}

			// author information
			if ($this->getItemFeedAuthorEmail($item) && $this->getItemFeedAuthorName($item))
			{
				$xml[] = sprintf('  <author>%s (%s)</author>', $this->getItemFeedAuthorEmail($item), $this->getItemFeedAuthorName($item));
			}
			if ($this->getItemFeedPubdate($item))
			{
				$xml[] = '  <pubDate>'.date('r', $this->getItemFeedPubdate($item)).'</pubDate>';
			}
			if ($this->getItemFeedComments($item))
			{
				$xml[] = '  <comments>'.htmlspecialchars($this->getItemFeedComments($item)).'</comments>';
			}

			// enclosure
			if ((method_exists($item, 'getFeedEnclosure')) && ($enclosure = $item->getFeedEnclosure()))
			{
				$enclosure_attributes = sprintf('url="%s" length="%s" type="%s"', $enclosure->getUrl(), $enclosure->getLength(), $enclosure->getMimeType());
				$xml[] = '  <enclosure '.$enclosure_attributes.'></enclosure>';
			}

			// categories
			foreach ($this->getItemFeedCategories($item) as $category)
			{
				$xml[] = '  <category>'.$category.'</category>';
			}

			if ($this->getItemFeedLatitude($item))
			{
				$xml[] = '  <geo:lat>'.$this->getItemFeedLatitude($item).'</geo:lat>';
			}
			if ($this->getItemFeedLongitude($item))
			{
				$xml[] = '  <geo:long>'.$this->getItemFeedLongitude($item).'</geo:long>';
			}

			$xml[] = '</item>';
		}

		return $xml;
	}

	public function getItemFeedLatitude ($item)
	{
		foreach (array('getLatitude', 'getLat') as $methodName)
		{
			if (method_exists($item, $methodName))
			{
				return $item->$methodName();
			}
		}

		return '';
	}	

	public function getItemFeedLongitude ($item)
	{
		foreach (array('getLongitude', 'getLon', 'getLong') as $methodName)
		{
			if (method_exists($item, $methodName))
			{
				return $item->$methodName();
			}
		}

		return '';
	}	


	protected function getVersion()
	{
		// following yahoo's specs: http://developer.yahoo.com/maps/georss/index.html
		return '2.0';
	}
}

?>