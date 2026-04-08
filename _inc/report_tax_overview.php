<?php 
ob_start();
session_start();
include ("../_init.php");

// Check, if user logged in or not
// If user is not logged in then return error
if (!is_loggedin()) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_login')));
  exit();
}

// Check, if user has reading permission or not
// If user have not reading permission return error
if (user_group_id() != 1 && !has_permission('access', 'read_tax_overview_report')) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_read_permission')));
  exit();
}

$store_id = store_id();

// LOAD BOX MODEL
$invoice_model = registry()->get('loader')->model('invoice');

/**
 *===================
 * START DATATABLE
 *===================
 */

$where_query = "selling_item.store_id =".$store_id;

$from = from();
$to = to();
$where_query .= date_range_item_filter($from, $to);
 
// DB table to use
$table = "(SELECT selling_item.id, selling_item.taxrate_id, SUM(selling_item.item_total) as subtotal, SUM(selling_item.item_tax) as tax_amount, selling_item.created_at, tr.taxrate_name, tr.code_name, tr.taxrate, COUNT(*) as count FROM selling_item
  LEFT JOIN taxrates tr ON (selling_item.taxrate_id = tr.taxrate_id) 
  WHERE $where_query GROUP by selling_item.taxrate_id
  ) as selling_item";
 
// Table's primary key
$primaryKey = 'id';

$columns = array(
  array(
      'db' => 'id',
      'dt' => 'DT_RowId',
      'formatter' => function( $d, $row ) {
          return 'row_'.$d;
      }
  ),
  array( 'db' => 'id', 'dt' => 'id' ),
  array( 
    'db' => 'taxrate_name',   
    'dt' => 'tax_percent' ,
    'formatter' => function($d, $row) {
        return $row['taxrate_name'];
    }
  ),
  array( 
    'db' => 'count',   
    'dt' => 'count' ,
    'formatter' => function($d, $row) {
        return $row['count'];
    }
  ),
  array( 
    'db' => 'tax_amount',   
    'dt' => 'tax_amount' ,
    'formatter' => function($d, $row) {
        return currency_format($row['tax_amount']);
    }
  ),
  array( 
    'db' => 'subtotal',   
    'dt' => 'subtotal' ,
    'formatter' => function($d, $row) {
        return currency_format($row['subtotal']-$row['tax_amount']);
    }
  ),
  array( 
    'db' => 'id',   
    'dt' => 'total' ,
    'formatter' => function($d, $row) {
        return currency_format($row['subtotal']+$row['tax_amount']);
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