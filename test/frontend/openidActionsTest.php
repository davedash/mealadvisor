<?php

// create a new test browser
$browser = new sfTestBrowser();
$browser->initialize();

$browser->
  get('/openid/index')->
  isStatusCode(200)->
  isRequestParameter('module', 'openid')->
  isRequestParameter('action', 'index')->
  checkResponseElement('body', '/openid/')
;
