<?php

// create a new test browser
$browser = new sfTestBrowser();
$browser->initialize();

$browser->
  get('/sfGuardAuth/index')->
  isStatusCode(200)->
  isRequestParameter('module', 'sfGuardAuth')->
  isRequestParameter('action', 'index')->
  checkResponseElement('body', '/sfGuardAuth/')
;
