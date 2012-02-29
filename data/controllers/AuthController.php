<?php
	class AuthController{
		public function client_actions($args) {
			if(isset($args[":action"])){
				if ($args[":action"] == "register"){
					$this->register($args);
				}
			}
		}
		
		public function index($args){
			global $smarty, $BASE_URL;
			
			$request = $args["request"];
			if($request->user->isUserLoggedIn()) {
				redirectToPage('user-dashboard'); 
				die(); 
			}
			
			$smarty->assign("request", $request);
			$smarty->display('auth/index.tpl');
		}
		
		public function activate_account($args){
			global $smarty, $BASE_URL;
			
			$request = $args["request"];
			if($request->user->isUserLoggedIn()) {
				redirectToPage('user-dashboard'); 
				die(); 
			}
			$errors = array();
			
			//Get token param
			if(isset($request->GET["token"])) {
				$token = $request->GET["token"];
				
				if(!isset($token)) {
					$errors[] = lang("FORGOTPASS_INVALID_TOKEN");
				} else if(!$dao->validateActivationToken($token)){
					 //Check for a valid token. Must exist and active must be = 0 {
					$errors[] = "Token does not exist / Account is already activated";
				} else {
					//Activate the users account
					if(!setUserActive($token)) {
						$errors[] = lang("SQL_ERROR");
					}
				}
			} else {
				$errors[] = lang("FORGOTPASS_INVALID_TOKEN");
			}
			
			$smarty->assign("errors", errorBlock($errors));
			$smarty->assign("ACCOUNT_NOW_ACTIVE", lang("ACCOUNT_NOW_ACTIVE"));
			$smarty->assign("ACCOUNT_NOW_ACTIVE", lang("ACCOUNT_NOW_ACTIVE"));
			$smarty->assign("request", $request);
			
			$smarty->display('auth/activate-account.tpl');
		}
		
		public function change_password($args){
			global $smarty, $BASE_URL;
			
			$request = $args["request"];
			if(!$request->user->isUserLoggedIn()) {
				redirectToPage('auth-login');
				die(); 
			}
			
			if($request->method == "POST"){
				$dao = new AuthDao();
				$errors = array();
				
				$password = $request->POST["password"];
				$password_new = $request->POST["passwordc"];
				$password_confirm = $request->POST["passwordcheck"];
			
				//Perform some validation
				//Feel free to edit / change as required
				if(trim($password) == "") {
					$errors[] = lang("ACCOUNT_SPECIFY_PASSWORD");
				} else if(trim($password_new) == "") {
					$errors[] = lang("ACCOUNT_SPECIFY_NEW_PASSWORD");
				} else if(minMaxRange(8,50,$password_new)) {
					$errors[] = lang("ACCOUNT_NEW_PASSWORD_LENGTH",array(8,50));
				} else if($password_new != $password_confirm) {
					$errors[] = lang("ACCOUNT_PASS_MISMATCH");
				}
				
				//End data validation
				if(empty($errors)) {
					//Confirm the hash's match before updating a users password
					$entered_pass = generateHash($password,$request->user->password);
					//Also prevent updating if someone attempts to update with the same password
					$entered_pass_new = generateHash($password_new,$request->user->password);
				
					if($entered_pass != $request->user->password)
					{
						//No match
						$errors[] = lang("ACCOUNT_PASSWORD_INVALID");
					} else if($entered_pass_new == $request->user->password) {
						//Don't update, this fool is trying to update with the same password ¬¬
						$errors[] = lang("NOTHING_TO_UPDATE");
					} else {
						//This function will create the new hash and update the Password property.
						$dao->updatePassword($request->user, $password_new);
						$smarty->assign("success_message", lang("ACCOUNT_DETAILS_UPDATED"));
					}
				}
				$smarty->assign("errors", errorBlock($errors));
			}
			
			$smarty->assign("request", $request);
			$smarty->display('auth/change-password.tpl');
		}
		
		public function account($args){
			global $smarty, $BASE_URL;
			
			$request = $args["request"];
			if(!$request->user->isUserLoggedIn()) {
				redirectToPage('auth-login'); 
				die(); 
			}
			
			$dao = new AuthDao();
			
			$smarty->assign("request", $request);
			$smarty->assign("signup_date", date("l \\t\h\e jS Y", $request->user->signupTimeStamp()));
			$smarty->assign("group", $dao->group($request->user));
			$smarty->display('auth/account.tpl');
		}
		
		public function register($args){
			$request = $args["request"];
			if($request->user->isUserLoggedIn()) {
				redirectToPage('user-dashboard'); 
				die(); 
			}
			
			global $smarty, $EMAIL_ACTIVATION, $WEBSITE_URL, $BASE_URL;
			$dao = new AuthDao();
			
			if($request->method == "POST") {
				$email = trim($request->POST["email"]);
				$username = trim($request->POST["username"]);
				$fullname = trim($request->POST["fullname"]);
				$password = trim($request->POST["password"]);
				$confirm_pass = trim($request->POST["passwordc"]);
				$errors = array();
				
				//Perform some validation
				//Feel free to edit / change as required
				if(minMaxRange(4,25,$username)) {
					$errors[] = lang("ACCOUNT_USER_CHAR_LIMIT",array(4,25));
				}
				if(minMaxRange(2,50,$password) && minMaxRange(2,50,$confirm_pass)) {
					$errors[] = lang("ACCOUNT_PASS_CHAR_LIMIT",array(8,50));
				} else if($password != $confirm_pass) {
					$errors[] = lang("ACCOUNT_PASS_MISMATCH");
				}
				
				if(!isValidEmail($email)) {
					$errors[] = lang("ACCOUNT_INVALID_EMAIL");
				}
				//End data validation
				if(empty($errors)) {
					//Used for display only
					$unclean_username = $username;
					$status = false;
					
					//Sanitize
					$clean_email = sanitize($email);
					$clean_password = trim($password);
					$clean_username = sanitize($username);
					
					if($dao->usernameExists($clean_username)) {
						$errors["username_taken"] = true;
					} else if($dao->emailExists($clean_email)) {
						$errors["email_taken"] = true;
					} else {
						$status = true;
					}
			
					if($status) {
						$secure_pass = generateHash($clean_password);
						$activation_token = $dao->generateActivationToken();
						if($EMAIL_ACTIVATION) {
							$user_active = 0;
							$activation_message = lang("ACTIVATION_MESSAGE",array($WEBSITE_URL,$activation_token));
							$hooks = array(
								"searchStrs" => array("#ACTIVATION-MESSAGE","#ACTIVATION-KEY","#USERNAME#"),
								"subjectStrs" => array($activation_message,$activation_token,$unclean_username)
							);
							
							$message = "
							Hello #USERNAME#
			
							Thank you for joining our website #WEBSITENAME#
							
							#ACTIVATION-MESSAGE
							
							#INC-FOOTER#
							";
							
							/* Build the template - Optional, you can just use the sendMail function 
							Instead to pass a message. */
							if(!newTemplateMsg($message,$hooks)) {
								$errors["mail_failure"] = true;
							} else {
								if(!sendMail($clean_email,"New User")) {
									$errors["mail_failure"] = true;
								}
							}
						} else {
							$user_active = 1;
						}	
			
						if(!isset($errors["mail_failure"])) {
							$user = $dao->createUser(
								$unclean_username, 
								$clean_username,
								$fullname, 
								$secure_pass, 
								$clean_email, 
								$activation_token, 
								time(), 
								'0', 
								$user_active,
								1
							);
							$smarty->assign("message", 
								lang(
									$EMAIL_ACTIVATION?
										"ACCOUNT_REGISTRATION_COMPLETE_TYPE1" 
									:
										"ACCOUNT_REGISTRATION_COMPLETE_TYPE2"
								)
							);
							redirectToPage('auth-login');
						}
					}

					if ($errors){
						if(isset($errors["username_taken"])) $errors[] = lang("ACCOUNT_USERNAME_IN_USE",array($username));
						if(isset($errors["email_taken"])) 	 $errors[] = lang("ACCOUNT_EMAIL_IN_USE",array($email));		
						if(isset($errors["mail_failure"])) 	 $errors[] = lang("MAIL_ERROR");
					}
				}
				$smarty->assign("errors", errorBlock($errors));
			}
			
			$smarty->assign("request", $request);
			$smarty->display('auth/register.tpl');
		}
		
		public function resend_activation($args){
			global $smarty, $EMAIL_ACTIVATION, $BASE_URL;
			
			$request = $args["request"];
			if($request->user->isUserLoggedIn()) {
				redirectToPage('user-dashboard'); 
				die(); 
			}
			
			$dao = new AuthDao();
			
			if (!$EMAIL_ACTIVATION){
				$smarty->assign("feature_disabled", lang("FEATURE_DISABLED"));
			}

			if($request->method == "POST" && $EMAIL_ACTIVATION) {
				$email 		= $request->POST["email"];
				$username 	= $request->POST["username"];
				
				$errors = array();

				if(trim($email) == "") {
					$errors[] = lang("ACCOUNT_SPECIFY_EMAIL");
				} else if(!isValidEmail($email) || !$dao->emailExists($email)) {
					$errors[] = lang("ACCOUNT_INVALID_EMAIL");
				}
				
				if(trim($username) == "") {
					$errors[] =  lang("ACCOUNT_SPECIFY_USERNAME");
				} else if(!$dao->usernameExists($username)) {
					$errors[] = lang("ACCOUNT_INVALID_USERNAME");
				}
			
				if(count($errors) == 0) {
					if(!$dao->emailUsernameLinked($email,$username)) {
						$errors[] = lang("ACCOUNT_USER_OR_EMAIL_INVALID");
					} else {
						$userdetails = $dao->fetchUserDetails($username);
					
						if($userdetails->is_active) {
							$errors[] = lang("ACCOUNT_ALREADY_ACTIVE");
						} else {
							$hours_diff = round((time()-$userdetails->last_activation_request) / (3600*$resend_activation_threshold),0);
			
							if($resend_activation_threshold!=0 && $hours_diff <= $resend_activation_threshold) {
								$errors[] = lang("ACCOUNT_LINK_ALREADY_SENT",array($resend_activation_threshold));
							} else {
								$new_activation_token = generateActivationToken();
								
								if(!updateLastActivationRequest($new_activation_token,$username,$email)) {
									$errors[] = lang("SQL_ERROR");
								} else {
									$mail = new userCakeMail();
									
									$activation_url = $WEBSITE_URL."activate-account.php?token=".$new_activation_token;
								
									$hooks = array(
										"searchStrs" => array("#ACTIVATION-URL","#USERNAME#"),
										"subjectStrs" => array($activation_url,$userdetails->username)
									);
									
									if(!$mail->newTemplateMsg("resend-activation.txt",$hooks)) {
										$errors[] = lang("MAIL_TEMPLATE_BUILD_ERROR");
									} else {
										if(!$mail->sendMail($userdetails->email,"Activate your UserCake Account")) {
											$errors[] = lang("MAIL_ERROR");
										} else {
											$success_message = lang("ACCOUNT_NEW_ACTIVATION_SENT");
										}
									}
								}
							}
						}
					}
				}
			}
			
			if (isset($success_message)){
				$smarty->assign("success_message", $success_message);
			}
			
			$smarty->assign("request", $request);
			$smarty->display('auth/resend-activation.tpl');
		}
		
		public function update_email_address($args){
			global $smarty, $BASE_URL;
			
			$request = $args["request"];
			if(!$request->user->isUserLoggedIn()) {
				redirectToPage('auth-login');
				die(); 
			}
			$dao = new AuthDao();
			
			//Forms posted
			if($request->POST){
				$errors = array();
				$email = $request->POST["email"];
		
				//Perform some validation
				//Feel free to edit / change as required
				if(trim($email) == "") {
					$errors[] = lang("ACCOUNT_SPECIFY_EMAIL");
				} else if(!isValidEmail($email)) {
					$errors[] = lang("ACCOUNT_INVALID_EMAIL");
				} else if($email == $request->user->email) {
					$errors[] = lang("NOTHING_TO_UPDATE");
				} else if($dao->emailExists($email)) {
					$errors[] = lang("ACCOUNT_EMAIL_TAKEN");	
				}
				
				//End data validation
				if(count($errors) == 0) {
					$dao->updateEmail($request->user, $email);
					$smarty->assign("success_message", lang("ACCOUNT_DETAILS_UPDATED"));
				}
				$smarty->assign("errors", errorBlock($errors));
			}
			
			$smarty->assign("request", $request);
			$smarty->display('auth/update-email-address.tpl');
		}
		
		public function forgot_password($args){
			global $smarty, $WEBSITE_URL;
			
			$request = $args["request"];
			$dao = new AuthDao();
			$errors = array();
			
			if($request->method == "POST") {
				$email = $_POST["email"];
				$username = $_POST["username"];
				
				//Perform some validation
				//Feel free to edit / change as required
				
				if(trim($email) == "") {
					$errors[] = lang("ACCOUNT_SPECIFY_EMAIL");
				} else if(!isValidEmail($email) || !$dao->emailExists($email)) {
					$errors[] = lang("ACCOUNT_INVALID_EMAIL");
				}
				
				if(trim($username) == "") {
					$errors[] = lang("ACCOUNT_SPECIFY_USERNAME");
				} else if(!$dao->usernameExists($username)) {
					$errors[] = lang("ACCOUNT_INVALID_USERNAME");
				}
				
				if(empty($errors)) {
					//Check that the username / email are associated to the same account
					if(!$dao->emailUsernameLinked($email,$username)) {
						$errors[] =  lang("ACCOUNT_USER_OR_EMAIL_INVALID");
					} else {
						//Check if the user has any outstanding lost password requests
						$user = $dao->fetchUserDetails($username);
						if($user->lost_password_request) {
							$errors[] = lang("FORGOTPASS_REQUEST_EXISTS");
						} else {
							//We use the activation token again for the url key it gets regenerated everytime it's used.
							$confirm_url = lang("CONFIRM")."\n".$WEBSITE_URL.$BASE_URL."/auth/forgot-password/?confirm=".$user->activation_token;
							$deny_url = ("DENY")."\n".$WEBSITE_URL.$BASE_URL."/auth/forgot-password/?deny=".$user->activation_token;
							
							//Setup our custom hooks
							$hooks = array(
								"searchStrs" => array("#CONFIRM-URL#","#DENY-URL#","#USERNAME#"),
								"subjectStrs" => array($confirm_url,$deny_url,$user->username)
							);
							
							if(!newTemplateMsg("lost-password-request.txt",$hooks)) {
								$errors[] = lang("MAIL_TEMPLATE_BUILD_ERROR");
							} else {
								if(!sendMail($user->email,"Lost password request")) {
									$errors[] = lang("MAIL_ERROR");
								} else {
									//Update the DB to show this account has an outstanding request
									$dao->flagLostPasswordRequest($username,1);
									$success_message = lang("FORGOTPASS_REQUEST_SUCCESS");
								}
							}
						}
					}
				}
			}else if ($request->method == "GET"){
				if(!empty($_GET["deny"])) {
						$token = trim($_GET["deny"]);
						
						if($token == "" || !validateActivationToken($token,TRUE)) {
							$errors[] = lang("FORGOTPASS_INVALID_TOKEN");
						} else {
							$user = fetchUserDetails(NULL,$token);
							flagLostPasswordRequest($user->username_clean,0);
							$success_message = lang("FORGOTPASS_REQUEST_CANNED");
						}
				}
				if(!empty($request->GET["confirm"])) {
					$token = trim($request->GET["confirm"]);
					if($token == "" || !validateActivationToken($token,TRUE)) {
						$errors[] = lang("FORGOTPASS_INVALID_TOKEN");
					} else {
						$rand_pass = getUniqueCode(15);
						$secure_pass = generateHash($rand_pass);
						$user = fetchUserDetails(NULL,$token);
										
						$hooks = array(
								"searchStrs" => array("#GENERATED-PASS#","#USERNAME#"),
								"subjectStrs" => array($rand_pass,$user->username)
						);
									
						if(!newTemplateMsg("your-lost-password.txt",$hooks)) {
							$errors[] = lang("MAIL_TEMPLATE_BUILD_ERROR");
						} else {
							if(!sendMail($user->email,"Your new password")) {
									$errors[] = lang("MAIL_ERROR");
							} else {
								if(!updatePasswordFromToken($secure_pass,$token)) {
									$errors[] = lang("SQL_ERROR");
								} else {
									//Might be wise if this had a time delay to prevent a flood of requests.
									flagLostPasswordRequest($user->username_clean,0);
									$success_message  = lang("FORGOTPASS_NEW_PASS_EMAIL");
								}
							}
						}
					}
				}
			}
			if (isset($success_message)){
				$smarty->assign("success_message", $success_message);
			}
			$smarty->assign("errors", errorBlock($errors));
			$smarty->assign("request", $request);
			
			$smarty->display('auth/forgot-password.tpl');
		}
		
		public function login($args){
			global $smarty, $BASE_URL;
			$request = $args["request"];
			
			if($request->user->isUserLoggedIn()) {
				redirectToPage('user-dashboard'); 
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
								redirectToPage('user-dashboard');
								die();
							}
						}
					}
				}
				$smarty->assign("errors", errorBlock($errors));
			}
			$smarty->assign("request", $request);
			$smarty->display('auth/login.tpl');
		}
		
		public function logout($args){
			global $BASE_URL;
			
			$request = $args["request"];
			if ($request->method == "GET"){
				$dao = new AuthDAO();
				//Log the user out
				$request->user->userLogOut();
				if(!empty($WEBSITE_URL)) 
				{
					$add_http = "";
					if(strpos($WEBSITE_URL,"http://") === false){
						$add_http = "http://";
					}
					header("Location: ".$add_http.$WEBSITE_URL);
					die();
				}
				else
				{
					redirectToPage('auth-login');
					die();
				}
			}
		}
	}
?> 