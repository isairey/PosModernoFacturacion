<?php
function dd($data)
{
	echo "<pre>".print_r($data,true)."</pre>"; exit;
}

function isRTL()
{
	return RTL;
}
function device_type()
{
	global $deviceType;
	return $deviceType;
}

function redirect($url, $status = 302) 
{	
	if (function_exists('registry')) 
	{
		if (registry()->get('user') && registry()->get('user')->isLogged() && isset(registry()->get('request')->get['redirect_to']) && registry()->get('request')->get['redirect_to']) {
			header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), registry()->get('request')->get['redirect_to']), true, $status);
			exit();
		}
	}
	header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $url), true, $status);
	exit();
}

function is_https()
{
	return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on" ? true : false;
}

function is_ajax() 
{
	return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
}

function get_protocol()
{
	return PROTOCOL;
}

function root_url() 
{
    return ROOT_URL;
}

function url() 
{
    $request_uri = SUBDIRECTORY ? str_replace(SUBDIRECTORY, '', $_SERVER['REQUEST_URI']) : $_SERVER['REQUEST_URI'];
    return root_url() . str_replace('//','/',$request_uri);
}

function relative_url() 
{
	return strtok($_SERVER["REQUEST_URI"], '?');
}

function query_string($name)
{
	global $request;
	if (isset($request->get[$name])) {
		return htmlspecialchars($request->get[$name]);
	}	
}

function is_cli()
{
	return (PHP_SAPI === 'cli' OR defined('STDIN'));
}

function current_nav() 
{
	return basename(relative_url(), ".php");
}

function create_box_state()
{
	global $request;
	$box_state = array(
		'open'
	);
	if (isset($request->get['box_state'] ) 
		&& in_array($request->get['box_state'], $box_state)) {
		return null;
	}
	return ' collapsed-box';
}

function year()
{
	return date('Y');
}

function month() 
{
	return date('m');
}

function day() 
{
	return date('d');
}

function current_time() 
{
	return date('h:i:s');
}

function to_am_pm($time) {
	return date("g:i A", strtotime($time));
}

function date_time()
{
	return date('Y-m-d H:i:s');
}

function format_date($date) 
{
	return date("j M Y g:i A", strtotime($date));
}

function format_only_date($date) 
{
	return date("j M Y", strtotime($date));
}

function format_input_number($val)
{
	return number_format($val,2,'.','');
}

function randomNumber($length) {
    $result = '';

    for($i = 0; $i < $length; $i++) {
        $result .= mt_rand(0, 9);
    }

    return $result;
}

function unique_id($limit = 8) 
{
    return substr(md5(uniqid(mt_rand(), true)), 0, $limit);
}

function random_color_part() 
{
    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
}

function random_color() 
{
    return random_color_part() . random_color_part() . random_color_part();
}

function get_months($index) 
{
	$array = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
	return isset($array[$index]) ? $array[$index] : $index;
}

function get_total_day_in_month()
{
	return cal_days_in_month(CAL_GREGORIAN, month(), year());
}

function limit_char($string, $max = 255)
{
   if(mb_strlen($string, 'utf-8') >= $max){
       $string = mb_substr($string, 0, $max - 5, 'utf-8').'...';
   } 

   return $string;
}

function from()
{
	global $request;
	$from = null;
	if (isset($request->get['from']) && $request->get['from'] && ($request->get['from'] != 'null')) {
	  $from = $request->get['from'];
	}
	return $from;
}

function to()
{
	global $request;
	$to = null;
	if (isset($request->get['to']) && isset($request->get['from']) && ($request->get['to'] != 'null') && ($request->get['from'] != 'null')) {
	  $to = $request->get['to'];
	} elseif(isset($request->get['from']) && ($request->get['from'] != 'null')) {
		$to = date('Y-m-d 23:59:59', strtotime($request->get['from']));
	}
	return $to;
}

