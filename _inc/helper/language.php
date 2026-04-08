<?php

function get_the_lang($lang_id)
{
	$statement = db()->prepare("SELECT * FROM `languages` WHERE `id` = ? OR `code` = ?"); 
	$statement->execute(array($lang_id, $lang_id));
	return $statement->fetch(PDO::FETCH_ASSOC);
}

function get_langs()
{
	$statement = db()->prepare("SELECT * FROM `languages`"); 
	$statement->execute(array());
	return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function trans($key)
{
	global  $language;
	return $language->get($key);
}