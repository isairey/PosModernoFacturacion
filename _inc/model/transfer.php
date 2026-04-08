<?php

class ModelTransfer extends Model 
{
	public function getTransfers($store_id = null, $limit = 100000) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$statement = $this->db->prepare("SELECT * FROM `transfers` 
			WHERE (`transfers`.`from_store_id` = ? OR `transfers`.`to_store_id` = ?) ORDER BY `transfers`.`created_at` DESC LIMIT $limit");
		$statement->execute(array($store_id, $store_id));
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getTransferInfo($id, $store_id = null)
    {
        $store_id = $store_id ? $store_id : store_id();
        $statement = $this->db->prepare("SELECT `transfers`.* FROM `transfers` WHERE `transfers`.`id` = ?");
        $statement->execute(array($id));
        $invoice = $statement->fetch(PDO::FETCH_ASSOC);
        $invoice['created_by'] = get_the_user($invoice['created_by'], 'username');
        return $invoice;
    }

	public function getTransferItems($transfer_id)
    {
        $statement = $this->db->prepare("SELECT * FROM `transfer_items` WHERE `transfer_id` = ?");
        $statement->execute(array($transfer_id));
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        $array = array();
        $i = 0;
        foreach ($rows as $row) {
            $array[$i] = $row;
            $array[$i]['unit_name'] = get_the_unit(get_the_product($row['product_id'], 'unit_id'), 'unit_name');
            $i++;
        }
        return $array;
    }
}