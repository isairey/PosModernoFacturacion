<?php

class ModelGiftcard extends Model 
{
	public function addGiftcard($data) 
	{		
    	$statement = $this->db->prepare("INSERT INTO `gift_cards` (card_no, value, balance, customer_id, expiry, created_by) VALUES (?, ?, ?, ?, ?, ?)");
    	$statement->execute(array($data['card_no'], $data['giftcard_value'], $data['balance'], $data['customer_id'], $data['expiry'], user_id()));
    	$id = $this->db->lastInsertId();

    	$statement = $this->db->prepare("UPDATE `customers` SET `is_giftcard` = ? WHERE customer_id = ? ");
    	$statement->execute(array(1, $data['customer_id']));

		// Deposit
	    if (($account_id = store('deposit_account_id')) && $data['giftcard_value'] > 0) {
			$ref_no = unique_transaction_ref_no();
			$statement = $this->db->prepare("SELECT `source_id` FROM `income_sources` WHERE `for_giftcard_sell` = ?");
		    $statement->execute(array(1));
		    $source = $statement->fetch(PDO::FETCH_ASSOC);
		    $source_id = $source['source_id'];
		    $title = 'Deposit while giftcard sell';
		    $details = '';
		    $image = 'NULL';
		    $deposit_amount = $data['giftcard_value'];
		    $transaction_type = 'deposit';

		    $statement = $this->db->prepare("INSERT INTO `bank_transaction_info` (store_id, account_id, source_id, ref_no, invoice_id, transaction_type, title, details, image, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      		$statement->execute(array(store_id(), $account_id, $source_id, $ref_no, $id, $transaction_type, $title, $details, $image, user_id(), date_time()));
			$info_id = $this->db->lastInsertId();

		    $statement = $this->db->prepare("INSERT INTO `bank_transaction_price` (store_id, info_id, ref_no, amount) VALUES (?, ?, ?, ?)");
		    $statement->execute(array(store_id(), $info_id, $ref_no, $deposit_amount));

		    $statement = $this->db->prepare("UPDATE `bank_account_to_store` SET `deposit` = `deposit` + $deposit_amount WHERE `store_id` = ? AND `account_id` = ?");
		    $statement->execute(array(store_id(),$account_id));

		    $statement = $this->db->prepare("UPDATE `bank_accounts` SET `total_deposit` = `total_deposit` + $deposit_amount WHERE `id` = ?");
		    $statement->execute(array($account_id));
	    }

    	if ($data['balance'] > 0) {
    		$statement = $this->db->prepare("INSERT INTO `gift_card_topups` (date, card_id, amount, created_by) VALUES (?, ?, ?, ?)");
    		$statement->execute(array(date_time(), $data['card_no'], $data['balance'], user_id()));

    		// Deposit
		    if (($account_id = store('deposit_account_id')) && $data['balance'] > 0) {
				$ref_no = unique_transaction_ref_no();
				$statement = $this->db->prepare("SELECT `source_id` FROM `income_sources` WHERE `for_topup` = ?");
			    $statement->execute(array(1));
			    $source = $statement->fetch(PDO::FETCH_ASSOC);
			    $source_id = $source['source_id'];
			    $title = 'Deposit while giftcard topup';
			    $details = '';
			    $image = 'NULL';
			    $deposit_amount = $data['balance'];
			    $transaction_type = 'deposit';

			    $statement = $this->db->prepare("INSERT INTO `bank_transaction_info` (store_id, account_id, source_id, ref_no, invoice_id, transaction_type, title, details, image, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
	      		$statement->execute(array(store_id(), $account_id, $source_id, $ref_no, $id, $transaction_type, $title, $details, $image, user_id(), date_time()));
				$info_id = $this->db->lastInsertId();
				
			    $statement = $this->db->prepare("INSERT INTO `bank_transaction_price` (store_id, info_id, ref_no, amount) VALUES (?, ?, ?, ?)");
			    $statement->execute(array(store_id(), $info_id, $ref_no, $deposit_amount));

			    $statement = $this->db->prepare("UPDATE `bank_account_to_store` SET `deposit` = `deposit` + $deposit_amount WHERE `store_id` = ? AND `account_id` = ?");
			    $statement->execute(array(store_id(),$account_id));

			    $statement = $this->db->prepare("UPDATE `bank_accounts` SET `total_deposit` = `total_deposit` + $deposit_amount WHERE `id` = ?");
			    $statement->execute(array($account_id));
		    }
    	}

    	return $id; 
	}

	public function editGiftcard($id, $data) 
	{
    	$statement = $this->db->prepare("UPDATE `gift_cards` SET `card_no` = ?, `expiry` = ? WHERE id = ? ");
    	$statement->execute(array($data['card_no'], $data['expiry'], $id));
    	return $id;
	}

	public function topupGiftcard($card_no, $amount, $expiry) 
	{
		$statement = $this->db->prepare("UPDATE `gift_cards` SET `balance` = `balance` + $amount, `expiry` = ? WHERE `card_no` = ? ");
    	$statement->execute(array($expiry, $card_no));

    	$statement = $this->db->prepare("INSERT INTO `gift_card_topups` (date, card_id, amount, created_by) VALUES (?, ?, ?, ?)");
    	$statement->execute(array(date_time(), $card_no, $amount, user_id()));

    	// Deposit
	    if (($account_id = store('deposit_account_id')) && $amount > 0) {
			$ref_no = unique_transaction_ref_no();
			$statement = $this->db->prepare("SELECT `source_id` FROM `income_sources` WHERE `for_topup` = ?");
		    $statement->execute(array(1));
		    $source = $statement->fetch(PDO::FETCH_ASSOC);
		    $source_id = $source['source_id'];
		    $title = 'Deposit while giftcard topup';
		    $details = '';
		    $image = 'NULL';
		    $deposit_amount = $amount;
		    $transaction_type = 'deposit';

		    $statement = $this->db->prepare("INSERT INTO `bank_transaction_info` (store_id, account_id, source_id, ref_no, invoice_id, transaction_type, title, details, image, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      		$statement->execute(array(store_id(), $account_id, $source_id, $ref_no, $card_no, $transaction_type, $title, $details, $image, user_id(), date_time()));
			$info_id = $this->db->lastInsertId();
			
		    $statement = $this->db->prepare("INSERT INTO `bank_transaction_price` (store_id, info_id, ref_no, amount) VALUES (?, ?, ?, ?)");
		    $statement->execute(array(store_id(), $info_id, $ref_no, $deposit_amount));

		    $statement = $this->db->prepare("UPDATE `bank_account_to_store` SET `deposit` = `deposit` + $deposit_amount WHERE `store_id` = ? AND `account_id` = ?");
		    $statement->execute(array(store_id(),$account_id));

		    $statement = $this->db->prepare("UPDATE `bank_accounts` SET `total_deposit` = `total_deposit` + $deposit_amount WHERE `id` = ?");
		    $statement->execute(array($account_id));
	    }

    	return $card_no;
	}

	public function deleteGiftcard($id) 
	{
		$giftcard = $this->getGiftcard($id);
		if ($giftcard) {

	    	// Substract bank transaction
	        if (($account_id = store('deposit_account_id')) && $giftcard['value'] > 0) {
	          $ref_no = unique_transaction_ref_no('withdraw');
	          $statement = $this->db->prepare("SELECT `category_id` FROM `expense_categorys` WHERE `giftcard_sell_delete` = ?");
	          $statement->execute(array(1));
	          $category = $statement->fetch(PDO::FETCH_ASSOC);
	          $exp_category_id = $category['category_id'];
	          $statement = $this->db->prepare("SELECT `source_id` FROM `income_sources` WHERE `for_giftcard_sell` = ?");
	          $statement->execute(array(1));
	          $source = $statement->fetch(PDO::FETCH_ASSOC);
	          $source_id = $source['source_id'];
	          $title = 'Debit while deleting giftcard';
	          $details = '';
	          $image = 'NULL';
	          $withdraw_amount = $giftcard['value'];
	          $transaction_type = 'withdraw';

	          $statement = $this->db->prepare("INSERT INTO `bank_transaction_info` (store_id, is_substract, account_id, source_id, exp_category_id, ref_no, invoice_id, transaction_type, title, details, image, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
	          $statement->execute(array(store_id(), 1, $account_id, $source_id, $exp_category_id, $ref_no, $giftcard['id'], $transaction_type, $title, $details, $image, user_id(), date_time()));
			  $info_id = $this->db->lastInsertId();
			
	          $statement = $this->db->prepare("INSERT INTO `bank_transaction_price` (store_id, info_id, ref_no, amount) VALUES (?, ?, ?, ?)");
	          $statement->execute(array(store_id(), $info_id, $ref_no, $withdraw_amount));

	          $statement = $this->db->prepare("UPDATE `bank_account_to_store` SET `withdraw` = `withdraw` + $withdraw_amount WHERE `store_id` = ? AND `account_id` = ?");
	          $statement->execute(array(store_id(), $account_id));

	          $statement = $this->db->prepare("UPDATE `bank_accounts` SET `total_deposit` = `total_deposit` + $withdraw_amount WHERE `id` = ?");
	          $statement->execute(array($account_id));
	        }


	        // Substract bank transaction
		    if (($account_id = store('deposit_account_id')) && $giftcard['balance'] > 0) {
		      $ref_no = unique_transaction_ref_no('withdraw');
		      $statement = $this->db->prepare("SELECT `category_id` FROM `expense_categorys` WHERE `topup_delete` = ?");
		      $statement->execute(array(1));
		      $category = $statement->fetch(PDO::FETCH_ASSOC);
		      $exp_category_id = $category['category_id'];
		      $statement = $this->db->prepare("SELECT `source_id` FROM `income_sources` WHERE `for_topup` = ?");
		      $statement->execute(array(1));
		      $source = $statement->fetch(PDO::FETCH_ASSOC);
		      $source_id = $source['source_id'];
		      $title = 'Debit while deleting topup';
		      $details = '';
		      $image = 'NULL';
		      $withdraw_amount = $giftcard['balance'];
		      $transaction_type = 'withdraw';

		      $statement = $this->db->prepare("INSERT INTO `bank_transaction_info` (store_id, is_substract, account_id, source_id, exp_category_id, ref_no, invoice_id, transaction_type, title, details, image, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		      $statement->execute(array(store_id(), 1, $account_id, $source_id, $exp_category_id, $ref_no, $giftcard['id'], $transaction_type, $title, $details, $image, user_id(), date_time()));
			  $info_id = $this->db->lastInsertId();
					
		      $statement = $this->db->prepare("INSERT INTO `bank_transaction_price` (store_id, info_id, ref_no, amount) VALUES (?, ?, ?, ?)");
		      $statement->execute(array(store_id(), $info_id, $ref_no, $withdraw_amount));

		      $statement = $this->db->prepare("UPDATE `bank_account_to_store` SET `withdraw` = `withdraw` + $withdraw_amount WHERE `store_id` = ? AND `account_id` = ?");
		      $statement->execute(array(store_id(), $account_id));

		      $statement = $this->db->prepare("UPDATE `bank_accounts` SET `total_deposit` = `total_deposit` + $withdraw_amount WHERE `id` = ?");
		      $statement->execute(array($account_id));
		    }


			$statement = $this->db->prepare("DELETE FROM `gift_cards` WHERE `id` = ? LIMIT 1");
	    	$statement->execute(array($id));

	    	$statement = $this->db->prepare("DELETE FROM `gift_card_topups` WHERE `card_id` = ?");
	    	$statement->execute(array($giftcard['card_no']));

	    	$statement = $this->db->prepare("UPDATE `customers` SET `is_giftcard` = ? WHERE customer_id = ? ");
    		$statement->execute(array(0, $giftcard['customer_id']));
		}
        return $id;
	}

	public function getGiftcard($id, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$statement = $this->db->prepare("SELECT * FROM `gift_cards` WHERE (`id` = ? OR `card_no` = ?)");
	  	$statement->execute(array($id, $id));
	  	$giftcard = $statement->fetch(PDO::FETCH_ASSOC);
	    return $giftcard;
	}

	public function getGiftcards($data = array(), $store_id = null) {

		$store_id = $store_id ? $store_id : store_id();

		$sql = "SELECT * FROM `gift_cards` WHERE `expiry` > NOW()";

		if (isset($data['filter_card_no'])) {
			$sql .= " AND `card_no` = " . $data['filter_card_no'];
		}

		if (isset($data['exclude'])) {
			$sql .= " AND `id` != " . $data['exclude'];
		}

		$sql .= " GROUP BY `gift_cards`.`id`";

		$sort_data = array(
			'id',
			'card_no',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `id`";
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

	public function total($store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();

		$statement = $this->db->prepare("SELECT * FROM `gift_cards` WHERE `expiry` > NOW()");
		$statement->execute(array($store_id, 1));
		return $statement->rowCount();
	}

	public function totalPrice($from, $to, $store_id = null) 
	{
		$where_query = "1=1";
		if ($from) {
			$where_query .= date_range_giftcard_filter($from, $to);
		}
		$statement = $this->db->prepare("SELECT SUM(`value`) as total FROM `gift_cards` WHERE $where_query");
		$statement->execute(array());
		$row = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($row['total']) ? $row['total'] : 0;
	}

	public function totalTopup($from, $to) 
	{
		$where_query = "1=1";
		if ($from) {
			$where_query .= date_range_giftcard_topup_filter($from, $to);
		}
		$statement = $this->db->prepare("SELECT SUM(`amount`) as total FROM `gift_card_topups` WHERE $where_query");
		$statement->execute(array());
		$row = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($row['total']) ? $row['total'] : 0;
	}

	public function getCustomerBallance($customer_id)
	{
		$statement = $this->db->prepare("SELECT SUM(`balance`) as total FROM `gift_cards` WHERE `customer_id` = ?");
		$statement->execute(array($customer_id));
		$row = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($row['total']) ? $row['total'] : 0;
	}
}