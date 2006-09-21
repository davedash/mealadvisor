<?php

/**
 * menuitemnote actions.
 *
 * @package    reviewsby.us
 * @subpackage menuitemnote
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 1415 2006-06-11 08:33:51Z fabien $
 */
class menuitemnoteActions extends sfActions
{
  /**
   * Executes index action
   *
   */
	public function executeSave() 
	{
		$note = MenuItemNotePeer::retrieveByPk($this->getRequestParameter('id'));
		
		$this->forward404Unless($note instanceof MenuitemNote);
		if ($note->getUserId() == $this->getUser()->getId()) {
			$note->setNote($this->getRequestParameter('value'));
			$note->save();
		}
		$this->note = $note;
	}
	
	public function executeShow ()
  {
    $this->note = MenuItemNotePeer::retrieveByPk($this->getRequestParameter('id'));

    $this->forward404Unless($this->note instanceof MenuitemNote);
  }
}
