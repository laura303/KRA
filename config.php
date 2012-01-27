<?php
	/*
		UserCake Version: 1.4
		http://usercake.com
		
		Developed by: Adam Davis
	*/

	if(is_dir("install/"))
	{
		header("Location: install/");
		die();
	}
	
	require_once("data_settings.php");
	
	require_once("lang/en.php");
	
	require_once("data_controllers_AuthController.php");
	require_once("data_daos_AuthDao.php");

	session_start();
	
	//Global User Object Var
	//loggedInUser can be used globally if constructed
	if(isset($_SESSION["userCakeUser"]) && is_object($_SESSION["userCakeUser"]))
	{
		$loggedInUser = $_SESSION["userCakeUser"];
	}
?>