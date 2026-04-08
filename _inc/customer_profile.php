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
if (user_group_id() != 1 && !has_permission('access', 'read_customer_profile')) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_read_permission')));
  exit();
}

$customer_id = (int)$request->get['customer_id'];

/**
 *===================
 * START DATATABLE
 *===================
 */

$Hooks->do_action('Before_Showing_Customer_Profile');

$where_query = "selling_info.store_id = " . store_id();
if (isset($request->get['type']) && ($request->get['type'] != 'undefined') && $request->get['type'] != '') {
    switch ($request->get['type']) {
        case 'due':
        case 'all_due':
            $where_query .= " AND selling_info.payment_status = 'due'";
            break;
        case 'paid':
            $where_query .= " AND selling_info.payment_status = 'paid'";
            break;
        case 'inactive':
            $where_query .= " AND selling_info.status = 0";
            break;
        default:
            $where_query .= " AND selling_info.status = 1";
            break;
    }
};
if ($request->get['type'] != 'all_due' && $request->get['type'] != 'all_invoice' && $request->get['type'] != 'all_due') {
    $from = from();
    $to = to();
    $where_query .= date_range_filter($from, $to);
}
// DB table to use
$table = "(SELECT selling_info.*, selling_price.previous_due, selling_price.payable_amount, selling_price.prev_due_paid, selling_price.paid_amount, selling_price.due, selling_price.balance 
  FROM selling_info 
  JOIN selling_price ON selling_info.invoice_id = selling_price.invoice_id
  WHERE $where_query) as selling_info";
 
// Table's primary key
$primaryKey = 'info_id';

$columns = array(
    array(
        'db' => 'invoice_id',
        'dt' => 'DT_RowId',
        'formatter' => function( $d, $row ) {
            return 'row_'.$d;
        }
    ),
    array( 'db' => 'edit_counter', 'dt' => 'edit_counter' ),
    array( 'db' => 'payment_status', 'dt' => 'payment_status' ),
    array( 'db' => 'is_installment', 'dt' => 'is_installment' ),
    array( 
      'db' => 'created_at',   
      'dt' => 'created_at' ,
      'formatter' => function($d, $row) {
          return $row['created_at'];
      }
    ),
    array(
        'db'        => 'invoice_id',
        'dt'        => 'invoice_id',
        'formatter' => function( $d, $row) {
            $o = $row['invoice_id'];   
            if ($row['edit_counter'] > 0) {
                $o .= ' <span class="fa fa-edit text-red" title="Edited: '.$row['edit_counter'].' time(s)"></span>';
            }         
            return $o;
        }
    ),
    array( 
      'db' => 'invoice_note',   
      'dt' => 'invoice_note' ,
      'formatter' => function($d, $row) {
          return limit_char($row['invoice_note'], 500);
      }
    ),
    array( 
      'db' => 'invoice_id',   
      'dt' => 'items' ,
      'formatter' => function($d, $row) {
          return get_invoice_items_html($row['invoice_id']);
      }
    ),
    array( 
      'db' => 'previous_due',   
      'dt' => 'previous_due',
      'formatter' => function($d, $row) {
        $total = $row['previous_due'];
        return currency_format($total, 2);
      }
    ),
    array( 
      'db' => 'payable_amount',   
      'dt' => 'invoice_amount',
      'formatter' => function($d, $row) {
        $total = $row['payable_amount'];
        return currency_format($total, 2);
      }
    ),
    array( 
      'db' => 'payable_amount',   
      'dt' => 'payable_amount',
      'formatter' => function($d, $row) {
        $total = $row['payable_amount'] + $row['previous_due'];
        return currency_format($total, 2);
      }
    ),
    array( 
      'db' => 'prev_due_paid',   
      'dt' => 'prev_due_paid',
      'formatter' => function($d, $row) {
        $total = $row['prev_due_paid'];
        return currency_format($total, 2);
      }
    ),
    array( 
      'db' => 'paid_amount',   
      'dt' => 'paid_amount',
      'formatter' => function($d, $row) {
        $total = $row['paid_amount']+$row['prev_due_paid'];
        return currency_format($total, 2);
      }
    ),
    array( 
      'db' => 'due',   
      'dt' => 'due' ,
      'formatter' => function($d, $row) {
          return currency_format(($row['due']+$row['previous_due'])-$row['prev_due_paid']);
      }
    ),
    array(
        'db' => 'invoice_id',
        'dt' => 'btn_view',
        'formatter' => function($d, $row) {
            if ($row['is_installment']) {
                return '<button id="view-installment-btn" class="btn btn-sm btn-block btn-info" title="'.trans('button_view_details').'" data-loading-text="..."><i class="fa fa-eye"></i></button>';
            }
            return '<a class="btn btn-sm btn-block btn-info" href="view_invoice.php?invoice_id='.$row['invoice_id'].'" title="'.trans('button_view_receipt').'" data-loading-text="..."><i class="fa fa-eye"></i></a>';
        }
    ),
    array(
        'db' => 'invoice_id',
        'dt' => 'btn_pay',
        'formatter' => function($d, $row) {
            if ($row['is_installment']) {
                return '<span class="label label-warning">Installment</span>';
            }
            if ($row['payment_status'] != 'paid') {
                return '<button id="pay_now" class="btn btn-sm btn-block btn-success" title="'.trans('button_view_receipt').'" data-loading-text="..."><i class="fa fa-money"></i></button>';
            }
            return '-';
        }
    ),
);
 
$where_query = "1=1";
if ($customer_id) {
  $where_query .= "  AND customer_id = " . $customer_id;
}

// Output for datatable
echo json_encode(
  SSP::complex( $request->get, $sql_details, $table, $primaryKey, $columns, null, $where_query)
);

$Hooks->do_action('After_Showing_Customer_Profile');

/**
 *===================
 * END DATATABLE
 *===================
 */