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
if ($request->get['pid']) {
  $where_query .= " AND item_id = " . $request->get['pid'];
}
if (from()) {
  $from = from();
  $to = to();
  $where_query .= date_range_filter2($from, $to);
}

// DB table to use
$table = "(SELECT purchase_info.info_id, purchase_info.invoice_id, purchase_info.created_at, purchase_item.item_purchase_price, purchase_item.item_selling_price, purchase_item.item_quantity, purchase_item.total_sell, purchase_item.return_quantity, purchase_item.status FROM purchase_info 
      LEFT JOIN purchase_item ON (purchase_info.invoice_id = purchase_item.invoice_id)
      WHERE $where_query
      ORDER BY purchase_item.item_quantity DESC) as purchase_info";

// Table's primary key
$primaryKey = 'info_id';
$columns = array(
    array( 'db' => 'info_id', 'dt' => 'info_id' ),
    array( 
      'db' => 'created_at',  
      'dt' => 'created_at',
      'formatter' => function( $d, $row ) {
        return date('Y-m-d', strtotime($row['created_at']));
      }
    ),
    array( 
      'db' => 'invoice_id',  
      'dt' => 'invoice_id',
      'formatter' => function( $d, $row ) {
        return $row['invoice_id'];
      }
    ),
    array( 
      'db' => 'item_purchase_price',  
      'dt' => 'purchase',
      'formatter' => function( $d, $row ) {
        return currency_format($row['item_purchase_price']);
      }
    ),
    array( 
      'db' => 'item_selling_price',  
      'dt' => 'sell',
      'formatter' => function( $d, $row ) {
        return currency_format($row['item_selling_price']);
      }
    ),
    array( 
      'db' => 'return_quantity',
      'dt' => 'return_quantity',
      'formatter' => function( $d, $row ) {
        return currency_format($row['return_quantity']);
      }
    ),
    array( 
      'db' => 'item_quantity',
      'dt' => 'quantity',
      'formatter' => function( $d, $row ) {
        return currency_format($row['item_quantity']-$row['return_quantity']);
      }
    ),
    array( 
      'db' => 'total_sell',  
      'dt' => 'sold',
      'formatter' => function( $d, $row ) {
        return currency_format($row['total_sell']);
      }
    ),
    array(
      'db' => 'invoice_id',  
      'dt' => 'available',
      'formatter' => function( $d, $row ) {
        return currency_format($row['item_quantity'] - ($row['total_sell']+$row['return_quantity']));
      }
    ),
    array( 
      'db' => 'status',  
      'dt' => 'status',
      'formatter' => function( $d, $row ) {
        if ($row['status'] == 'active') {
          return '<span class="label label-success">'.$row['status'].'</span>';
        } elseif ($row['status'] == 'stock') {
          return '<span class="label label-info">'.$row['status'].'</span>';
        } else {
          return '<span class="label label-danger">'.$row['status'].'</span>';
        }
      }
    ),
);
 
echo json_encode(
    SSP::simple( $request->get, $sql_details, $table, $primaryKey, $columns )
);

/**
 *===================
 * END DATATABLE
 *===================
 */