function date_range_filter($from, $to)
{
	$from = $from ? $from : date('Y-m-d');
	$to = $to ? $to : date('Y-m-d');
	$where_query = '';
	if (($from && ($to == false)) || ($from == $to)) {
		$day = date('d', strtotime($from));
		$month = date('m', strtotime($from));
		$year = date('Y', strtotime($from));
		$where_query .= " AND DAY(`selling_info`.`created_at`) = {$day}";
		$where_query .= " AND MONTH(`selling_info`.`created_at`) = {$month}";
		$where_query .= " AND YEAR(`selling_info`.`created_at`) = {$year}";
	} else {
		$from = date('Y-m-d H:i:s', strtotime($from.' '. '00:00:00')); 
		$to = date('Y-m-d H:i:s', strtotime($to.' '. '23:59:59'));
		$where_query .= " AND selling_info.created_at >= '{$from}' AND selling_info.created_at <= '{$to}'";
	}
	return $where_query;
}

function date_range_sell_log_filter($from, $to)
{
	$from = $from ? $from : date('Y-m-d');
	$to = $to ? $to : date('Y-m-d');
	$where_query = '';
	if (($from && ($to == false)) || ($from == $to)) {
		$day = date('d', strtotime($from));
		$month = date('m', strtotime($from));
		$year = date('Y', strtotime($from));
		$where_query .= " AND DAY(`sell_logs`.`created_at`) = {$day}";
		$where_query .= " AND MONTH(`sell_logs`.`created_at`) = {$month}";
		$where_query .= " AND YEAR(`sell_logs`.`created_at`) = {$year}";
	} else {
		$from = date('Y-m-d H:i:s', strtotime($from.' '. '00:00:00')); 
		$to = date('Y-m-d H:i:s', strtotime($to.' '. '23:59:59'));
		$where_query .= " AND sell_logs.created_at >= '{$from}' AND sell_logs.created_at <= '{$to}'";
	}
	return $where_query;
}

function date_range_holding_order_filter($from, $to)
{
	$from = $from ? $from : date('Y-m-d');
	$to = $to ? $to : date('Y-m-d');
	$where_query = '';
	if (($from && ($to == false)) || ($from == $to)) {
		$day = date('d', strtotime($from));
		$month = date('m', strtotime($from));
		$year = date('Y', strtotime($from));
		$where_query .= " AND DAY(`holding_info`.`created_at`) = {$day}";
		$where_query .= " AND MONTH(`holding_info`.`created_at`) = {$month}";
		$where_query .= " AND YEAR(`holding_info`.`created_at`) = {$year}";
	} else {
		$from = date('Y-m-d H:i:s', strtotime($from.' '. '00:00:00')); 
		$to = date('Y-m-d H:i:s', strtotime($to.' '. '23:59:59'));
		$where_query .= " AND holding_info.created_at >= '{$from}' AND holding_info.created_at <= '{$to}'";
	}
	return $where_query;
}

function date_range_quotation_filter($from, $to)
{
	$from = $from ? $from : date('Y-m-d');
	$to = $to ? $to : date('Y-m-d');
	$where_query = '';
	if (($from && ($to == false)) || ($from == $to)) {
		$day = date('d', strtotime($from));
		$month = date('m', strtotime($from));
		$year = date('Y', strtotime($from));
		$where_query .= " AND DAY(`quotation_info`.`created_at`) = {$day}";
		$where_query .= " AND MONTH(`quotation_info`.`created_at`) = {$month}";
		$where_query .= " AND YEAR(`quotation_info`.`created_at`) = {$year}";
	} else {
		$from = date('Y-m-d H:i:s', strtotime($from.' '. '00:00:00')); 
		$to = date('Y-m-d H:i:s', strtotime($to.' '. '23:59:59'));
		$where_query .= " AND quotation_info.created_at >= '{$from}' AND quotation_info.created_at <= '{$to}'";
	}
	return $where_query;
}

