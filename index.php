<?php

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

#hola

include("inc/config.inc.php");



function myAutoload($className)
{
  $file = __DIR__ . '/core/' . $className . '.php';

  if (file_exists($file)) {
    require_once $file;
  }
}
spl_autoload_register('myAutoload');



$core = new core();

//$lizDb = new lizDb($servername, $username, $password, $database);


$routeData = routing::handleRequest();
$content = $routeData['content'];
$head_title = $routeData['head_title'];
$head_description = $routeData['head_description'];
$canonical_name = $routeData['canonical_name'];
