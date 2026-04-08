<?php

class ModelInstallment extends Model 
{
	public function getInvoiceCount($store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
	    $statement = $this->db->prepare("SELECT * FROM `installment_orders` WHERE `store_id` = ?");
	    $statement->execute(array($store_id));
	    return $statement->rowCount();
	}


	public function getSellAmount($from=null, $to=null, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`selling_info`.`store_id` = '$store_id' AND `is_installment` = 1";
		if($from) {
			$where_query .= date_range_filter($from, $to);
		}
		$statement = $this->db->prepare("SELECT SUM(`selling_price`.`payable_amount`) as total FROM `selling_info` 
			LEFT JOIN `selling_price` ON (`selling_info`.`invoice_id` = `selling_price`.`invoice_id`) 
			WHERE $where_query");

		$statement->execute(array());
		$rows = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($rows['total']) ? $rows['total'] : 0;
	}

	public function getReceivedAmount($store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`installment_payments`.`store_id` = '$store_id'";
		$statement = $this->db->prepare("SELECT SUM(`installment_payments`.`paid`) as total FROM `installment_payments` WHERE $where_query");
		$statement->execute(array());
		$rows = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($rows['total']) ? $rows['total'] : 0;
	}

	public function getDueAmount($store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`installment_payments`.`store_id` = '$store_id'";
		$statement = $this->db->prepare("SELECT SUM(`installment_payments`.`due`) as total FROM `installment_payments` WHERE $where_query");
		$statement->execute(array());
		$rows = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($rows['total']) ? $rows['total'] : 0;
	}
}