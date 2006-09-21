<?php

	#doc
	#	classname:	myActions
	#	scope:		PUBLIC
	#
	#/doc

	class myActions extends sfActions
	{
		public function isPost()
		{
			return ($this->getRequest()->getMethod() == sfRequest::POST);
		}
		
		public function addFeed($feed, $title = null)
		{
			$feedArray = array('url' => $feed, 'title' => $title);

			$this->getRequest()->setAttribute($feed, $feedArray, 'helper/asset/auto/feed');
		}
		public function notice($m) {
			$notice = $this->getFlash('notice');
			if ($notice && !is_array($notice)) {
				$notice = array($notice);
			} else {
				$notice = array();
			}

			array_push($notice, $m);
			$this->setFlash('notice', $notice);
		}
	}
	###

?>