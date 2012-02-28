<?php
	require_once("auth/urls.php");
	require_once("vehicles/urls.php");
	require_once("licences/urls.php");
	require_once("clients/urls.php");
	require_once("payments/urls.php");
	require_once("mobile/urls.php");
	require_once("search_urls.php");
	
	//setup the default url
	$default_route = new Route("$BASE_URL/");
	$default_route->setMapClass("Default")->setMapMethod("index");
	$router->addRoute( "default", $default_route );
	
	$user_dashboard = new Route("$BASE_URL/dashboard/");
	$user_dashboard->setMapClass("Default")->setMapMethod("user_dashboard");
	$router->addRoute( "user-dashboard", $user_dashboard );
	
	$admin_dashboard = new Route("$BASE_URL/dashboard/admin/");
	$admin_dashboard->setMapClass("Default")->setMapMethod("admin_dashboard");
	$router->addRoute( "admin-dashboard", $admin_dashboard );
?>