function date_range_filter2($from, $to)
{
	$from = $from ? $from : date('Y-m-d');
	$to = $to ? $to : date('Y-m-d');
	$where_query = '';
	if (($from && ($to == false)) || ($from == $to)) {
		$day = date('d', strtotime($from));
		$month = date('m', strtotime($from));
		$year = date('Y', strtotime($from));
		$where_query .= " AND DAY(`purchase_info`.`created_at`) = {$day}";
		$where_query .= " AND MONTH(`purchase_info`.`created_at`) = {$month}";
		$where_query .= " AND YEAR(`purchase_info`.`created_at`) = {$year}";
	} else {
		$from = date('Y-m-d H:i:s', strtotime($from.' '. '00:00:00')); 
		$to = date('Y-m-d H:i:s', strtotime($to.' '. '23:59:59'));
		$where_query .= " AND purchase_info.created_at >= '{$from}' AND purchase_info.created_at <= '{$to}'";
	}
	return $where_query;
}

function date_range_item_filter($from, $to)
{
	$from = $from ? $from : date('Y-m-d');
	$to = $to ? $to : date('Y-m-d');
	$where_query = '';
	if (($from && ($to == false)) || ($from == $to)) {
		$day = date('d', strtotime($from));
		$month = date('m', strtotime($from));
		$year = date('Y', strtotime($from));
		$where_query .= " AND DAY(`selling_item`.`created_at`) = {$day}";
		$where_query .= " AND MONTH(`selling_item`.`created_at`) = {$month}";
		$where_query .= " AND YEAR(`selling_item`.`created_at`) = {$year}";
	} else {
		$from = date('Y-m-d H:i:s', strtotime($from.' '. '00:00:00')); 
		$to = date('Y-m-d H:i:s', strtotime($to.' '. '23:59:59'));
		$where_query .= " AND selling_item.created_at >= '{$from}' AND selling_item.created_at <= '{$to}'";
	}
	return $where_query;
}

function date_range_selling_return_filter($from, $to)
{
	$from = $from ? $from : date('Y-m-d');
	$to = $to ? $to : date('Y-m-d');
	$where_query = '';
	if (($from && ($to == false)) || ($from == $to)) {
		$day = date('d', strtotime($from));
		$month = date('m', strtotime($from));
		$year = date('Y', strtotime($from));
		$where_query .= " AND DAY(`returns`.`created_at`) = {$day}";
		$where_query .= " AND MONTH(`returns`.`created_at`) = {$month}";
		$where_query .= " AND YEAR(`returns`.`created_at`) = {$year}";
	} else {
		$from = date('Y-m-d H:i:s', strtotime($from.' '. '00:00:00')); 
		$to = date('Y-m-d H:i:s', strtotime($to.' '. '23:59:59'));
		$where_query .= " AND returns.created_at >= '{$from}' AND returns.created_at <= '{$to}'";
	}
	return $where_query;
}

function date_range_purchase_return_filter($from, $to)
{
	$from = $from ? $from : date('Y-m-d');
	$to = $to ? $to : date('Y-m-d');
	$where_query = '';
	if (($from && ($to == false)) || ($from == $to)) {
		$day = date('d', strtotime($from));
		$month = date('m', strtotime($from));
		$year = date('Y', strtotime($from));
		$where_query .= " AND DAY(`purchase_returns`.`created_at`) = {$day}";
		$where_query .= " AND MONTH(`purchase_returns`.`created_at`) = {$month}";
		$where_query .= " AND YEAR(`purchase_returns`.`created_at`) = {$year}";
	} else {
		$from = date('Y-m-d H:i:s', strtotime($from.' '. '00:00:00')); 
		$to = date('Y-m-d H:i:s', strtotime($to.' '. '23:59:59'));
		$where_query .= " AND purchase_returns.created_at >= '{$from}' AND purchase_returns.created_at <= '{$to}'";
	}
	return $where_query;
}

