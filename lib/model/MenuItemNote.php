<?php

require_once 'lib/model/om/BaseMenuItemNote.php';


/**
 * Skeleton subclass for representing a row from the 'menuitem_note' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package model
 */	
class MenuItemNote extends BaseMenuItemNote {
	public function setNote($v)
	{
	  parent::setNote($v);

	  require_once('markdown.php');

	  $this->setHtmlNote(markdown($v));
	}
	
	public function getAuthor()
	{
		if ($this->getUser()) {
			return $this->getUser()->__toString();
		} else {
			return 'anonymous diner';
		}
	}
} // MenuItemNote
