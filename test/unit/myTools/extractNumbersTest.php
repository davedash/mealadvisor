<?php

include(dirname(__FILE__).'/../../bootstrap/unit.php');
require(dirname(__FILE__).'/../../../lib/myTools.class.php');

$t = new lime_test(4, new lime_output_color());

// '123 bar' => 123

$result = myTools::extractNumbers('123 bar');
$expected_result = array(123);

$t->is($result,$expected_result,'123 bar is 123');

//  '248 gold 27' => 248,27

$result = myTools::extractNumbers('248 gold 27');
$expected_result = array(248, 27);

$t->is($result,$expected_result,'248 gold 27');

//  '20-21' => 20,21

$result = myTools::extractNumbers('20-21');
$expected_result = array(20,21);

$t->is($result,$expected_result,'20-21');

$result = myTools::extractNumbers('Hi there');
$expected_result = null;

$t->is($result,$expected_result,'Hi there');
