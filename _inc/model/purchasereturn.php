<?php

class ModelPurchasereturn extends Model 
{
	public function getInvoices($store_id = null, $limit = 100000) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$statement = $this->db->prepare("SELECT `purchase_returns`.* FROM `purchase_returns` 
			WHERE `purchase_returns`.`store_id` = ? ORDER BY `purchase_returns`.`created_at` DESC LIMIT $limit");
		$statement->execute(array($store_id));
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function getInvoiceInfo($invoice_id, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$statement = $this->db->prepare("SELECT * FROM `purchase_returns` 
			WHERE `store_id` = ? AND `invoice_id` = ?");
		$statement->execute(array($store_id, $invoice_id));
		return $statement->fetch(PDO::FETCH_ASSOC);
	}

	public function getInvoiceItems($invoice_id, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$statement = $this->db->prepare("SELECT * FROM `purchase_return_items` WHERE `store_id` = ? AND `invoice_id` = ?");
		$statement->execute(array($store_id, $invoice_id));
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}
}