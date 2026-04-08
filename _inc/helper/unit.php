<?php
function get_unit_id_by_code($id) 
{
	
	$model = registry()->get('loader')->model('unit');
	return $model->getUnitIdByCode($id);
}

function get_units() 
{
	

	$model = registry()->get('loader')->model('unit');

	return $model->getUnits();
}

function get_the_unit($id, $field = null) 
{
	

	$model = registry()->get('loader')->model('unit');

	$unit = $model->getUnit($id);

	if ($field && isset($unit[$field])) {
		return $unit[$field];
	} elseif ($field) {
		return;
	}

	return $unit;
}