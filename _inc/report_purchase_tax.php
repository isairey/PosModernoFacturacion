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

// Check, if user has reading permission or not
// If user have not reading permission return an alert message
if (user_group_id() != 1 && !has_permission('access', 'read_purchase_tax_report')) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_read_permission')));
  exit();
}

$store_id = store_id();
$user_id = user_id();

// LOAD INVOICE MODEL
$invoice_model = registry()->get('loader')->model('invoice');


/**
 *===================
 * START DATATABLE
 *===================
 */

$Hooks->do_action('Before_Showing_purchase_Tax_Report');

$where_query = "(purchase_price.item_tax > 0 OR purchase_price.order_tax > 0) AND purchase_info.is_visible = 1 AND purchase_info.store_id = $store_id";
$from = from();
$to = to();
$where_query .= date_range_filter2($from, $to);

// DB table to use
$table = "(SELECT purchase_info.*, purchase_price.item_tax, purchase_price.order_tax FROM `purchase_info` LEFT JOIN `purchase_price` ON (purchase_info.invoice_id = purchase_price.invoice_id) WHERE $where_query) as purchase_info";

// Table's primary key
$primaryKey = 'info_id';

$columns = array(
    array(
      'db' => 'info_id',
      'dt' => 'DT_RowId',
      'formatter' => function( $d, $row ) {
          return 'row_'.$d;
      }
    ),
    array( 
      'db' => 'created_at',   
      'dt' => 'created_at' ,
      'formatter' => function($d, $row) {
        return $row['created_at'];
      }
    ),
    array(
        'db' => 'invoice_id',
        'dt' => 'invoice_id',
        'formatter' => function( $d, $row) {
            $o = $row['invoice_id'];         
            return $o;
        }
    ),
    array('db' => 'order_tax','dt' => 'order_tax'),
    array(
        'db' => 'item_tax',
        'dt' => 'tax_amount',
        'formatter' => function($d, $row) {
            return currency_format($row['item_tax']+$row['order_tax']);
        }
    ),
);

echo json_encode(
    SSP::simple($request->get, $sql_details, $table, $primaryKey, $columns)
);

$Hooks->do_action('After_Showing_purchase_Tax_Report');

/**
 *===================
 * END DATATABLE
 *===================
 */