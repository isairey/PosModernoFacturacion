<?php
function get_category_id_by_slug($id) 
{	
	$model = registry()->get('loader')->model('category');
	return $model->getCategoryIdBySlug($id);
}

function get_categorys($data = array(), $store = null) 
{	
	$model = registry()->get('loader')->model('category');
	return $model->getCategorys($data, $store);
}

function get_category_tree($data = array(), $store = null) 
{	
	$model = registry()->get('loader')->model('category');
	return $model->getCategoryTree($data, $store);
}

function get_the_category($id, $field = null) 
{	
	$model = registry()->get('loader')->model('category');
	$category = $model->getCategory($id);

	if ($field) {
		return isset($category[$field]) ? $category[$field] : null;
	} elseif ($field) {
		return;
	}
	return $category;
}

function get_total_valid_category_item($category_id)
{	
	$model = registry()->get('loader')->model('category');
	return $model->totalValidItem($category_id);
}

function get_total_category_item($category_id)
{	
	$model = registry()->get('loader')->model('category');
	return $model->totalItem($category_id);
}