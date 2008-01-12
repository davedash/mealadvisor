<?php

  /*
   * test that we can find mountain, view
   */
   
  include(dirname(__FILE__).'/../../bootstrap/functional.php');
  
  // create test browser
  $b = new sfTestBrowser();
  $b->initialize();
  
  $b->get('/')
    ->post('/search',  array('location' => 'Mountain View, CA', ))
    ->followRedirect()
    ->responseContains('Locations in ')
    ->responseContains('Mountain View');
  
  /*
   * test that we can find "indian" (food) in "ca" (canada)
   */
 
 $b->get('/')
   ->post('/search',  array('search' => 'indian', 'location' => 'ca', ))
//   ->followRedirect()
   ->responseContains('Canada');