<?php
	class DefaultController{
		public function index($args){
			$request = $args["request"];
			checkLoggedIn($request->user);
			
			redirectToPage('user-dashboard');
		}
		public function user_dashboard($args){
			$request = $args["request"];
			checkLoggedIn($request->user);
			
			global $smarty;
			
			if($request->user->belongsToGroup(array('admin'))){
				redirectToPage('admin-dashboard');
			}
			
			$smarty->assign("payments", R::find('payment'));
			$smarty->assign("applications", R::find('licence', 'status = null and user = ? ', array($request->user)));
			$smarty->assign("licences", R::find('licence', 'status = "approved" and user = ? ', array($request->user)));
			$smarty->assign("request", $request);
			$smarty->display('dashboard.tpl');
		}
		
		public function admin_dashboard($args){
			$request = $args["request"];
			userIsAdmin($request->user);
			
			$smarty->assign("request", $request);
			$smarty->display('admin_dashboard.tpl');
		}
		
		public function search($args){
			$request = $args["request"];
			global $smarty;
			checkLoggedIn($request->user);
			
			if($request->method == "GET" && isset($request->GET['q'])){
				$results = R::find("user", "username = ?", array($request->GET['q']));
				if (!count($results)){
					$results = R::find("driverslicence", "licence_number = ?", array($request->GET['q']));
				}
				$smarty->assign("results", $results);
			}
			
			$smarty->assign("request", $request);
			$smarty->display('search_results.tpl');
		}
	}
?>