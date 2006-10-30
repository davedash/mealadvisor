<?php

require_once 'lib/model/om/BaseLocation.php';


/**
 * Skeleton subclass for representing a row from the 'location' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package model
 */	
class Location extends BaseLocation {
	public $search_distance = null;
	
	
	public function toLargeString ()
	{
		$l = array();
		if ($this->getAddress()) $l[] = $this->getAddress();
		if ($this->getCity()) $l[] = $this->getCity();
		if ($this->getState()) $l[] = $this->getState();
		if ($this->getCountry()) $l[] = $this->getCountry()->__toString();
		$loc = join(', ', $l);
		$str = $loc;
		if ($this->getPhone()) $loc .= ' (<acronym title="Phone">P</acronym>: ' . "{$this->getPhone()})";
		return $loc;
	}
	public function __toString()
	{
		
		$l = array();
		if ($this->getCity()) $l[] = $this->getCity();
		if ($this->getState()) $l[] = $this->getState();
		if ($this->getCountry()) $l[] = $this->getCountry()->__toString();
		$loc = join(', ', $l);
		if ($this->getName()) {
			return $this->getName() . " <em>($loc)</em>";
		} elseif ($loc) {
			return $loc;
		} else {
			return $this->getId();
		}
		
	}
	
	public function getFullAddress($format = '%a, %c, %s, %z', $options = null)
	{
		$str = $format;
		$str = str_replace('%a', $this->getAddress(), $str);
		$str = str_replace('%c', $this->getCity(), $str);
		$str = str_replace('%s', $this->getState(), $str);
		$str = str_replace('%z', $this->getZip(), $str);
		$str = trim($str, ' ,');
		return $str;
	}
	public function save($con = null)
	{
		if ($this->getName())
		{
			$this->setStrippedTitle(myTools::stripText($this->getName()));
		} else {
			$this->setStrippedTitle(myTools::stripText($this->__toString()));
		}
		
		// save latitude and longitude
		$locdata = myTools::getLatLng($this->getAddress(), $this->getCity(), $this->getState(), $this->getZip());

		if ($locdata) 
		{
			$this->setLatitude($locdata['Latitude']);
			$this->setLongitude($locdata['Longitude']);
		}
		
		parent::save($con);
	}
	
	public function getFeedTitle()
	{
		return $this->getRestaurant()->getName() . ' (' . strip_tags($this->__toString()) . ')';
	}
	public function getLink()
	{
		return '@location?restaurant=' . $this->getRestaurant()->getStrippedTitle() . '&location=' . $this->getStrippedTitle();
	}
} // Location
