<?php
	require_once('pharmacist/urls.php');
	require_once('nurse/urls.php');
	require_once('stock_personel/urls.php');
	require_once('doctor/urls.php');
	
	//setup the default url
	$default_route = new Route('/Pharmacy/staff/');
	$default_route->setMapClass('Staff')->setMapMethod('index');
	$router->addRoute( 'staff', $default_route );
?>