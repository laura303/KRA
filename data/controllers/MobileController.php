<?php
	class MobileController{
		public function home($args){
			$request = $args["request"];
			global $router, $smarty;
			
			$smarty->assign("request", $request);
			$smarty->assign("licences", R::find('licence'));
			return $smarty->display("mobile/index.html");
		}
		
		public function view($args){
			$request = $args["request"];
			global $smarty;
			checkLoggedIn($request->user);
			
			if ($request->method == "GET"){
				$id = $args[":id"];
				$licence = R::load('licence', $id);
				if (!$licence->id){
					PageError::show('404',NULL,'Licence not found!', "Licence with Id: $id not found!");
					die();
				}
				$smarty->assign("licence", $licence);
			}
			$smarty->assign("request", $request);
			$smarty->display("mobile/licence_detail.tpl");
		}
		
		public function renew($args){
			$request = $args["request"];
			global $router, $smarty;
			
			if (isset($args[":id"])){
				$id = $args[":id"];
				$licence = R::load('licence', $id);
				if (!$licence->id){
					PageError::show('404',NULL,'Licence not found!', "Licence with Id: $id not found!");
					die();
				}
			}
			
			if ($request->method == "POST"){
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
				R::store($licence);
				redirectToPage('mobile-home', array(':type' => $type_, ':id' => $licence->id));
			}else if ($request->method == "GET"){
				if ($licence->paying_now_is_reasonable() || 
					(isset($request->REQUEST['force_pay']) && $request->REQUEST['force_pay'] == 1)){
					
					$smarty->assign("licence", $licence);
					$template_name = 'mobile/renew.tpl';
				}else{
					$template_name = 'mobile/force_pay.tpl';
				}
			}
			$smarty->assign("request", $request);
			return $smarty->display($template_name);
		}
		
		public function login($args){
			global $smarty, $BASE_URL;
			$request = $args["request"];
			
			if($request->user->isUserLoggedIn()) {
				redirectToPage('mobile-home'); 
				die();
			}
			
			if($request->method == "POST"){
				$errors = array();
				$dao = new AuthDao();
				$username = trim($request->POST["username"]);
				$password = trim($request->POST["password"]);
			
				//Perform some validation
				//Feel free to edit / change as required
				if($username == ""){ $errors[] = lang("ACCOUNT_SPECIFY_USERNAME"); }
				if($password == ""){ $errors[] = lang("ACCOUNT_SPECIFY_PASSWORD"); }
				
				//End data validation
				if(count($errors) == 0)
				{
					//A security note here, never tell the user which credential was incorrect
					if(!$dao->usernameExists($username)){
						$errors[] = lang("ACCOUNT_USER_OR_PASS_INVALID");
					} else {
						$user = $dao->fetchUserDetails($username);
						
						//See if the user's account is activation
						if(!$user->is_active){
							$errors[] = lang("ACCOUNT_INACTIVE");
						} else {
							//Hash the password and use the salt from the database to compare the password.
							$entered_pass = generateHash($password,$user->password);
							
							if($entered_pass != $user->password) {
								//Again, we know the password is at fault here, but lets not give away the combination incase of someone bruteforcing
								$errors[] = lang("ACCOUNT_USER_OR_PASS_INVALID");
							} else {
								//Passwords match! we're good to go'
								//Construct a new logged in user object
								//Transfer some db data to the session object
								$dao->loginUser($user);
								//Redirect to user account page
								redirectToPage('mobile-home');
								die();
							}
						}
					}
				}
				$smarty->assign("errors", errorBlock($errors));
			}
			$smarty->assign("request", $request);
			$smarty->display('mobile/login.tpl');
		}
	}
?>