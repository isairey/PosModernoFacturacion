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
$where_query = "bank_transaction_info.store_id = $store_id AND bank_transaction_info.transaction_type IN ('deposit')";
if (isset($request->get['account_id']) && $request->get['account_id'] != 'null') {
  $account_id = $request->get['account_id'];
  $where_query .= " AND bank_transaction_info.account_id = $account_id";
}

$from = from() ? from() : date('Y-m-d');
$to = to();
$where_query .= date_range_accounting_filter($from, $to);

// DB table to use
$table = "(SELECT bank_transaction_info.*, income_sources.source_slug, income_sources.profitable, bank_transaction_price.price_id, SUM(bank_transaction_price.amount) amount 
  FROM bank_transaction_info 
  JOIN income_sources ON bank_transaction_info.source_id = income_sources.source_id
  JOIN bank_transaction_price ON bank_transaction_info.info_id = bank_transaction_price.info_id
  WHERE $where_query GROUP BY bank_transaction_info.source_id) as bank_transaction_info";
 
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
     array( 'db' => 'source_slug', 'dt' => 'source_slug' ),
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

          if ($row['source_slug'] == 'sell') {
            return '<a href="report_overview.php?type=sell">'.$parent . $category['source_name'].'</a>';
          }
          return $parent . $category['source_name'];
      }
    ),
    array( 
      'db' => 'amount',   
      'dt' => 'amount',
      'formatter' => function($d, $row) use ($from, $to) {
        $total = get_total_source_income($row['source_id'], $from, $to);
        return currency_format($total);
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