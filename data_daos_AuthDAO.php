<?php
require_once('data_orm_rb.php');
require_once('data_daos_BaseDao.php');
require_once('data_models_auth_User.php');

class AuthDAO extends BaseDao{
	private $_db;

	public function __construct() {
		parent::__construct();
	}

	public function createUser($email, $password, $salt, $verification) {
		$user = $this->createModel('user');
		
		$ver = 0;
		$act = 1;
		$adm = 0;
		
		$user->email = $email;
		$user->password = $password;
		$user->user_salt = $salt;
		$user->is_verified = $ver;
		$user->is_active = $act;
		$user->is_admin = $adm; 
		$user->verification_code = $verification;
		
		echo $user->email;
		return R::store($user, false);
	}

	public function getUserInfo($email) {
		return R::findOne("user", "email = '".$email."'");
	}

	public function removePriorLogins($userId) {
		$user = R::load("user", $userId);
		$user = R::clearRelations($user, "loggeduser");
	}

	public function markUserLoggedIn($userId, $session, $token) {
		$loggedUser = R::dispense('loggeduser');
		$user 		= R::load('user', $userId);
		
		$loggedUser->session_id = $session;
		$loggedUser->token = $token;
		
		R::assorciate($loggedUser, $user);
	}
	
	public function checkSession($userId) {
		$loggedUser = R::load('loggeduser', $userId);
		return $loggedUser->session_id;
	}

	public function updateSession($userId, $session, $token) {
		$loggedUser = R::related(R::load('user', $userId), 'loggeduser');
		$loggedUser->session_id = $session;
		$loggedUser->token = $token;
		$loggedUser->lastUpdate = "NOW()";
	}

	public function logoutUser($user_id) {
		$user = R::load("user", $userId);
		$user = R::clearRelations($user, "loggeduser");
	}
	
	public function checkVerification($email, $code) {
		$user = R::findOne("user", "email = ".$email." and verification_code = ".$code);
		$user->is_verified = 1;
		$user->is_active = 1;
	}
	
	public function retrieveCode($email) {
		$user = R::findOne("user", "email = ".$email);		
		return $user->verification_code;
	}
	
	public function newPassword($email, $password, $salt) {
		$user = R::findOne("user", "email = ".$email);	
		$user->password = $password;
		$user->salt = $salt;
		
		R::store($user);
	}
	
	public function updatePassword($userId, $password, $salt) {
		$user = R::load("user", $userId);	
		$user->password = $password;
		$user->salt = $salt;
		
		R::store($user);
	}
}
?>