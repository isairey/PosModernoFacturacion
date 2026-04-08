<?php 
ob_start();
session_start();
include ("../_init.php");

// Check, if user logged in or not
// If user is not logged in then return an alert message
if (!is_loggedin()) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_login')));
  exit();
}

$store_id = store_id();
$user_id = user_id();


/**
 *===================
 * START DATATABLE
 *===================
 */

$where_query = "bank_transaction_info.store_id = '$store_id' AND bank_transaction_info.transaction_type IN ('deposit') AND income_sources.profitable='yes'";
if (isset($request->get['account_id']) && $request->get['account_id'] != 'null') {
  $account_id = $request->get['account_id'];
  $where_query .= " AND bank_transaction_info.account_id = $account_id";
}
$from = from();
$to = to();
// DB table to use
$table = "(SELECT bank_transaction_info.*, income_sources.source_slug, income_sources.for_sell, income_sources.for_due_collection, income_sources.profitable, bank_transaction_price.price_id, SUM(bank_transaction_price.amount) amount 
  FROM bank_transaction_info 
  JOIN bank_transaction_price ON bank_transaction_info.info_id = bank_transaction_price.info_id
  JOIN income_sources ON bank_transaction_info.source_id = income_sources.source_id
  WHERE $where_query GROUP BY bank_transaction_info.transaction_type) as bank_transaction_info";
 
// Table's primary key
$primaryKey = 'info_id';

// indexes
$columns = array(
    array(
        'db' => 'ref_no',
        'dt' => 'DT_RowId',
        'formatter' => function( $d, $row ) {
            return 'row_'.$d;
        }
    ),
    array( 'db' => 'price_id', 'dt' => 'serial_no' ),
    array( 'db' => 'for_sell', 'dt' => 'for_sell' ),
    array( 'db' => 'for_due_collection', 'dt' => 'for_due_collection' ),
    array( 
      'db' => 'source_id',   
      'dt' => 'title',
      'formatter' => function($d, $row) {
          $parent = '';
          $category = get_the_income_source($row['source_id']);
          if ($category['parent_id']) {
              $parent = get_the_income_source($category['parent_id']);
              $parent = $parent['source_name'] .  ' > ';
          }
          $category = get_the_income_source($row['source_id']);
          if ($category['for_sell'] == 1 || $category['for_due_collection'] == 1) {
            $category_name = 'Profit from Sell';
          } else {
            $category_name = $category['source_name'];
          }
          return $parent . $category_name;
      }
    ),
    array( 
      'db' => 'amount',   
      'dt' => 'this_month',
      'formatter' => function($d, $row) use($from,$to) {
        $year = $from ? date('Y', strtotime($from)) : year();
        $month = $from ? date('m', strtotime($from)) : month();
        $days_in_month = get_total_day_in_month();
        $from = date('Y-m-d',strtotime($year.'-'.$month.'-1'));
        $to = $year.'-'.$month.'-'.$days_in_month;
        if($row['for_sell'] == 1 || $row['for_due_collection'] == 1) {
          return currency_format(get_profit_amount($from, $to));
        }
        return currency_format(get_total_source_income($row['source_id'],$from,$to));
      }
    ),
    array( 
      'db' => 'amount',   
      'dt' => 'this_year',
      'formatter' => function($d, $row) use($from,$to) {
        $year = $from ? date('Y', strtotime($from)) : year();
        $from = date('Y-m-d',strtotime($year.'-1-1'));
        $to = $year.'-12-31';
        if($row['for_sell'] == 1 || $row['for_due_collection'] == 1) {
          $total = get_profit_amount($from, $to);
          return currency_format($total);
        }
        return currency_format(get_total_source_income($row['source_id'],$from,$to));
      }
    ),
    array( 
      'db' => 'amount',   
      'dt' => 'till_now',
      'formatter' => function($d, $row) use($from,$to) {
        $year = $from ? date('Y', strtotime($from)) : year();
        $from = date('Y-m-d',strtotime($year.'-1-1'));
        $to = $year.'-12-31';
        if($row['for_sell'] == 1 || $row['for_due_collection'] == 1) {
          $total = get_profit_amount();
          return currency_format($total);
        }
        return currency_format(get_total_source_income($row['source_id']));
      }
    ),
);

echo json_encode(
    SSP::simple($request->get, $sql_details, $table, $primaryKey, $columns)
);

/**
 *===================
 * END DATATABLE
 *===================
 */