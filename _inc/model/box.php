<?php

class ModelBox extends Model 
{
	public function addBox($data) 
	{
    	$statement = $this->db->prepare("INSERT INTO `boxes` (box_name, code_name, box_details) VALUES (?, ?, ?)");
    	$statement->execute(array($data['box_name'],$data['code_name'], $data['box_details']));
    	$box_id = $this->db->lastInsertId();
    	if (isset($data['box_store'])) {
			foreach ($data['box_store'] as $store_id) {
				$statement = $this->db->prepare("INSERT INTO `box_to_store` SET `box_id` = ?, `store_id` = ?");
				$statement->execute(array((int)$box_id, (int)$store_id));
			}
		}

		$this->updateStatus($box_id, $data['status']);
		$this->updateSortOrder($box_id, $data['sort_order']);

    	return $box_id; 
	}

	public function updateStatus($box_id, $status, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();

		$statement = $this->db->prepare("UPDATE `box_to_store` SET `status` = ? WHERE `store_id` = ? AND `box_id` = ?");
		$statement->execute(array((int)$status, $store_id, (int)$box_id));
	}

	public function updateSortOrder($box_id, $sort_order, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();

		$statement = $this->db->prepare("UPDATE `box_to_store` SET `sort_order` = ? WHERE `store_id` = ? AND `box_id` = ?");
		$statement->execute(array((int)$sort_order, $store_id, (int)$box_id));
	}

	public function editBox($box_id, $data) 
	{
    	$statement = $this->db->prepare("UPDATE `boxes` SET `box_name` = ?, `code_name` = ?, `box_details` = ? WHERE box_id = ? ");
    	$statement->execute(array($data['box_name'], $data['code_name'], $data['box_details'], $box_id));
		
		// Insert box into store
    	if (isset($data['box_store'])) {

    		$store_ids = array();

			foreach ($data['box_store'] as $store_id) {

				$statement = $this->db->prepare("SELECT * FROM `box_to_store` WHERE `store_id` = ? AND `box_id` = ?");
			    $statement->execute(array($store_id, $box_id));
			    $box = $statement->fetch(PDO::FETCH_ASSOC);
			    if (!$box) {
			    	$statement = $this->db->prepare("INSERT INTO `box_to_store` SET `box_id` = ?, `store_id` = ?");
					$statement->execute(array((int)$box_id, (int)$store_id));
			    }

			    $store_ids[] = $store_id;
			}

			// Delete unwanted store
			if (!empty($store_ids)) {

				$unremoved_store_ids = array();

				// get unwanted stores
				$statement = $this->db->prepare("SELECT * FROM `box_to_store` WHERE `store_id` NOT IN (" . implode(',', $store_ids) . ")");
				$statement->execute();
				$unwanted_stores = $statement->fetchAll(PDO::FETCH_ASSOC);
				foreach ($unwanted_stores as $store) {

					$store_id = $store['store_id'];
					
					// Fetch purchase invoice id
				    $statement = $this->db->prepare("SELECT * FROM `product_to_store` as p2s WHERE `store_id` = ? AND `box_id` = ?");
				    $statement->execute(array($store_id, $box_id));
				    $item_available = $statement->fetch(PDO::FETCH_ASSOC);

				     // If item available then store in variable
				    if ($item_available) {
				      $unremoved_store_ids[$item_available['store_id']] = store_field('name', $item_available['store_id']);
				      continue;
				    }

				    // Delete unwanted store link
					$statement = $this->db->prepare("DELETE FROM `box_to_store` WHERE `store_id` = ? AND `box_id` = ?");
					$statement->execute(array($store_id, $box_id));

				}

				if (!empty($unremoved_store_ids)) {

					throw new Exception('The Box belongs to the stores(s) "' . implode(', ', $unremoved_store_ids) . '" contains products, so its can not be removed');
				}				
			}
		}

		$this->updateStatus($box_id, $data['status']);
		$this->updateSortOrder($box_id, $data['sort_order']);

    	return $box_id;
	}

	public function getBoxIdByCode($code_name)
	{
		$statement = $this->db->prepare("SELECT `box_id` FROM `boxes` WHERE `code_name` = ?");
		$statement->execute(array($code_name));
		$row = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($row['box_id']) ? $row['box_id'] : null;
	}

	public function deleteBox($box_id) 
	{
    	$statement = $this->db->prepare("DELETE FROM `boxes` WHERE `box_id` = ? LIMIT 1");
    	$statement->execute(array($box_id));

    	$statement = $this->db->prepare("DELETE FROM `box_to_store` WHERE `box_id` = ?");
    	$statement->execute(array($box_id));

        return $box_id;
	}

	public function getBox($box_id, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$statement = $this->db->prepare("SELECT * FROM `boxes`
			LEFT JOIN `box_to_store` as b2s ON (`boxes`.`box_id` = `b2s`.`box_id`)  
	    	WHERE `b2s`.`store_id` = ? AND `boxes`.`box_id` = ?");
	  	$statement->execute(array($store_id, $box_id));
	  	$box = $statement->fetch(PDO::FETCH_ASSOC);

	    // Fetch stores related to boxes
	    $statement = $this->db->prepare("SELECT `store_id` FROM `box_to_store` WHERE `box_id` = ?");
	    $statement->execute(array($box_id));
	    $all_stores = $statement->fetchAll(PDO::FETCH_ASSOC);
	    $stores = array();
	    foreach ($all_stores as $store) {
	    	$stores[] = $store['store_id'];
	    }
	    $box['stores'] = $stores;
	    return $box;
	}

	public function getBoxes($data = array(), $store_id = null) {

		$store_id = $store_id ? $store_id : store_id();

		$sql = "SELECT * FROM `boxes` LEFT JOIN `box_to_store` b2s ON (`boxes`.`box_id` = `b2s`.`box_id`) WHERE `b2s`.`store_id` = ? AND `b2s`.`status` = ?";

		if (isset($data['filter_name'])) {
			$sql .= " AND `box_name` LIKE '" . $data['filter_name'] . "%'";
		}

		if (isset($data['exclude'])) {
			$sql .= " AND `box_id` != " . $data['exclude'];
		}

		$sql .= " GROUP BY `boxes`.`box_id`";

		$sort_data = array(
			'box_name'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `box_name`";
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

	public function getBelongsStore($box_id)
	{
		$statement = $this->db->prepare("SELECT * FROM `box_to_store` WHERE `box_id` = ?");
		$statement->execute(array($box_id));

		return $statement->fetchAll(PDO::FETCH_ASSOC);

	}

	public function total($store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();

		$statement = $this->db->prepare("SELECT * FROM `boxes`LEFT JOIN `box_to_store` b2s ON (`boxes`.`box_id` = `b2s`.`box_id`) where `b2s`.`store_id` = ? AND `b2s`.`status` = ?");
		$statement->execute(array($store_id, 1));
		
		return $statement->rowCount();
	}

	public function totalProduct($box_id, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();

		$statement = $this->db->prepare("SELECT * FROM `products` p LEFT JOIN `product_to_store` p2s ON (`p`.`p_id` = `p2s`.`product_id`) WHERE `store_id` = ? AND `box_id` = ?");
		$statement->execute(array($store_id, $box_id));
	
		return $statement->rowCount();
	}
}