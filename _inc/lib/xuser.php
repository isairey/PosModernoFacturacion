<?php

class User 
{
	private $id;
	private $group_id;
	private $username;
	private $permission = array();
	private $preference = array();

	public function __construct($registry)
	{
		$this->db = registry()->get('db');
		$this->request = registry()->get('request');
		$this->session = registry()->get('session');

		if (isset($this->session->data['id'])) {
			$statement = $this->db->prepare("SELECT * FROM `users` LEFT JOIN `user_to_store` as `u2s` ON (`users`.`id` = `u2s`.`user_id`) WHERE `id` = ? AND `u2s`.`status` = ?");
			$statement->execute(array((int)$this->session->data['id'], 1));
			$user = $statement->fetch(PDO::FETCH_ASSOC);
			if ($statement->rowCount()){
				$this->id = $user['id'];
				$this->username = $user['username'];
				$this->group_id = $user['group_id'];
				$this->setPreference(unserialize($user['preference']));

				$statement = $this->db->prepare("UPDATE `users` SET `ip` = ? WHERE `id` = ?");
				$statement->execute(array( get_real_ip(), (int)$this->session->data['id']));

				$statement = $this->db->prepare("SELECT `permission` FROM `user_group` WHERE `group_id` = ?");
				$statement->execute(array((int)$user['group_id']));
				$user_group = $statement->fetch(PDO::FETCH_ASSOC);

				$permissions = unserialize($user_group['permission']);
				if (is_array($permissions)) {
					foreach ($permissions as $key => $value) {
						$this->permission[$key] = $value;
					}
				}

				$statement = $this->db->prepare("DELETE FROM `login_logs` WHERE `created_at` < ?");
				$statement->execute(array(date('Y-m-d H:i:s', strtotime('-30 day'))));
			} else {
				$this->logout();
			}
		}
		// base64_decode('aGVhbHRoX2NoZWNrdXA=')();
	}

	public function login($username, $password) 
	{
		$statement = $this->db->prepare("SELECT * FROM `users` LEFT JOIN `user_to_store` as u2s ON (`users`.`id` = `u2s`.`user_id`) WHERE (`email` = ? OR `mobile` = ?) AND `password` = ?");
		$statement->execute(array($username, $username, md5($password)));
		$the_user = $statement->fetch(PDO::FETCH_ASSOC);
		if ($the_user) {
			unset($this->session->data['email']);
			unset($this->session->data['username']);
			unset($this->session->data['ref_url']);
			$this->session->data['id'] = $the_user['id'];
			$this->id = $the_user['id'];
			$this->username = $the_user['username'];
			$this->group_id = $the_user['group_id'];

			$statement = $this->db->prepare("SELECT `permission` FROM `user_group` WHERE `group_id` = ?");
			$statement->execute(array((int)$the_user['group_id']));
			$the_user_group = $statement->fetch(PDO::FETCH_ASSOC);

			$permissions = unserialize($the_user_group['permission']);

			if (is_array($permissions)) {
				foreach ($permissions as $key => $value) {
					$this->permission[$key] = $value;
				}
			}

			return true;
		} 

		return false;
	}

	public function logout() 
	{
		unset($this->session->data['id']);
		unset($this->session->data['stock_value']);
		$this->id = '';
		$this->username = '';
	}

	public function hasPermission($key, $value) 
	{
		if (isset($this->permission[$key])) {
			return isset($this->permission[$key][$value]);
		} else {
			return false;
		}
	}

	public function isLogged() 
	{
		return $this->id;
	}

	public function getId() 
	{
		return $this->id;
	}

	public function getUserName($id = null, $field = 'username') 
	{
		if ($id) {
			$statement = $this->db->prepare("SELECT * FROM `users` WHERE `id` = ?");
			$statement->execute(array((int)$id));
			$user = $statement->fetch(PDO::FETCH_ASSOC);
			return isset($user[$field]) ? $user[$field] : null;
		}
		return $this->username;
	}
	
	public function getGroupId() 
	{
		return $this->group_id;
	}	

	public function getRole()
	{
		$statement = $this->db->prepare("SELECT `name` FROM `user_group` WHERE `group_id` = ?");
		$statement->execute(array((int)$this->getGroupId()));
		
		return $statement->fetch(PDO::FETCH_ASSOC)['name'];
	}

	public function setPreference($preference) 
	{
		$this->preference = $preference;
	}

	public function updatePreference($preference, $user_id)
	{
		$statement = $this->db->prepare("UPDATE `users` SET `preference` = ? WHERE `id` = ? ");
	    $statement->execute(array(serialize($preference), $user_id));
	    $this->preference = $preference;
	}

	public function getPreference($index, $default = null) 
	{
		return isset($this->preference[$index]) ? $this->preference[$index] : $default;
	}

	public function getAllPreference()
	{
		return $this->preference;
	}

	public function getBelongsStore($user_id = null)
	{
		$user_id = $user_id ? $user_id : $this->getId();

		$statement = $this->db->prepare("SELECT `s`.* FROM `stores` s LEFT JOIN `user_to_store` u2s ON (`s`.`store_id` = `u2s`.`store_id`) WHERE `user_id` = ?");
		$statement->execute(array($user_id));

		return $statement->fetchAll(PDO::FETCH_ASSOC);

	}

	public function countBelongsStore($user_id = null)
	{
		$user_id = $user_id ? $user_id : $this->getId();
		
		$statement = $this->db->prepare("SELECT * FROM `user_to_store` WHERE `user_id` = ?");
		$statement->execute(array($user_id));

		return $statement->rowCount();

	}

	public function getSingleStoreId($user_id = null)
	{
		$user_id = $user_id ? $user_id : $this->getId();
		
		$statement = $this->db->prepare("SELECT * FROM `user_to_store` WHERE `user_id` = ?");
		$statement->execute(array($user_id));
		$store = $statement->fetch(PDO::FETCH_ASSOC);

		if ($store['store_id']) {
			return $store['store_id'];
		} 

		return false;
	}
}