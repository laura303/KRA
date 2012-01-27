<?php
	class AuthController{
		public static function sanitize($str)
		{
			return strtolower(strip_tags(trim(($str))));
		}
		
		public static function isValidEmail($email)
		{
			return preg_match('/^[_A-Za-z0-9-]+(\\.[_A-Za-z0-9-]+)*@[A-Za-z0-9]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$/',trim($email));
		}
		
		public static function minMaxRange($min, $max, $what)
		{
			if(strlen(trim($what)) < $min)
			   return true;
			else if(strlen(trim($what)) > $max)
			   return true;
			else
			   return false;
		}
		
		//@ Thanks to - http://phpsec.org
		public static function generateHash($plainText, $salt = null)
		{
			if ($salt === null)
			{
				$salt = substr(md5(uniqid(rand(), true)), 0, 25);
			}
			else
			{
				$salt = substr($salt, 0, 25);
			}
		
			return $salt . sha1($salt . $plainText);
		}
		
		public static function replaceDefaultHook($str)
		{
			global $default_hooks,$default_replace;
		
			return (str_replace($default_hooks,$default_replace,$str));
		}
		
		public static function getUniqueCode($length = "")
		{	
			$code = md5(uniqid(rand(), true));
			if ($length != "") return substr($code, 0, $length);
			else return $code;
		}
		
		public static function errorBlock($errors)
		{
			if(!count($errors) > 0)
			{
				return false;
			}
			else
			{
				echo "<ul>";
				foreach($errors as $error)
				{
					echo "<li>".$error."</li>";
				}
				echo "</ul>";
			}
		}
		
		public function lang($key,$markers = NULL)
		{
			global $lang;
			
			if($markers == NULL)
			{
				$str = $lang[$key];
			}
			else
			{
				//Replace any dyamic markers
				$str = $lang[$key];
	
				$iteration = 1;
				
				foreach($markers as $marker)
				{
					$str = str_replace("%m".$iteration."%",$marker,$str);
					
					$iteration++;
				}
			}
			
			//Ensure we have something to return
			if($str == "")
			{
				return ("No language key found");
			}
			else
			{
				return $str;
			}
		}
		
		public static function destorySession($name)
		{
			if(isset($_SESSION[$name]))
			{
				$_SESSION[$name] = NULL;
				
				unset($_SESSION[$name]);
			}
		}		
		
			//Logout
		function userLogOut()
		{
			destorySession("userCakeUser");
		}
		
	}
?> 