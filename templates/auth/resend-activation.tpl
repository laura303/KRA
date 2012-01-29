{extends "auth/auth_layout.tpl"}

$errors = array();
$success_message = "";

//Forms posted
//----------------------------------------------------------------------------------------------
if(!empty($_POST) && $emailActivation)
{
		$email = $_POST["email"];
		$username = $_POST["username"];
		
		//Perform some validation
		//Feel free to edit / change as required
		
		if(trim($email) == "")
		{
			$errors[] = lang("ACCOUNT_SPECIFY_EMAIL");
		}
		//Check to ensure email is in the correct format / in the db
		else if(!AuthController::isValidEmail($email) || !emailExists($email))
		{
			$errors[] = lang("ACCOUNT_INVALID_EMAIL");
		}
		
		if(trim($username) == "")
		{
			$errors[] =  lang("ACCOUNT_SPECIFY_USERNAME");
		}
		else if(!$dao->usernameExists($username))
		{
			$errors[] = lang("ACCOUNT_INVALID_USERNAME");
		}
		
		
		if(count($errors) == 0)
		{
			//Check that the username / email are associated to the same account
			if(!emailUsernameLinked($email,$username))
			{
				$errors[] = lang("ACCOUNT_USER_OR_EMAIL_INVALID");
			}
			else
			{
				$userdetails = fetchUserDetails($username);
			
				//See if the user's account is activation
				if($userdetails->is_active)
				{
					$errors[] = lang("ACCOUNT_ALREADY_ACTIVE");
				}
				else
				{
					$hours_diff = round((time()-$userdetails->last_activation_request) / (3600*$resend_activation_threshold),0);

					if($resend_activation_threshold!=0 && $hours_diff <= $resend_activation_threshold)
					{
						$errors[] = lang("ACCOUNT_LINK_ALREADY_SENT",array($resend_activation_threshold));
					}
					else
					{
						//For security create a new activation url;
						$new_activation_token = generateActivationToken();
						
						if(!updateLastActivationRequest($new_activation_token,$username,$email))
						{
							$errors[] = lang("SQL_ERROR");
						}
						else
						{
							$mail = new userCakeMail();
							
							$activation_url = $websiteUrl."activate-account.php?token=".$new_activation_token;
						
							//Setup our custom hooks
							$hooks = array(
								"searchStrs" => array("#ACTIVATION-URL","#USERNAME#"),
								"subjectStrs" => array($activation_url,$userdetails->username)
							);
							
							if(!$mail->newTemplateMsg("resend-activation.txt",$hooks))
							{
								$errors[] = lang("MAIL_TEMPLATE_BUILD_ERROR");
							}
							else
							{
								if(!$mail->sendMail($userdetails->email,"Activate your UserCake Account"))
								{
									$errors[] = lang("MAIL_ERROR");
								}
								else
								{
									//Success, user details have been updated in the db now mail this information out.
									$success_message = lang("ACCOUNT_NEW_ACTIVATION_SENT");
								}
							}
						}
					}
				}
			}
		}
}
{block "main"} 
	<h1>Resend Activation Email</h1>
 
    <?php
    if(!empty($_POST) || !empty($_GET["confirm"]) || !empty($_GET["deny"]) && $emailActivation)
    {     
	
			if(count($errors) > 0)
            {
		?>
        	<div id="errors">
            	<?php errorBlock($errors); ?>
            </div> 
        <?php
            }
			else
			{
		?>
            <div id="success">
            
                <p><?php echo $success_message; ?></p>
            
            </div>
        <?php
			}
        }
        ?> 
    
    <div id="regbox">
	
	<?php 
    
    if(!$emailActivation)
    { 
        echo lang("FEATURE_DISABLED");
    }
	else
	{
    ?>
        <form name="resendActivation" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
        
        
        <p>
            <label>Username:</label>
            <input type="text" name="username" />
        </p>     
            
         <p>
            <label>Email:</label>
            <input type="text" name="email" />
         </p>    
    
    	 <p>
         	<label>&nbsp;</label>
            <input type="submit" value="Login" class="submit" />
         </p>
            
        </form>

	 <?php } ?> 
     </div>   
{/main}

