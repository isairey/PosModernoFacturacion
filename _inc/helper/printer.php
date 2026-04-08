<?php
function get_priinters() 
{
	
	$model = registry()->get('loader')->model('priinter');
	return $model->getPrinters();
}

function get_the_priinter($id, $field = null, $store_id = null) 
{
	
	$model = registry()->get('loader')->model('priinter');
	$priinter = $model->getPrinter($id, $store_id);
	if ($field && isset($priinter[$field])) {
		return $priinter[$field];
	} elseif ($field) {
		return;
	}
	return $priinter;
}