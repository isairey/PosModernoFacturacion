<?php
function get_banner_id_by_slug($id) 
{	
	$model = registry()->get('loader')->model('banner');
	return $model->getBannerIdBySlug($id);
}

function get_banners($data = array(), $store = null) 
{	
	$model = registry()->get('loader')->model('banner');
	return $model->getBanners($data, $store);
}

function get_the_banner($id, $field = null) 
{	
	$model = registry()->get('loader')->model('banner');
	$banner = $model->getBanner($id);

	if ($field) {
		return isset($banner[$field]) ? $banner[$field] : null;
	} elseif ($field) {
		return;
	}
	return $banner;
}

function get_banner_images($id) 
{	
	$model = registry()->get('loader')->model('banner');
	return $model->getBannerImages($id);
}