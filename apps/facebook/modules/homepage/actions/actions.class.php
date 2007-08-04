<?php

/**
 * homepage actions.
 *
 * @package    reviewsby.us
 * @subpackage homepage
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class homepageActions extends sfActions
{
  /**
   * Executes index action
   *
   */
  public function executeIndex()
  {
    $this->unlinked = false;
    $ask_fb_linked = $this->getUser()->getPreference('ask_fb_linked', false);
    if (!$ask_fb_linked && $this->getUser()->isUnlinked())
    {
      $this->unlinked = true;
    }
    
  }
}
