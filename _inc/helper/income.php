<?php
function get_total_income($from=null, $to=null, $store_id=null) 
{	
	$income_model = registry()->get('loader')->model('income');
	return $income_model->getTotalIncome($from, $to, $store_id);
}

function get_total_substract_income($from=null, $to=null, $store_id=null) 
{	
	$income_model = registry()->get('loader')->model('income');
	return $income_model->getTotalSubstractIncome($from, $to, $store_id);
}

function get_total_source_income($source_id, $from=null, $to=null, $store_id=null) 
{	
	$income_model = registry()->get('loader')->model('income');
	return $income_model->getTotalSourceIncome($source_id, $from, $to, $store_id);
}

function get_total_substract_source_income($source_id, $from=null, $to=null, $store_id=null) 
{	
	$income_model = registry()->get('loader')->model('income');
	return $income_model->getTotalSubstractSourceIncome($source_id, $from, $to, $store_id);
}

function get_total_expense($from=null, $to=null, $store_id=null) 
{	
	$income_model = registry()->get('loader')->model('income');
	return $income_model->getTotalExpense($from, $to, $store_id);
}

function get_total_category_expense($source_id, $from=null, $to=null, $store_id=null) 
{	
	$income_model = registry()->get('loader')->model('income');
	return $income_model->getTotalCategoryExpense($source_id, $from, $to, $store_id);
}

function get_capital_amount($from=null, $to=null, $store_id=null)
{	
	$payment_model = registry()->get('loader')->model('payment');
	return $payment_model->getCapitalAmount($from, $to, $store_id);
}

function get_total_profit($from=null, $to=null, $store_id=null)
{	
	$income_model = registry()->get('loader')->model('income');
	return $income_model->getTotalProfit($from, $to, $store_id);
}

function get_profit_amount($from=null, $to=null, $store_id=null)
{	
	$payment_model = registry()->get('loader')->model('payment');
	return $payment_model->getProfitAmount($from, $to, $store_id);
}