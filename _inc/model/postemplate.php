<?php

class ModelPostemplate extends Model 
{
	public function addTemplate($data) 
	{
    	$statement = $this->db->prepare("INSERT INTO `pos_templates` (template_name, template_content) VALUES (?, ?)");
    	$statement->execute(array($data['template_name'], $data['template_content']));
    	$template_id = $this->db->lastInsertId();
    	if (isset($data['box_store'])) {
			foreach ($data['box_store'] as $store_id) {
				$statement = $this->db->prepare("INSERT INTO `pos_template_to_store` SET `ttemplate_id` = ?, `store_id` = ?");
				$statement->execute(array((int)$template_id, (int)$store_id));
			}
		}

		$this->updateStatus($template_id, $data['status']);
		$this->updateSortOrder($template_id, $data['sort_order']);

    	return $template_id; 
	}

	public function updateStatus($template_id, $status, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();

		$statement = $this->db->prepare("UPDATE `pos_template_to_store` SET `status` = ? WHERE `store_id` = ? AND `ttemplate_id` = ?");
		$statement->execute(array((int)$status, $store_id, (int)$template_id));
	}

	public function updateSortOrder($template_id, $sort_order, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();

		$statement = $this->db->prepare("UPDATE `pos_template_to_store` SET `sort_order` = ? WHERE `store_id` = ? AND `ttemplate_id` = ?");
		$statement->execute(array((int)$sort_order, $store_id, (int)$template_id));
	}

	public function editTemplate($template_id, $data) 
	{
    	$statement = $this->db->prepare("UPDATE `pos_templates` SET `template_name` = ?, `template_content` = ? WHERE template_id = ? ");
    	$statement->execute(array($data['template_name'], $data['template_content'], $template_id));
		
		// Insert box into store
    	if (isset($data['box_store'])) {

    		$store_ids = array();

			foreach ($data['box_store'] as $store_id) {

				$statement = $this->db->prepare("SELECT * FROM `pos_template_to_store` WHERE `store_id` = ? AND `ttemplate_id` = ?");
			    $statement->execute(array($store_id, $template_id));
			    $box = $statement->fetch(PDO::FETCH_ASSOC);
			    if (!$box) {
			    	$statement = $this->db->prepare("INSERT INTO `pos_template_to_store` SET `ttemplate_id` = ?, `store_id` = ?");
					$statement->execute(array((int)$template_id, (int)$store_id));
			    }

			    $store_ids[] = $store_id;
			}

			// Delete unwanted store
			if (!empty($store_ids)) {

				$unremoved_store_ids = array();

				// get unwanted stores
				$statement = $this->db->prepare("SELECT * FROM `pos_template_to_store` WHERE `store_id` NOT IN (" . implode(',', $store_ids) . ")");
				$statement->execute();
				$unwanted_stores = $statement->fetchAll(PDO::FETCH_ASSOC);
				foreach ($unwanted_stores as $store) {

					$store_id = $store['store_id'];
					
					// Fetch purchase invoice id
				    $statement = $this->db->prepare("SELECT * FROM `product_to_store` as p2s WHERE `store_id` = ? AND `template_id` = ?");
				    $statement->execute(array($store_id, $template_id));
				    $item_available = $statement->fetch(PDO::FETCH_ASSOC);

				     // If item available then store in variable
				    if ($item_available) {
				      $unremoved_store_ids[$item_available['store_id']] = store_field('name', $item_available['store_id']);
				      continue;
				    }

				    // Delete unwanted store link
					$statement = $this->db->prepare("DELETE FROM `pos_template_to_store` WHERE `store_id` = ? AND `ttemplate_id` = ?");
					$statement->execute(array($store_id, $template_id));

				}

				if (!empty($unremoved_store_ids)) {

					throw new Exception('The Template belongs to the stores(s) "' . implode(', ', $unremoved_store_ids) . '" contains products, so its can not be removed');
				}				
			}
		}

		$this->updateStatus($template_id, $data['status']);
		$this->updateSortOrder($template_id, $data['sort_order']);

    	return $template_id;
    
	}

	public function deleteTemplate($template_id) 
	{
    	$statement = $this->db->prepare("DELETE FROM `pos_templates` WHERE `template_id` = ? LIMIT 1");
    	$statement->execute(array($template_id));

    	$statement = $this->db->prepare("DELETE FROM `pos_template_to_store` WHERE `ttemplate_id` = ?");
    	$statement->execute(array($template_id));

        return $template_id;
	}

	public function getTemplate($template_id, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();

		$statement = $this->db->prepare("SELECT * FROM `pos_templates`
			LEFT JOIN `pos_template_to_store` as pt2s ON (`pos_templates`.`template_id` = `pt2s`.`ttemplate_id`)  
	    	WHERE `pt2s`.`store_id` = ? AND `pos_templates`.`template_id` = ?");
	  	$statement->execute(array($store_id, $template_id));
	  	$box = $statement->fetch(PDO::FETCH_ASSOC);

	    // Fetch stores related to boxs
	    $statement = $this->db->prepare("SELECT `store_id` FROM `pos_template_to_store` WHERE `ttemplate_id` = ?");
	    $statement->execute(array($template_id));
	    $all_stores = $statement->fetchAll(PDO::FETCH_ASSOC);
	    $stores = array();
	    foreach ($all_stores as $store) {
	    	$stores[] = $store['store_id'];
	    }

	    $box['stores'] = $stores;

	    return $box;
	}

	public function getTemplates($data = array(), $store_id = null) {

		$store_id = $store_id ? $store_id : store_id();

		$sql = "SELECT * FROM `pos_templates` LEFT JOIN `pos_template_to_store` pt2s ON (`pos_templates`.`template_id` = `pt2s`.`ttemplate_id`) WHERE `pt2s`.`store_id` = ? AND `pt2s`.`status` = ?";

		if (isset($data['filter_name'])) {
			$sql .= " AND `template_name` LIKE '" . $data['filter_name'] . "%'";
		}

		if (isset($data['exclude'])) {
			$sql .= " AND `template_id` != " . $data['exclude'];
		}

		$sql .= " GROUP BY `pos_templates`.`template_id`";

		$sort_data = array(
			'template_name',
			'sort_order',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `sort_order`";
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

	public function getBelongsStore($template_id)
	{
		$statement = $this->db->prepare("SELECT * FROM `pos_template_to_store` WHERE `ttemplate_id` = ?");
		$statement->execute(array($template_id));

		return $statement->fetchAll(PDO::FETCH_ASSOC);

	}

	public function total($store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();

		$statement = $this->db->prepare("SELECT * FROM `pos_templates`LEFT JOIN `pos_template_to_store` pt2s ON (`pos_templates`.`template_id` = `pt2s`.`ttemplate_id`) where `pt2s`.`store_id` = ? AND `pt2s`.`status` = ?");
		$statement->execute(array($store_id, 1));
		
		return $statement->rowCount();
	}
}