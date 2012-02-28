<?php
	class UserController{
		public function add($args){
			$request = $args["request"];

			global $router, $smarty;
			userIsAdmin($request->user);
			
			$dao = new AuthDAO();
			
			if ($request->method == "POST"){
				$new_user = R::graph($request->POST['user']);
				$new_user->password = generateHash(sanitize($new_user->password));
				$new_user->is_active = ($new_user->is_active == "on");
				
				if($dao->usernameExists($new_user->username)) {
					echo "username exists";
				} else if($dao->emailExists($new_user->email)) {
					echo "email taken";
				}
				
				if(isset($request->POST["groups"])){
					$group_ids = $request->POST["groups"];
					if(count($group_ids)){
						R::clearRelations($edited_user, 'group');
					}
					foreach ($group_ids as $group_id){
						$group = R::load('group', $group_id);
						if($group->id){
							R::associate($new_user, $group);
						}
					}
				}
				
				$_id = R::store($new_user);
				redirectToPage('user-list');
			}
			
			$smarty->assign("request", $request);
			$smarty->assign("user", R::dispense('user'));
			$smarty->assign("groups", R::find('group'));
			$smarty->display('auth/users/edit.tpl');
		}
		
		public function edit($args){
			$request = $args["request"];
			global $smarty;
			userIsAdmin($request->user);
			
			$id = $args[":id"];
			$user = R::load("user", $id);
			
			if ($request->method == "POST"){
				if($user->id){
					$edited_user = R::graph($request->POST['user']);
					$edited_user->id = $id;
					
					if(isset($request->POST["groups"])){
						$group_ids = $request->POST["groups"];
						if(count($group_ids)){
							R::clearRelations($edited_user, 'group');
						}
						foreach ($group_ids as $group_id){
							$group = R::load('group', $group_id);
							if($group->id){
								R::associate($edited_user, $group);
							}
						}
					}
					
					$_id = R::store($edited_user);
					redirectToPage('user-list');
				}
			}else if ($request->method == "GET"){
				if ($user->id){
					$smarty->assign("user", $user);
					$smarty->assign("related_groups", R::related($user, 'group'));
				}else{
					PageError::show('404',NULL,'User not found!', "User with Id: $id not found!");
				}
			}
			
			$smarty->assign("request", $request);
			$smarty->assign("groups", R::find('group'));
			$smarty->display('auth/users/edit.tpl');
		}
		
		public function view($args){
			$request = $args["request"];
			userIsAdmin($request->user);
			global $smarty;
			
			if ($request->method == "GET"){
				$id = $args[":id"];
				$user = R::load("user", $id);
				
				if($user->id){ 
					$smarty->assign("user", $user);
				}else{
					PageError::show('404',NULL,'User not found!', "User with Id: $id not found!");
					die();
				}
			}
			
			$smarty->assign("request", $request);
			$smarty->display('auth/users/detailview.tpl');
		}
		
		public function view_list($args){
			$request = $args["request"];
			userIsAdmin($request->user);
			global $smarty;
			
			if ($request->method == "GET"){
				$smarty->assign("users", R::find("user"));	
			}
			
			$smarty->assign("request", $request);
			$smarty->display('auth/users/list.tpl');
		}
	}
?>