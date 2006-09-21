<?php
// auto-generated by sfPropelCrud
// date: 02/24/2006 22:29:34
?>
<?php

/**
 * restaurantnote actions.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage restaurantnote
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 500 2006-01-23 09:15:57Z fabien $
 */
class restaurantnoteActions extends sfActions
{
  public function executeIndex ()
  {
    return $this->forward('restaurantnote', 'list');
  }

  public function executeList ()
  {
    $this->restaurant_notes = RestaurantNotePeer::doSelect(new Criteria());
  }

  public function executeShow ()
  {
    $this->restaurant_note = RestaurantNotePeer::retrieveByPk($this->getRequestParameter('id'));

    $this->forward404Unless($this->restaurant_note instanceof RestaurantNote);
  }

  public function executeEdit ()
  {
    $this->restaurant_note = $this->getRestaurantNoteOrCreate();
  }

	public function executeSave() 
	{
		$note = RestaurantNotePeer::retrieveByPk($this->getRequestParameter('id'));
		
		$this->forward404Unless($note instanceof RestaurantNote);
		if ($note->getUserId() == $this->getUser()->getId()) {
			$note->setNote($this->getRequestParameter('value'));
			$note->save();
		}
		$this->note = $note;
	}
  public function executeUpdate ()
  {
    $restaurant_note = $this->getRestaurantNoteOrCreate();

    $restaurant_note->setId($this->getRequestParameter('id'));
    $restaurant_note->setUserId($this->getRequestParameter('user_id'));
    $restaurant_note->setNote($this->getRequestParameter('note'));
    $restaurant_note->setRestaurantId($this->getRequestParameter('restaurant_id'));
    $restaurant_note->setLocationId($this->getRequestParameter('location_id'));

    $restaurant_note->save();

    return $this->redirect('restaurantnote/show?id='.$restaurant_note->getId());
  }

  public function executeDelete ()
  {
    $restaurant_note = RestaurantNotePeer::retrieveByPk($this->getRequestParameter('id'));

    $this->forward404Unless($restaurant_note instanceof RestaurantNote);

    $restaurant_note->delete();

    return $this->redirect('restaurantnote/list');
  }

  private function getRestaurantNoteOrCreate ($id = 'id')
  {
    if (!$this->getRequestParameter($id, 0))
    {
      $restaurant_note = new RestaurantNote();
    }
    else
    {
      $restaurant_note = RestaurantNotePeer::retrieveByPk($this->getRequestParameter($id));

      $this->forward404Unless($restaurant_note instanceof RestaurantNote);
    }

    return $restaurant_note;
  }

}

?>