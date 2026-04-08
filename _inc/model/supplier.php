<?php

class ModelSupplier extends Model 
{
	public function addSupplier($data) 
	{
		$gtin = isset($data['gtin']) ? $data['gtin'] : '';
		$sup_state = isset($data['sup_state']) ? $data['sup_state'] : '';
    	$statement = $this->db->prepare("INSERT INTO `suppliers` (sup_name, code_name, sup_mobile, sup_email, gtin, sup_address, sup_city, sup_state, sup_country, sup_details, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    	$statement->execute(array($data['sup_name'], $data['code_name'], $data['sup_mobile'], $data['sup_email'], $gtin, $data['sup_address'], $data['sup_city'],  $sup_state,  $data['sup_country'], $data['sup_details'], date_time()));

    	$sup_id = $this->db->lastInsertId();

    	if (isset($data['supplier_store'])) {
			foreach ($data['supplier_store'] as $store_id) {
				$statement = $this->db->prepare("INSERT INTO `supplier_to_store` SET `sup_id` = ?, `store_id` = ?");
				$statement->execute(array((int)$sup_id, (int)$store_id));
			}
		}

		$this->updateStatus($sup_id, $data['status']);
		$this->updateSortOrder($sup_id, $data['sort_order']);

    	return $sup_id;   
	}

	public function updateStatus($sup_id, $status, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();

		$statement = $this->db->prepare("UPDATE `supplier_to_store` SET `status` = ? WHERE `store_id` = ? AND `sup_id` = ?");
		$statement->execute(array((int)$status, $store_id, (int)$sup_id));
	}

	public function updateSortOrder($sup_id, $sort_order, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();

		$statement = $this->db->prepare("UPDATE `supplier_to_store` SET `sort_order` = ? WHERE `store_id` = ? AND `sup_id` = ?");
		$statement->execute(array((int)$sort_order, $store_id, (int)$sup_id));
	}

	public function editSupplier($sup_id, $data) 
	{
		$gtin = isset($data['gtin']) ? $data['gtin'] : '';
		$sup_state = isset($data['sup_state']) ? $data['sup_state'] : '';
    	$statement = $this->db->prepare("UPDATE `suppliers` SET `sup_name` = ?, `code_name` = ?, `sup_mobile` = ?, `sup_email` = ?, `gtin` = ?, `sup_address` = ?, `sup_city` = ?, `sup_state` = ?, `sup_country` = ?, `sup_details` = ? WHERE `sup_id` = ? ");
    	$statement->execute(array($data['sup_name'], $data['code_name'], $data['sup_mobile'], $data['sup_email'], $gtin, $data['sup_address'], $data['sup_city'], $sup_state, $data['sup_country'], $data['sup_details'], $sup_id));
		
		// Insert supplier into store
    	if (isset($data['supplier_store'])) {

    		$store_ids = array();

			foreach ($data['supplier_store'] as $store_id) {

				$statement = $this->db->prepare("SELECT * FROM `supplier_to_store` WHERE `store_id` = ? AND `sup_id` = ?");
			    $statement->execute(array($store_id, $sup_id));
			    $supplier = $statement->fetch(PDO::FETCH_ASSOC);
			    if (!$supplier) {
			    	$statement = $this->db->prepare("INSERT INTO `supplier_to_store` SET `sup_id` = ?, `store_id` = ?");
					$statement->execute(array((int)$sup_id, (int)$store_id));
			    }

			    $store_ids[] = $store_id;
			}

			// Delete unwanted store
			if (!empty($store_ids)) {

				$unremoved_store_ids = array();

				// get unwanted stores
				$statement = $this->db->prepare("SELECT * FROM `supplier_to_store` WHERE `store_id` NOT IN (" . implode(',', $store_ids) . ")");
				$statement->execute();
				$unwanted_stores = $statement->fetchAll(PDO::FETCH_ASSOC);
				foreach ($unwanted_stores as $store) {

					$store_id = $store['store_id'];
					
					// Fetch purchase invoice id
				    $statement = $this->db->prepare("SELECT * FROM `product_to_store` as p2s WHERE `store_id` = ? AND `sup_id` = ?");
				    $statement->execute(array($store_id, $sup_id));
				    $item_available = $statement->fetch(PDO::FETCH_ASSOC);

				     // If item available then store in variable
				    if ($item_available) {
				      $unremoved_store_ids[$item_available['store_id']] = store_field('name', $item_available['store_id']);
				      continue;
				    }

				    // Delete unwanted store link
					$statement = $this->db->prepare("DELETE FROM `supplier_to_store` WHERE `store_id` = ? AND `sup_id` = ?");
					$statement->execute(array($store_id, $sup_id));

				}

				if (!empty($unremoved_store_ids)) {

					throw new Exception('The Supplier belongs to the stores(s) "' . implode(', ', $unremoved_store_ids) . '" has products, so its can not be removed');
				}				
			}
		}

		$this->updateStatus($sup_id, $data['status']);
		$this->updateSortOrder($sup_id, $data['sort_order']);

    	return $sup_id;
	}

	public function getSupplierIdByCode($code_name)
	{
		$statement = $this->db->prepare("SELECT `sup_id` FROM `suppliers` WHERE `code_name` = ?");
		$statement->execute(array($code_name));
		$row = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($row['sup_id']) ? $row['sup_id'] : null;
	}

	public function deleteSupplier($sup_id) 
	{
    	$statement = $this->db->prepare("DELETE FROM `suppliers` WHERE `sup_id` = ? LIMIT 1");
    	$statement->execute(array($sup_id));

    	$statement = $this->db->prepare("DELETE FROM `supplier_to_store` WHERE `sup_id` = ?");
    	$statement->execute(array($sup_id));

        return $sup_id;
	}

	public function getSupplier($sup_id, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();

		$statement = $this->db->prepare("SELECT * FROM `suppliers`
			LEFT JOIN `supplier_to_store` as s2s ON (`suppliers`.`sup_id` = `s2s`.`sup_id`)  
	    	WHERE `s2s`.`store_id` = ? AND `suppliers`.`sup_id` = ?");
	  	$statement->execute(array($store_id, $sup_id));
	    $supplier = $statement->fetch(PDO::FETCH_ASSOC);

	    // Fetch stores related to suppliers
	    $statement = $this->db->prepare("SELECT `store_id` FROM `supplier_to_store` WHERE `sup_id` = ?");
	    $statement->execute(array($sup_id));
	    $all_stores = $statement->fetchAll(PDO::FETCH_ASSOC);
	    $stores = array();
	    foreach ($all_stores as $store) {
	    	$stores[] = $store['store_id'];
	    }

	    $supplier['stores'] = $stores;

	    return $supplier;
	}

	public function getSuppliers($data = array(), $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();

		$sql = "SELECT * FROM `suppliers` LEFT JOIN `supplier_to_store` s2s ON (`suppliers`.`sup_id` = `s2s`.`sup_id`) WHERE `s2s`.`store_id` = ? AND `s2s`.`status` = ?";

		if (isset($data['filter_name'])) {
			$sql .= " AND `sup_name` LIKE '" . $data['filter_name'] . "%'";
		}

		$sql .= " GROUP BY `suppliers`.`sup_id`";

		$sort_data = array(
			'sup_name'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `suppliers`.`sup_id`";
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
		$statement->execute(array($store_id, 1));

		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getSellingPrice($sup_id, $from, $to, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();

		$where_query = "`selling_info`.`inv_type` != 'due_paid' AND `selling_item`.`sup_id` = ? AND `selling_item`.`store_id` = ?";
		$where_query .= date_range_filter($from, $to);

		$statement = $this->db->prepare("SELECT SUM(`selling_price`.`discount_amount`) as discount, SUM(`selling_price`.`subtotal`) as total FROM `selling_info` 
			LEFT JOIN `selling_item` ON (`selling_info`.`invoice_id` = `selling_item`.`invoice_id`) 
			LEFT JOIN `selling_price` ON (`selling_info`.`invoice_id` = `selling_price`.`invoice_id`) 
			WHERE $where_query");

		$statement->execute(array($sup_id, $store_id));
		$invoice = $statement->fetch(PDO::FETCH_ASSOC);

		return (int)($invoice['total'] - $invoice['discount']);
	}

	public function getpurchasePrice($sup_id, $from, $to, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();

		$where_query = "`purchase_info`.`inv_type` != 'others' AND `purchase_info`.`sup_id` = ? AND `purchase_item`.`store_id` = ?";
		$where_query .= date_range_filter2($from, $to);

		$statement = $this->db->prepare("SELECT SUM(`purchase_price`.`paid_amount`) as total FROM `purchase_info` 
			LEFT JOIN `purchase_item` ON (`purchase_info`.`invoice_id` = `purchase_item`.`invoice_id`) 
			LEFT JOIN `purchase_price` ON (`purchase_info`.`invoice_id` = `purchase_price`.`invoice_id`) 
			WHERE $where_query");
		$statement->execute(array($sup_id, $store_id));
		$purchase_price = $statement->fetch(PDO::FETCH_ASSOC);

		return (int)$purchase_price['total'];
	}

	public function getBelongsStore($sup_id)
	{
		$statement = $this->db->prepare("SELECT * FROM `supplier_to_store` WHERE `sup_id` = ?");
		$statement->execute(array($sup_id));

		return $statement->fetchAll(PDO::FETCH_ASSOC);

	}

	public function totalProduct($sup_id, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$statement = $this->db->prepare("SELECT * FROM `product_to_store` WHERE `store_id` = ? AND `sup_id` = ? AND `status` = ?");
		$statement->execute(array($store_id, $sup_id, 1));
		return $statement->rowCount();
	}

	public function totalInvoice($sup_id = null, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		if ($sup_id) {
			$statement = $this->db->prepare("SELECT 'item_id' FROM `purchase_info` WHERE `store_id` = ? AND `sup_id` = ? AND `purchase_info`.`is_visible` = ?");
			$statement->execute(array($store_id, $sup_id, 1));
		} else {
			$statement = $this->db->prepare("SELECT `info_id` FROM `purchase_info` WHERE `store_id` = ? AND `purchase_info`.`is_visible` = ?");
			$statement->execute(array($store_id, 1));
		}

		return $statement->rowCount();
	}

	public function totalAmount($sup_id = null, $store_id = null)
	{
		$store_id = $store_id ? $store_id : store_id();
		$sql = "SELECT SUM(`balance`)as total FROM `supplier_to_store` 
		WHERE `store_id` = ?";
		if ($sup_id) {
			$sql .= " AND `sup_id` = $sup_id";
		}
		$sql .= " GROUP BY `sup_id`";
		$statement = $this->db->prepare($sql);
		$statement->execute(array($store_id));
		$invoice = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($invoice['total']) ? $invoice['total'] : 0;
	}

	public function totalToday($store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`s2s`.`store_id` = {$store_id} AND `s2s`.`status` = 1";
		$from = date('Y-m-d');
		$to = date('Y-m-d');
		if (($from && ($to == false)) || ($from == $to)) {
			$day = date('d', strtotime($from));
			$month = date('m', strtotime($from));
			$year = date('Y', strtotime($from));
			$where_query .= " AND DAY(`suppliers`.`created_at`) = $day";
			$where_query .= " AND MONTH(`suppliers`.`created_at`) = $month";
			$where_query .= " AND YEAR(`suppliers`.`created_at`) = $year";
		} else {
			$from = date('Y-m-d H:i:s', strtotime($from.' '. '00:00:00')); 
			$to = date('Y-m-d H:i:s', strtotime($to.' '. '23:59:59'));
			$where_query .= " AND suppliers.created_at >= '{$from}' AND suppliers.created_at <= '{$to}'";
		}
		$statement = $this->db->prepare("SELECT * FROM `suppliers` LEFT JOIN `supplier_to_store` s2s ON (`suppliers`.`sup_id` = `s2s`.`sup_id`) WHERE $where_query");
		$statement->execute(array());
		return $statement->rowCount();
	}

	public function total($from, $to, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`s2s`.`store_id` = {$store_id} AND `s2s`.`status` = 1";
		if ($from) {
			$from = $from ? $from : date('Y-m-d');
			$to = $to ? $to : date('Y-m-d');
			if (($from && ($to == false)) || ($from == $to)) {
				$day = date('d', strtotime($from));
				$month = date('m', strtotime($from));
				$year = date('Y', strtotime($from));
				$where_query .= " AND DAY(`suppliers`.`created_at`) = $day";
				$where_query .= " AND MONTH(`suppliers`.`created_at`) = $month";
				$where_query .= " AND YEAR(`suppliers`.`created_at`) = $year";
			} else {
				$from = date('Y-m-d H:i:s', strtotime($from.' '. '00:00:00')); 
				$to = date('Y-m-d H:i:s', strtotime($to.' '. '23:59:59'));
				$where_query .= " AND suppliers.created_at >= '{$from}' AND suppliers.created_at <= '{$to}'";
			}
		}
		$statement = $this->db->prepare("SELECT * FROM `suppliers` LEFT JOIN `supplier_to_store` s2s ON (`suppliers`.`sup_id` = `s2s`.`sup_id`) WHERE $where_query");
		$statement->execute(array());
		return $statement->rowCount();
	}
}