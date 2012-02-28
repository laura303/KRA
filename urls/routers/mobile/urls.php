<?php
	$mobile_home = new Route("$BASE_URL/mobile/");
	$mobile_home->setMapClass("Mobile")->setMapMethod("home");
	$router->addRoute("mobile-home",$mobile_home);

	$auth_login = new Route("$BASE_URL/mobile/login/");
	$auth_login->setMapClass("Mobile")->setMapMethod("login");
	$router->addRoute( "auth-mobile-login", $auth_login );
	
	$mobile_view = new Route("$BASE_URL/mobile/licences/:id/");
	$mobile_view->setMapClass("Mobile")->setMapMethod("view")
				->addDynamicElement( ":id", '^\d+$' );
	$router->addRoute("mobile-view", $mobile_view);

	$mobile_home = new Route("$BASE_URL/mobile/licences/:id/renew/");
	$mobile_home->setMapClass("Mobile")->setMapMethod("renew")
				->addDynamicElement( ":id", '^\d+$' );
	$router->addRoute("mobile-renew",$mobile_home);
?>