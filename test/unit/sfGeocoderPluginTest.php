<?php

include(dirname(__FILE__).'/../bootstrap/unit.php');
require_once(dirname(__FILE__).'/../../plugins/sfGeocoderPlugin/lib/sfGeocoder.class.php');

sfConfig::set('app_google_maps_api_key', 'ABQIAAAAyAAmLPzn6NjNWlSghNqTKxTTEM7e85ZdOLxclOTP3CThLJ0yaxTCLs4phoI67W6KqIr1j4bqwaMTUQ');
$t = new lime_test(6, new lime_output_color());

$gc = sfGeocoder::getGeocoder();
// test 55408
$t->diag('Geocoding 55408');
$result = $gc->query('55408');
$expected_result = array('precision' => sfGeocoder::ZIP, 'city' => 'Minneapolis', 'state' => 'MN', 'country' => 'US', 'zip' => '55408', 'longitude' => '-93.294025', 'latitude' => '44.941164');

$t->is_deeply($result,$expected_result,'Queries 55408');
$t->is($gc->getPrecision(), sfGeocoder::ZIP, 'Query is accurate to Zip level');
// test minneapolis

$t->diag('Geocoding Minneapolis');
$result = $gc->query('Minneapolis');
$expected_result = array('precision' => sfGeocoder::CITY,'city' => 'Minneapolis', 'state' => 'MN', 'country' => 'US', 'longitude' => '-93.265290', 'latitude' => '44.978500');

$t->is_deeply($result,$expected_result,'Queries Minneapolis');
$t->is($gc->getPrecision(), sfGeocoder::CITY, 'Query is accurate to City level');

$t->diag('Geocoding 3555 Fremont Ave S, Minneapolis');
$result = $gc->query('3555 Fremont Ave S, Minneapolis');
$expected_result = array('precision' => sfGeocoder::ADDRESS,'address' => '3555 Fremont Ave S', 'city' => 'Minneapolis', 'state' => 'MN', 'country' => 'US', 'zip' => '55408', 'longitude' => '-93.295870', 'latitude' => '44.938524');

$t->is_deeply($result,$expected_result,'Queries 3555 Fremont Ave S, Minneapolis');
$t->is($gc->getPrecision(), sfGeocoder::ADDRESS, 'Query is accurate to Address level');


