<?php
class Model_User extends RedBean_SimpleModel{
	public $username;
	public $username_clean;
	public $password;
	public $email;
	public $activation_token;
	public $last_activation_request;
	public $lost_password_request;
	public $is_active;
	public $sign_up_date;
	public $last_sign_in;
	
	public function open() {}
    public function dispense(){}
    public function update() {}
    public function after_update(){}
    public function delete() {}
    public function after_delete() {}
	
	public function isUserLoggedIn(){
		if ($this->id){
			return true;
		}else{
			//No result returned kill the user session, user banned or deleted
			$this->userLogOut();
			return false;
		}
	}
	
	public function userLogOut(){
		if ($this->id){
			destorySession("authUser");
		}
	}
	
	public function belongsToGroups($groupNames){
		if (is_string($groupNames)){
			$groupNames=explode(",",$groupNames);
		}
		
		$groups = R::find('group', 'slug IN ('.R::genSlots($groupNames).')', $groupNames);
		foreach($groups as $group){
			if (R::areRelated($this->bean, $group)){
				return true;
			}
		}
		return false;
	}
}
?>