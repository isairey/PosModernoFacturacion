
<?php

class ModelPosregister extends Model 
{
	public function addGiftcard($data) 
	{		
    	$statement = $this->db->prepare("INSERT INTO `gift_cards` (card_no, value, balance, customer_id, expiry, created_by) VALUES (?, ?, ?, ?, ?, ?)");
    	$statement->execute(array($data['card_no'], $data['giftcard_value'], $data['balance'], $data['customer_id'], $data['expiry'], user_id()));
    	$id = $this->db->lastInsertId();

    	$statement = $this->db->prepare("UPDATE `customers` SET `is_giftcard` = ? WHERE customer_id = ? ");
    	$statement->execute(array(1, $data['customer_id']));

    	if ($data['balance'] > 0) {
    		$statement = $this->db->prepare("INSERT INTO `gift_card_topups` (card_id, amount, created_by) VALUES (?, ?, ?)");
    		$statement->execute(array($data['card_no'], $data['balance'], user_id()));
    	}

    	return $id; 
	}

	public function editGiftcard($id, $data) 
	{
    	$statement = $this->db->prepare("UPDATE `gift_cards` SET `card_no` = ?, `value` = ?, `expiry` = ? WHERE id = ? ");
    	$statement->execute(array($data['card_no'], $data['giftcard_value'], $data['expiry'], $id));
    	return $id;
	}

	public function deleteGiftcard($id) 
	{
		$giftcard = $this->getGiftcard($id);
		if ($giftcard) {
			$statement = $this->db->prepare("DELETE FROM `gift_cards` WHERE `id` = ? LIMIT 1");
	    	$statement->execute(array($id));

	    	$statement = $this->db->prepare("DELETE FROM `gift_card_topups` WHERE `card_id` = ?");
	    	$statement->execute(array($giftcard['card_no']));

	    	$statement = $this->db->prepare("UPDATE `customers` SET `is_giftcard` = ? WHERE customer_id = ? ");
    		$statement->execute(array(0, $giftcard['customer_id']));
		}
        return $id;
	}

	public function getOpeningBalance($from,$store_id=null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		if (!$from) {
			$from = date('Y-m-d');
		}
		$day = date('d', strtotime($from));
		$month = date('m', strtotime($from));
		$year = date('Y', strtotime($from));
		$where_query = " DAY(`pos_register`.`created_at`) = $day";
		$where_query .= " AND MONTH(`pos_register`.`created_at`) = $month";
		$where_query .= " AND YEAR(`pos_register`.`created_at`) = $year";

		$statement = $this->db->prepare("SELECT `opening_balance` as total FROM `pos_register` WHERE $where_query AND `store_id`=? AND `status`=?");
		$statement->execute(array($store_id, 1));
		$row = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($row['total']) ? $row['total'] : 0;
	}

	public function getClosingBalance($from,$store_id=null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		if (!$from) {
			$from = date('Y-m-d');
		}
		$day = date('d', strtotime($from));
		$month = date('m', strtotime($from));
		$year = date('Y', strtotime($from));
		$where_query = " DAY(`pos_register`.`created_at`) = $day";
		$where_query .= " AND MONTH(`pos_register`.`created_at`) = $month";
		$where_query .= " AND YEAR(`pos_register`.`created_at`) = $year";

		$statement = $this->db->prepare("SELECT `closing_balance` as total FROM `pos_register` WHERE $where_query AND `store_id`=? AND `status`=?");
		$statement->execute(array($store_id, 1));
		$row = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($row['total']) ? $row['total'] : 0;
	}
}