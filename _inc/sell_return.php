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

// LOAD INVOICE MODEL
$invoice_model = registry()->get('loader')->model('invoice');
$return_model = registry()->get('loader')->model('sellreturn');

// return product
if ($request->server['REQUEST_METHOD'] == 'POST' && $request->get['action_type'] == 'RETURN')
{
  try {

    // Check product return permission
    if (user_group_id() != 1 && !has_permission('access', 'create_sell_return')) {
      throw new Exception(trans('error_return_permission'));
    }

    // Validate invoice id
    if(empty($request->post['invoice-id'])) {
      throw new Exception(trans('error_invoice_id'));
    }
    $invoice_id = $request->post['invoice-id']; 
    $customer_id = $request->post['customer-id']; 

    // Check, if invoice exist or not
    $statement = db()->prepare("SELECT `selling_info`.*, `selling_price`.`subtotal`, `selling_price`.`order_tax`, `selling_price`.`payable_amount`, `selling_price`.`paid_amount`, `selling_price`.`due`, `selling_price`.`balance`, `selling_price`.`cgst`, `selling_price`.`sgst`, `selling_price`.`igst` FROM `selling_info` LEFT JOIN `selling_price` ON (`selling_info`.`invoice_id` = `selling_price`.`invoice_id`) WHERE `selling_info`.`invoice_id` = ?");
    $statement->execute(array($invoice_id));
    $invoice = $statement->fetch(PDO::FETCH_ASSOC);
    if (!$invoice) {
      throw new Exception(trans('error_invoice_not_found'));
    }

    $reference_no = generate_return_reference_no();
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
      $item_id = $item['item_id'];
      $item_info = $invoice_model->getInvoiceItemInfo($invoice_id, $item_id);
      $item_quantity = $item['item_quantity'];
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
    $total_purchase_price = 0;
    foreach ($items as $item) 
    {
      if (!isset($item['check']) OR !$item['check']) {
        continue;
      }

      $total_item++;

      $item_id = $item['item_id'];
      $item_quantity = $item['item_quantity'];
      $item_quantity_add = $item_quantity;
      $total_quantity += $item_quantity;

      $statement = db()->prepare("SELECT * FROM `selling_item` WHERE `invoice_id` = ? AND `item_id` = ?");
      $statement->execute(array($invoice_id, $item_id));
      $invoice_item = $statement->fetch(PDO::FETCH_ASSOC);
      if (!$invoice_item) {
        throw new Exception(trans('error_return_item_not_found'));
      }

      $statement = db()->prepare("SELECT * FROM `products`
        LEFT JOIN `product_to_store` p2s ON (`products`.`p_id` = `p2s`.`product_id`)
        WHERE `p2s`.`store_id` = ? AND `p_id` = ?");
      $statement->execute(array($store_id, $item_id));
      $product = $statement->fetch(PDO::FETCH_ASSOC);
      if (!$product) {
        throw new Exception(trans('error_return_item_not_found'));
      }

      $Hooks->do_action('Before_Sell_Return', $request);

      $purchase_invoice_id = $invoice_item['purchase_invoice_id'];
      $item_quantity_exist = $item_quantity;
      $return_quantity = $item_quantity;
      $item_purchase_price = 0;
      $purchase_item = null;
      $inc = 0;
      while ($item_quantity_exist > 0) 
      {
        if ($product['p_type'] == 'service') {
          $total_purchase_price += $product['purchase_price']; 
          break;
        }

        $statement = db()->prepare("SELECT * FROM `purchase_item` WHERE `store_id` = ? AND `invoice_id` = ? AND `item_id` = ?");
        $statement->execute(array($store_id, $purchase_invoice_id, $item_id));
        $purchase_item = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$purchase_item) 
        {
          throw new Exception('The product: '.$product['p_name'].' was not found in purchase history');
        }

        $statement = db()->prepare("UPDATE `purchase_item` SET `status` = ? WHERE `store_id` = ? AND `invoice_id` = ? AND `item_id` = ? AND `status` = ?");
        $statement->execute(array('stock', $store_id, $invoice_id, $item_id, 'active'));
        $sold = $purchase_item['total_sell'];
        if ($sold < $item_quantity_exist) {
          $return_quantity = $sold;
          $item_quantity_exist = $item_quantity_exist - $sold;
        } else {
          $return_quantity = $item_quantity_exist;
          $item_quantity_exist = 0;
        }
        $statement = db()->prepare("UPDATE `purchase_item` SET `total_sell` = `total_sell`-$return_quantity, `status` = ? WHERE `store_id` = ? AND `invoice_id` = ? AND `item_id` = ?");
        $statement->execute(array('active', $store_id, $purchase_invoice_id, $item_id));
        $item_purchase_price += $purchase_item['item_purchase_price'] * $return_quantity;
        $total_purchase_price += $item_purchase_price;

        if ($inc > 10000) 
        {
          throw new Exception(trans('error_unexpected'));
          $item_quantity_exist = 0;
          break;
        }
        $inc++;
      }

      $item_name = $invoice_item['item_name'];
      $item_price = $invoice_item['item_price'];
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

      $statement = db()->prepare("UPDATE `selling_item` SET `return_quantity` = `return_quantity`+$item_quantity WHERE `id` = ?");
      $statement->execute(array($invoice_item['id']));

      $statement = db()->prepare("UPDATE `product_to_store` SET `quantity_in_stock` = `quantity_in_stock` + $item_quantity_add WHERE `store_id` = ? AND `product_id` = ?");
      $statement->execute(array($store_id, $item_id));

      $statement = db()->prepare("INSERT INTO `return_items` SET `store_id` = ?, `invoice_id` = ?, `item_id` = ?, `item_name` = ?, `item_quantity` = ?, `item_price` = ?, `item_purchase_price` = ?, `item_tax` = ?, `cgst` = ?, `sgst` = ?, `igst` = ?, `item_total` = ?");
      $statement->execute(array($store_id, $invoice_id, $item_id, $item_name, $item_quantity, $item_price, $item_purchase_price, $item_tax, $cgst, $sgst, $igst, $item_total));
    };

    if ($total_quantity <= 0) {
      throw new Exception(trans('error_empty_list'));
    }

    $price_info = $invoice_model->getSellingPrice($invoice_id, $store_id);
    $tpayable = $tpayable - $price_info['discount_amount'];
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
    $statement = db()->prepare("UPDATE `selling_info` SET `payment_status` = ? WHERE `store_id` = ? AND `invoice_id` = ?");
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

    $capital = $total_purchase_price / $tsubtotal;
    $capital = $capital > 0 ? $capital * $tpayable : 0;
    $profit = ($tsubtotal - $total_purchase_price) - $price_info['discount_amount'];

    $statement = db()->prepare("UPDATE `selling_price` SET `due` = ? WHERE `store_id` = ? AND `invoice_id` = ?");
    $statement->execute(array($due, $store_id, $invoice_id));

    $statement = db()->prepare("INSERT INTO `returns` SET `store_id` = ?, `reference_no` = ?, `invoice_id` = ?, `customer_id` = ?, `note` = ?, `total_item` = ?, `total_quantity` = ?, `subtotal` = ?, `total_amount` = ?, `item_tax` = ?, `cgst` = ?, `sgst` = ?, `igst` = ?, `total_purchase_price` = ?, `profit` = ?, `created_by` = ?");
    $statement->execute(array($store_id, $reference_no, $invoice_id, $customer_id, $note, $total_item, $total_quantity, $tsubtotal, $tpayable, $titem_tax, $tcgst, $tsgst, $tigst, $total_purchase_price, $profit, $user_id));

    if ($return_amount > 0) {
      $is_profit = 0;
      $is_hide = 1;
      if ($invoice['paid_amount'] > 0) {
        $is_profit = 1;
        $is_hide = 0;
      }
      $statement = db()->prepare("INSERT INTO `payments` SET `type` = ?,  `is_profit` = ?, `is_hide` = ?, `store_id` = ?, `invoice_id` = ?, `reference_no` = ?, `capital` = ?, `amount` = ?, `created_by` = ?");
      $statement->execute(array('return', $is_profit, $is_hide, $store_id, $invoice_id, $reference_no, -$capital, -$return_amount, $user_id));

      $statement = db()->prepare("UPDATE `selling_price` SET `return_amount` = `return_amount`+$return_amount WHERE `store_id` = ? AND `invoice_id` = ?");
      $statement->execute(array($store_id, $invoice_id));
    }

    if ($balance > 0) {
      $statement = db()->prepare("INSERT INTO `payments` SET `type` = ?, `store_id` = ?, `invoice_id` = ?, `note` = ?, `pos_balance` = ?, `created_by` = ?");
      $statement->execute(array('change', $store_id, $invoice_id, 'return_change', $balance, $user_id));

      $statement = db()->prepare("UPDATE `selling_price` SET `balance` = $balance WHERE `store_id` = ? AND `invoice_id` = ?");
      $statement->execute(array($store_id, $invoice_id));
    }

    if ($due > 0) {
      $due_paid = $invoice['due'] - $due;
      $statement = db()->prepare("UPDATE `customer_to_store` SET `due` = `due` - {$due_paid}  WHERE `store_id` = ? AND `customer_id` = ?");
      $statement->execute(array($store_id, $customer_id));
    }

    // Withdraw
    if (($account_id = store('deposit_account_id')) && $return_amount > 0) 
    {
      $ref_no = unique_transaction_ref_no('withdraw');
      $statement = db()->prepare("SELECT `category_id` FROM `expense_categorys` WHERE `sell_return` = ?");
      $statement->execute(array(1));
      $category = $statement->fetch(PDO::FETCH_ASSOC);
      $exp_category_id = $category['category_id'];
      $statement = db()->prepare("SELECT `source_id` FROM `income_sources` WHERE `for_sell` = ?");
      $statement->execute(array(1));
      $source = $statement->fetch(PDO::FETCH_ASSOC);
      $source_id = $source['source_id'];
      $title = 'Debit for Product Return';
      $details = 'Customer name: ' . get_the_customer($customer_id, 'customer_name');
      $image = 'NULL';
      $withdraw_amount = $return_amount;
      $transaction_type = 'withdraw';
      $is_substract = 0;
      $is_hide = 1;
      if ($invoice['paid_amount'] > 0) {
        $is_substract = 1;
        $is_hide = 0;
      }

      $statement = db()->prepare("INSERT INTO `bank_transaction_info` (store_id, is_substract, is_hide, account_id, source_id, exp_category_id, ref_no, invoice_id, transaction_type, title, details, image, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $statement->execute(array($store_id, $is_substract, $is_hide, $account_id, $source_id, $exp_category_id, $ref_no, $invoice_id, $transaction_type, $title, $details, $image, $user_id, date_time()));
      $info_id = db()->lastInsertId();
  
      $statement = db()->prepare("INSERT INTO `bank_transaction_price` (store_id, info_id, ref_no, amount) VALUES (?, ?, ?, ?)");
      $statement->execute(array($store_id, $info_id, $ref_no, $withdraw_amount));

      $statement = db()->prepare("UPDATE `bank_account_to_store` SET `withdraw` = `withdraw` + $withdraw_amount WHERE `store_id` = ? AND `account_id` = ?");
      $statement->execute(array($store_id, $account_id));

      $statement = db()->prepare("UPDATE `bank_accounts` SET `total_deposit` = `total_deposit` + $withdraw_amount WHERE `id` = ?");
      $statement->execute(array($account_id));
    }

    $Hooks->do_action('After_Sell_Return', $request);

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
    include ROOT.'/_inc/template/sell_return_view.php';
    exit();
}


/**
 *===================
 * START DATATABLE
 *===================
 */

$Hooks->do_action('Before_Showing_Sell_Return_List');

$where_query = "returns.store_id = " . $store_id;
if (from()) {
    $from = from();
    $to = to();
    $where_query .= date_range_selling_return_filter($from, $to);
}

// DB table to use
$table = "(SELECT returns.* FROM `returns` 
  WHERE $where_query) as returns";

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
        'db' => 'customer_id',
        'dt' => 'customer',
        'formatter' => function( $d, $row) {
            $customer = get_the_customer($row['customer_id']);
			if (isset($customer['customer_id'])) {
				return '<a href="customer_profile.php?customer_id=' . $customer['customer_id'] . '">' . $customer['customer_name'] . '</a>';
			}
			return '';
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
          return '<button id="view-btn" class="btn btn-sm btn-block btn-success" title="'.trans('button_view').'" data-loading-text="..."><i class="fa fa-eye"></i></button>';
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

$Hooks->do_action('After_Showing_Sell_Return_List');

/**
 *===================
 * END DATATABLE
 *===================
 */