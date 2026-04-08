<?php
function is_loggedin()
{
	global $user;
	return $user->isLogged();
}

function is_admin()
{
	global $user;
	return user_group_id() == 1;
}

function user($field) 
{
	return get_the_user(user_id(), $field);
}

function user_id() 
{
	global $user;
	return $user->getId();
}

function user_group_id() 
{
	global $user;
	return $user->getGroupId();
}

function get_users() 
{
	
	$model = registry()->get('loader')->model('user');
	return $model->getUsers();
}

function get_the_user($id, $field = null) 
{
	
	$model = registry()->get('loader')->model('user');
	$user = $model->getUser($id);
	if ($field && isset($user[$field])) {
		return $user[$field];
	} elseif ($field) {
		return;
	}
	return $user;
}

function count_user_store($id = false) 
{
	global $user;
	$id  = $id ? $id : user_id();
	return $user->countBelongsStore($id);
}

function total_user_today($store_id = null)
{
	
	$user_model = registry()->get('loader')->model('user');
	return $user_model->totalToday($store_id);
}

function total_user($from = null, $to = null, $store_id = null)
{
	
	$user_model = registry()->get('loader')->model('user');
	return $user_model->total($from, $to, $store_id);
}

function get_user_due($id, $store_id = null, $index = 'due_amount')
{
	
	$user_model = registry()->get('loader')->model('user');
	return $user_model->getDueAmount($id, $store_id, $index);
}

function recent_users($limit)
{
	
	$user_model = registry()->get('loader')->model('user');
	return $user_model->getRecentUsers($limit);
}

function user_total_purchase_amount($id) 
{
	
	$user_model = registry()->get('loader')->model('user');
	return $user_model->getTotalpurchaseAmount($id);
}

function user_total_invoice($id = null) 
{
	
	$user_model = registry()->get('loader')->model('user');
	return $user_model->getTotalInvoiceNumber($id);
}

function best_user($field) 
{
	
	$user_model = registry()->get('loader')->model('user');
	return $user_model->getBestUser($field);
}

function get_best_user_purchase_amount() 
{
	
	$user_model = registry()->get('loader')->model('user');
	return $user_model->getBestUserTotalpurchaseAmount();
}

function user_avatar($sex)
{
	
	$user_model = registry()->get('loader')->model('user');
	return $user_model->getAvatar($sex);
}

function has_permission($type, $param)
{
	global $user;
	return $user->hasPermission($type, $param);
}