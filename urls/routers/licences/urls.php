<?php
	$application_list = new Route("$BASE_URL/applications/");
	$application_list->setMapClass("Licence")->setMapMethod("choose_application_list");
	$router->addRoute("application-choose", $application_list);

	$application_list = new Route("$BASE_URL/applications/:type/");
	$application_list->setMapClass("Licence")->setMapMethod("view_application_list")
					 ->addDynamicElement( ":type", '^(drivers-licence|motor-vehicle|driving-instructor|psv-licences-badges)+$' );
	$router->addRoute("application-list", $application_list);
	
	$application_list = new Route("$BASE_URL/applications/:type/:status/");
	$application_list->setMapClass("Licence")->setMapMethod("view_list")
					 ->addDynamicElement( ":status", '^[-\w]+$' )
					 ->addDynamicElement( ":type", '^(drivers-licence|motor-vehicle|driving-instructor|psv-licences-badges)+$' );
	$router->addRoute("application-status-list", $application_list);
	
	$licence_list = new Route("$BASE_URL/licences/:type/");
	$licence_list->setMapClass("Licence")->setMapMethod("view_list")
				 ->addDynamicElement( ":type", '^(drivers-licence|motor-vehicle|driving-instructor|psv-licences-badges)+$' );
	$router->addRoute( "licence-list", $licence_list );
	
	$licence_apply = new Route("$BASE_URL/licences/:type/apply/");
	$licence_apply->setMapClass("Licence")->setMapMethod("apply")
				  ->addDynamicElement( ":type", '^(drivers-licence|motor-vehicle|driving-instructor|psv-licences-badges)+$');
	$router->addRoute("licence-apply",$licence_apply);
	
	$licence_view = new Route("$BASE_URL/licences/:type/:id/");
	$licence_view->setMapClass("Licence")->setMapMethod("view")
				   ->addDynamicElement( ":type", '^(drivers-licence|motor-vehicle|driving-instructor|psv-licences-badges)+$' )
				   ->addDynamicElement( ":id", '^\d+$' );
	$router->addRoute("licence-view",$licence_view);
	
	$licence_action = new Route("$BASE_URL/licences/:type/:id/notification/close/");
	$licence_action->setMapClass("Licence")->setMapMethod("notification_close")
				   ->addDynamicElement( ":type", '^(drivers-licence|motor-vehicle|driving-instructor|psv-licences-badges)+$' )
				   ->addDynamicElement( ":id", '^\d+$' );
	$router->addRoute("licence-notification-listen",$licence_action);

	$licence_edit = new Route("$BASE_URL/admin/licences/:type/:id/edit/");
	$licence_edit->setMapClass("Licence")->setMapMethod("edit")
				   ->addDynamicElement( ":type", '^(drivers-licence|motor-vehicle|driving-instructor|psv-licences-badges)+$' )
				   ->addDynamicElement( ":id", '^\d+$' );
	$router->addRoute("licence-edit",$licence_edit);
	
	$licence_delete = new Route("$BASE_URL/admin/licences/:type/:id/delete/");
	$licence_delete->setMapClass("Licence")->setMapMethod("delete")
				   ->addDynamicElement( ":type", '^(drivers-licence|motor-vehicle|driving-instructor|psv-licences-badges)+$' )
				   ->addDynamicElement(":id", '^\d+$');
	$router->addRoute("licence-delete",$licence_delete);
	
	$licence_action = new Route("$BASE_URL/admin/licences/:type/:id/:action/");
	$licence_action->setMapClass("Licence")->setMapMethod("action")
				   ->addDynamicElement( ":type", '^(drivers-licence|motor-vehicle|driving-instructor|psv-licences-badges)+$' )
				   ->addDynamicElement( ":id", '^\d+$' )
				   ->addDynamicElement( ":action", '^[-\w]+$' );
	$router->addRoute("licence-action",$licence_action);
?>