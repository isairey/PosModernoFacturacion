<?php
function get_income_sources($data = array(), $store = null) 
{
	

	$model = registry()->get('loader')->model('incomesource');

	return $model->getIncomeSources($data, $store);
}

function get_income_source_tree($data = array(), $store = null) 
{
	

	$model = registry()->get('loader')->model('incomesource');

	return $model->getIncomeSourceTree($data, $store);
}

function get_the_income_source($id, $field = null) 
{
	

	$model = registry()->get('loader')->model('incomesource');

	$income_source = $model->getIncomeSource($id);

	if ($field) {
		return isset($income_source[$field]) ? $income_source[$field] : null;
	} elseif ($field) {
		return;
	}
	
	return $income_source;
}

function get_total_valid_income_source_item($income_source_id)
{
	

	$model = registry()->get('loader')->model('incomesource');

	return $model->totalValidItem($income_source_id);
}

function get_total_income_source_item($income_source_id)
{
	

	$model = registry()->get('loader')->model('incomesource');

	return $model->totalItem($income_source_id);
}