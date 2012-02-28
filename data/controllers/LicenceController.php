<?php
	class LicenceController{
		public function choose_application_list($args){
			$request = $args["request"];
			global $router, $smarty;
			checkLoggedIn($request->user);
			
			$smarty->assign("request", $request);
			$smarty->display("licence/choose.tpl");
		}
		
		public function apply($args){
			$request = $args["request"];
			global $router, $smarty;
			checkLoggedIn($request->user);
			
			if (isset($args[":type"])){
				$type_ = $args[":type"];
				$type = str_replace('-', '_', $type_);
				$smarty->assign("type", $type);
				$smarty->assign("licence", R::dispense('licence'));
				$template_name = 'licence/'.$type.'_add.tpl';
			}
			
			if ($request->method == "POST"){
				$new_licence = R::graph($request->POST['licence']);
				$new_licence->type = $type_;
				
				if (R::store($new_licence)){
					$request->user->ownLicence[] = $new_licence;
					R::store($request->user);
					redirectToPage('licence-list', array(':type' => $type_ ));
				}
			}
			
			$smarty->assign("titles", array(
				'' => 'Title:', 'Mr' => 'Mr', 'Mrs' => 'Mrs', 'Miss' => 'Miss', 'Doctor' => 'Doctor', 'Professor' => 'Professor',
			));
			$smarty->assign("request", $request);
			$smarty->display($template_name);
		}
		
		public function notification_close($args) {
			$request = $args["request"];
			global $router, $smarty;
			
			if (isset($args[":type"])){
				$type_ = $args[":type"];
				$type = str_replace('-', '_', $type_);
			}
			
			if (isset($args[":id"])){
				$id = $args[":id"];
				$licence = R::load('licence', $id);
				if (!$licence->id){
					PageError::show('404',NULL,'Licence not found!', "Licence with Id: $id not found!");
					die();
				}else{
					if ($request->method == "GET"){
						$licence->latest_payment()->is_notified = true;
						R::store($licence);
						redirectToPage('licence-view', array(':type' => $type_, ':id' => $licence->id));
					}
				}
			}
		}
		
		public function action($args){
			$request = $args["request"];
			global $router, $smarty;
			
			if (isset($args[":type"])){
				$type_ = $args[":type"];
				$type = str_replace('-', '_', $type_);
				$smarty->assign("type", $type_);
					
				$template_name = 'licence/'.$type.'_add.tpl';
			}
			
			if (isset($args[":id"])){
				$id = $args[":id"];
				$licence = R::load('licence', $id);
				if (!$licence->id){
					PageError::show('404',NULL,'Licence not found!', "Licence with Id: $id not found!");
					die();
				}
			}
			
			if (isset($args[":action"])){
				$action = $args[":action"];
				if (in_array($action, array('approve', 'reject', 'revoke', 'issue', 'renew'))){
					if (in_array($action, array('approve', 'reject', 'revoke', 'issue'))){
						checkLoggedIn($request->user);
					}
					if ($request->method == "POST"){
						if ($action == 'renew'){
							$payment = R::graph($request->POST['payment']);
							$payment->time_of_transaction = time();
							$payment->message = ucwords(str_replace('-', ' ', $type_))." #$licence->id Renewed on ".date('d/m/Y @ H:i a', $payment->time_of_transaction);
							$payment->is_notified = false;
							
							$datetime = new DateTime();
							$datetime->modify("+ $payment->renewal_duration years");
							$payment->expiry_date = $datetime->getTimeStamp();
							
							if (R::store($payment)){
								$licence->ownPayment[] = $payment;
							}
						}else{
							if ($action == 'issue')$licence->is_active = true;
							if ($action == 'revoke')$licence->is_active = false;
							
							$licence->status = "$action".'d';
						}
						R::store($licence);
						redirectToPage('licence-view', array(':type' => $type_, ':id' => $licence->id));
					}else if ($request->method == "GET"){
						if ($action == 'renew'){
							if ($licence->paying_now_is_reasonable() || 
								(isset($request->REQUEST['force_pay']) && $request->REQUEST['force_pay'] == 1)){
								
								$smarty->assign("licence", $licence);
								$template_name = 'licence/renew.tpl';
							}else{
								$template_name = 'licence/force_pay.tpl';
							}
							$smarty->assign("request", $request);
							return $smarty->display($template_name);
						}
					}
				} 
			}else{
				PageError::show('404',NULL,'Unknown action', "Licence action not known.");
				die();
			}
			PageError::show('404',NULL,'Forbidden', "Action Forbidden");
		}

		public function view($args){
			$request = $args["request"];
			global $smarty;
			checkLoggedIn($request->user);
			
			if (isset($args[":type"])){
				$type_ = $args[":type"];
				$type = str_replace('-', '_', $type_);
				$template_name = 'licence/'.$type.'_detailview.tpl';
				
				if ($request->method == "GET"){
					$id = $args[":id"];
					$licence = R::load('licence', $id);
					$licence->type = $type_;
					if (!$licence->id){
						PageError::show('404',NULL,'Licence not found!', "Licence with Id: $id not found!");
						die();
					}
					$licence->is_expired();
	
					$smarty->assign("licence", $licence);
					$smarty->assign("type", $type);
					$smarty->assign("type_slug", $type_);
				}
			}
			$smarty->assign("request", $request);
			$smarty->display($template_name);
		}
		
		public function edit($args){
			$request = $args["request"];
			global $smarty;
			checkLoggedIn($request->user);
			
			if (isset($args[":type"])){
				$type_ = $args[":type"];
				$type = str_replace('-', '_', $type_);
				$template_name = 'licence/'.$type.'_add.tpl';
				$smarty->assign("type", $type_);
			}

			$id = $args[":id"];
			$licence = R::load('licence', $id);
			
			if (!$licence->id){
				PageError::show('404',NULL,'Licence not found!', "Licence with Id: $id not found!");
				die();
			}
			
			if ($request->method == "POST"){
				$edited_licence = R::graph($request->POST['licence']);
				$edited_licence->id = $id;
				$edited_licence->type = $type_;
				
				if (R::store($edited_licence)){
					redirectToPage('licence-list', array(':type' => $type_ ));
				}
			}else if ($request->method == "GET"){

				$smarty->assign("licence", $licence);
				$smarty->assign("titles", array(
					'' => 'Title:',
					'Mr.' => 'Mr',
					'Mrs.' => 'Mrs',
					'Miss' => 'Miss',
					'Doctor' => 'Doctor',
					'Professor' => 'Professor',
				));
			}
			
			$smarty->assign("request", $request);
			$smarty->display($template_name);
		}
		
		public function view_application_list($args){
			$args[":status"] = '';
			return $this->view_list($args);
		}
		
		public function view_list($args){
			$request = $args["request"];
			checkLoggedIn($request->user);
			global $smarty;
			$status = '';
			
			if (isset($args[":type"])){
				$type_ = $args[":type"];
				$type = str_replace('-', '_', $type_);
				$template_name = 'licence/'.$type.'_list.tpl';
				$smarty->assign("type", $type_);
			}
			
			if (isset($args[':status'])){
				$status = $args[':status'];
			}
			
			if ($request->method == "GET"){
				if ($status){
					$licences = R::find('licence', 'status = ?', array($status));
				}else{
					$licences = R::find('licence');
				}
				$smarty->assign("licences", $licences);
			}
			
			$smarty->assign("request", $request);
			$smarty->display($template_name);
		}
		
		public function delete($args){
			$request = $args["request"];
			checkLoggedIn($request->user);
			
			global $smarty;
			
			if (isset($args[":type"])){
				$type_ = $args[":type"];
				$type = str_replace('-', '_', $type_);
				$smarty->assign("type", $type_);
			}
			
			if ($request->method == "POST"){
				$id = $args[":id"];
				$licence = R::load('licence', $id);
				
				if (!$licence->id){
					PageError::show('404',NULL,'Licence not found!', "Licence with Id: $id not found!");
					die();
				}
				
				R::trash($licence);
				redirectToPage('licence-list', array(':type' => $type_));
			}else if ($request->method == "GET"){
				$smarty->assign("request", $request);
				$smarty->display('licence/confirm_delete.tpl');
			}
		}
	}
?>