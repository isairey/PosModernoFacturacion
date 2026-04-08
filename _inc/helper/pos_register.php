<?php

function get_opening_balance($from=null, $store_id=null)
{
	
	
	$register_model = registry()->get('loader')->model('posregister');
	return $register_model->getOpeningBalance($from, $store_id);
}

function get_closing_balance($from=null, $store_id=null)
{
	
	
	$register_model = registry()->get('loader')->model('posregister');
	return $register_model->getClosingBalance($from,$store_id);
}