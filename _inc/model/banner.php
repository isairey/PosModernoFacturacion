<?php

class ModelBanner extends Model 
{
	public function addBanner($data) 
	{
    	$statement = $this->db->prepare("INSERT INTO `banners` (name, slug, created_at) VALUES (?, ?, ?)");
    	$statement->execute(array($data['name'], $data['slug'], date_time()));
    	$id = $this->db->lastInsertId();

    	if (isset($data['banner_store'])) {
			foreach ($data['banner_store'] as $store_id) {
				$statement = $this->db->prepare("INSERT INTO `banner_to_store` SET `banner_id` = ?, `store_id` = ?");
				$statement->execute(array((int)$id, (int)$store_id));
			}
		}
		if (isset($data['image'])) {
			$this->syncImage($id, $data['image']);
		}
		$this->updateStatus($id, $data['status']);
		$this->updateSortOrder($id, $data['sort_order']);
    	return $id; 
	}

	public function syncImage($id, $imgArray)
	{
		$statement = $this->db->prepare("DELETE FROM `banner_images` WHERE `banner_id` = ?");
		$statement->execute(array($id));
		foreach ($imgArray as $img) {
			if ($img['url']) {
				$statement = $this->db->prepare("INSERT INTO `banner_images` SET `banner_id` = ?, `url` = ?, `link` = ?, `sort_order` = ?");
				$statement->execute(array($id, $img['url'], $img['link'], $img['sort_order']));
			}
		}
	}

	public function updateStatus($id, $status, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();

		$statement = $this->db->prepare("UPDATE `banner_to_store` SET `status` = ? WHERE `store_id` = ? AND `banner_id` = ?");
		$statement->execute(array((int)$status, $store_id, (int)$id));
	}

	public function updateSortOrder($id, $sort_order, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();

		$statement = $this->db->prepare("UPDATE `banner_to_store` SET `sort_order` = ? WHERE `store_id` = ? AND `banner_id` = ?");
		$statement->execute(array((int)$sort_order, $store_id, (int)$id));
	}

	public function editBanner($id, $data) 
	{
    	$statement = $this->db->prepare("UPDATE `banners` SET `name` = ?, `slug` = ? WHERE id = ?");
    	$statement->execute(array($data['name'], $data['slug'], $id));

    	if (isset($data['banner_store'])) {
    		$store_ids = array();
			foreach ($data['banner_store'] as $store_id) {
				$statement = $this->db->prepare("SELECT * FROM `banner_to_store` WHERE `store_id` = ? AND `banner_id` = ?");
			    $statement->execute(array($store_id, $id));
			    $banner = $statement->fetch(PDO::FETCH_ASSOC);
			    if (!$banner) {
			    	$statement = $this->db->prepare("INSERT INTO `banner_to_store` SET `banner_id` = ?, `store_id` = ?");
					$statement->execute(array((int)$id, (int)$store_id));
			    }
			    $store_ids[] = $store_id;
			}
			if (!empty($store_ids)) {
				$statement = $this->db->prepare("SELECT * FROM `banner_to_store` WHERE `store_id` NOT IN (" . implode(',', $store_ids) . ")");
				$statement->execute();
				$unwanted_stores = $statement->fetchAll(PDO::FETCH_ASSOC);
				foreach ($unwanted_stores as $store) {
					$store_id = $store['store_id'];
					$statement = $this->db->prepare("DELETE FROM `banner_to_store` WHERE `store_id` = ? AND `banner_id` = ?");
					$statement->execute(array($store_id, $id));

				}			
			}
		}
		if (isset($data['image'])) {
			$this->syncImage($id, $data['image']);
		}
		$this->updateStatus($id, $data['status']);
		$this->updateSortOrder($id, $data['sort_order']);

    	return $id;
	}

	public function replaceWith($new_id, $id)
	{
		$belongs_stores = $this->getBelongsStore($id);
	    foreach ($belongs_stores as $the_store) {
	      $statement = $this->db->prepare("SELECT * FROM `banner_to_store` WHERE `banner_id` = ? AND `store_id` = ?");
	      $statement->execute(array($new_id, $the_store['store_id']));
	      if ($statement->rowCount() > 0) continue;

	      $statement = $this->db->prepare("INSERT INTO `banner_to_store` SET `banner_id` = ?, `store_id` = ?");
	      $statement->execute(array($new_id, $the_store['store_id']));
	    }

	    $statement = $this->db->prepare("UPDATE `banners` SET `id` = ? WHERE `id` = ?");
      	$statement->execute(array($new_id, $id));

      	$statement = $this->db->prepare("UPDATE `purchase_item` SET `id` = ? WHERE `id` = ?");
      	$statement->execute(array($new_id, $id));

      	$statement = $this->db->prepare("UPDATE `selling_item` SET `id` = ? WHERE `id` = ?");
      	$statement->execute(array($new_id, $id));

      	$statement = $this->db->prepare("UPDATE `holding_item` SET `id` = ? WHERE `id` = ?");
      	$statement->execute(array($new_id, $id));

      	$statement = $this->db->prepare("UPDATE `quotation_item` SET `id` = ? WHERE `id` = ?");
      	$statement->execute(array($new_id, $id));
	}

