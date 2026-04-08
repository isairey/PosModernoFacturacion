<?php
function get_giftcards() 
{
	$model = registry()->get('loader')->model('giftcard');
	return $model->getGiftcards();
}

function get_the_giftcard($id, $field = null) 
{
	$model = registry()->get('loader')->model('giftcard');
	$giftcard = $model->getGiftcard($id);
	if ($field && isset($giftcard[$field])) {
		return $giftcard[$field];
	} elseif ($field) {
		return;
	}
	return $giftcard;
}

function get_giftcard_total_price($from, $to) 
{
	$model = registry()->get('loader')->model('giftcard');
	return $model->totalPrice($from, $to);
}

function get_giftcard_total_topup($from, $to) 
{
	$model = registry()->get('loader')->model('giftcard');
	return $model->totalTopup($from, $to);
}