<?php

require_once 'lib/model/om/BaseMenuItemImage.php';


/**
 * Skeleton subclass for representing a row from the 'menu_item_image' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package model
 */	
class MenuItemImage extends BaseMenuItemImage {
	public function save($con = null)
	{
		$gdimg = imagecreatefromstring($this->getData()->getContents());
		$this->setWidth(imagesx($gdimg));
		$this->setHeight(imagesy($gdimg));
		
		parent::save($con);
	}
	public function getScaledDimensions ($options = array())
	{
		$h = $this->getHeight();
		$w = $this->getWidth();
		if (!($h && $w)) {
			return array(0, 0);
		}
		if (isset($options['longest_side'])) {
			if ($this->isPortrait()) {
				$h = $options['longest_side'];
				$w = $h * $this->getWidth()/ $this->getHeight();
			} else {
				$w = $options['longest_side'];
				$h = $w * $this->getHeight()/$this->getWidth();
			}
		}
		return array($h, $w);
	}
	
	public function isPortrait ()
	{
		return ($this->getHeight() > $this->getWidth());
	}
} // MenuItemImage
