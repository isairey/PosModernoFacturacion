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
if (user_group_id() != 1 && !has_permission('access', 'read_purchase_report')) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_read_permission')));
  exit();
}

/**
 *===================
 * START DATATABLE
 *===================
 */

$where_query = "purchase_info.inv_type != 'expense' AND purchase_item.store_id = " . store_id();
$from = from();
$to = to();
$where_query .= date_range_filter2($from, $to);

// DB table to use
$table = "(SELECT purchase_info.*, purchase_item.id, purchase_item.item_id, purchase_item.item_name, purchase_item.item_quantity, SUM(purchase_item.item_total) as purchase_price, SUM(purchase_item.item_quantity) as total_stock, SUM(purchase_price.paid_amount) as paid_amount FROM purchase_item 
      LEFT JOIN purchase_info ON (purchase_item.invoice_id = purchase_info.invoice_id)
      LEFT JOIN purchase_price ON (purchase_item.invoice_id = purchase_price.invoice_id)
      WHERE $where_query
      GROUP BY purchase_item.item_id
      ORDER BY total_stock DESC) as products";
// Table's primary key
$primaryKey = 'id';
$columns = array(
    array( 'db' => 'item_id', 'dt' => 'p_id' ),
    array( 
      'db' => 'created_at',  
      'dt' => 'created_at',
      'formatter' => function( $d, $row ) {
        return date('Y-m-d', strtotime($row['created_at']));
      }
    ),
    array( 
      'db' => 'item_name',  
      'dt' => 'item_name',
      'formatter' => function( $d, $row ) {
        return '<a href="product.php?p_id=' . $row['item_id'] . '&p_name=' . $row['item_name'] . '">' . $row['item_name'] . '</a>';
      }
    ),
    array( 
      'db' => 'total_stock',  
      'dt' => 'total_item',
      'formatter' => function( $d, $row ) {
        return currency_format($row['total_stock']);
      }
    ),
    array( 
      'db' => 'purchase_price',  
      'dt' => 'purchase_price',
      'formatter' => function( $d, $row ) {
        $total = $row['purchase_price'];
        return currency_format($total);
      }
    ),
    array( 
      'db' => 'paid_amount',  
      'dt' => 'paid_amount',
      'formatter' => function( $d, $row ) {
        $total = $row['paid_amount'];
        return currency_format($total);
      }
    )
);
 
echo json_encode(
    SSP::simple( $request->get, $sql_details, $table, $primaryKey, $columns )
);

/**
 *===================
 * END DATATABLE
 *===================
 */