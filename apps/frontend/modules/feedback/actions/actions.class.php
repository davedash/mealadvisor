<?php

/**
 * feedback actions.
 *
 * @package    reviewsby.us
 * @subpackage feedback
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 1814 2006-08-24 12:20:11Z fabien $
 */
class feedbackActions extends myActions
{
  /**
   * Executes index action
   *
   */
	public function executeIndex()
	{
		if ($this->isPost()) {
			
			$raw_email = $this->sendEmail('mail', 'sendFeedback');
			return 'FeedbackSent';
		}
	}
	
	public function handleErrorLocation ()
	{
		$this->restaurant = RestaurantPeer::retrieveByStrippedTitle($this->getRequestParameter('restaurant'));

		$this->location = LocationPeer::retrieveByStrippedTitles($this->getRequestParameter('restaurant'), $this->getRequestParameter('location'));
		
		return sfView::SUCCESS;
	}
	/**
	 *  Allow a reviewer to report an error with a location
	 *
	 */
	public function executeLocation()
	{
		$this->restaurant = RestaurantPeer::retrieveByStrippedTitle($this->getRequestParameter('restaurant'));

		$this->location = LocationPeer::retrieveByStrippedTitles($this->getRequestParameter('restaurant'), $this->getRequestParameter('location'));
		$this->getResponse()->setTitle('Feedback :: ' . strip_tags($this->location->__toString()) . ' &laquo; ' .$this->location->getRestaurant()->__toString() . ' &laquo; ' . sfConfig::get('app_title'), true);

		if ($this->isPost()) {

			$raw_email = $this->sendEmail('mail', 'sendFeedback');
			
			use_helper('Global');
			$this->notice('Your email has been sent.');
			
			return $this->redirect(url_for_location($this->location));
		}
	
		$this->restaurant = RestaurantPeer::retrieveByStrippedTitle($this->getRequestParameter('restaurant'));

		$this->location = LocationPeer::retrieveByStrippedTitles($this->getRequestParameter('restaurant'), $this->getRequestParameter('location'));
	}
	
}
