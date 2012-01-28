<?php
require_once('/../orm/rb.php');

class AuthDAO{
	public $contents = NULL;
	
	public function __construct() {
		
	}
	
	public function createUser($unclean_username, $clean_username, $secure_pass, $clean_email,
		$activation_token, $lastActivationRequest, $lostPasswordRequest,$user_active, $group_id){
		
		$user = R::dispense("user");
		$user->Username = $unclean_username;
		$user->Username_Clean = $clean_username;
		$user->Password = $secure_pass; 
		$user->Email = $clean_email;
		$user->ActivationToken = $activation_token;
		$user->LastActivationRequest = $lastActivationRequest;
		$user->LostPasswordRequest = $lostPasswordRequest;
		$user->Active = $user_active;
		
		$group = R::load("group", $group_id);
		if ($group->id){
			R::associate($group, $user);
			R::store($group);
		}
		
		$user->SignUpDate = time();
		$user->LastSignIn = '0';
		
		R::store($user);
	}
	
	//Simple function to update the last sign in of a user
	public function updateLastSignIn($user)
	{
		$user->LastSignIn = time();
		R::store($user);
		return $user;
	}
	
	//return $user->SignUpDate;
	
	//Update a users password
	public function updatePassword($loggedInUser, $pass)
	{
		$secure_pass = AuthController::generateHash($pass);
		$user = $loggedInUser->user;
		
		if ($user->id){
			$user->Password = $secure_pass;
			R::store($user);
		}
		
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
		if ($user->id){
			return R::related($user);
		}
		return $user;
	}
	
	//Is a user member of a group
	public function isGroupMember($user, $id)
	{
		$user = R::load("user", $this->user_id);
		if($user->id){
			$groups = R::related($user, "group");
			$group = R::load("group", $id);
			return in_array($group, $groups);
		}
		return false;
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
		if ($user && $user->id){
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
		return R::findOne("user", "Username_Clean = ? AND Email = ?", array($username, $email));
	}
	
	public function loginUser($user){
		$loggedInUser = R::dispense("loggedinuser");
		
		$loggedInUser->email = $user->Email;
		$loggedInUser->user = $user;
		$loggedInUser->hash_pw = $user->Password;
		$loggedInUser->display_username = $user->Username;
		$loggedInUser->clean_username = $user->Username_Clean;
		
		R::store($loggedInUser);
		
		//Update last sign in
		$this->updateLastSignIn($user);
		$_SESSION["authUser"] = $loggedInUser;
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
			$user = R::findOne("user", "Password = ? and id = ?", array($loggedInUser->hash_pw, $loggedInUser->user->id));
			//Query the database to ensure they haven't been removed or possibly banned?
			if ($user->id){
				return true;
			}
			else
			{
				//No result returned kill the user session, user banned or deleted
				$this->userLogOut();
				return false;
			}
		}
	}
	
	function destorySession($name)
	{
		if(isset($_SESSION[$name]))
		{
			$_SESSION[$name] = NULL;
			unset($_SESSION[$name]);
		}
	}
	
	function userLogOut()
	{
		$this->destorySession("authUser");
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