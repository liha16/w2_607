<?php

require_once("controller/Router.php"); 

ini_set('magic_quotes_gpc', 'Off');

$router = new \Controller\Router();
$html = $router->run();

?>