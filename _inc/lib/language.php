<?php

class Language 
{
	private $default = 'en';
	private $code;
	private $lang_id;
	private $data = array();

	public function __construct($lang_code) 
	{
		$this->lang_code = $lang_code;
		$statement = db()->prepare("SELECT `id` FROM `languages` WHERE `code` = ?");
      	$statement->execute(array($this->lang_code));
      	$lang = $statement->fetch(PDO::FETCH_ASSOC);
      	if (!$lang) {
      		$statement = db()->prepare("SELECT `id` FROM `languages` WHERE `code` = ?");
	      	$statement->execute(array('en'));
	      	$lang = $statement->fetch(PDO::FETCH_ASSOC);
      	} 
      	$this->lang_id = $lang['id'];
	}

	public function get($key) 
	{
		if (!isset($this->data[$key])) {
			$statement = db()->prepare("SELECT `id` FROM `language_translations` WHERE `lang_id` = ? AND `lang_key` = ?");
      		$statement->execute(array($this->lang_id, $key));
      		$lang = $statement->fetch(PDO::FETCH_ASSOC);
      		if (!$lang) {
      			$statement = db()->prepare("INSERT INTO `language_translations` (lang_id, lang_key) VALUES(?, ?)");
				$statement->execute(array($this->lang_id, $key));
      		}
		}

		if (isset($this->data[$key]) && $this->data[$key]) {
			return html_entity_decode($this->data[$key]);
		}

		$key1 = str_replace(array('_', 'menu', 'label', 'text', 'button', 'title', 'success', 'hint', 'placeholder'), ' ', $key);
		return isset($this->data[$key]) && $this->data[$key] ? html_entity_decode($this->data[$key]) : ucwords($key1);
		// return $key;
	}
	
	public function load() 
	{
		if (empty($this->data)) {
			$statement = db()->prepare("SELECT `lang_key`, `lang_value` FROM `language_translations` WHERE `lang_id` = ?");
			$statement->execute(array($this->lang_id));
			foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $value) {
				// $this->data[trim($value['lang_key'])] = trim($value['lang_value']);
			}
		}
	}
}