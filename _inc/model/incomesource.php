<?php

class ModelIncomeSource extends Model 
{
	public function addIncomeSource($data) 
	{
    	$statement = $this->db->prepare("INSERT INTO `income_sources` (source_name, source_slug, parent_id, source_details, status, sort_order, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
    	$statement->execute(array($data['source_name'], $data['source_slug'], $data['parent_id'], $data['source_details'], $data['status'], $data['sort_order'], date_time()));
    	$source_id = $this->db->lastInsertId();
    	return $source_id; 
	}

	public function editIncomeSource($source_id, $data) 
	{
    	$statement = $this->db->prepare("UPDATE `income_sources` SET `source_name` = ?, `source_slug` = ?, `parent_id` = ?, `source_details` = ?, `status` = ?, `sort_order` = ? WHERE source_id = ? ");
    	$statement->execute(array($data['source_name'], $data['source_slug'], (int)$data['parent_id'], $data['source_details'], $data['status'], $data['sort_order'], $source_id));

    	return $source_id;
	}

	public function deleteIncomeSource($source_id) 
	{
    	$statement = $this->db->prepare("DELETE FROM `income_sources` WHERE `source_id` = ? LIMIT 1");
    	$statement->execute(array($source_id));

        return $source_id;
	}

	public function getIncomeSource($source_id) 
	{
		$statement = $this->db->prepare("SELECT * FROM `income_sources`
	    	WHERE `source_id` = ?");
	  	$statement->execute(array($source_id));
	  	$income_source = $statement->fetch(PDO::FETCH_ASSOC);
	    return $income_source;
	}

	public function isTopLevel($source_id)
	{
		$statement = $this->db->prepare("SELECT `source_id` FROM `income_sources` WHERE `source_id` = ? AND `parent_id` = ?");
	    $statement->execute(array($source_id, 0));
	    return $statement->fetch(PDO::FETCH_ASSOC);
	}

	public function getParentID($source_id)
	{
		$statement = $this->db->prepare("SELECT `parent_id` FROM `income_sources` WHERE `source_id` = ?");
	    $statement->execute(array($source_id));
	    $income_source = $statement->fetch(PDO::FETCH_ASSOC);
	    return isset($income_source['parent_id']) ? $income_source['parent_id'] : 0;
	}

	public function getIncomeSources($data = array()) 
	{
		$sql = "SELECT * FROM `income_sources` WHERE `status` = ?";

		if (isset($data['filter_parent_id'])) {
			$sql .= " AND `parent_id` = " . $data['filter_parent_id'];
		} elseif (!isset($data['filter_fetch_all'])) {
			$sql .= " AND `parent_id` = 0";
		}

		if (isset($data['filter_source_name'])) {
			$sql .= " AND `source_name` LIKE '" . $data['filter_source_name'] . "%'";
		}

		if (isset($data['filter_type'])) {
			$sql .= " AND `type` = '" . $data['filter_type']."'";
		}

		if (isset($data['filter_show_in_income'])) {
			$sql .= " AND `show_in_income` = '" . $data['filter_show_in_income']."'";
		}

		if (isset($data['only'])) {
			$sql .= " AND `source_id` IN (" . implode("','", $data['only']) . ")";
		}

		if (isset($data['exclude'])) {
			$sql .= " AND `source_id` != " . $data['exclude'];
		}

		$sql .= " GROUP BY `income_sources`.`source_id`";

		$sort_data = array(
			'source_name'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `source_name`";
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

	public function getIncomeSourceTree($data = array())
	{
		$tree = array();
		$income_sources = $this->getIncomeSources($data);
		foreach ($income_sources as $income_source) {
			$name = '';
			$parent = $this->getIncomeSource($income_source['parent_id']);
			if (isset($parent['source_id'])) {
				$name = $parent['source_name'] .  ' > ';
			}

			$tree[$income_source['source_id']] = $name . $income_source['source_name'];
		}		
		return $tree;
	}

	public function total() 
	{
		$statement = $this->db->prepare("SELECT * FROM `income_sources` WHERE `status` = ?");
		$statement->execute(array(1));
		return $statement->rowCount();
	}

	public function totalItem($source_id) 
	{
		$statement = $this->db->prepare("SELECT * FROM `bank_transaction_info` WHERE `source_id` = ? AND `status` = ?");
		$statement->execute(array($source_id, 1));
		return $statement->rowCount();
	}

	public function replaceWith($new_source_id, $source_id)
	{
      	$statement = $this->db->prepare("UPDATE `bank_transaction_info` SET `source_id` = ? WHERE `source_id` = ?");
      	$statement->execute(array($new_source_id, $source_id));
	}
}