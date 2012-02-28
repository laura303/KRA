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
	}
?>