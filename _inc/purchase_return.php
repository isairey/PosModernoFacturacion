<?php 
ob_start();
session_start();
include '../_init.php';

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

// LOAD PURCHASE MODEL
$purchase_model = registry()->get('loader')->model('purchase');
$return_model = registry()->get('loader')->model('purchasereturn');

// Return product
if ($request->server['REQUEST_METHOD'] == 'POST' && $request->get['action_type'] == 'RETURN')
{
  try {

    // Check product return permission
    if (user_group_id() != 1 && !has_permission('access', 'purchase_return')) {
      throw new Exception(trans('error_return_permission'));
    }

    // Validate invoice id
    if(empty($request->post['invoice-id'])) {
      throw new Exception(trans('error_invoice_id'));
    }
    $invoice_id = $request->post['invoice-id']; 
    $sup_id = $request->post['sup-id']; 

    $statement = db()->prepare("SELECT `purchase_info`.*, `purchase_price`.`subtotal`, `purchase_price`.`order_tax`, `purchase_price`.`payable_amount`, `purchase_price`.`paid_amount`, `purchase_price`.`due`, `purchase_price`.`balance`, `purchase_price`.`cgst`, `purchase_price`.`sgst`, `purchase_price`.`igst` FROM `purchase_info` LEFT JOIN `purchase_price` ON (`purchase_info`.`invoice_id` = `purchase_price`.`invoice_id`) WHERE `purchase_info`.`invoice_id` = ?");
    $statement->execute(array($invoice_id));
    $invoice = $statement->fetch(PDO::FETCH_ASSOC);
    if (!$invoice) {
      throw new Exception(trans('error_invoice_not_found'));
    }

    $reference_no = generate_purchase_return_reference_no();
    $note = $request->post['note'];
    $items = isset($request->post['items']) && !empty($request->post['items']) ? $request->post['items'] : array();
    if (empty($items)) {
      throw new Exception(trans('error_invalid_item'));
    }
    $checked_item = 0;
    
    // Validate quantity
    foreach ($items as $item) 
    {
      if (!isset($item['check']) OR !$item['check']) {
        continue;
      } else {
        $checked_item++;
      }
      $item_info = $purchase_model->getTheInvoiceItem($invoice_id, $item['item_id']);
      $item_quantity = $item['item_quantity'];
      // if ($quantity > $item_info['item_quantity']) {
      if($item_quantity > $item_info['item_quantity'] || !validateFloat($item['item_quantity']) || $item['item_quantity'] <= 0) {
        throw new Exception(trans('error_quantity_exceed'));
      }
      if(!validateFloat($item['item_quantity'])) {
        throw new Exception(trans('error_quantity_exceed'));
      }
      if ($item['item_quantity'] <= 0) {
        throw new Exception(trans('error_quantity_exceed'));
      }
    }

    if ($checked_item <= 0) {
      throw new Exception(trans('error_select_at_least_one_item'));
    }

    $return_amount = 0;
    $total_item = 0;
    $total_quantity = 0;
    $total_amount = 0;
    $tpayable = 0;
    $tsubtotal = 0;
    $titem_tax = 0;
    foreach ($items as $item) 
    {
      if (!isset($item['check']) OR !$item['check']) continue;

      $total_item++;

      $item_id = $item['item_id'];
      $item_quantity = $item['item_quantity'];
      $total_quantity += $item_quantity;

      // purchase INVOICE ADJUSTMENT

      $statement = db()->prepare("SELECT * FROM `purchase_item` WHERE `invoice_id` = ? AND `item_id` = ? AND `status` IN ('stock', 'active')");
      $statement->execute(array($invoice_id, $item_id));
      $invoice_item = $statement->fetch(PDO::FETCH_ASSOC);
      if (!$invoice_item) {
        throw new Exception(trans('error_return_item_not_found'));
      }

      $quantity_available = $invoice_item['item_quantity'] - $invoice_item['total_sell'];
      if ($item_quantity > $quantity_available) {
        throw new Exception(trans('error_return_quantity_exceed'));
      }

      $statement = db()->prepare("SELECT * FROM `products`
        LEFT JOIN `product_to_store` p2s ON (`products`.`p_id` = `p2s`.`product_id`)
        WHERE `p2s`.`store_id` = ? AND `p_id` = ?");
      $statement->execute(array($store_id, $item_id));
      $product = $statement->fetch(PDO::FETCH_ASSOC);
      if (!$product) {
        throw new Exception(trans('error_return_item_not_found'));
      }

      $Hooks->do_action('Before_Purchase_Return', $request);

      $item_name = $invoice_item['item_name'];
      $item_price = $invoice_item['item_purchase_price'];
      $per_item_tax = $invoice_item['item_tax'] / $invoice_item['item_quantity'];
      $item_tax = $per_item_tax * $item_quantity;
      $tax_method = $product['tax_method'];
      if ($tax_method == 'exclusive') {
        $item_total = ($item_price * $item_quantity) + $item_tax;

        $subtotal = $item_price * $item_quantity;
        $payable_amount = $item_total;
        $tpayable += $payable_amount;
        $tsubtotal += $subtotal;

      }
      else {

        $item_total = $item_price * $item_quantity;

        $subtotal = $item_total;
        $payable_amount = $item_total;
        $tpayable += $payable_amount;
        $tsubtotal += $subtotal;
      }

      $cgst = 0;
      $sgst = 0;
      $igst = 0;
      if ($invoice_item['cgst'] > 0) {
        $cgst = $item_tax / 2;
      }
      if ($invoice_item['sgst'] > 0) {
        $sgst = $item_tax / 2;
      }
      if ($invoice_item['igst'] > 0) {
        $igst = $item_tax;
      }
      $titem_tax += $item_tax;

      $statement = db()->prepare("UPDATE `purchase_item` SET `return_quantity` = `return_quantity`+$item_quantity WHERE `id` = ?");
      $statement->execute(array($invoice_item['id']));

      $statement = db()->prepare("UPDATE `product_to_store` SET `quantity_in_stock` = `quantity_in_stock`-$item_quantity WHERE `store_id` = ? AND `product_id` = ?");
      $statement->execute(array($store_id, $item_id));

      $statement = db()->prepare("INSERT INTO `purchase_return_items` SET `store_id` = ?, `invoice_id` = ?, `item_id` = ?, `item_name` = ?, `item_quantity` = ?, `item_price` = ?, `item_tax` = ?, `cgst` = ?, `sgst` = ?, `igst` = ?, `item_total` = ?");
      $statement->execute(array($store_id, $invoice_id, $item_id, $item_name, $item_quantity, $item_price, $item_tax, $cgst, $sgst, $igst, $item_total));
    };

    if ($total_quantity <= 0) {
      throw new Exception(trans('error_empty_list'));
    }

    $return_amount = $tpayable;
    $paid_amount = $invoice['paid_amount'];
    $due = $invoice['due'];
    $balance = $invoice['balance'];
    $payment_status = $invoice['payment_status'];
    if ($due <= 0) {
      $balance = $balance+$return_amount;
    }
    if ($return_amount >= $due) {
      $due = 0;
      $payment_status = 'paid';
    } elseif ($due > $return_amount) {
      $due = $due-$return_amount;
    }

    $statement = db()->prepare("UPDATE `purchase_info` SET `payment_status` = ? WHERE `store_id` = ? AND `invoice_id` = ?");
    $statement->execute(array($payment_status, $store_id, $invoice_id));

    $tcgst = 0;
    $tsgst = 0;
    $tigst = 0;
    if ($invoice['cgst'] > 0) {
      $tcgst = $titem_tax / 2;
    }
    if ($invoice['sgst'] > 0) {
      $tsgst = $titem_tax / 2;
    }
    if ($invoice['igst'] > 0) {
      $tigst = $titem_tax;
    }

    $statement = db()->prepare("UPDATE `purchase_price` SET `due` = ? WHERE `store_id` = ? AND `invoice_id` = ?");
    $statement->execute(array($due, $store_id, $invoice_id));  

    $statement = db()->prepare("INSERT INTO `purchase_returns` SET `store_id` = ?, `reference_no` = ?, `invoice_id` = ?, `sup_id` = ?, `note` = ?, `total_item` = ?, `total_quantity` = ?, `subtotal` = ?, `total_amount` = ?, `item_tax` = ?, `cgst` = ?, `sgst` = ?, `igst` = ?, `created_by` = ?");
    $statement->execute(array($store_id, $reference_no, $invoice_id, $sup_id, $note, $total_item, $total_quantity, $tsubtotal, $tpayable, $titem_tax, $tcgst, $tsgst, $tigst, $user_id));


    if ($return_amount > 0) {
      $is_hide = 1;
      if ($paid_amount > 0) {
        $is_hide = 0;
      }
      $statement = db()->prepare("INSERT INTO `purchase_payments` SET `type` = ?, `is_hide` = ?, `store_id` = ?, `invoice_id` = ?, `reference_no` = ?, `amount` = ?, `created_by` = ?");
      $statement->execute(array('return', $is_hide, $store_id, $invoice_id, $reference_no, -$return_amount, $user_id));

      $statement = db()->prepare("UPDATE `purchase_price` SET `return_amount` = `return_amount`+$return_amount WHERE `store_id` = ? AND `invoice_id` = ?");
      $statement->execute(array($store_id, $invoice_id));
    }

    if ($balance > 0) {
      $statement = db()->prepare("INSERT INTO `purchase_payments` SET `type` = ?, `store_id` = ?, `invoice_id` = ?, `note` = ?, `balance` = ?, `created_by` = ?");
      $statement->execute(array('change', $store_id, $invoice_id, 'return_change', $balance, $user_id));

      $statement = db()->prepare("UPDATE `purchase_price` SET `balance` = ? WHERE `store_id` = ? AND `invoice_id` = ?");
      $statement->execute(array($balance, $store_id, $invoice_id));
    }

    if ($due > 0) {
      $due_paid = $invoice['due'] - $due;
      $statement = db()->prepare("UPDATE `supplier_to_store` SET `balance` = `balance` - {$due_paid}  WHERE `store_id` = ? AND `sup_id` = ?");
      $statement->execute(array($store_id, $sup_id));
    }


    // Deposit
    if (($account_id = store('deposit_account_id')) && $return_amount > 0) 
    {
      $ref_no = $invoice_id;
      $statement = db()->prepare("SELECT `source_id` FROM `income_sources` WHERE `for_purchase_return` = ?");
      $statement->execute(array(1));
      $source = $statement->fetch(PDO::FETCH_ASSOC);
      $source_id = $source['source_id'];
      $title = 'Deposit for purchase return';
      $details = 'Supplier name: ' . get_the_supplier($sup_id, 'sup_id');
      $image = 'NULL';
      $deposit_amount = $return_amount;
      $transaction_type = 'deposit';

      $statement = db()->prepare("INSERT INTO `bank_transaction_info` (store_id, account_id, source_id, ref_no, transaction_type, title, details, image, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $statement->execute(array($store_id, $account_id, $source_id, $ref_no, $transaction_type, $title, $details, $image, $user_id, date_time()));
      $info_id = db()->lastInsertId();
    
      $statement = db()->prepare("INSERT INTO `bank_transaction_price` (store_id, info_id, ref_no, amount) VALUES (?, ?, ?, ?)");
      $statement->execute(array($store_id, $info_id, $ref_no, $deposit_amount));

      $statement = db()->prepare("UPDATE `bank_account_to_store` SET `deposit` = `deposit` + $deposit_amount WHERE `store_id` = ? AND `account_id` = ?");
      $statement->execute(array($store_id, $account_id));

      $statement = db()->prepare("UPDATE `bank_accounts` SET `total_deposit` = `total_deposit` + $deposit_amount WHERE `id` = ?");
      $statement->execute(array($account_id));
    }

    $Hooks->do_action('After_Purchase_Return', $item_id);

    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_return_success'), 'id' => $item_id));
    exit();
    
  } catch (Exception $e) {

    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();
  }
}

// View Invoice
if (isset($request->get['invoice_id']) && $request->get['action_type'] == 'VIEW') 
{
    $invoice_id = $request->get['invoice_id'];
    $invoice = $return_model->getInvoiceInfo($invoice_id);
    $invoice_items = $return_model->getInvoiceItems($invoice_id);
    include ROOT.'/_inc/template/purchase_return_view.php';
    exit();
}


/**
 *===================
 * START DATATABLE
 *===================
 */

$Hooks->do_action('Before_Showing_Purchase_Return_List');

$where_query = "purchase_returns.store_id = " . $store_id;
if (from()) {
    $from = from();
    $to = to();
    $where_query .= date_range_purchase_return_filter($from, $to);
}

// DB table to use
$table = "(SELECT purchase_returns.* FROM `purchase_returns` 
  WHERE $where_query) as purchase_returns";

// Table's primary key
$primaryKey = 'id';

$columns = array(
    array(
      'db' => 'reference_no',
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
        'db' => 'reference_no',
        'dt' => 'reference_no',
        'formatter' => function( $d, $row) {
          return $row['reference_no'];           
        }
    ),
    array(
        'db' => 'invoice_id',
        'dt' => 'invoice_id',
        'formatter' => function( $d, $row) {
          return $row['invoice_id'];           
        }
    ),
    array(
        'db' => 'sup_id',
        'dt' => 'supplier',
        'formatter' => function( $d, $row) {
            $supplier = get_the_supplier($row['sup_id']);
            return '<a href="supplier_profile.php?sup_id=' . $supplier['sup_id'] . '">' . $supplier['sup_name'] . '</a>';
        }
    ),
    array(
        'db' => 'created_by',
        'dt' => 'return_by',
        'formatter' => function( $d, $row) {
            $the_user = get_the_user($row['created_by']);
            if (isset($the_user['id'])) {
                return '<a href="user.php?user_id=' . $the_user['id'] . '&username='.$the_user['username'].'">' . $the_user['username'] . '</a>';
            }
            return;
        }
    ),
    array(
        'db' => 'total_amount',
        'dt' => 'amount',
        'formatter' => function($d, $row) {
            return currency_format($row['total_amount']);
        }
    ),
    array(
        'db' => 'reference_no',
        'dt' => 'btn_view',
        'formatter' => function($d, $row) {
          return '<button id="view-btn" class="btn btn-sm btn-block btn-success" title="'.trans('button_view_receipt').'" data-loading-text="..."><i class="fa fa-eye"></i></button>';
        }
    ),
    array(
        'db' => 'reference_no',
        'dt' => 'btn_edit',
        'formatter' => function($d, $row) {
          return '-';
          return '<button id="edit-btn" class="btn btn-sm btn-block btn-info" title="'.trans('button_edit').'" data-loading-text="..."><span class="fa fa-pencil"></span></button>';     
        }
    ),
    array(
        'db' => 'reference_no',
        'dt' => 'btn_delete',
        'formatter' => function($d, $row) {
          return '-';
          return '<button id="delete-btn" class="btn btn-sm btn-block btn-danger" title="'.trans('button_delete').'" data-loading-text="..."><i class="fa fa-trash"></i></button>';

        }
    ),
);

echo json_encode(
    SSP::simple($request->get, $sql_details, $table, $primaryKey, $columns)
);

$Hooks->do_action('Before_Showing_Purchase_Return_List');

/**
 *===================
 * END DATATABLE
 *===================
 */