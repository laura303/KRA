<?php
require_once 'data_daos_AuthDAO.php';

class AuthController {
	private $_siteKey;
	private $_dao;

	public function __construct()
	{
		$this->_siteKey = 'SLLLLSDIE*#&Slks*(Lsdf***,asdf';
		$this->_dao = new AuthDAO();
	}

	private function randomString($length = 50)
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$string = '';

		for ($p = 0; $p < $length; $p++) {
			$string .= $characters[mt_rand(0, strlen($characters) - 1)];
		}

		return $string;
	}

	protected function hashData($data)
	{
		return hash_hmac('sha512', $data, $this->_siteKey);
	}

	public function isAdmin()
	{
		//$selection being the array of the row returned from the database.
		if($selection['is_admin'] == 1) {
			return true;
		}

		return false;
	}

	public function createUser($email, $password, $is_admin = 0)
	{
		//Generate users salt
		$user_salt = $this->randomString();
			
		//Salt and Hash the password
		$password = $user_salt . $password;
		$password = $this->hashData($password);
			
		//Create verification code
		$code = $this->randomString();

		//Commit values to database here.
		$user = $this->_dao->createUser($email, $password, $user_salt, $code);

		$this->_dao = null;

		if($user){
			//send verification
			$this->sendVerification($email, $code);
			return true;
		}
			
		return false;
	}

	public function login($email, $password)
	{
		//Select users row from database base on $email
		$user = $this->_dao->getUserInfo($email);

		//Salt and hash password for checking
		$password = $user->user_salt.$password;
		$password = $this->hashData($password);

		//Check email and password hash match database row
		if ($password == $user->password) 
			$match = true;
		else 
			$match = false;

		//Convert to boolean
		$is_active = (boolean) $user->is_active;
		$verified = (boolean) $user->is_verified;

		if($match == true) {
			if($is_active == true) {
				if($verified == true) {
					//Email/Password combination exists, set sessions
					//First, generate a random string.
					$random = $this->randomString();
					//Build the token
					$token = $_SERVER['HTTP_USER_AGENT'] . $random;
					$token = $this->hashData($token);

					//Setup sessions vars
					if (!isset($_SESSION)) {
						session_start();
					}
					$_SESSION['token'] = $token;
					$_SESSION['user_id'] = $user->id;
					$_SESSION['email'] = $email;

					//Delete old logged_in_member records for user
					$this->_dao->removePriorLogins($user->id);

					//Insert new logged_in_member record for user
					$inserted = $this->_dao->markUserLoggedIn($user->id, session_id(), $token);

					//Logged in
					if($inserted != false) {
						return 0;
					}

					return 3;
				}
				else {
					//Not verified
					return 1;
				}
			}
			else {
				//Not active
				return 2;
			}
		}

		//No match, reject
		return 4;
	}

	public function checkSession() {
		if (isset($_SESSION['user_id'])) {
			//session available, continue
			//Select the row
			$session = $this->_dao->checkSession($_SESSION['user_id']);

			if($selection) {
				if(session_id() == $session->session_id && $_SESSION['token'] == $session->token) {
					//Id and token match, refresh the session for the next request
					$this->refreshSession();
					return true;
				}
				//TODO: Possibly remove session since exists ?
			}
		}
		return false;
	}

	private function refreshSession()
	{
		//Regenerate id
		session_regenerate_id();

		//Regenerate token
		$random = $this->randomString();
		//Build the token
		$token = $_SERVER['HTTP_USER_AGENT'] . $random;
		$token = $this->hashData($token);

		//Store in session
		$_SESSION['token'] = $token;
		if ($this->dao->updateSession($_SESSION['user_id'], session_id(), $token)) {
			return true;
		}

		return false;
	}

	public function logout() {
		//destroy session
		session_destroy();
		//delete the row based on user_id
		$this->dao->logoutUser($_SESSION['user_id']);
	}

	public function sendVerification($email, $code = null) {
		//if code = null, then retrieve code from db
		if ($code == null) {
			$this->dao = new AuthDAO();
			$code = $this->dao->retrieveCode($email);
			if (!$code) {
				return false;
			}
		}

		//set email subject
		$subject = 'Account Creation Verification';

		//set email body
		$message = 'This is to verify your new account has a valid email address';
		$message .= '<br /><br />Your verificatoin code is <b>'. $code .'</b>';
		$message .= '<br /><br />You can click <a href="http://' . SITE_HTTP . '/verify.php?email=' . $email . '&code=' . urlencode($code) . '">here</a> to verify automatically';
		$message .= '<br />or visit <a href="http://'. SITE_HTTP . '/verify.php">http://' . SITE_HTTP . '/verify.php</a>';
		$message .= '<br /><br />Thank you for your coorperation';

		//set email headers
		$headers = 'From: ' . FROM_EMAIL . "\r\n" .
		    'Reply-To: ' . FROM_EMAIL . "\r\n" .
			'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
		    'X-Mailer: PHP/' . phpversion();


		//send email
		//if (mail($email, $subject, $message, $headers)) {
		//	return true;
		//}

		return false;
	}

	public function checkVerification($email, $code) {
		$this->dao = new AuthDAO();

		if ($this->dao->checkVerification($email, $code) > 0) {
			return true;
		}

		return false;
	}

	public function forgotPassword($email) {
		$this->_dao = new AuthDAO();

		//Generate users salt
		$user_salt = $this->randomString();

		//Salt and Hash the password
		$password2 = $this->randomString(8);
		$password = $user_salt . $password2;
		$password = $this->hashData($password);

		//Commit values to database here.
		$created = $this->_dao->newPassword($email, $password, $user_salt);

		$this->_dao = null;

		if($created > 0) {
			//send new pw via email
			$this->sendNewPassword($email, $password2);
			return true;
		}

		return false;
	}

	private function sendNewPassword($email, $password) {
		//set email subject
		$subject = 'Password Change';

		//set email body
		$message = 'Please find your new password below. Once logged in, please change your password';
		$message .= '<br /><br />Your new password is <b>'. $password .'</b>';
		$message .= '<br /><br />Thank you for your coorperation';

		//set email headers
		$headers = 'From: ' . FROM_EMAIL . "\r\n" .
				    'Reply-To: ' . FROM_EMAIL . "\r\n" .
					'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
				    'X-Mailer: PHP/' . phpversion();


		//send email
		if (mail($email, $subject, $message, $headers)) {
			return true;
		}

		return false;
	}

	public function changePassword($userId, $email, $currentPassword, $newPassword) {
		$this->_dao = new AuthDAO();

		//Select users row from database base on $email
		$selection = $this->_dao->getUserInfo($email);

		//Salt and hash password for checking
		$currentPassword = $user->user_salt . $currentPassword;
		$currentPassword = $this->hashData($currentPassword);

		//Check email and password hash match database row
		if ($currentPassword == $user->password) $match = true;
		else $match = false;

		//Convert to boolean
		$is_active = (boolean) $user->is_active;
		$verified = (boolean) $user->is_verified;

		if($match == true) {
			if($is_active == true) {
				if($verified == true) {
					$salt = $this->randomString();
					$newPassword = $salt . $newPassword;
					$newPassword = $this->hashData($newPassword);
					$cp = $this->_dao->updatePassword($_SESSION['user_id'], $newPassword, $salt);
					if ($cp > 0) {
						return true;
					}
				}
			}
		}

		return false;
	}
}
?>