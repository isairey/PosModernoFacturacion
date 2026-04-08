<?php

class ModelUser extends Model 
{
	public function addUser($data) 
	{
    	$statement = $this->db->prepare("INSERT INTO `users` (username, email, mobile, password, raw_password, group_id, dob, user_image, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    	$statement->execute(array($data['username'], $data['email'], $data['mobile'], md5($data['password']), $data['password'], (int)$data['group_id'], $data['dob'], $data['user_image'], date_time()));

    	$id = $this->db->lastInsertId();

    	if (isset($data['user_store'])) {
			foreach ($data['user_store'] as $store_id) {
				$statement = $this->db->prepare("INSERT INTO `user_to_store` SET `user_id` = ?, `store_id` = ?");
				$statement->execute(array((int)$id, (int)$store_id));
			}
		}

		$this->updateStatus($id, $data['status']);
		$this->updateSortOrder($id, $data['sort_order']);
    
    	return $id;    
	}

	public function updateStatus($id, $status, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();

		$statement = $this->db->prepare("UPDATE `user_to_store` SET `status` = ? WHERE `store_id` = ? AND `user_id` = ?");
		$statement->execute(array((int)$status, $store_id, (int)$id));
	}

	public function updateSortOrder($id, $sort_order, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();

		$statement = $this->db->prepare("UPDATE `user_to_store` SET `sort_order` = ? WHERE `store_id` = ? AND `user_id` = ?");
		$statement->execute(array((int)$sort_order, $store_id, (int)$id));
	}		

	public function editUser($id, $data) 
	{    	
    	$statement = $this->db->prepare("UPDATE `users` SET `username` = ?,`email` = ?, `mobile` = ?, `group_id` = ?, `dob` = ?, `user_image` = ? WHERE `id` = ? ");
    	$statement->execute(array($data['username'],$data['email'], $data['mobile'], (int)$data['group_id'], $data['dob'], $data['user_image'], $id));


    	// Delete store data balongs to the user
    	$statement = $this->db->prepare("DELETE FROM `user_to_store` WHERE `user_id` = ?");
    	$statement->execute(array($id));
		
		// Insert user into store
    	if (isset($data['user_store'])) {
			foreach ($data['user_store'] as $store_id) {
				$statement = $this->db->prepare("INSERT INTO `user_to_store` SET `user_id` = ?, `store_id` = ?");
				$statement->execute(array((int)$id, (int)$store_id));
			}
		}

		$this->updateStatus($id, $data['status']);
		$this->updateSortOrder($id, $data['sort_order']);
    
    	return $id;
	}

	public function deleteUser($id) 
	{
    	$statement = $this->db->prepare("DELETE FROM `users` WHERE `id` = ? LIMIT 1");
    	$statement->execute(array($id));
        return $id;
	}

	public function getUser($id, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();

		$statement = $this->db->prepare("SELECT `users`.*, `ug`.`slug` as `group_name`, `ug`.`sort_order` FROM `users`
			LEFT JOIN `user_to_store` as u2s ON (`users`.`id` = `u2s`.`user_id`)  
			LEFT JOIN `user_group` as ug ON (`users`.`group_id` = `ug`.`group_id`)  
	    	WHERE `u2s`.`store_id` = ? AND `users`.`id` = ?");
	  	$statement->execute(array($store_id, $id));
		$user = $statement->fetch(PDO::FETCH_ASSOC);

		// Fetch stores related to users
	    $statement = $this->db->prepare("SELECT `store_id` FROM `user_to_store` WHERE `user_id` = ?");
	    $statement->execute(array($id));
	    $all_stores = $statement->fetchAll(PDO::FETCH_ASSOC);
	    $stores = array();
	    foreach ($all_stores as $store) {
	    	$stores[] = $store['store_id'];
	    }

	    $user['stores'] = $stores;

	    return $user;
	}

	public function getUsers($data = array(), $store_id = null) {

		$store_id = $store_id ? $store_id : store_id();

		$sql = "SELECT * FROM `users` LEFT JOIN `user_to_store` as `u2s` ON (`users`.`id` = `u2s`.`user_id`) 
			WHERE `u2s`.`store_id` = ? AND `u2s`.`status` = ?";

		if (isset($data['filter_name'])) {
			$sql .= " AND `username` LIKE '" . $data['filter_name'] . "%'";
		}

		if (isset($data['filter_email'])) {
			$sql .= " AND `email` LIKE '" . $data['filter_email'] . "%'";
		}

		if (isset($data['filter_mobile'])) {
			$sql .= " AND `mobile` LIKE '" . $data['filter_mobile'] . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND `u2s`.`status` = '" . (int)$data['filter_status'] . "'";
		}

		$sql .= " GROUP BY `id`";

		$sort_data = array(
			'username'
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
		$statement->execute(array($store_id, 1));
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getBestUser($field, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$statement = $this->db->prepare("SELECT `selling_info`.*, `selling_price`.*, `users`.*, SUM(`selling_price`.`payable_amount`) as total 
			FROM `selling_info` 
			LEFT JOIN `users` ON (`selling_info`.`created_by` = `users`.`id`)
			LEFT JOIN `selling_price` ON (`selling_info`.`invoice_id` = `selling_price`.`invoice_id`) 
			WHERE `selling_info`.`store_id` = ?
			GROUP BY `selling_info`.`created_by` ORDER BY `total` DESC");
		$statement->execute(array($store_id));
		$user = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($user[$field]) ? $user[$field] : null;
	}

	public function getRecentUsers($limit, $store_id = null)
	{
		$store_id = $store_id ? $store_id : store_id();
		$statement = $this->db->prepare("SELECT users.* FROM `selling_info` 
			LEFT JOIN `users` ON (`selling_info`.`created_by` = `users`.`id`) 
			LEFT JOIN `user_to_store` as u2s ON (`selling_info`.`created_by` = `u2s`.`user_id`)
			where `selling_info`.`store_id` = ? AND `u2s`.`status` = ?
			GROUP BY `selling_info`.`created_by`
			ORDER BY `selling_info`.`info_id` DESC 
			LIMIT $limit"
			);
	    $statement->execute(array($store_id, 1));
	    return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getTotalpurchaseAmount($id, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$statement = $this->db->prepare("SELECT `selling_info`.*, `selling_price`.*, `users`.*, SUM(`selling_price`.`payable_amount`) as total FROM `selling_info` 
			LEFT JOIN `users` ON (`selling_info`.`created_by` = `users`.`id`)
			LEFT JOIN `selling_price` ON (`selling_info`.`invoice_id` = `selling_price`.`invoice_id`)
			where `users`.`id` = ? AND `selling_info`.`store_id` = ? 
			ORDER BY `total` DESC");
		$statement->execute(array($id, $store_id));
		$user = $statement->fetch(PDO::FETCH_ASSOC);

		return isset($user['total']) ? $user['total'] : '0';
	}

	public function getTotalInvoiceNumber($id = null, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		if ($id) {
			$statement = $this->db->prepare("SELECT * FROM `selling_info` 
				WHERE `created_by` = ? AND `store_id` = ?");
			$statement->execute(array($id, store_id()));
		}
		else {
			$statement = $this->db->prepare("SELECT * FROM `selling_info` WHERE `store_id` = ?");
			$statement->execute(array($store_id));
		}

		return $statement->rowCount();
	}

	public function getBelongsStore($id)
	{
		$statement = $this->db->prepare("SELECT * FROM `user_to_store` WHERE `user_id` = ?");
		$statement->execute(array($id));
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function totalToday($store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`u2s`.`store_id` = '$store_id'";
		$from = date('Y-m-d');
		$to = date('Y-m-d');
		if (($from && ($to == false)) || ($from == $to)) {
			$day = date('d', strtotime($from));
			$month = date('m', strtotime($from));
			$year = date('Y', strtotime($from));
			$where_query .= " AND DAY(`users`.`created_at`) = $day";
			$where_query .= " AND MONTH(`users`.`created_at`) = $month";
			$where_query .= " AND YEAR(`users`.`created_at`) = $year";
		} else {
			$from = date('Y-m-d H:i:s', strtotime($from.' '. '00:00:00')); 
			$to = date('Y-m-d H:i:s', strtotime($to.' '. '23:59:59'));
			$where_query .= " AND users.created_at >= '{$from}' AND users.created_at <= '{$to}'";
		}

		$statement = $this->db->prepare("SELECT * FROM `users` LEFT JOIN `user_to_store` u2s ON (`users`.`id` = `u2s`.`user_id`) WHERE $where_query");
		$statement->execute(array());

		return $statement->rowCount();
	}

	public function total($from, $to, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$where_query = "`u2s`.`store_id` = '$store_id'";
		if ($from) {
			$from = $from ? $from : date('Y-m-d');
			$to = $to ? $to : date('Y-m-d');
			if (($from && ($to == false)) || ($from == $to)) {
				$day = date('d', strtotime($from));
				$month = date('m', strtotime($from));
				$year = date('Y', strtotime($from));
				$where_query .= " AND DAY(`users`.`created_at`) = $day";
				$where_query .= " AND MONTH(`users`.`created_at`) = $month";
				$where_query .= " AND YEAR(`users`.`created_at`) = $year";
			} else {
				$from = date('Y-m-d H:i:s', strtotime($from.' '. '00:00:00')); 
				$to = date('Y-m-d H:i:s', strtotime($to.' '. '23:59:59'));
				$where_query .= " AND users.created_at >= '{$from}' AND users.created_at <= '{$to}'";
			}
		}
		$statement = $this->db->prepare("SELECT * FROM `users` LEFT JOIN `user_to_store` u2s ON (`users`.`id` = `u2s`.`user_id`) WHERE $where_query");
		$statement->execute(array());
		return $statement->rowCount();
	}

	public function getAvatar($sex)
	{
		switch ($sex) {
			case 1:
				$avatar = 'avatar';
				break;
			case 2:
				$avatar = 'avatar-female';
				break;
			default:
				$avatar = 'avatar-others';
				break;
		}
		
		return $avatar;
	}
}