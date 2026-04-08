<?php 
ob_start();
session_start();
include ("../_init.php");

// Check, if your logged in or not
// If user is not logged in then return an alert message
if (!is_loggedin()) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_login')));
  exit();
}

// Check, if user has reading permission or not
// If user have not reading permission return an alert message
if (user_group_id() != 1 && !has_permission('access', 'read_collection_report')) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_read_permission')));
  exit();
}

$store_id = store_id();
$user_id = user_id();
$report_model = registry()->get('loader')->model('report');

$where_query = "selling_info.store_id = $store_id AND selling_info.status = 1";
if (user_group_id() != 1 && !has_permission('access', 'read_full_collection_report')) {
  $where_query .= " AND selling_info.created_by = $user_id";
}

if (isset($request->get['user_id']) && ($request->get['user_id'] != 'undefined') && $request->get['user_id'] != '' && $request->get['user_id'] != 'null') {
  $id = $request->get['user_id'];
  $where_query .= " AND selling_info.created_by = {$id}";
}

$from = from();
  $to = to();
if ($from) {
  $where_query .= date_range_filter($from, $to);
}


/**
 *===================
 * END DATATABLE
 *===================
 */

$Hooks->do_action('Before_Showing_Collection_Report');

// DB table to use
$table = "(SELECT selling_info.info_id, selling_info.created_by FROM selling_info 
  LEFT JOIN selling_price ON (selling_info.invoice_id = selling_price.invoice_id)
  WHERE $where_query
  GROUP BY selling_info.created_by
  ORDER BY selling_info.invoice_id DESC) as selling_info";

// Table's primary key
$primaryKey = 'info_id';

// indexes
$columns = array(
    array( 
      'db' => 'created_by',  
      'dt' => 'sl',
      'formatter' => function( $d, $row ) {
        return '';
      }
    ),
    array( 
      'db' => 'created_by',  
      'dt' => 'username',
      'formatter' => function( $d, $row ) {
        return '<a href="user.php?user_id='.$row['created_by'].'&username='.get_the_user($row['created_by'], 'username').'">'.get_the_user($row['created_by'], 'username').'</a>';
      }
    ),
    array( 'db' => 'created_by', 'dt' => 'total_invoice' ),
    array( 
      'db' => 'created_by',  
      'dt' => 'invoice_count',
      'formatter' => function( $d, $row ) use($report_model, $from, $to) {
        $invoice_count = $report_model->userTotalInvoiceCount($row['created_by'], $from, $to);
        $username = get_the_user($row['created_by'], 'username');
        if ($invoice_count > 0) {
          return $invoice_count;
        } else {
          return '0';
        }
      }
    ),
    array( 
      'db' => 'created_by',  
      'dt' => 'invoice_amount',
      'formatter' => function( $d, $row ) use($report_model, $from, $to) {
        return currency_format($report_model->getTotalInvoiceAmountBy('userwise', $row['created_by'], $from, $to));
      }
    ),
    array( 
      'db' => 'created_by',  
      'dt' => 'tax_amount',
      'formatter' => function( $d, $row ) use($report_model, $from, $to) {
        $tax = $report_model->getTotalTaxAmountBy('userwise', $row['created_by'], $from, $to);
         return currency_format($tax);
      }
    ),
    array( 
      'db' => 'created_by',  
      'dt' => 'discount_amount',
      'formatter' => function( $d, $row ) use($report_model, $from, $to) {
         $discount = $report_model->getTotalDiscountAmountBy('userwise', $row['created_by'], $from, $to);
         return currency_format($discount);
      }
    ),
    array( 
      'db' => 'created_by',  
      'dt' => 'net_amount',
      'formatter' => function( $d, $row ) use($report_model, $from, $to) {
        $invoice_amount = $report_model->getTotalInvoiceAmountBy('userwise', $row['created_by'], $from, $to);
        $tax = $report_model->getTotalTaxAmountBy('userwise', $row['created_by'], $from, $to);
        $shipping_charge = $report_model->getTotalShippingChargeBy('userwise', $row['created_by'], $from, $to);
        $others_charge = $report_model->getTotalOthersChargeBy('userwise', $row['created_by'], $from, $to);
        $discount = $report_model->getTotalDiscountAmountBy('userwise', $row['created_by'], $from, $to);
        $total = $invoice_amount + $tax + $shipping_charge + $others_charge;
        $net_amount = $total > 0 ? $total - $discount : $discount;
        return currency_format($net_amount);
      }
    ),
    array( 
      'db' => 'created_by',  
      'dt' => 'prev_due_collection',
      'formatter' => function( $d, $row ) use($report_model, $from, $to) {
        $total = $report_model->getTotalPrevDueCollectionBy($row['created_by'], $from, $to);
        if ((int)$total <= 0) {
          return '0.00';
        }
        return currency_format($total, 2);
      }
    ),
    array( 
      'db' => 'created_by',  
      'dt' => 'due_collection',
      'formatter' => function( $d, $row ) use($report_model, $from, $to) {
        $total = $report_model->getTotalPrevDueCollectionBy($row['created_by'], $from, $to);
        if ((int)$total <= 0) {
          return '0.00';
        }
        return currency_format($total, 2);
      }
    ),
    array( 
      'db' => 'created_by',  
      'dt' => 'due_given',
      'formatter' => function( $d, $row ) use($report_model, $from, $to) {
        $username = get_the_user($row['created_by'], 'username');
        $total = $report_model->getTotalDueAmountBy('userwise', $row['created_by'], $from, $to);
        if ($total > 0) {
          return currency_format($total, 2);
        } 
        return '0.00';
      }
    ),
    array( 
      'db' => 'created_by',  
      'dt' => 'received_amount',
      'formatter' => function( $d, $row ) use($report_model, $from, $to) {
        $received_amount = $report_model->getTotalCashReceivedBy("userwise", $row['created_by'], $from, $to);
        return currency_format($received_amount);
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