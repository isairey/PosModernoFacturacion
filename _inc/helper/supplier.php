<?php
function get_supplier_id_by_code($id) 
{
	$model = registry()->get('loader')->model('supplier');
	return $model->getSupplierIdByCode($id);
}

function get_suppliers($data = array()) 
{
	$model = registry()->get('loader')->model('supplier');
	return $model->getSuppliers($data);
}

function get_the_supplier($id, $field = null) 
{
	$model = registry()->get('loader')->model('supplier');
	$supplier = $model->getSupplier($id);
	if ($field && isset($supplier[$field])) {
		return $supplier[$field];
	} elseif ($field) {
		return;
	}
	return $supplier;
}

function supplier_selling_price($sup_id, $from, $to)
{
	$supplier_model = registry()->get('loader')->model('supplier');
	return $supplier_model->getSellingPrice($sup_id, $from, $to);
}

function supplier_purchase_price($sup_id, $from, $to)
{
	$supplier_model = registry()->get('loader')->model('supplier');
	return $supplier_model->getpurchasePrice($sup_id, $from, $to);
}

function total_supplier_today($store_id = null)
{
	$supplier_model = registry()->get('loader')->model('supplier');
	return $supplier_model->totalToday($store_id);
}

function total_supplier($from = null, $to = null, $store_id = null)
{
	$supplier_model = registry()->get('loader')->model('supplier');
	return $supplier_model->total($from, $to, $store_id);
}

function total_product_of_supplier($sup_id)
{
	$supplier_model = registry()->get('loader')->model('supplier');
	return $supplier_model->totalProduct($sup_id);
}

function get_supplier_balance($sup_id, $store_id = null)
{
	$supplier_model = registry()->get('loader')->model('supplier');
	return $supplier_model->totalAmount($sup_id, $store_id);
}