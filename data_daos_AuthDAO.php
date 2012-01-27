<?php
require_once('data_orm_rb.php');

class AuthDAO{
	public $email = NULL;
	public $hash_pw = NULL;
	public $user_id = NULL;
	public $clean_username = NULL;
	public $display_username = NULL;
	
	public $contents = NULL;
	
	public function __construct() {
		global $db_host, $db_name, $db_user, $db_pass;
		R::setup("mysql:host=".$db_host.";dbname=".$db_name, $db_user, $db_pass);
	}
	
	public function createUser($user,$pass,$email)
	{
		global $emailActivation, $websiteUrl;
		
		//Used for display only
		$unclean_username = $user;
		$status = false;
		
		//Sanitize
		$clean_email = AuthController::sanitize($email);
		$clean_password = trim($pass);
		$clean_username = AuthController::sanitize($user);
		
		$errors = array();
		
		if($this->usernameExists($clean_username))
		{
			$errors["username_taken"] = true;
		} else if($this->emailExists($clean_email)) {
			$errors["email_taken"] = true;
		} else {
			//No problems have been found.
			$status = true;
		}

		//Prevent this function being called if there were construction errors
		if($status)
		{
			//Construct a secure hash for the plain text password
			$secure_pass = AuthController::generateHash($clean_password);
			//Construct a unique activation token
			$activation_token = $this->generateActivationToken();
			//Do we need to send out an activation email?
			if($emailActivation)
			{
				//User must activate their account first
				$user_active = 0;

				//Build the activation message
				$activation_message = AuthController::lang("ACTIVATION_MESSAGE",array($websiteUrl,$activation_token));
				//Define more if you want to build larger structures
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
				if(!$this->newTemplateMsg($message,$hooks))
				{
					$errors["mail_failure"] = true;
				}
				else
				{
					//Send the mail. Specify users email here and subject. 
					//SendMail can have a third parementer for message if you do not wish to build a template.
					
					if(!$this->sendMail($clean_email,"New User"))
					{
						$errors["mail_failure"] = true;
					}
				}
			}
			else
			{
				//Instant account activation
				$user_active = 1;
			}	

			if(!isset($errors["mail_failure"]))
			{
					//Insert the user into the database providing no errors have been found.
					$user = R::dispense("user");
					$user->Username = $unclean_username;
					$user->Username_Clean = $clean_username;
					$user->Password = $secure_pass; 
					$user->Email = $clean_email;
					$user->ActivationToken = $activation_token;
					$user->LastActivationRequest = time();
					$user->LostPasswordRequest = '0';
					$user->Active = $user_active;
					
					$group = R::load("group", 1);
					$user->ownGroup[] = $group;
					
					$user->SignUpDate = time();
					$user->LastSignIn = '0';
					
					R::store($group);
					R::store($user);
			}
		}
		return $errors;
	}

	//Function used for replacing hooks in our templates
	public function newTemplateMsg($content,$additionalHooks)
	{
		global $mail_templates_dir,$debug_mode;
		$this->contents = $content;

		//Check to see we can access the file / it has some contents
		if(!$this->contents || empty($this->contents))
		{
			if($debug_mode)
			{
				if(!$this->contents)
				{ 
					echo AuthController::lang("MAIL_TEMPLATE_DIRECTORY_ERROR",array(getenv("DOCUMENT_ROOT")));
					die(); 
				}
				else if(empty($this->contents))
				{
					echo AuthController::lang("MAIL_TEMPLATE_FILE_EMPTY"); 
					
					die();
				}
			}
		
			return false;
		}
		else
		{
			//Replace default hooks
			$this->contents = AuthController::replaceDefaultHook($this->contents);
		
			//Replace defined / custom hooks
			$this->contents = str_replace($additionalHooks["searchStrs"],$additionalHooks["subjectStrs"],$this->contents);

			//Do we need to include an email footer?
			//Try and find the #INC-FOOTER hook
			if(strpos($this->contents,"#INC-FOOTER#") !== FALSE)
			{
				$footer = 
				"
				- Regards
				UserCake.com
				";
				if($footer && !empty($footer)) $this->contents .= AuthController::replaceDefaultHook($footer); 
				$this->contents = str_replace("#INC-FOOTER#","",$this->contents);
			}
			
			return true;
		}
	}
	
