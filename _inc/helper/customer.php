<?php
function get_customers($data = array()) 
{
	$model = registry()->get('loader')->model('customer');
	return $model->getCustomers($data);
}

function get_the_customer($id, $field = null) 
{
	$model = registry()->get('loader')->model('customer');
	$customer = $model->getCustomer($id);
	if ($field && isset($customer[$field])) {
		return $customer[$field];
	} elseif ($field) {
		return;
	}
	return $customer;
}

function total_customer_today($store_id = null)
{
	$customer_model = registry()->get('loader')->model('customer');
	return $customer_model->totalToday($store_id);
}

function total_customer($from = null, $to = null, $store_id = null)
{
	$customer_model = registry()->get('loader')->model('customer');
	return $customer_model->total($from, $to, $store_id);
}

function get_customer_balance($customer_id, $store_id = null)
{
	$customer_model = registry()->get('loader')->model('customer');
	return $customer_model->getBalance($customer_id, $store_id);
}

function get_customer_due($customer_id, $store_id = null, $index = 'due')
{
	$customer_model = registry()->get('loader')->model('customer');
	return $customer_model->getDueAmount($customer_id, $store_id, $index);
}

function recent_customers($limit)
{	
	$customer_model = registry()->get('loader')->model('customer');
	return $customer_model->getRecentCustomers($limit);
}

function customer_total_purchase_amount($customer_id) 
{	
	$customer_model = registry()->get('loader')->model('customer');
	return $customer_model->getTotalpurchaseAmount($customer_id);
}

function customer_total_invoice($customer_id = null) 
{	
	$customer_model = registry()->get('loader')->model('customer');
	return $customer_model->getTotalInvoiceNumber($customer_id);
}

function best_customer($field) 
{	
	$customer_model = registry()->get('loader')->model('customer');
	return $customer_model->getBestCustomer($field);
}

function get_best_customer_purchase_amount() 
{	
	$customer_model = registry()->get('loader')->model('customer');
	return $customer_model->getBestCustomerTotalpurchaseAmount();
}

function customer_avatar($sex)
{
	$customer_model = registry()->get('loader')->model('customer');
	return $customer_model->getAvatar($sex);
}

function get_today_birthday_customers()
{
	$customer_model = registry()->get('loader')->model('customer');
	return $customer_model->getTodayBirthDayCustomers();
}

function get_customer_giftcard_balance($customer_id)
{
	$giftcard_model = registry()->get('loader')->model('giftcard');
	return $giftcard_model->getCustomerBallance($customer_id);
}

function is_clogged_in()
{
	global $session;
	return isset($session->data['is_clogged_in']) ? $session->data['is_clogged_in'] : false;
}

function customer_id()
{
	global $session;
	return isset($session->data['cid']) ? $session->data['cid'] : false;
}

function customer_name()
{
	global $session;
	return isset($session->data['cname']) ? $session->data['cname'] : false;
}