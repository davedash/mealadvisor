<?php
 
define('SF_ROOT_DIR',    realpath(dirname(__FILE__).'/..'));
define('SF_APP',         'frontend');
define('SF_ENVIRONMENT', 'dev');
define('SF_DEBUG',       true);
 
require_once(SF_ROOT_DIR.DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.SF_APP.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php');
 
// initialize database manager
$databaseManager = new sfDatabaseManager();
$databaseManager->initialize();

$c = new Criteria();
$c->add(LocationPeer::LATITUDE, null, Criteria::ISNULL);
$locations = LocationPeer::doSelect($c);

foreach ($locations AS $l)
{
	$l->save();
	
	echo "{$l->getLatitude()}, {$l->getLongitude()}\n";
}