function date_range_installment_filter($from, $to)
{
	$from = $from ? $from : date('Y-m-d');
	$to = $to ? $to : date('Y-m-d');
	$where_query = '';
	if (($from && ($to == false)) || ($from == $to)) {
		$day = date('d', strtotime($from));
		$month = date('m', strtotime($from));
		$year = date('Y', strtotime($from));
		$where_query .= " AND DAY(`installment_orders`.`created_at`) = {$day}";
		$where_query .= " AND MONTH(`installment_orders`.`created_at`) = {$month}";
		$where_query .= " AND YEAR(`installment_orders`.`created_at`) = {$year}";
	} else {
		$from = date('Y-m-d H:i:s', strtotime($from.' '. '00:00:00')); 
		$to = date('Y-m-d H:i:s', strtotime($to.' '. '23:59:59'));
		$where_query .= " AND installment_orders.created_at >= '{$from}' AND installment_orders.created_at <= '{$to}'";
	}
	return $where_query;
}

function date_range_installment_payment_filter($from, $to)
{
	$from = $from ? $from : date('Y-m-d');
	$to = $to ? $to : date('Y-m-d');
	$where_query = '';
	if (($from && ($to == false)) || ($from == $to)) {
		$day = date('d', strtotime($from));
		$month = date('m', strtotime($from));
		$year = date('Y', strtotime($from));
		$where_query .= " AND DAY(`installment_payments`.`payment_date`) = {$day}";
		$where_query .= " AND MONTH(`installment_payments`.`payment_date`) = {$month}";
		$where_query .= " AND YEAR(`installment_payments`.`payment_date`) = {$year}";
	} else {
		$from = date('Y-m-d H:i:s', strtotime($from.' '. '00:00:00')); 
		$to = date('Y-m-d H:i:s', strtotime($to.' '. '23:59:59'));
		$where_query .= " AND installment_payments.payment_date >= '{$from}' AND installment_payments.payment_date <= '{$to}'";
	}
	return $where_query;
}

function date_range_sell_payments_filter($from, $to)
{
	$from = $from ? $from : date('Y-m-d');
	$to = $to ? $to : date('Y-m-d');
	$where_query = '';
	if (($from && ($to == false)) || ($from == $to)) {
		$day = date('d', strtotime($from));
		$month = date('m', strtotime($from));
		$year = date('Y', strtotime($from));
		$where_query .= " AND DAY(`payments`.`created_at`) = {$day}";
		$where_query .= " AND MONTH(`payments`.`created_at`) = {$month}";
		$where_query .= " AND YEAR(`payments`.`created_at`) = {$year}";
	} else {
		$from = date('Y-m-d H:i:s', strtotime($from.' '. '00:00:00')); 
		$to = date('Y-m-d H:i:s', strtotime($to.' '. '23:59:59'));
		$where_query .= " AND payments.created_at >= '{$from}' AND payments.created_at <= '{$to}'";
	}
	return $where_query;
}

function date_range_sell_payments_reverse_filter($from, $to)
{
	$from = $from ? $from : date('Y-m-d');
	$from = date('Y-m-d H:i:s', strtotime($from.' '. '00:00:00')); 
	$where_query = " AND payments.created_at < '{$from}'";
	return $where_query;
}

function date_range_purchase_payments_reverse_filter($from, $to)
{
	$from = $from ? $from : date('Y-m-d');
	$from = date('Y-m-d H:i:s', strtotime($from.' '. '00:00:00')); 
	$where_query = " AND purchase_payments.created_at < '{$from}'";
	return $where_query;
}

function date_range_purchase_payments_filter($from, $to)
{
	$from = $from ? $from : date('Y-m-d');
	$to = $to ? $to : date('Y-m-d');
	$where_query = '';
	if (($from && ($to == false)) || ($from == $to)) {
		$day = date('d', strtotime($from));
		$month = date('m', strtotime($from));
		$year = date('Y', strtotime($from));
		$where_query .= " AND DAY(`purchase_payments`.`created_at`) = {$day}";
		$where_query .= " AND MONTH(`purchase_payments`.`created_at`) = {$month}";
		$where_query .= " AND YEAR(`purchase_payments`.`created_at`) = {$year}";
	} else {
		$from = date('Y-m-d H:i:s', strtotime($from.' '. '00:00:00')); 
		$to = date('Y-m-d H:i:s', strtotime($to.' '. '23:59:59'));
		$where_query .= " AND purchase_payments.created_at >= '{$from}' AND purchase_payments.created_at <= '{$to}'";
	}
	return $where_query;
}

