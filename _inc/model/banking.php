<?php

class ModelBanking extends Model 
{
	public function addDeposit($data, $store_id = null)
	{
		$store_id = $store_id ? $store_id : store_id();
		$ref_no = $data['ref_no'];
		$account_id = $data['account_id'];
	    $source_id = $data['source_id'];
	    $title = $data['title'];
	    $details = $data['details'];
	    $image = $data['image'];
	    $deposit_amount = $data['amount'];
	    $transaction_type = 'deposit';
	    $created_by = user_id();
	    $created_at = date_time();

	    $statement = $this->db->prepare("INSERT INTO `bank_transaction_info` (store_id, account_id, source_id, ref_no, transaction_type, title, details, image, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
	    $statement->execute(array($store_id, $account_id, $source_id, $ref_no, $transaction_type, $title, $details, $image, $created_by, $created_at));
	    $info_id = $this->db->lastInsertId();

	    $statement = $this->db->prepare("INSERT INTO `bank_transaction_price` (store_id, info_id, ref_no, amount) VALUES (?, ?, ?, ?)");
	    $statement->execute(array($store_id, $info_id, $ref_no, $deposit_amount));

	    $statement = $this->db->prepare("UPDATE `bank_account_to_store` SET `deposit` = `deposit` + $deposit_amount WHERE `store_id` = ? AND `account_id` = ?");
	    $statement->execute(array($store_id, $account_id));

	    $statement = $this->db->prepare("UPDATE `bank_accounts` SET `total_deposit` = `total_deposit` + $deposit_amount WHERE `id` = ?");
	    $statement->execute(array($account_id));


	    if (isset($data['capital']) && $data['capital'] > 0) {
	    	$statement = $this->db->prepare("INSERT INTO `payments` (store_id, invoice_id, pmethod_id, capital, amount, total_paid, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
	    	$statement->execute(array($store_id, $ref_no, 1, $data['capital'], $deposit_amount, $deposit_amount, user_id(), $created_at));
	    	$info_id = $this->db->lastInsertId();
	    }

	    return $info_id;
	}

	public function getTransactions($type, $store_id = null, $limit = 10)
	{
		$store_id = $store_id ? $store_id : store_id();
		$statement = $this->db->prepare("SELECT `bank_transaction_info`.*, `bank_transaction_price`.* FROM `bank_transaction_info` 
			LEFT JOIN `bank_transaction_price` ON `bank_transaction_info`.`ref_no` = `bank_transaction_price`.`ref_no` 
			WHERE `bank_transaction_info`.`store_id` = ? AND `bank_transaction_info`.`transaction_type` = ? ORDER BY `bank_transaction_info`.`created_at` DESC LIMIT $limit");
		$statement->execute(array($store_id, $type));
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getTransactionInfo($ref_no, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$statement = $this->db->prepare("SELECT `bank_transaction_info`.*, `bank_transaction_price`.* FROM `bank_transaction_info` 
			LEFT JOIN `bank_transaction_price` ON `bank_transaction_info`.`ref_no` = `bank_transaction_price`.`ref_no` 
			WHERE `bank_transaction_info`.`store_id` = ? AND `bank_transaction_info`.`ref_no` = ?");
		$statement->execute(array($store_id, $ref_no));
		return $statement->fetch(PDO::FETCH_ASSOC);
	}

	public function gePrevWithdraw($price_id, $store_id = null, $account_id = null) 
	{	
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`bank_transaction_info`.`store_id` = ? AND `bank_transaction_price`.`price_id` BETWEEN ? AND ?
			AND `transaction_type` = ?";
		if ($account_id) {
			$where_query .= " AND `account_id` = '{$account_id}'";
		}
		$statement = $this->db->prepare("SELECT SUM(`bank_transaction_price`.`amount`) as `total` FROM `bank_transaction_info` 
			LEFT JOIN `bank_transaction_price` ON (`bank_transaction_info`.`info_id` = `bank_transaction_price`.`info_id`) 
			WHERE {$where_query} ORDER BY `price_id` ASC");
		$statement->execute(array($store_id, 1, ((int)$price_id)-1, 'withdraw'));
		$bank_transaction = $statement->fetch(PDO::FETCH_ASSOC);
		return $bank_transaction ? $bank_transaction['total'] : 0;
	}

	public function getPrevBalance($price_id, $store_id = null, $account_id = null) 
	{	
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`bank_transaction_info`.`store_id` = ? AND `bank_transaction_info`.`transaction_type` = 'deposit'
			AND `bank_transaction_price`.`price_id` BETWEEN ? AND ?";
		if ($account_id) {
			$where_query .= " AND `account_id` = '{$account_id}'";
		}
		$statement = $this->db->prepare("SELECT SUM(`bank_transaction_price`.`amount`) as `total` FROM `bank_transaction_info` 
			LEFT JOIN `bank_transaction_price` ON (`bank_transaction_info`.`info_id` = `bank_transaction_price`.`info_id`) 
			WHERE {$where_query}  ORDER BY `bank_transaction_price`.`price_id` ASC");
		$statement->execute(array($store_id, 1, ((int)$price_id)-1));
		$bank_transaction = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($bank_transaction['total']) ? $bank_transaction['total'] : 0;
	}

	public function getDepositAmount($from = null, $to = null, $store_id = null)
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`bank_transaction_info`.`store_id` = $store_id AND `bank_transaction_info`.`transaction_type` = 'deposit'";
		if ($from) {
			$where_query .= date_range_accounting_filter($from, $to);
		}
		$statement = $this->db->prepare("SELECT SUM(`bank_transaction_price`.`amount`) as `total` FROM `bank_transaction_info` 
			LEFT JOIN `bank_transaction_price` ON (`bank_transaction_info`.`info_id` = `bank_transaction_price`.`info_id`) 
			WHERE  $where_query");
		$statement->execute(array());
		$row = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($row['total']) ? $row['total'] : 0;
	}

	public function getWithdrawAmount($from = null, $to = null, $store_id = null)
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`bank_transaction_info`.`store_id` = $store_id AND `bank_transaction_info`.`transaction_type` = 'withdraw'";
		if ($from) {
			$where_query .= date_range_accounting_filter($from, $to);
		}
		$statement = $this->db->prepare("SELECT SUM(`bank_transaction_price`.`amount`) as `total` FROM `bank_transaction_info` 
			LEFT JOIN `bank_transaction_price` ON (`bank_transaction_info`.`info_id` = `bank_transaction_price`.`info_id`) 
			WHERE  $where_query");
		$statement->execute(array());
		$row = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($row['total']) ? $row['total'] : 0;
	}

	public function getBalance($from = null, $to = null, $store_id = null) 
	{	
		$deposit = $this->getDepositAmount($from, $to);
		$withdraw = $this->getWithdrawAmount($from, $to);
		return $deposit - $withdraw;
	}
}