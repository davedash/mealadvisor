<?php

	#doc
	#	classname:	myActions
	#	scope:		PUBLIC
	#
	#/doc

	class myActions extends sfActions
	{
	  
	  public function prependTitle($title)
	  {
	    $r = $this->getResponse();
	    $d = sfConfig::get('app_title_delimiter', ' &laquo; ');
	    $t = sfConfig::get('app_title');
	    $r->setTitle($title.$d.$t, false);
	  }
	  
		public function addPrototype()
		{
			$this->getResponse()->addJavascript(sfConfig::get('SF_PROTOTYPE_WEB_DIR').'/js/prototype.js');
		}
		
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
