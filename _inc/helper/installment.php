<?php
function get_installment_invoice_count()
{
	
	$model = registry()->get('loader')->model('installment');
	return $model->getInvoiceCount();
}

function get_installment_sell_amount()
{
	
	$model = registry()->get('loader')->model('installment');
	return $model->getSellAmount();
}

function get_installment_intereset_amount()
{
	
	$model = registry()->get('loader')->model('report');
	return $model->getInterestAmount();
}

function get_installment_received_amount()
{
	
	$model = registry()->get('loader')->model('installment');
	return $model->getReceivedAmount();
}

function get_installment_due_amount()
{
	
	$model = registry()->get('loader')->model('installment');
	return $model->getDueAmount();
}