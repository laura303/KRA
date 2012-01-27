<?php
require_once ('controllers_auth_AuthController.php');

session_start();
$authController = new AuthController();

if (!isset($_SESSION['user_id'])) {
	//Not logged in, send to login page.
	header('Location: auth/login.php');
} else {
	//Check we have the right user
	$session = $authController->checkSession();
	if(!$logged_in){
		//Bad session, ask to login
		$authController->logout();
		header( 'Location: auth/login.php' );
	} else {
		//User is logged in, show the page
		echo "<b>Am logged in</b>";
	}
}

