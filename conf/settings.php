<?php
	//General Settings
	//--------------------------------------------------------------------------
	
	$DATABASES = array(
	    'default' => array(
	    	'ENGINE' => 'mysql',
	        'NAME' => 'kra_app',
	        'USER' => 'root',
	        'PASSWORD' => '',
	        'HOST' => 'localhost',
	        'PORT' => ''
	    )
	);
	
	$LANGUAGE = "en";
	
	$DEBUG = false;
	
	//Generic website variables
	$WEBSITE_NAME = "KRA";
	$WEBSITE_URL = ""; //including trailing slash

	//Do you wish UserCake to send out emails for confirmation of registration?
	//We recommend this be set to true to prevent spam bots.
	//False = instant activation
	//If this variable is falses the resend-activation file not work.
	$EMAIL_ACTIVATION = true;

	//In hours, how long before UserCake will allow a user to request another account activation email
	//Set to 0 to remove threshold
	$RESEND_ACTIVATION_THRESHOLD = 1;
	
	//Tagged onto our outgoing emails
	$EMAIL_ADDRESS = "noreply@kra.org";
	
	//Date format used on email's
	$EMAIL_DATE = date("l \\t\h\e jS");

	//Directory where txt files are stored for the email templates.
	$MAIL_TEMPLATES_DIR = "/../templates/mail/";
	
	$DEFAULT_HOOKS = array("#WEBSITENAME#","#WEBSITEURL#","#DATE#");
	$DEFAULT_REPLACE = array($WEBSITE_NAME,$WEBSITE_URL,$EMAIL_DATE);
	
	$BASE_URL = '/kra_app';
	$MEDIA_URL = '/static/';
	
	$USER_REGISTRATION = true;
	$UPLOAD_DIRECTORY = __DIR__.'/../media/uploads';
?>