	public function sendMail($email,$subject,$msg = NULL)
	{
		global $websiteName,$emailAddress;
		
		$header = "MIME-Version: 1.0\r\n";
		$header .= "Content-type: text/plain; charset=iso-8859-1\r\n";
		$header .= "From: ". $websiteName . " <" . $emailAddress . ">\r\n";
		
		$message = "";
		
		//Check to see if we sending a template email.
		if($msg == NULL)
			$msg = $this->contents; 

		$message .= $msg;
		$message = wordwrap($message, 70);

		//if (mail($email,$subject,$message,$header)){
			return true;
		//}
		//return false;
	}


	
	//Simple function to update the last sign in of a user
	public function updateLastSignIn()
	{
		$user = R::load("user", $this->user_id);
		$user->LastSignIn = time();
		return $user;
	}
	
	//Return the timestamp when the user registered
	public function signupTimeStamp()
	{
		$user = R::load("user", $this->user_id);
		return $user->SignUpDate;
	}
	
	//Update a users password
	public function updatePassword($pass)
	{
		$secure_pass = generateHash($pass);
		$this->hash_pw = $secure_pass;
		
		$user = R::load("user", $this->user_id);
		$user->Password = $pass;
		
		R::store($user);
		return $user;
	}
	
	//Update a users email
	public function updateEmail($email)
	{
		$user = R::load("user", $this->user_id);
		$user->email = $email;
		
		R::store($user);
		return $user;
	}
	
	//Fetch all user group information
	public function groupID()
	{
		$user = R::load("user", $this->user_id);
		return $user->ownGroup;
	}
	
	//Is a user member of a group
	public function isGroupMember($id)
	{
		$user = R::load("user", $this->user_id);
		$groups = R::related($user, "group");
		
		$group = R::load("user", $id);
		
		return in_array($group, $groups);
	}
	
	function usernameExists($username)
	{
		$user = R::findOne("user", "Username_Clean = ?", array($username));
		return $user;
	}
	
	function emailExists($email)
	{
		$user = R::findOne("user", "Email = ?", array($email));
		return $user;
	}
	
	//Function lostpass var if set will check for an active account.
	function validateActivationToken($token,$lostpass=NULL)
	{
		if($lostpass == NULL) 
		{
			$user = R::findOne("user", "Active = ? AND ActivationToken = ?", array(0, $token));
		}
		else 
		{
			$user = R::findOne("user", "Active = ? AND LostPasswordRequest = ? AND ActivationToken = ?", array(1, 0, $token));
		}
		if ($user){
			return $user->ActivationToken;
		}else{
			return NULL;
		}
	}
	
	
	function setUserActive($token)
	{
		$user = R::findOne("user", "ActivationToken = ?", array($token));
		$user->Active = 1;
		
		return R::store($user);
	}
	
	//You can use a activation token to also get user details here
	function fetchUserDetails($username=NULL,$token=NULL)
	{
		if($username!=NULL) 
		{
			$user = R::findOne("user", "Username_Clean = ?", array($username));  
		} else {
			$user = R::findOne("user", "ActivationToken = ?", array($token));
		}
		 
		return $user;
	}
	
	function flagLostPasswordRequest($username,$value)
	{
		$user = R::findOne("user", "Username_Clean = ?", array($username));
		$user->LostPasswordRequest = $value;
		
		return R::store($user);
	}
	
	function updatePasswordFromToken($pass,$token)
	{
		$new_activation_token = generateActivationToken();
		
		$user = R::findOne("user", "ActivationToken = ?", array($token));
		$user->Password = $pass;
		$user->ActivationToken = $new_activation_token;
		
		return R::store($user);
	}
	
	function emailUsernameLinked($email,$username)
	{
		$user = R::findOne("user", "Username_Clean = ? AND Email = ?", array($username, $email));
		return $user;
	}
	
	function isUserLoggedIn()
	{
		global $loggedInUser;
		if($loggedInUser == NULL)
		{
			return false;
		}
		else
		{
			//Query the database to ensure they haven't been removed or possibly banned?
			if ($user->Password == $loggedInUser->hash_pw && $user->Active == 1){
				return true;
			}
			else
			{
				//No result returned kill the user session, user banned or deleted
				$loggedInUser->userLogOut();
				return false;
			}
		}
	}
	
	//Generate an activation key 
	function generateActivationToken()
	{
		$gen;
		do {
			$gen = md5(uniqid(mt_rand(), false));
		}
		while($this->validateActivationToken($gen));
		return $gen;
	}
	
	function updateLastActivationRequest($new_activation_token,$username,$email)
	{
		$user = R::findOne("user", "Username_Clean = ? AND Email = ?", array($username, $email));
		$user->ActivationToken = $new_activation_token;
		$user->LastActivationRequest = time();
		
		return R::store($user);
	}
}
?>