<?php
require_once ('controllers_auth_AuthController.php');

session_start();
$authController = new AuthController();

if (!isset($_SESSION['user_id'])) {
	//Not logged in, send to login page.
	header('Location: index.html');
} else {
	//Check we have the right user
	$logged_in = $authController->checkSession();
	if(empty($logged_in)){
		//Bad session, ask to login
		$auth->logout();
		header( 'Location: index.html' );
	} else {
		//User is logged in, show the page
	}
}

