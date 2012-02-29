<?php
class AuthDAO{
	public function createUser($unclean_username, $clean_username, $fullname, $secure_pass, $clean_email,
		$activation_token, $lastActivationRequest, $lostPasswordRequest,$user_active, $group_id){
		
		$user = R::dispense("user");
		$user->username = $clean_username;
		$user->password = $secure_pass; 
		$user->email = $clean_email;
		$user->fullname = $fullname;
		$user->activation_token = $activation_token;
		$user->last_activation_request = $lastActivationRequest;
		$user->lost_password_request = $lostPasswordRequest;
		$user->is_active = $user_active;
		$user->sign_up_date = time();
		$user->last_sign_in = '0';
		
		$group = R::load("group", $group_id);
		if ($group->id){
			R::associate($group, $user);
			R::store($group);
		}
		
		return R::store($user);
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
	public function updatePassword($user, $pass)
	{
		$secure_pass = generateHash($pass);
		
		if ($user->id){
			$user->password = $secure_pass;
			R::store($user);
		}
		
		return $user;
	}
	
	//Update a users email
	public function updateEmail($user, $email)
	{
		$user->email = $email;
		R::store($user);
		return $user;
	}
	
	//Fetch all user group information
	public function group($user)
	{
		if ($user->id){
			$group = R::related($user, "group");
			if (isset($group[key($group)])){
				return $group[key($group)];
			}
			return $group;
		}
		return null;
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
		$user = R::findOne("user", "username = ?", array($username));
		return $user;
	}
	
	function emailExists($email)
	{
		$user = R::findOne("user", "email = ?", array($email));
		if ($user && $user->id){
			return true;
		}
		return false;
	}
	
	//Function lostpass var if set will check for an active account.
	function validateActivationToken($token,$lostpass=NULL)
	{
		if($lostpass == NULL) 
		{
			$user = R::findOne("user", "active = ? AND activation_token = ?", array(0, $token));
		}
		else
		{
			$user = R::findOne(
				"user",
				"is_active = ? AND lost_password_request = ? AND activation_token = ?", 
				array(1, 0, $token)
			);
		}
		if ($user && $user->id){
			return $user->ActivationToken;
		}else{
			return NULL;
		}
	}
	
	function setUserActive($token)
	{
		$user = R::findOne("user", "activation_token = ?", array($token));
		$user->is_active = 1;
		
		return R::store($user);
	}
	
	//You can use a activation token to also get user details here
	function fetchUserDetails($username=NULL,$token=NULL)
	{
		if($username!=NULL) 
		{
			$user = R::findOne("user", "username = ?", array($username));  
		} else {
			$user = R::findOne("user", "activation_token = ?", array($token));
		}
		 
		return $user;
	}
	
	function flagLostPasswordRequest($username,$value)
	{
		$user = R::findOne("user", "username = ?", array($username));
		$user->LostPasswordRequest = $value;
		
		return R::store($user);
	}
	
	function updatePasswordFromToken($pass,$token)
	{
		$new_activation_token = generateActivationToken();
		
		$user = R::findOne("user", "activation_token = ?", array($token));
		$user->password = $pass;
		$user->ActivationToken = $new_activation_token;
		
		return R::store($user);
	}
	
	function emailUsernameLinked($email,$username)
	{
		return R::findOne("user", "username = ? AND email = ?", array($username, $email));
	}
	
	public function loginUser($user){
		//Update last sign in
		$this->updateLastSignIn($user);
		$_SESSION["authUser"] = $user;
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
		$user = R::findOne("user", "username = ? AND email = ?", array($username, $email));
		$user->ActivationToken = $new_activation_token;
		$user->LastActivationRequest = time();
		
		return R::store($user);
	}
}
?>