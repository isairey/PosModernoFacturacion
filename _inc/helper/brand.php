<?php
function get_brand_id_by_code($id) 
{
	
	$model = registry()->get('loader')->model('brand');
	return $model->getBrandIdByCode($id);
}

function get_brands($data = array()) 
{
	
	$model = registry()->get('loader')->model('brand');
	return $model->getBrands($data);
}

function get_the_brand($id, $field = null) 
{
	
	$model = registry()->get('loader')->model('brand');
	$brand = $model->getBrand($id);
	if ($field && isset($brand[$field])) {
		return $brand[$field];
	} elseif ($field) {
		return;
	}
	return $brand;
}

function brand_selling_price($brand_id, $from, $to)
{
	
	$brand_model = registry()->get('loader')->model('brand');
	return $brand_model->getSellingPrice($brand_id, $from, $to);
}

function brand_purchase_price($brand_id, $from, $to)
{
	
	$brand_model = registry()->get('loader')->model('brand');
	return $brand_model->getpurchasePrice($brand_id, $from, $to);
}

function total_brand_today($store_id = null)
{
	
	$brand_model = registry()->get('loader')->model('brand');
	return $brand_model->totalToday($store_id);
}

function total_brand($from = null, $to = null, $store_id = null)
{
	
	$brand_model = registry()->get('loader')->model('brand');
	return $brand_model->total($from, $to, $store_id);
}

function total_product_of_brand($brand_id)
{
	
	
	$brand_model = registry()->get('loader')->model('brand');
	return $brand_model->totalProduct($brand_id);

}