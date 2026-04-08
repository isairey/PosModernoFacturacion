<?php
function selling_price($from, $to) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getSellingPrice($from, $to);
}

function sell_purchase_price($from, $to) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getPurchasePriceOfSell($from, $to);
}

function discount_amount($from, $to) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getDiscountAmount($from, $to);
}

function purchase_discount_amount($from, $to) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getPurchaseDiscountAmount($from, $to);
}

function shipping_charge($from, $to) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getShippingCharge($from, $to);
}

function purchase_shipping_charge($from, $to) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getPurchaseShippingCharge($from, $to);
}

function others_charge($from, $to) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getOthersCharge($from, $to);
}

function purchase_others_charge($from, $to) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getPurchaseOthersCharge($from, $to);
}

function purchase_price($from, $to) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getPurchasePrice($from, $to);
}

function selling_return_amount($from, $to) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getSellingReturnAmount($from, $to);
}

function tax_return_amount($from, $to) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getTaxReturnAmount($from, $to);
}

function gst_return_amount($from, $to) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getGSTReturnAmount($from, $to);
}

function purchase_return_amount($from, $to) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getPurchaseReturnAmount($from, $to);
}

function purchase_tax_return_amount($from, $to) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getPurchaseTaxReturnAmount($from, $to);
}

function purchase_gst_return_amount($from, $to) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getPurchaseGSTReturnAmount($from, $to);
}

function due_amount($from, $to) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getDueAmount($from, $to);
}

function purchase_due_amount($from, $to) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getpurchaseDueAmount($from, $to);
}

function due_collection_amount($from, $to) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getDueCollectionAmount($from, $to);
}

function anotherday_due_collection_amount($from, $to) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getAnothrDayDueCollectionAmount($from, $to);
}

function anotherday_due_paid_amount($from, $to) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getAnothrDayDuePaidAmount($from, $to);
}

function purchase_due_paid_amount($from, $to) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getpurchaseDuePaidAmount($from, $to);
}

function received_amount($from, $to) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getReceivedAmount($from, $to);
}

function sell_received_amount($from, $to) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getSellReceivedAmount($from, $to);
}

function purchase_total_paid($from, $to) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getPurchaseTotalPaidAmount($from, $to);
}

function sourcewise_profit_amount($source_id,$from=null, $to=null) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getSourcewiseProfitAmount($source_id,$from, $to);
}

function get_tax($type, $from, $to) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getTax($type, $from, $to);
}

function get_in_or_exclusive_tax($type, $from, $to) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getInOrExclusiveTax($type, $from, $to);
}

function get_purchase_tax($type, $from, $to) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getpurchaseTax($type, $from, $to);
}

function get_in_or_exclusive_purchase_tax($type, $from, $to) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getInOrExclusivepurchaseTax($type, $from, $to);
}

function selling_price_daywise($year, $month = null, $day = null) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getSellingPriceDaywise($year, $month, $day);
}

function received_amount_daywise($year, $month = null, $day = null) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getReceivedAmountDaywise($year, $month, $day);
}

function profit_amount_daywise($year, $month = null, $day = null) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getProfitAmountDaywise($year, $month, $day);
}

function tax_amount_daywise($year, $month = null, $day = null) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getTaxAmountDaywise($year, $month, $day);
}

function expense_amount($from, $to) 
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getExpenseAmount($from, $to);
}

function purchase_in_year($year) 
{
	$totalPurchase = [];
	for ($i=1; $i < 12; $i++) { 
		$totalPurchase[$i] = purchase_price($year, $i);
	}
	return $totalPurchase;
}

function sell_in_year($year) 
{
	$totalSell = [];
	for ($i=1; $i < 12; $i++) { 
		$totalSell[$i] = sell_price($year, $i);
	}
	return $totalSell;
}

function total_out_of_stock()
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->totalOutOfStock();
}

function total_expired()
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->totalExpired();
}

function get_balance($customer_id, $index = null) 
{	
	

	$customer_model = registry()->get('loader')->model('customer');
	return $customer_model->getBalance($customer_id, $index);
}

function get_quantity_in_stock($p_id, $store_id = null)
{
	$store_id = $store_id ? $store_id : store_id();

	

	$product_model = registry()->get('loader')->model('product');

	return $product_model->getQtyInStock($p_id, $store_id);
}

function top_products($from, $to, $limit = 3)
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getTopProducts($from, $to, $limit);
}

function top_customers($from, $to, $limit = 3)
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getTopCustomers($from, $to, $limit);
}

function top_suppliers($from, $to, $limit = 3)
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getTopSuppliers($from, $to, $limit);
}

function top_brands($from, $to, $limit = 3)
{	
	$report_model = registry()->get('loader')->model('report');
	return $report_model->getTopBrands($from, $to, $limit);
}