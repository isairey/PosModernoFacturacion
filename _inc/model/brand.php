<?php

class ModelBrand extends Model 
{
	public function addBrand($data) 
	{
    	$statement = $this->db->prepare("INSERT INTO `brands` (brand_name, code_name, brand_details, brand_image, created_at) VALUES (?, ?, ?, ?, ?)");
    	$statement->execute(array($data['brand_name'], $data['code_name'], $data['brand_details'], $data['brand_image'], date_time()));
    	$brand_id = $this->db->lastInsertId();
    	if (isset($data['brand_store'])) {
			foreach ($data['brand_store'] as $store_id) {
				$statement = $this->db->prepare("INSERT INTO `brand_to_store` SET `brand_id` = ?, `store_id` = ?");
				$statement->execute(array((int)$brand_id, (int)$store_id));
			}
		}
		$this->updateStatus($brand_id, $data['status']);
		$this->updateSortOrder($brand_id, $data['sort_order']);
    	return $brand_id;   
	}

	public function updateStatus($brand_id, $status, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$statement = $this->db->prepare("UPDATE `brand_to_store` SET `status` = ? WHERE `store_id` = ? AND `brand_id` = ?");
		$statement->execute(array((int)$status, $store_id, (int)$brand_id));
	}

	public function updateSortOrder($brand_id, $sort_order, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$statement = $this->db->prepare("UPDATE `brand_to_store` SET `sort_order` = ? WHERE `store_id` = ? AND `brand_id` = ?");
		$statement->execute(array((int)$sort_order, $store_id, (int)$brand_id));
	}

	public function editBrand($brand_id, $data) 
	{
    	$statement = $this->db->prepare("UPDATE `brands` SET `brand_name` = ?, `code_name` = ?, `brand_details` = ?, `brand_image` = ? WHERE `brand_id` = ? ");
    	$statement->execute(array($data['brand_name'], $data['code_name'], $data['brand_details'], $data['brand_image'], $brand_id));
		
		// Insert brand into store
    	if (isset($data['brand_store'])) 
    	{
    		$store_ids = array();
			foreach ($data['brand_store'] as $store_id) {
				$statement = $this->db->prepare("SELECT * FROM `brand_to_store` WHERE `store_id` = ? AND `brand_id` = ?");
			    $statement->execute(array($store_id, $brand_id));
			    $brand = $statement->fetch(PDO::FETCH_ASSOC);
			    if (!$brand) {
			    	$statement = $this->db->prepare("INSERT INTO `brand_to_store` SET `brand_id` = ?, `store_id` = ?");
					$statement->execute(array((int)$brand_id, (int)$store_id));
			    }
			    $store_ids[] = $store_id;
			}

			// Delete unwanted store
			if (!empty($store_ids)) {

				$unremoved_store_ids = array();

				// get unwanted stores
				$statement = $this->db->prepare("SELECT * FROM `brand_to_store` WHERE `store_id` NOT IN (" . implode(',', $store_ids) . ")");
				$statement->execute();
				$unwanted_stores = $statement->fetchAll(PDO::FETCH_ASSOC);
				foreach ($unwanted_stores as $store) {

					$store_id = $store['store_id'];
					
					// Fetch purchase invoice id
				    $statement = $this->db->prepare("SELECT * FROM `product_to_store` as p2s WHERE `store_id` = ? AND `brand_id` = ?");
				    $statement->execute(array($store_id, $brand_id));
				    $item_available = $statement->fetch(PDO::FETCH_ASSOC);

				     // If item available then store in variable
				    if ($item_available) {
				      $unremoved_store_ids[$item_available['store_id']] = store_field('name', $item_available['store_id']);
				      continue;
				    }

				    // Delete unwanted store link
					$statement = $this->db->prepare("DELETE FROM `brand_to_store` WHERE `store_id` = ? AND `brand_id` = ?");
					$statement->execute(array($store_id, $brand_id));

				}

				if (!empty($unremoved_store_ids)) {

					throw new Exception('The Brand belongs to the stores(s) "' . implode(', ', $unremoved_store_ids) . '" has products, so its can not be removed');
				}				
			}
		}

		$this->updateStatus($brand_id, $data['status']);
		$this->updateSortOrder($brand_id, $data['sort_order']);

    	return $brand_id;
	}

