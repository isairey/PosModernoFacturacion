<?php

class ModelLoan extends Model 
{
	public function addLoanPay($data) 
	{
		$created_by = user_id();
		$created_at = date_time();
		$paid = $data['paid'];
		$loan_id = $data['loan_id'];
    	$statement = $this->db->prepare("INSERT INTO `loan_payments` (lloan_id, ref_no, paid, note, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?)");
    	$statement->execute(array($loan_id, $data['ref_no'], $paid, $data['note'], $created_by, $created_at));
    	$paid_id = $this->db->lastInsertId();

    	// Upade paid and due amount
    	$statement = $this->db->prepare("UPDATE `loans` SET `paid` = `paid` + $paid, `due` = `due` - $paid WHERE `loan_id` = ?");
		$statement->execute(array($loan_id));

		// Substract bank transaction
        if (($account_id = store('deposit_account_id')) && $paid > 0) {
          $ref_no = unique_transaction_ref_no('withdraw');
          // $ref_no = $data['ref_no'];
          $statement = $this->db->prepare("SELECT `category_id` FROM `expense_categorys` WHERE `loan_payment` = ?");
          $statement->execute(array(1));
          $category = $statement->fetch(PDO::FETCH_ASSOC);
          $exp_category_id = $category['category_id'];
          $statement = $this->db->prepare("SELECT `source_id` FROM `income_sources` WHERE `for_loan` = ?");
          $statement->execute(array(1));
          $source = $statement->fetch(PDO::FETCH_ASSOC);
          $source_id = $source['source_id'];
          $title = 'Debit while loan payment';
          $details = '';
          $image = 'NULL';
          $withdraw_amount = $paid;
          $transaction_type = 'withdraw';

          $statement = $this->db->prepare("INSERT INTO `bank_transaction_info` (store_id, is_substract, account_id, source_id, exp_category_id, ref_no, invoice_id, transaction_type, title, details, image, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
          $statement->execute(array(store_id(), 1, $account_id, $source_id, $exp_category_id, $ref_no, $data['ref_no'], $transaction_type, $title, $details, $image, user_id(), date_time()));
		  $info_id = $this->db->lastInsertId();
			
          $statement = $this->db->prepare("INSERT INTO `bank_transaction_price` (store_id, info_id, ref_no, amount) VALUES (?, ?, ?, ?)");
          $statement->execute(array(store_id(), $info_id, $ref_no, $withdraw_amount));

          $statement = $this->db->prepare("UPDATE `bank_account_to_store` SET `withdraw` = `withdraw` + $withdraw_amount WHERE `store_id` = ? AND `account_id` = ?");
          $statement->execute(array(store_id(), $account_id));

          $statement = $this->db->prepare("UPDATE `bank_accounts` SET `total_deposit` = `total_deposit` + $withdraw_amount WHERE `id` = ?");
          $statement->execute(array($account_id));
        }

		return $paid_id;
	}

	public function addLoan($data) 
	{
		$payable = $data['interest'] > 0 ? $data['amount']+(($data['interest']/100)*$data['amount']) : $data['amount'];
		$due = $payable;
		$created_by = user_id();
		$created_at = date('Y-m-d H:i:s', strtotime($data['date']));
    	$statement = $this->db->prepare("INSERT INTO `loans` (ref_no, loan_from, title, amount, interest, payable, due, details, attachment, status, sort_order, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    	$statement->execute(array($data['ref_no'], $data['loan_from'], $data['title'], $data['amount'], $data['interest'], $payable, $due, $data['details'], $data['image'], $data['status'], $data['sort_order'], $created_by, $created_at));
    	$loan_id = $this->db->lastInsertId();

    	// Deposit
	    if (($account_id = store('deposit_account_id')) && $data['amount'] > 0) {
			// $ref_no = unique_transaction_ref_no();
			$ref_no = $data['ref_no'];
			$statement = $this->db->prepare("SELECT `source_id` FROM `income_sources` WHERE `for_loan` = ?");
		    $statement->execute(array(1));
		    $source = $statement->fetch(PDO::FETCH_ASSOC);
		    $source_id = $source['source_id'];
		    $title = 'Deposit for loan taken';
		    $details = '';
		    $image = 'NULL';
		    $deposit_amount = $data['amount'];
		    $transaction_type = 'deposit';

		    $statement = $this->db->prepare("INSERT INTO `bank_transaction_info` (store_id, account_id, source_id, ref_no, invoice_id, transaction_type, title, details, image, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      		$statement->execute(array(store_id(), $account_id, $source_id, $ref_no, $ref_no, $transaction_type, $title, $details, $image, user_id(), date_time()));
			$info_id = $this->db->lastInsertId();
					
		    $statement = $this->db->prepare("INSERT INTO `bank_transaction_price` (store_id, info_id, ref_no, amount) VALUES (?, ?, ?, ?)");
		    $statement->execute(array(store_id(), $info_id, $ref_no, $deposit_amount));

		    $statement = $this->db->prepare("UPDATE `bank_account_to_store` SET `deposit` = `deposit` + $deposit_amount WHERE `store_id` = ? AND `account_id` = ?");
		    $statement->execute(array(store_id(),$account_id));

		    $statement = $this->db->prepare("UPDATE `bank_accounts` SET `total_deposit` = `total_deposit` + $deposit_amount WHERE `id` = ?");
		    $statement->execute(array($account_id));
	    }

    	return $loan_id; 
	}

	public function editLoan($loan_id, $data) 
	{
    	$statement = $this->db->prepare("UPDATE `loans` SET `loan_from` = ?, `ref_no` = ?, `title` = ?, `details` = ?, `attachment` = ?, `status` = ?, `sort_order` = ? WHERE `loan_id` = ? ");
    	$statement->execute(array($data['loan_from'], $data['ref_no'], $data['title'], $data['details'], $data['image'], $data['status'], $data['sort_order'], $loan_id));

    	return $loan_id;
	}

	public function deleteLoan($loan_id) 
	{

		$loan = $this->getLoan($loan_id);
    	// Substract bank transaction
    	$interest_amount = $loan['payable']-$loan['amount'];
    	$amount = $loan['due'] - $interest_amount;
        if (($account_id = store('deposit_account_id')) && $amount > 0) {
          $ref_no = unique_transaction_ref_no('withdraw');
          $statement = $this->db->prepare("SELECT `category_id` FROM `expense_categorys` WHERE `loan_delete` = ?");
          $statement->execute(array(1));
          $category = $statement->fetch(PDO::FETCH_ASSOC);
          $exp_category_id = $category['category_id'];
          $statement = $this->db->prepare("SELECT `source_id` FROM `income_sources` WHERE `for_loan` = ?");
          $statement->execute(array(1));
          $source = $statement->fetch(PDO::FETCH_ASSOC);
          $source_id = $source['source_id'];
          $title = 'Debit while deleting loan';
          $details = '';
          $image = 'NULL';
          $withdraw_amount = $amount;
          $transaction_type = 'withdraw';

          $statement = $this->db->prepare("INSERT INTO `bank_transaction_info` (store_id, is_substract, account_id, source_id, exp_category_id, ref_no, invoice_id, transaction_type, title, details, image, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
          $statement->execute(array(store_id(), 1, $account_id, $source_id, $exp_category_id, $ref_no, $loan['loan_id'], $transaction_type, $title, $details, $image, user_id(), date_time()));
		  $info_id = $this->db->lastInsertId();
			
          $statement = $this->db->prepare("INSERT INTO `bank_transaction_price` (store_id, info_id, ref_no, amount) VALUES (?, ?, ?, ?)");
          $statement->execute(array(store_id(), $info_id, $ref_no, $withdraw_amount));

          $statement = $this->db->prepare("UPDATE `bank_account_to_store` SET `withdraw` = `withdraw` + $withdraw_amount WHERE `store_id` = ? AND `account_id` = ?");
          $statement->execute(array(store_id(), $account_id));

          $statement = $this->db->prepare("UPDATE `bank_accounts` SET `total_deposit` = `total_deposit` + $withdraw_amount WHERE `id` = ?");
          $statement->execute(array($account_id));
        }

    	$statement = $this->db->prepare("DELETE FROM `loans` WHERE `loan_id` = ? LIMIT 1");
    	$statement->execute(array($loan_id));

    	$statement = $this->db->prepare("DELETE FROM `loan_payments` WHERE `lloan_id` = ?");
    	$statement->execute(array($loan_id));

        return $loan_id;
	}

	public function getLoan($loan_id) 
	{
		$statement = $this->db->prepare("SELECT * FROM `loans` WHERE `loans`.`loan_id` = ?");
	  	$statement->execute(array($loan_id));
	  	$loan = $statement->fetch(PDO::FETCH_ASSOC);

	    return $loan;
	}

	public function getLoanPayments($loan_id) 
	{
		$statement = $this->db->prepare("SELECT * FROM `loan_payments` WHERE `lloan_id` = ?");
	  	$statement->execute(array($loan_id));
	  	$payments = $statement->fetchAll(PDO::FETCH_ASSOC);
	    return $payments;
	}

	public function getLoans($data = array()) {


		$sql = "SELECT * FROM `loans`  WHERE `status` = ?";

		if (isset($data['filter_name'])) {
			$sql .= " AND `loan_name` LIKE '" . $data['filter_name'] . "%'";
		}

		if (isset($data['exclude'])) {
			$sql .= " AND `loan_id` != " . $data['exclude'];
		}

		$sql .= " GROUP BY `loans`.`loan_id`";

		$sort_data = array(
			'loan_name'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `loan_name`";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$statement = $this->db->prepare($sql);
		$statement->execute(array(1));

		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function totalLoan($from, $to) 
	{
		$where_query = "`status` = ?";
		if ($from) {
			$where_query .= date_range_loan_filter($from, $to);
		}
		$statement = $this->db->prepare("SELECT SUM(`payable`) as total FROM `loans` WHERE $where_query");
		$statement->execute(array(1));
		$row = $statement->fetch(PDO::FETCH_ASSOC);
		return $row['total'];
	}

	public function totalPaid($from, $to) 
	{
		$where_query = "`status` = ?";
		if ($from) {
			$where_query .= date_range_loan_filter($from, $to);
		}
		$statement = $this->db->prepare("SELECT SUM(`paid`) as total FROM `loans` WHERE $where_query");
		$statement->execute(array(1));
		$row = $statement->fetch(PDO::FETCH_ASSOC);
		return $row['total'];
	}

	public function totalDue($from, $to) 
	{
		$where_query = "`status` = ?";
		if ($from) {
			$where_query .= date_range_loan_filter($from, $to);
		}
		$statement = $this->db->prepare("SELECT SUM(`due`) as total FROM `loans` WHERE $where_query");
		$statement->execute(array(1));
		$row = $statement->fetch(PDO::FETCH_ASSOC);
		return $row['total'];
	}
}