	public function deleteBanner($id) 
	{
    	$statement = $this->db->prepare("DELETE FROM `banners` WHERE `id` = ? LIMIT 1");
    	$statement->execute(array($id));

    	$statement = $this->db->prepare("DELETE FROM `banner_to_store` WHERE `banner_id` = ?");
    	$statement->execute(array($id));

    	$statement = $this->db->prepare("DELETE FROM `banner_images` WHERE `banner_id` = ?");
    	$statement->execute(array($id));

        return $id;
	}

	public function getBanner($id, $store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();
		$statement = $this->db->prepare("SELECT * FROM `banners`
			LEFT JOIN `banner_to_store` as b2s ON (`banners`.`id` = `b2s`.`banner_id`)  
	    	WHERE `b2s`.`store_id` = ? AND `id` = ?");
	  	$statement->execute(array($store_id, $id));
	  	$banner = $statement->fetch(PDO::FETCH_ASSOC);

	  	// Fetch stores related to banners
	    $statement = $this->db->prepare("SELECT `store_id` FROM `banner_to_store` WHERE `banner_id` = ?");
	    $statement->execute(array($id));
	    $all_stores = $statement->fetchAll(PDO::FETCH_ASSOC);
	    $stores = array();
	    foreach ($all_stores as $store) {
	    	$stores[] = $store['store_id'];
	    }

	    $banner['stores'] = $stores;

	    return $banner;
	}

	public function getBannerImages($banner_id) 
	{
		$statement = $this->db->prepare("SELECT * FROM `banners` LEFT JOIN `banner_images` ON (`banners`.`id` = `banner_images`.`banner_id`) WHERE (`banner_images`.`banner_id` = ? OR `banners`.`slug` = ?) ORDER BY `sort_order` ASC");
	    $statement->execute(array($banner_id, $banner_id));
	    $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
	    return $rows;
	}

	public function getBanners($data = array(), $store_id = null) {

		$store_id = $store_id ? $store_id : store_id();
		$sql = "SELECT * FROM `banners` LEFT JOIN `banner_to_store` b2s ON (`banners`.`id` = `b2s`.`banner_id`) WHERE `b2s`.`store_id` = ? AND `b2s`.`status` = ?";

		if (isset($data['filter_parent_id'])) {
			$sql .= " AND `parent_id` = " . $data['filter_parent_id'];
		} elseif (!isset($data['filter_fetch_all'])) {
			$sql .= " AND `parent_id` = 0";
		}

		if (isset($data['filter_name'])) {
			$sql .= " AND `name` LIKE '" . $data['filter_name'] . "%'";
		}

		if (isset($data['only'])) {
			$sql .= " AND `id` IN (" . implode("','", $data['only']) . ")";
		}

		if (isset($data['exclude'])) {
			$sql .= " AND `id` != " . $data['exclude'];
		}

		$sql .= " GROUP BY `banners`.`id`";

		$sort_data = array(
			'name'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `name`";
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

	public function getBannerIdBySlug($slug)
	{
		$statement = $this->db->prepare("SELECT `id` FROM `banners` WHERE `slug` = ?");
		$statement->execute(array($slug));
		$row = $statement->fetch(PDO::FETCH_ASSOC);
		return isset($row['id']) ? $row['id'] : null;
	}

	public function getBelongsStore($id)
	{
		$statement = $this->db->prepare("SELECT * FROM `banner_to_store` WHERE `banner_id` = ?");
		$statement->execute(array($id));

		return $statement->fetchAll(PDO::FETCH_ASSOC);

	}

	public function total($store_id = null) 
	{
		$store_id = $store_id ? $store_id : store_id();

		$statement = $this->db->prepare("SELECT * FROM `banners`LEFT JOIN `banner_to_store` b2s ON (`banners`.`id` = `b2s`.`banner_id`) where `b2s`.`store_id` = ? AND `b2s`.`status` = ?");
		$statement->execute(array($store_id, 1));
		
		return $statement->rowCount();
	}
}