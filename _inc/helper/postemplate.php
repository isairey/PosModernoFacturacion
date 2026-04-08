<?php
function get_postemplates($data = array(), $store_id = null) 
{
	
	$model = registry()->get('loader')->model('postemplate');
	return $model->getTemplates($data, $store_id);
}

function get_the_postemplate($id, $field = null) 
{
	
	$model = registry()->get('loader')->model('postemplate');
	$postemplate = $model->getTemplate($id);
	if ($field && isset($postemplate[$field])) {
		return $postemplate[$field];
	} elseif ($field) {
		return;
	}
	return $postemplate;
}