<?php
function get_box_id_by_code($id) 
{
	
	$model = registry()->get('loader')->model('box');
	return $model->getBoxIdByCode($id);
}

function get_boxes() 
{
	
	$model = registry()->get('loader')->model('box');
	return $model->getBoxes();
}

function get_the_box($id, $field = null) 
{
	
	$model = registry()->get('loader')->model('box');
	$box = $model->getBox($id);
	if ($field && isset($box[$field])) {
		return $box[$field];
	} elseif ($field) {
		return;
	}
	return $box;
}