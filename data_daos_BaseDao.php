<?php
require_once('data_orm_rb.php');
require_once('conf_database.php');

abstract class BaseDao{
	public function __construct(){
		R::setup("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS);
	}
	
	public function createModel($model_name){
		return R::dispense($model_name);
	}
}

?>