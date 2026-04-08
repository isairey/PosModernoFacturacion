<?php
function get_taxrate_id_by_code($id) 
{
	
	$model = registry()->get('loader')->model('taxrate');
	return $model->getTaxrateIdByCode($id);
}

function get_taxrates() 
{
	
	$model = registry()->get('loader')->model('taxrate');
	return $model->getTaxrates();
}

function get_the_taxrate($id, $field = null) 
{
	
	$model = registry()->get('loader')->model('taxrate');
	$taxrate = $model->getTaxrate($id);
	if ($field && isset($taxrate[$field])) {
		return $taxrate[$field];
	} elseif ($field) {
		return;
	}
	return $taxrate;
}