<?php
	class PageError{
		public static function show($type, $url, $message='', $long_message=''){
			if ($type == '404'){
				if (!$message){
					$message = "Page not Found";
					$long_message = "The requested URL $url was not found on this server.";
				}
				$template_name = '404.tpl';
			}else if ($type == '500'){
				if (!$message){
					$message = "Server Error.";
					$long_message = "An error occured while trying to load page [$url].";
				}
				$template_name = '500.tpl';
			}else{
				if (!$message){
					$message = "Server Error";
					$long_message = "An error occured while trying to load page.";
				}
				$template_name = '500.tpl';
			}
			global $DEBUG, $smarty;
			
			$smarty->assign("message", "$message");
			$smarty->assign("long_message", $long_message);
			
			$smarty->display($template_name);
			
			if ($DEBUG){
				die();
			}
		}
	}
?>