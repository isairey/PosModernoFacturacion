<?php
function get_pmethods($data = array()) 
{
	$model = registry()->get('loader')->model('pmethod');
	return $model->getPmethods($data);
}

function get_the_pmethod($id, $field = null)
{
	$model = registry()->get('loader')->model('pmethod');
	$pmethods = $model->getPmethod($id);
	if ($field && isset($pmethods[$field])) {
		return $pmethods[$field];
	} elseif ($field) {
		return;
	}
	return '';
}