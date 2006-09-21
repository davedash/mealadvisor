<?php

/**
 * mail actions.
 *
 * @package    mymen.us
 * @subpackage mail
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 500 2006-01-23 09:15:57Z fabien $
 */
class mailActions extends sfActions
{
 	public function executeSendFeedback()
	{
		$mail = new sfMail();
		$mail->addAddress('dave.dash@gmail.com');
		$mail->addAddress('kt.bonn@gmail.com');
		$email = $this->getRequestParameter('email');
		$name = $this->getRequestParameter('name', 'Someone on reviewsBy.us');
		$mail->setFrom("$name <$email>");
		$subject = $this->getRequestParameter('subject', 'Feedback - or - Spam?  You decide.');
		$mail->setSubject($subject);
		$mail->setPriority(1);
		$this->mail = $mail;
		$this->current_info = $this->getRequestParameter('info');
		$this->message = $this->getRequestParameter('message');
		$mail->setContentType('text/html');
	}
}

?>