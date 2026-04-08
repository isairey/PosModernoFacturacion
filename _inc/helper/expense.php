<?php
function get_total_loss($from=null, $to=null, $store_id=null)
{
	$expense_model = registry()->get('loader')->model('expense');
	return $expense_model->getTotalLoss($from, $to, $store_id);
}