function date_range_accounting_filter($from, $to)
{
	$from = $from ? $from : date('Y-m-d');
	$to = $to ? $to : date('Y-m-d');
	$where_query = '';
	if (($from && ($to == false)) || ($from == $to)) {
		$day = date('d', strtotime($from));
		$month = date('m', strtotime($from));
		$year = date('Y', strtotime($from));
		$where_query .= " AND DAY(`bank_transaction_info`.`created_at`) = {$day}";
		$where_query .= " AND MONTH(`bank_transaction_info`.`created_at`) = {$month}";
		$where_query .= " AND YEAR(`bank_transaction_info`.`created_at`) = {$year}";
	} else {
		$from = date('Y-m-d H:i:s', strtotime($from.' '. '00:00:00')); 
		$to = date('Y-m-d H:i:s', strtotime($to.' '. '23:59:59'));
		$where_query .= " AND bank_transaction_info.created_at >= '{$from}' AND bank_transaction_info.created_at <= '{$to}'";
	}
	return $where_query;
}

function date_range_loan_filter($from, $to)
{
	$from = $from ? $from : date('Y-m-d');
	$to = $to ? $to : date('Y-m-d');
	$where_query = '';
	if (($from && ($to == false)) || ($from == $to)) {
		$day = date('d', strtotime($from));
		$month = date('m', strtotime($from));
		$year = date('Y', strtotime($from));
		$where_query .= " AND DAY(`loans`.`created_at`) = {$day}";
		$where_query .= " AND MONTH(`loans`.`created_at`) = {$month}";
		$where_query .= " AND YEAR(`loans`.`created_at`) = {$year}";
	} else {
		$from = date('Y-m-d H:i:s', strtotime($from.' '. '00:00:00')); 
		$to = date('Y-m-d H:i:s', strtotime($to.' '. '23:59:59'));
		$where_query .= " AND loans.created_at >= '{$from}' AND loans.created_at <= '{$to}'";
	}
	return $where_query;
}

function date_range_loan_payment_filter($from, $to)
{
	$from = $from ? $from : date('Y-m-d');
	$to = $to ? $to : date('Y-m-d');
	$where_query = '';
	if (($from && ($to == false)) || ($from == $to)) {
		$day = date('d', strtotime($from));
		$month = date('m', strtotime($from));
		$year = date('Y', strtotime($from));
		$where_query .= " AND DAY(`loan_payments`.`created_at`) = {$day}";
		$where_query .= " AND MONTH(`loan_payments`.`created_at`) = {$month}";
		$where_query .= " AND YEAR(`loan_payments`.`created_at`) = {$year}";
	} else {
		$from = date('Y-m-d H:i:s', strtotime($from.' '. '00:00:00')); 
		$to = date('Y-m-d H:i:s', strtotime($to.' '. '23:59:59'));
		$where_query .= " AND loan_payments.created_at >= '{$from}' AND loan_payments.created_at <= '{$to}'";
	}
	return $where_query;
}

function date_range_filter_customer($from, $to)
{
	$from = $from ? $from : date('Y-m-d');
	$to = $to ? $to : date('Y-m-d');
	$where_query = '';
	if (($from && ($to == false)) || ($from == $to)) {
		$day = date('d', strtotime($from));
		$month = date('m', strtotime($from));
		$year = date('Y', strtotime($from));
		$where_query .= " AND DAY(`customers`.`created_at`) = {$day}";
		$where_query .= " AND MONTH(`customers`.`created_at`) = {$month}";
		$where_query .= " AND YEAR(`customers`.`created_at`) = {$year}";
	} else {
		$from = date('Y-m-d H:i:s', strtotime($from.' '. '00:00:00')); 
		$to = date('Y-m-d H:i:s', strtotime($to.' '. '23:59:59'));
		$where_query .= " AND customers.created_at >= '{$from}' AND customers.created_at <= '{$to}'";
	}
	return $where_query;
}

