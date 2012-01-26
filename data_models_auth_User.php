<?php
require_once ('data_orm_rb.php');

class Model_User extends RedBean_SimpleModel {
	public $email;
	public $password;
	public $salt;
	public $is_verified;
	public $is_active;
	public $is_admin;
	public $verification_code;
}
?> 