	public function getBrandIdByCode($code_name)
	{
		$statement = $this->db->prepare("SELECT `brand_id` FROM `brands` WHERE `code_name` = ?");
		$statement->execute(array($code_name));
		$row = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($row['brand_id']) ? $row['brand_id'] : null;
	}

	public function deleteBrand($brand_id) 
	{
    	$statement = $this->db->prepare("DELETE FROM `brands` WHERE `brand_id` = ? LIMIT 1");
    	$statement->execute(array($brand_id));
        return $brand_id;
	}

	public function getBrand($brand_id, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$statement = $this->db->prepare("SELECT * FROM `brands`
			LEFT JOIN `brand_to_store` as b2s ON (`brands`.`brand_id` = `b2s`.`brand_id`)  
	    	WHERE `b2s`.`store_id` = ? AND `brands`.`brand_id` = ?");
	  	$statement->execute(array($store_id, $brand_id));
	    $brand = $statement->fetch(PDO::FETCH_ASSOC);

	    // Fetch stores related to brands
	    $statement = $this->db->prepare("SELECT `store_id` FROM `brand_to_store` WHERE `brand_id` = ?");
	    $statement->execute(array($brand_id));
	    $all_stores = $statement->fetchAll(PDO::FETCH_ASSOC);
	    $stores = array();
	    foreach ($all_stores as $store) {
	    	$stores[] = $store['store_id'];
	    }

	    $brand['stores'] = $stores;

	    return $brand;
	}

	public function getBrands($data = array(), $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();

		$sql = "SELECT * FROM `brands` LEFT JOIN `brand_to_store` b2s ON (`brands`.`brand_id` = `b2s`.`brand_id`) WHERE `b2s`.`store_id` = ? AND `b2s`.`status` = ?";

		if (isset($data['filter_name'])) {
			$sql .= " AND `brand_name` LIKE '" . $data['filter_name'] . "%'";
		}

		$sql .= " GROUP BY `brands`.`brand_id`";

		$sort_data = array(
			'brand_name'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY brand_name";
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

	public function getSellingPrice($brand_id, $from, $to, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();

		$where_query = "`selling_info`.`inv_type` != 'due_paid' AND `selling_item`.`brand_id` = ? AND `selling_item`.`store_id` = ?";
		$where_query .= date_range_filter($from, $to);

		$statement = $this->db->prepare("SELECT SUM(`selling_price`.`discount_amount`) as discount, SUM(`selling_price`.`subtotal`) as total FROM `selling_info` 
			LEFT JOIN `selling_item` ON (`selling_info`.`invoice_id` = `selling_item`.`invoice_id`) 
			LEFT JOIN `selling_price` ON (`selling_info`.`invoice_id` = `selling_price`.`invoice_id`) 
			WHERE $where_query");

		$statement->execute(array($brand_id, $store_id));
		$invoice = $statement->fetch(PDO::FETCH_ASSOC);

		return (int)($invoice['total'] - $invoice['discount']);
	}

	public function getpurchasePrice($brand_id, $from, $to, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();

		$where_query = "`purchase_info`.`inv_type` != 'others' AND `purchase_item`.`brand_id` = ? AND `purchase_item`.`store_id` = ?";
		$where_query .= date_range_filter2($from, $to);

		$statement = $this->db->prepare("SELECT SUM(`purchase_price`.`paid_amount`) as total FROM `purchase_info` 
			LEFT JOIN `purchase_item` ON (`purchase_info`.`invoice_id` = `purchase_item`.`invoice_id`) 
			LEFT JOIN `purchase_price` ON (`purchase_info`.`invoice_id` = `purchase_price`.`invoice_id`) 
			WHERE $where_query");
		$statement->execute(array($brand_id, $store_id));
		$purchase_price = $statement->fetch(PDO::FETCH_ASSOC);

		return (int)$purchase_price['total'];
	}

	public function getBelongsStore($brand_id)
	{
		$statement = $this->db->prepare("SELECT * FROM `brand_to_store` WHERE `brand_id` = ?");
		$statement->execute(array($brand_id));

		return $statement->fetchAll(PDO::FETCH_ASSOC);

	}

	public function totalSell($brand_id, $from = null, $to = null, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`selling_info`.`store_id` = $store_id AND `selling_item`.`brand_id` = $brand_id";
		if (from()) {
			$where_query .= date_range_filter($from, $to);
		}
		$statement = $this->db->prepare("SELECT SUM(`selling_item`.`item_total`) AS total FROM `selling_info` LEFT JOIN `selling_item` ON (`selling_info`.`invoice_id` = `selling_item`.`invoice_id`) WHERE $where_query GROUP BY `selling_item`.`brand_id`");
		$statement->execute(array());
		$row = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($row['total']) ? $row['total'] : 0;
	}

	public function totalProduct($brand_id, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$statement = $this->db->prepare("SELECT * FROM `product_to_store` WHERE `store_id` = ? AND `brand_id` = ? AND `status` = ?");
		$statement->execute(array($store_id, $brand_id, 1));
		return $statement->rowCount();
	}

	public function totalToday($store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`b2s`.`store_id` = {$store_id} AND `b2s`.`status` = 1";
		$from = date('Y-m-d');
		$to = date('Y-m-d');
		if (($from && ($to == false)) || ($from == $to)) {
			$day = date('d', strtotime($from));
			$month = date('m', strtotime($from));
			$year = date('Y', strtotime($from));
			$where_query .= " AND DAY(`brands`.`created_at`) = $day";
			$where_query .= " AND MONTH(`brands`.`created_at`) = $month";
			$where_query .= " AND YEAR(`brands`.`created_at`) = $year";
		} else {
			$from = date('Y-m-d H:i:s', strtotime($from.' '. '00:00:00')); 
			$to = date('Y-m-d H:i:s', strtotime($to.' '. '23:59:59'));
			$where_query .= " AND brands.created_at >= '{$from}' AND brands.created_at <= '{$to}'";
		}
		$statement = $this->db->prepare("SELECT * FROM `brands` LEFT JOIN `brand_to_store` b2s ON (`brands`.`brand_id` = `b2s`.`brand_id`) WHERE $where_query");
		$statement->execute(array());
		return $statement->rowCount();
	}

	public function total($from, $to, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`b2s`.`store_id` = {$store_id} AND `b2s`.`status` = 1";
		if ($from) {
			$from = $from ? $from : date('Y-m-d');
			$to = $to ? $to : date('Y-m-d');
			if (($from && ($to == false)) || ($from == $to)) {
				$day = date('d', strtotime($from));
				$month = date('m', strtotime($from));
				$year = date('Y', strtotime($from));
				$where_query .= " AND DAY(`brands`.`created_at`) = $day";
				$where_query .= " AND MONTH(`brands`.`created_at`) = $month";
				$where_query .= " AND YEAR(`brands`.`created_at`) = $year";
			} else {
				$from = date('Y-m-d H:i:s', strtotime($from.' '. '00:00:00')); 
				$to = date('Y-m-d H:i:s', strtotime($to.' '. '23:59:59'));
				$where_query .= " AND brands.created_at >= '{$from}' AND brands.created_at <= '{$to}'";
			}
		}
		$statement = $this->db->prepare("SELECT * FROM `brands` LEFT JOIN `brand_to_store` b2s ON (`brands`.`brand_id` = `b2s`.`brand_id`) WHERE $where_query");
		$statement->execute(array());
		return $statement->rowCount();
	}
}