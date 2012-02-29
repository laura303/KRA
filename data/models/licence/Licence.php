<?php
class Model_Licence extends RedBean_SimpleModel{
	public function latest_payment() {
		$payments_made = $this->bean->ownPayment;
		if(count($payments_made)){
			end($payments_made);
			return $payments_made[key($payments_made)];
		}
		return null;
	}
		
	public function latest_payment_date() {
		$latest_payment = $this->latest_payment();
		if($latest_payment){
			return $latest_payment->time_of_transaction;
		}
		return ($latest_payment) ? $latest_payment->expiry_date : 0;
	}
	
	public function expiry_date() {
		$latest_payment = $this->latest_payment();
		return ($latest_payment) ? $latest_payment->expiry_date : 0;
	}
	
	public function is_expired() {
		$expiry_date = $this->expiry_date();
		return ($expiry_date)? (time() > $expiry_date) : true;
	}
	
	public function paying_now_is_reasonable() {
		$latest_payment = $this->latest_payment();
		if (!$latest_payment){return true;}//No Entry Yet
		if (time() > $latest_payment->expiry_date){//expired?
			return  true;
		}else{
			$diff = $latest_payment->expiry_date - time();
			return ($diff/60/60/24) <= 30;
		}
		return false;
	}
	
	public function create_e_slip() {
		$latest_payment = $this->latest_payment();
		if ($latest_payment){
			createPDF($this->bean, $latest_payment);
		}else{
			PageError::show('404',NULL,'Unknown action', "No Payments made yet.");
		}
	}
}	
?>
