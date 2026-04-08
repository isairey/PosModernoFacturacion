<?php

class ModelPayment extends Model 
{
	public function getPayments($invoice_id, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
	    $statement = $this->db->prepare("SELECT * FROM `payments` 
	    	WHERE `store_id` = ? AND `invoice_id` = ?");
	    $statement->execute(array($store_id, $invoice_id));
	    $payments = $statement->fetchAll(PDO::FETCH_ASSOC);

	    $payment_array = array();
	    $i = 0;
	    foreach ($payments as $payment) {
	    	$payment_array[$i] = $payment;
	    	$payment_array[$i]['name'] = get_the_pmethod($payment['pmethod_id'], 'name');
	    	$payment_array[$i]['by'] = get_the_user($payment['created_by'], 'username');
	    	$i++;
	    }

	    return $payment_array;
	}

	public function getPurchasePayments($invoice_id, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
	    $statement = $this->db->prepare("SELECT * FROM `purchase_payments` 
	    	WHERE `store_id` = ? AND `invoice_id` = ?");
	    $statement->execute(array($store_id, $invoice_id));
	    $purchase_payments = $statement->fetchAll(PDO::FETCH_ASSOC);
	    $payment_array = array();
	    $i = 0;
	    foreach ($purchase_payments as $payment) {
	    	$payment_array[$i] = $payment;
	    	$payment_array[$i]['name'] = get_the_pmethod($payment['pmethod_id'], 'name');
	    	$payment_array[$i]['by'] = get_the_user($payment['created_by'], 'username');
	    	$i++;
	    }
	    return $payment_array;
	}

	public function getCapitalAmount($from=null,$to=null,$store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`store_id` = '$store_id' AND `is_hide` != 1";
		if ($from) {
			$where_query .= date_range_sell_payments_filter($from,$to);
		}
	    $statement = $this->db->prepare("SELECT SUM(`capital`) as total FROM `payments` 
	    	WHERE $where_query");
	    $statement->execute(array());
	    $row = $statement->fetch(PDO::FETCH_ASSOC);
	    return isset($row['total']) ? $row['total'] : 0;
	}

	public function getProfitAmount($from=null,$to=null,$store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`store_id` = '$store_id' AND `is_profit` = 1";
		if ($from) {
			$where_query .= date_range_sell_payments_filter($from,$to);
		}
	    $statement = $this->db->prepare("SELECT SUM(`capital`) as capital, SUM(`amount`) as total FROM `payments` 
	    	WHERE $where_query");
	    $statement->execute(array());
	    $row = $statement->fetch(PDO::FETCH_ASSOC);
	    return isset($row['total']) ? $row['total'] - $row['capital'] : 0;
	}
}