function date_range_purchase_log_filter($from, $to)
{
	$from = $from ? $from : date('Y-m-d');
	$to = $to ? $to : date('Y-m-d');
	$where_query = '';
	if (($from && ($to == false)) || ($from == $to)) {
		$day = date('d', strtotime($from));
		$month = date('m', strtotime($from));
		$year = date('Y', strtotime($from));
		$where_query .= " AND DAY(`purchase_logs`.`created_at`) = {$day}";
		$where_query .= " AND MONTH(`purchase_logs`.`created_at`) = {$month}";
		$where_query .= " AND YEAR(`purchase_logs`.`created_at`) = {$year}";
	} else {
		$from = date('Y-m-d H:i:s', strtotime($from.' '. '00:00:00')); 
		$to = date('Y-m-d H:i:s', strtotime($to.' '. '23:59:59'));
		$where_query .= " AND purchase_logs.created_at >= '{$from}' AND purchase_logs.created_at <= '{$to}'";
	}
	return $where_query;
}

function date_range_customer_transaction_filter($from, $to)
{
	$from = $from ? $from : date('Y-m-d');
	$to = $to ? $to : date('Y-m-d');
	$where_query = '';
	if (($from && ($to == false)) || ($from == $to)) {
		$day = date('d', strtotime($from));
		$month = date('m', strtotime($from));
		$year = date('Y', strtotime($from));
		$where_query .= " AND DAY(`customer_transactions`.`created_at`) = {$day}";
		$where_query .= " AND MONTH(`customer_transactions`.`created_at`) = {$month}";
		$where_query .= " AND YEAR(`customer_transactions`.`created_at`) = {$year}";
	} else {
		$from = date('Y-m-d H:i:s', strtotime($from.' '. '00:00:00')); 
		$to = date('Y-m-d H:i:s', strtotime($to.' '. '23:59:59'));
		$where_query .= " AND customer_transactions.created_at >= '{$from}' AND customer_transactions.created_at <= '{$to}'";
	}
	return $where_query;
}

function date_range_giftcard_topup_filter($from, $to)
{
	$from = $from ? $from : date('Y-m-d');
	$to = $to ? $to : date('Y-m-d');
	$where_query = '';
	if (($from && ($to == false)) || ($from == $to)) {
		$day = date('d', strtotime($from));
		$month = date('m', strtotime($from));
		$year = date('Y', strtotime($from));
		$where_query .= " AND DAY(`gift_card_topups`.`date`) = {$day}";
		$where_query .= " AND MONTH(`gift_card_topups`.`date`) = {$month}";
		$where_query .= " AND YEAR(`gift_card_topups`.`date`) = {$year}";
	} else {
		$from = date('Y-m-d H:i:s', strtotime($from.' '. '00:00:00')); 
		$to = date('Y-m-d H:i:s', strtotime($to.' '. '23:59:59'));
		$where_query .= " AND gift_card_topups.date >= '{$from}' AND gift_card_topups.date <= '{$to}'";
	}
	return $where_query;
}

function date_range_giftcard_filter($from, $to)
{
	$from = $from ? $from : date('Y-m-d');
	$to = $to ? $to : date('Y-m-d');
	$where_query = '';
	if (($from && ($to == false)) || ($from == $to)) {
		$day = date('d', strtotime($from));
		$month = date('m', strtotime($from));
		$year = date('Y', strtotime($from));
		$where_query .= " AND DAY(`gift_cards`.`date`) = {$day}";
		$where_query .= " AND MONTH(`gift_cards`.`date`) = {$month}";
		$where_query .= " AND YEAR(`gift_cards`.`date`) = {$year}";
	} else {
		$from = date('Y-m-d H:i:s', strtotime($from.' '. '00:00:00')); 
		$to = date('Y-m-d H:i:s', strtotime($to.' '. '23:59:59'));
		$where_query .= " AND gift_cards.date >= '{$from}' AND gift_cards.date <= '{$to}'";
	}
	return $where_query;
}

