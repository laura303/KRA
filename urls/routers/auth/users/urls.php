<?php
	$user_list = new Route("$BASE_URL/admin/users/");
	$user_list->setMapClass("User")->setMapMethod("view_list");
	$router->addRoute( "user-list", $user_list );
	
	$user_add = new Route("$BASE_URL/admin/users/add/");
	$user_add->setMapClass("User")->setMapMethod("add");
	$router->addRoute("user-add",$user_add);
	
	$user_edit = new Route("$BASE_URL/admin/users/:id/");
	$user_edit->setMapClass("User")->setMapMethod("view")
				   ->addDynamicElement( ":id", '^\d+$' );
	$router->addRoute("user-view",$user_edit);
	
	$user_edit = new Route("$BASE_URL/admin/users/:id/edit/");
	$user_edit->setMapClass("User")->setMapMethod("edit")
				   ->addDynamicElement( ":id", '^\d+$' );
	$router->addRoute("user-edit",$user_edit);
	
	$user_delete = new Route("$BASE_URL/admin/users/:id/delete/");
	$user_delete->setMapClass("User")->setMapMethod("delete")
				   ->addDynamicElement(":id", '^\d+$');
	$router->addRoute("user-delete",$user_delete);
?>