function date_range_expense_filter($from, $to)
{
	$from = $from ? $from : date('Y-m-d');
	$to = $to ? $to : date('Y-m-d');
	$where_query = '';
	if (($from && ($to == false)) || ($from == $to)) {
		$day = date('d', strtotime($from));
		$month = date('m', strtotime($from));
		$year = date('Y', strtotime($from));
		$where_query .= " AND DAY(`expenses`.`created_at`) = {$day}";
		$where_query .= " AND MONTH(`expenses`.`created_at`) = {$month}";
		$where_query .= " AND YEAR(`expenses`.`created_at`) = {$year}";
	} else {
		$from = date('Y-m-d H:i:s', strtotime($from.' '. '00:00:00')); 
		$to = date('Y-m-d H:i:s', strtotime($to.' '. '23:59:59'));
		$where_query .= " AND expenses.created_at >= '{$from}' AND expenses.created_at <= '{$to}'";
	}
	return $where_query;
}

function date_range_return_filter($from, $to)
{
	$from = $from ? $from : date('Y-m-d');
	$to = $to ? $to : date('Y-m-d');
	$where_query = '';
	if (($from && ($to == false)) || ($from == $to)) {
		$day = date('d', strtotime($from));
		$month = date('m', strtotime($from));
		$year = date('Y', strtotime($from));
		$where_query .= " AND DAY(`returns`.`created_at`) = {$day}";
		$where_query .= " AND MONTH(`returns`.`created_at`) = {$month}";
		$where_query .= " AND YEAR(`returns`.`created_at`) = {$year}";
	} else {
		$from = date('Y-m-d H:i:s', strtotime($from.' '. '00:00:00')); 
		$to = date('Y-m-d H:i:s', strtotime($to.' '. '23:59:59'));
		$where_query .= " AND returns.created_at >= '{$from}' AND returns.created_at <= '{$to}'";
	}
	return $where_query;
}

function barcode_generator()
{
	require_once(DIR_INCLUDE.'vendor/barcode-reader/src/BarcodeGenerator.php');
	require_once(DIR_INCLUDE.'vendor/barcode-reader/src/BarcodeGeneratorPNG.php');
	require_once(DIR_INCLUDE.'vendor/barcode-reader/src/BarcodeGeneratorSVG.php');
	require_once(DIR_INCLUDE.'vendor/barcode-reader/src/BarcodeGeneratorJPG.php');
	require_once(DIR_INCLUDE.'vendor/barcode-reader/src/BarcodeGeneratorHTML.php');

	$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
	return $generator;
}

function barcode_symbology($generator, $type = 'code39')
{
	switch ($type) {
		case 'code25':
			$symbology = $generator::TYPE_STANDARD_2_5;
			break;
		case 'code39':
			$symbology = $generator::TYPE_CODE_39;
			break;
		case 'code93':
			$symbology = $generator::TYPE_CODE_93;
			break;
		case 'code128':
			$symbology = $generator::TYPE_CODE_128;
			break;
		case 'ean5':
			$symbology = $generator::TYPE_EAN_5;
			break;
		case 'ean13':
			$symbology = $generator::TYPE_EAN_13;
			break;
		case 'upca':
			$symbology = $generator::TYPE_UPC_A;
			break;
		case 'upce':
			$symbology = $generator::TYPE_UPC_E;
			break;
		default:
			$symbology = $generator::TYPE_CODE_39;
	}
	return $symbology;
}

function pdo_start()
{
	global $sql_details;
	$host = $sql_details['host'];
	$db = $sql_details['db'];
	$user = $sql_details['user'];
	$pass = $sql_details['pass'];
	$port = $sql_details['port'];
	try {
		$db = new PDO("mysql:host={$host};port={$port};dbname={$db};charset=utf8",$user,$pass);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e) {
		die('Database Connection Error: '.$e->getMessage());
	}
	return $db;
}

function get_all_tables()
{
	global $sql_details;
	$db_name = $sql_details['db'];
	$statement = db()->prepare("SHOW TABLES FROM {$db_name}"); 
	$statement->execute(array());
	return $statement->fetchAll(PDO::FETCH_NUM); 
}

function tableExists($pdo, $table) {
    try {
        $result = $pdo->query("SELECT 1 FROM $table LIMIT 1");
    } catch (Exception $e) {
        return false;
    }
    return $result !== false;
}

function play_sound($name, $path = null) {
	$path = $path ? $path : root_url() . '/assets/itsolution24/mp3/' . $name;
	?>
	<audio style="display:none;" controls autoplay>
	  <source src="<?php echo $path;?>" type="audio/ogg">
	  <source src="<?php echo $path;?>" type="audio/mpeg">
	  <source src="<?php echo $path;?>" type="audio/mp3">
	</audio>
	<?php
}

function upper($state) {
    return str_replace('_', ' ', ucwords($state));
}

if (!function_exists('health_checkup'))
{
	function health_checkup($store_id = null)
	{		
		return true;
	}
}

function updateImageValue(&$image, $key) {
  if($key == 'p_image') {
    if (FILEMANAGERPATH && is_file(FILEMANAGERPATH.$image) && file_exists(FILEMANAGERPATH.$image))  {
    	$image = FILEMANAGERURL.$image;
    } elseif (is_file(DIR_STORAGE . 'products/' . $image) && file_exists(DIR_STORAGE . 'products/' . $image)) {
    	$image = root_url().'/storage/products'.$image;
    } else {
    	$image = root_url().'/assets/itsolution24/img/noproduct.png';
    }
  }
}

function updateNameValue(&$data, $key) {
  if($key == 'p_name') {
    $data = htmlspecialchars_decode($data);
  }
}

function get_progress_percentage($total, $substract)
{
	return 100 - (($substract / $total)*100);
}

function convert_number_to_word($number) 
{   
    $hyphen      = '-';
    $conjunction = '  ';
    $separator   = ' ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'Zero',
        1                   => 'One',
        2                   => 'Two',
        3                   => 'Three',
        4                   => 'Four',
        5                   => 'Five',
        6                   => 'Six',
        7                   => 'Seven',
        8                   => 'Eight',
        9                   => 'Nine',
        10                  => 'Ten',
        11                  => 'Eleven',
        12                  => 'Twelve',
        13                  => 'Thirteen',
        14                  => 'Fourteen',
        15                  => 'Fifteen',
        16                  => 'Sixteen',
        17                  => 'Seventeen',
        18                  => 'Eighteen',
        19                  => 'Nineteen',
        20                  => 'Twenty',
        30                  => 'Thirty',
        40                  => 'Fourty',
        50                  => 'Fifty',
        60                  => 'Sixty',
        70                  => 'Seventy',
        80                  => 'Eighty',
        90                  => 'Ninety',
        100                 => 'Hundred',
        1000                => 'Thousand',
        1000000             => 'Million',
        1000000000          => 'Billion',
        1000000000000       => 'Trillion',
        1000000000000000    => 'Quadrillion',
        1000000000000000000 => 'Quintillion'
    );
   
    if (!is_numeric($number)) {
        return false;
    }
   
    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_word only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }
 
    if ($number < 0) {
        return $negative . convert_number_to_word(abs($number));
    }
   
    $string = $fraction = null;
   
    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }
   
    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_word($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_word($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_word($remainder);
            }
            break;
    }
   
    if (null !== $fraction && is_numeric($fraction)) {
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
        	if ($number > 0) {
        		$words[] = $dictionary[$number];
        	}
        }
        if (!empty($words)) {
        	$string .= $decimal;
        }	
        $string .= implode(' ', $words);
    }
   
    return $string . ' only';
}

function mergeArray($array1,$array2)
{
    $mergedArray = [];

    foreach ($array1 as $key => $value) 
    {
        if(isset($array2[$key]))
        {
           $mergedArray[$key] = $array2[$key];
        } else {
            $mergedArray[$key] = $array1[$key];
        }
    }
    return $mergedArray;
}