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

// LOAD INVOICE MODEL
$invoice_model = registry()->get('loader')->model('purchase');

// Validate post data
function validate_request_data($request) 
{    
    // Validate date
    if (!validateString($request->post['reference_no'])) {
      throw new Exception(trans('error_reference_no'));
    }

    // Validate date
    if (!isItValidDate($request->post['date'])) {
      throw new Exception(trans('error_date'));
    }

    // Validate Reference No
    if (!validateString($request->post['reference_no'])) {
      throw new Exception(trans('error_reference_no'));
    }

    // Validate status
    if (!isset($request->post['sup_id']) || !validateInteger($request->post['sup_id'])) {
      throw new Exception(trans('error_sup_id'));
    }

    // Validate status
    if (empty($request->post['status'])) {
      throw new Exception(trans('error_status'));
    }

    // Validate supplier id
    if (!validateInteger($request->post['sup_id'])) {
      throw new Exception(trans('error_sup_id'));
    }

    // Validate product
    if (!isset($request->post['products']) || empty($request->post['products'])) {
        throw new Exception(trans('error_product_item'));
    }

    // Validate tax
    if (!is_numeric($request->post['total-tax'])) {
      throw new Exception(trans('error_tax'));
    }

    // Validate order tax
    if (!is_numeric($request->post['order-tax'])) {
      throw new Exception(trans('error_order_tax'));
    }

    // Validate shipping amount
    if (!is_numeric($request->post['shipping-amount'])) {
      throw new Exception(trans('error_shipping_amount'));
    }

    // Validate discount amount
    if (!is_numeric($request->post['discount-amount'])) {
      throw new Exception(trans('error_discount_amount'));
    }

    // Validate discount amount
    if (!is_numeric($request->post['others-charge'])) {
      throw new Exception(trans('error_others_charge'));
    }

    // Validate payadble amount
    if (!is_numeric($request->post['payable-amount'])) {
      throw new Exception(trans('error_payable_amount'));
    }

    // Validate payment method
    if (!validateInteger($request->post['pmethod-id'])) {
      throw new Exception(trans('error_payment_method'));
    }

    // Validate paid amount
    if (!is_numeric($request->post['paid-amount'])) {
      throw new Exception(trans('error_paid_amount'));
    }

    // Validate sub-total
    if (!validateFloat($request->post['total-amount'])) {
      throw new Exception(trans('error_total_amount'));
    }

    // Validate tax amount
    if (!is_numeric($request->post['total-tax'])) {
      throw new Exception(trans('error_tax_amount'));
    }
}


// Create invoice
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'CREATE')
{
  try {

    // Check create permission
    if (user_group_id() != 1 && !has_permission('access', 'create_purchase_invoice')) {
      throw new Exception(trans('error_create_permission'));
    }

    // Validate post data
    validate_request_data($request);

    // Validate invoice items
    if (!isset($request->post['products']) 
      && (isset($request->post['products']) || !is_array($request->post['products']))) {
      throw new Exception(trans('error_product_item'));
    }

    $sup_id = $request->post['sup_id'];
    $supplier_info = get_the_supplier($sup_id);
    $invoice_id = get_invoice_prefix('purchase').$request->post['reference_no'];
    foreach ($request->post['products'] as $product) 
    {  
        $id = $product['item_id'];
        $statement = db()->prepare("SELECT * FROM `products`
            LEFT JOIN `product_to_store` p2s ON (`products`.`p_id` = `p2s`.`product_id`) 
            WHERE `p2s`.`store_id` = ? AND `p_id` = ?");
        $statement->execute(array($store_id, $id));
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
          throw new Exception(trans('error_product_not_found'));
        }
        
        // Validate quantity    
        if(!validateFloat($product['quantity']) || $product['quantity'] <= 0) {
          throw new Exception(trans('error_quantity'));
        }

        // Validate purchase purchase_price
        if(!validateFloat($product['purchase_price']) || $product['purchase_price'] <= 0) {
          throw new Exception(trans('error_purchase_price'));
        }

        // Validate selling price
        if(!validateFloat($product['sell_price']) || $product['sell_price'] <= 0) {
          throw new Exception(trans('error_sell_price'));
        }

        if($product['purchase_price'] > $product['sell_price']) {
          throw new Exception(trans('error_sell_price_must_be_greated_that_purchase_price'));
        }
    }

    // Validate attachment
    if(isset($_FILES["image"]["type"]) && $_FILES["image"]["type"])
    {
        if (!$_FILES["image"]["type"] == "image/jpg" || !$_FILES["image"]["type"] == "application/pdf" || $_FILES["image"]["size"] > 1048576) {  // 1MB  
            throw new Exception(trans('error_size_or_typex'));
        }

        if ($_FILES["image"]["error"] > 0) {
            throw new Exception("Return Code: " . $_FILES["image"]["error"]);
        }
    }

    $Hooks->do_action('Before_Create_Purchase_Invoice', $request);

    $total_item = count($request->post['products']);
    $purchase_date = date('Y-m-d H:i:s', strtotime($request->post['date']));
    $created_at = date_time();
    $purchase_note = $request->post['purchase-note'];
    $attachment = $request->post['image'];
    $subtotal = $request->post['total-amount'];
    $item_tax = $request->post['total-tax'];
    $order_tax = $request->post['order-tax'] ? ($request->post['order-tax'] / 100)*($subtotal-$item_tax) : 0;
    $shipping_type  = isset($request->post['shipping-type']) ? $request->post['shipping-type'] : 'plain';
    $shipping_amount = $request->post['shipping-amount'];
    $shipping_status = $request->post['status'];
    $discount_type  = isset($request->post['discount-type']) ? $request->post['discount-type'] : 'plain';
    $discount_amount = $request->post['discount-amount'];
    $others_charge = $request->post['others-charge'];
    $payable_amount = $request->post['payable-amount'];
    $pmethod_id = $request->post['pmethod-id'];
    $paid_amount = $request->post['paid-amount'];
    $due = $request->post['due-amount'];
    $balance = $request->post['change-amount'];
    $total_paid = $paid_amount;

    // Check for dublicate, if present then update otherwise insert
    $statement = db()->prepare("SELECT * FROM `purchase_info` WHERE `invoice_id` = ?");
    $statement->execute(array($invoice_id));
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    if ($result) {
      throw new Exception(trans('error_reference_no_exist'));
    }
    
    $gst = 0;
    $cgst = 0;
    $sgst = 0;
    $igst = 0;

    $tgst = 0;
    $tcgst = 0;
    $tsgst = 0;
    $tigst = 0;
    $taxrate = 0;
    foreach ($request->post['products'] as  $product) {  
        $status = 'active';
        $id = $product['item_id'];
        $the_product = get_the_product($id, null, $store_id);
        if (isset($the_product['quantity_in_stock']) && $the_product['quantity_in_stock'] > 0) {
            $status = 'stock';
        }
        if ($the_product['taxrate']) {
            $taxrate = $the_product['taxrate']['taxrate'];
        }
        $item_name = $product['item_name'];
        $category_id = $product['category_id'];
        $brand_id = $the_product['brand_id'];
        $item_purchase_price = $product['purchase_price'];
        $item_selling_price = $product['sell_price'];
        $item_quantity = $product['quantity'];
        $item_tax = $product['tax_amount'];
        $tax_method = $product['tax_method'];
        $taxrate = $product['taxrate'];
        if ($tax_method == 'exclusive') {
            $item_total = ((int)$item_quantity * (float)$item_purchase_price) + $item_tax;
        } else {
            $item_total = ((int)$item_quantity * (float)$item_purchase_price);
        }

        if ($supplier_info['sup_state'] == get_preference('business_state')) {
          $cgst = $item_tax / 2;
          $sgst = $item_tax / 2;
        } else {
          $igst = $item_tax;
        }
        
        // Insert purchase item
        $statement = db()->prepare("INSERT INTO `purchase_item` (invoice_id, store_id, item_id, category_id, brand_id, item_name, item_purchase_price, item_selling_price, item_quantity, status, item_total, item_tax, tax_method, tax, gst, cgst, sgst, igst) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $statement->execute(array($invoice_id, $store_id, $id, $category_id, $brand_id, $item_name, $item_purchase_price, $item_selling_price, $item_quantity, $status, $item_total, $item_tax, $tax_method, $taxrate, $taxrate, $cgst, $sgst, $igst));
        
        // Update stock quantity
        $statement = db()->prepare("UPDATE `product_to_store` SET `purchase_price` = ?, `sell_price` = ?, `quantity_in_stock` = `quantity_in_stock` + $item_quantity WHERE `product_id` = ? AND `store_id` = ?");
        $statement->execute(array($item_purchase_price, $item_selling_price, $id, $store_id));
    }

    // Insert purchase info
    $statement = db()->prepare("INSERT INTO `purchase_info` (invoice_id, store_id, sup_id, total_item, purchase_note, attachment, shipping_status, created_by, purchase_date, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $statement->execute(array($invoice_id, $store_id, $sup_id, $total_item, $purchase_note, $attachment, $shipping_status, $user_id, $purchase_date, $created_at));

    if ($supplier_info['sup_state'] == get_preference('business_state')) {
      $tcgst = ($item_tax + $order_tax) / 2;
      $tsgst = ($item_tax + $order_tax) / 2;
    } else {
      $tigst = ($item_tax + $order_tax);
    }

    // Insert Price
    $statement = db()->prepare("INSERT INTO `purchase_price` (invoice_id, store_id, subtotal, discount_type, discount_amount, shipping_type, shipping_amount, others_charge, item_tax, order_tax, cgst, sgst, igst, payable_amount, paid_amount, due, balance) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $statement->execute(array($invoice_id, $store_id, $subtotal, $discount_type, $discount_amount, $shipping_type, $shipping_amount, $others_charge, $item_tax, $order_tax, $tcgst, $tsgst, $tigst, $payable_amount, $paid_amount, $due, $balance));

    // Upload attachment
    if(isset($_FILES["attachment"]["type"]) && $_FILES["attachment"]["type"])
    {
        $temporary = explode(".", $_FILES["attachment"]["name"]);
        $file_extension = end($temporary);
        $temp = explode(".", $_FILES["attachment"]["name"]);
        $newfilename = $invoice_id . '.' . end($temp);
        $sourcePath = $_FILES["attachment"]["tmp_name"]; // Storing source path of the file in a variable
        $targetFile = DIR_STORAGE . 'purchase-invoices/' . $newfilename; // Target path where file is to be stored
        if (file_exists($targetFile) && is_file($targetFile)) {
            if (!isset($request->post['force_upload'])) {
                throw new Exception(trans('error_image_exist'));
            } 
            unlink($targetFile);  
        } 
        // Update invoice url
        $statement = db()->prepare("UPDATE  `purchase_info` SET `attachment` = ? WHERE `invoice_id` = ? AND `store_id` = ?");
        $statement->execute(array($newfilename, $invoice_id, $store_id));
        move_uploaded_file($sourcePath, $targetFile);
    }

    if ($paid_amount > 0) {
        $statement = db()->prepare("INSERT INTO `purchase_payments` (store_id, invoice_id, pmethod_id, note, amount, total_paid, balance, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $statement->execute(array($store_id, $invoice_id, $pmethod_id, $purchase_note, $paid_amount, $total_paid, $balance, $user_id, $created_at));

        // Update checkout status
        $statement = db()->prepare("UPDATE `purchase_info` SET `checkout_status` = ? WHERE `invoice_id` = ? AND `store_id` = ?");
        $statement->execute(array(1, $invoice_id, $store_id));
    }

    $reference_no = generate_purchase_log_ref_no('purchase');
    $statement = db()->prepare("INSERT INTO `purchase_logs` (sup_id, reference_no, type, pmethod_id, description, amount, store_id, ref_invoice_id, created_by, created_at) VALUES (?,?,?,?,?,?,?,?,?,?)");
    $statement->execute(array($sup_id, $reference_no, 'purchase', $pmethod_id, 'Paid while purchasing', $paid_amount, $store_id, $invoice_id, $user_id, $created_at));

    if ($due > 0) {
        $reference_no = generate_purchase_log_ref_no('due');
        $statement = db()->prepare("INSERT INTO `purchase_logs` (sup_id, reference_no, type, description, amount, store_id, ref_invoice_id, created_by, created_at) VALUES (?,?,?,?,?,?,?,?,?)");
        $statement->execute(array($sup_id, $reference_no, 'due', 'Due while purchasing', $due, $store_id, $invoice_id, $user_id, $created_at));

        $update_due = db()->prepare("UPDATE `supplier_to_store` SET `balance` = `balance` + $due WHERE `sup_id` = ? AND `store_id` = ?");
        $update_due->execute(array($sup_id, $store_id));
    } else {
        $update_due = db()->prepare("UPDATE `purchase_info` SET `payment_status` = ? WHERE `invoice_id` = ? AND `store_id` = ?");
        $update_due->execute(array('paid', $invoice_id, $store_id));
    }

    // Withdraw
    if (($account_id = store('deposit_account_id')) && $paid_amount > 0) {
      $ref_no = unique_transaction_ref_no('withdraw');
      $statement = db()->prepare("SELECT `category_id` FROM `expense_categorys` WHERE `product_purchase` = ?");
      $statement->execute(array(1));
      $category = $statement->fetch(PDO::FETCH_ASSOC);
      $exp_category_id = $category['category_id'];
      $title = 'Debit for Product Purchase';
      $details = 'Supplier name: ' . get_the_supplier($sup_id, 'sup_name');
      $image = 'NULL';
      $withdraw_amount = $paid_amount;
      $transaction_type = 'withdraw';

      $statement = db()->prepare("INSERT INTO `bank_transaction_info` (store_id, account_id, exp_category_id, ref_no, invoice_id, transaction_type, title, details, image, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $statement->execute(array($store_id, $account_id, $exp_category_id, $ref_no, $invoice_id, $transaction_type, $title, $details, $image, $user_id, date_time()));
	    $info_id = db()->lastInsertId();
	  
      $statement = db()->prepare("INSERT INTO `bank_transaction_price` (store_id, info_id, ref_no, amount) VALUES (?, ?, ?, ?)");
      $statement->execute(array($store_id, $info_id, $ref_no, $withdraw_amount));

      $statement = db()->prepare("UPDATE `bank_account_to_store` SET `withdraw` = `withdraw` + $withdraw_amount WHERE `store_id` = ? AND `account_id` = ?");
      $statement->execute(array($store_id, $account_id));

      $statement = db()->prepare("UPDATE `bank_accounts` SET `total_deposit` = `total_deposit` + $withdraw_amount WHERE `id` = ?");
      $statement->execute(array($account_id));
    }

    $Hooks->do_action('After_Create_Purchase_Invoice', $invoice_id);

    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_success'), 'id' => $invoice_id));
    exit();

  } catch (Exception $e) { 

    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();
  }
}


// Delete Invoice
if($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'DELETE') 
{
  try {

    // Check delete permission
    if (user_group_id() != 1 && !has_permission('access', 'delete_purchase_invoice')) {
      throw new Exception(trans('error_delete_permission'));
    }

    // Validate invoice id
    if (empty($request->post['invoice_id'])) {
      throw new Exception(trans('error_invoice_id'));
    }

    $Hooks->do_action('Before_Delete_Purchase_Invoice', $request);

    $invoice_id = $request->post['invoice_id'];

    // Check, if invoice exist or not
    $invoice_info = $invoice_model->getInvoiceInfo($invoice_id, $store_id);
    if (!$invoice_info) {
        throw new Exception(trans('error_invoice_id'));
    }
    $purchase_date = date('Y-m-d H:i:s',strtotime($invoice_info['purchase_date']));
    $due = $invoice_info['due'];

    $statement = db()->prepare("SELECT `item_id`, SUM(`item_quantity`) as item_quantity, SUM(`total_sell`) as total_sell, `status`, `return_quantity` FROM `purchase_item` WHERE `store_id` = ? AND `invoice_id` = ? GROUP BY `status` DESC");
    $statement->execute(array($store_id, $invoice_id));
    $purchase_item = $statement->fetch(PDO::FETCH_ASSOC);

    if ($purchase_item['total_sell'] > 0
      && (($purchase_item['status'] == 'active') || ($purchase_item['status'] == 'sold'))) {

       throw new Exception(trans('error_active_or_sold'));
    }

    // Check invoice delete duration 
    if (invoice_delete_lifespan() > strtotime($purchase_date)) {
       throw new Exception(trans('error_delete_duration_expired'));
    }

    // Quantity Adjustment
    $return_quantity = $purchase_item['item_quantity'] - ($purchase_item['total_sell'] + $purchase_item['return_quantity']);
    $statement = db()->prepare("UPDATE `product_to_store` SET `quantity_in_stock` = `quantity_in_stock` - $return_quantity WHERE `store_id` = ? AND `product_id` = ?");
    $statement->execute(array($store_id, $purchase_item['item_id']));

    // Delete invoice info
    $statement = db()->prepare("DELETE FROM  `purchase_info` WHERE `store_id` = ? AND `invoice_id` = ? LIMIT 1");
    $statement->execute(array($store_id, $invoice_id));

    // Delete invocie item
    $statement = db()->prepare("DELETE FROM  `purchase_item` WHERE `store_id` = ? AND `invoice_id` = ?");
    $statement->execute(array($store_id, $invoice_id));

    // Delete purchase price info
    $statement = db()->prepare("DELETE FROM  `purchase_price` WHERE `store_id` = ? AND `invoice_id` = ?");
    $statement->execute(array($store_id, $invoice_id));

    // Delete purchase payments
    $statement = db()->prepare("DELETE FROM  `purchase_payments` WHERE `store_id` = ? AND `invoice_id` = ?");
    $statement->execute(array($store_id, $invoice_id));

    // Delete purchase returns
    $statement = db()->prepare("DELETE FROM  `purchase_returns` WHERE `store_id` = ? AND `invoice_id` = ?");
    $statement->execute(array($store_id, $invoice_id));

    // Delete purchase return items
    $statement = db()->prepare("DELETE FROM  `purchase_return_items` WHERE `store_id` = ? AND `invoice_id` = ?");
    $statement->execute(array($store_id, $invoice_id));

    if ($due > 0) {
      $statement = db()->prepare("UPDATE `supplier_to_store` SET `balance` = `balance`-$due  WHERE `store_id` = ? AND `sup_id` = ?");
      $statement->execute(array($store_id, $invoice_info['sup_id']));
    }

    // Deposit
    $deposit_amount = $invoice_info['paid_amount'] - $invoice_info['return_amount'];
    if (($account_id = store('deposit_account_id')) && $deposit_amount > 0) {
      $ref_no = unique_transaction_ref_no();
      $statement = db()->prepare("SELECT `source_id` FROM `income_sources` WHERE `for_purchase_delete` = ?");
      $statement->execute(array(1));
      $source = $statement->fetch(PDO::FETCH_ASSOC);
      $source_id = $source['source_id'];
      $title = 'Deposit for purchase delete';
      $details = 'Supplier name: ' . get_the_supplier($invoice_info['sup_id'], 'sup_name');
      $image = 'NULL';
      $transaction_type = 'deposit';

      $statement = db()->prepare("INSERT INTO `bank_transaction_info` (store_id, account_id, source_id, ref_no, invoice_id, transaction_type, title, details, image, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $statement->execute(array($store_id, $account_id, $source_id, $ref_no, $invoice_id, $transaction_type, $title, $details, $image, $user_id, date_time()));
        $info_id = db()->lastInsertId();
        
      $statement = db()->prepare("INSERT INTO `bank_transaction_price` (store_id, info_id, ref_no, amount) VALUES (?, ?, ?, ?)");
      $statement->execute(array($store_id, $info_id, $ref_no, $deposit_amount));

      $statement = db()->prepare("UPDATE `bank_account_to_store` SET `deposit` = `deposit` + $deposit_amount WHERE `store_id` = ? AND `account_id` = ?");
      $statement->execute(array($store_id, $account_id));

      $statement = db()->prepare("UPDATE `bank_accounts` SET `total_deposit` = `total_deposit` + $deposit_amount WHERE `id` = ?");
      $statement->execute(array($account_id));
    }

    $Hooks->do_action('After_Delete_Purchase_Invoice', $invoice_id);
    
    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_delete_success')));
    exit();

  } catch (Exception $e) { 

    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();
  }
}


// Update invoice info
if($request->server['REQUEST_METHOD'] == 'POST' && $request->post['action_type'] == 'UPDATEINVOICEINFO')
{
    try {
        
        // Check permission
        if (user_group_id() != 1 && !has_permission('access', 'update_purchase_invoice_info')) {
          throw new Exception(trans('error_update_permission'));
        }

        // Validate invoice id
        if (empty($request->post['invoice_id'])) {
            throw new Exception(trans('error_invoice_id'));
        }

        $invoice_id = $request->post['invoice_id'];

        // Check, if invoice exist or not
        $invoice_info = $invoice_model->getInvoiceInfo($invoice_id);
        if (!$invoice_info) {
            throw new Exception(trans('error_invoice_id'));
        }

        $Hooks->do_action('Before_Update_Purchase_Invoice', $invoice_id);

        // $sup_id = $request->post['sup_id'];
        $purchase_note = $request->post['purchase_note'];
        $payable_amount = $invoice_info['payable_amount'];

        // Update invoice info
        $statement = db()->prepare("UPDATE `purchase_info` SET `purchase_note` = ? WHERE `store_id` = ? AND `invoice_id` = ? LIMIT 1");
        $statement->execute(array($purchase_note, $store_id, $invoice_id));

        $Hooks->do_action('After_Update_Purchase_Invoice', $invoice_id);

        header('Content-Type: application/json');
        echo json_encode(array('msg' => trans('text_purchase_update_success')));
        exit();

    } catch(Exception $e) { 

        header('HTTP/1.1 422 Unprocessable Entity');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array('errorMsg' => $e->getMessage()));
        exit();
  }
}

// Invoice Info Edit Form
if (isset($request->get['action_type']) AND $request->get['action_type'] == 'INVOICEINFOEDIT') {

    try {

        $invoice_id = isset($request->get['invoice_id']) ? $request->get['invoice_id'] : null;
        $invoice = $invoice_model->getInvoiceInfo($invoice_id);
        if (!$invoice) {
            throw new Exception(trans('error_invoice_not_found'));
        }
        include('template/purchase_invoice_info_edit_form.php');
        exit();
        
    } catch (Exception $e) { 

        header('HTTP/1.1 422 Unprocessable Entity');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array('errorMsg' => $e->getMessage()));
        exit();
    }
}


// View Payment Form
if (isset($request->get['action_type']) && $request->get['action_type'] == 'PAYMENTFORMDETAILS')
{
    $invoice_id = isset($request->get['invoice_id']) && $request->get['invoice_id'] != 'null' ? $request->get['invoice_id'] : '';
    $order = array();
    $items = array();
    $where_query = "`purchase_info`.`store_id` = ?";
    if ($invoice_id) {
      $where_query .= " AND `purchase_info`.`invoice_id` = '{$invoice_id}'";
    }
    $statement = db()->prepare("SELECT * FROM `purchase_info` 
          LEFT JOIN `purchase_price` ON (`purchase_price`.`invoice_id` = `purchase_info`.`invoice_id`)
          WHERE $where_query");
    $statement->execute(array($store_id));
    $order = $statement->fetch(PDO::FETCH_ASSOC);

    $payment_model = registry()->get('loader')->model('payment');
    $items = $invoice_model->getInvoiceItems($order['invoice_id'], $store_id);
    $payments = $payment_model->getPurchasePayments($order['invoice_id'], $store_id);

    $order['items']     = $items;
    $order['payments']  = $payments;

    ob_start();
    include 'template/purchase_payment_form.php';
    $html = ob_get_contents();
    ob_end_clean();
    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_success'), 'html' => $html, 'order' => $order));
    exit();
}


// View invoice details
if (isset($request->get['action_type']) AND $request->get['action_type'] == 'INVOICEDETAILS') {

    try {

        $user_id = isset($request->get['user_id']) ? $request->get['user_id'] : null;
        $where_query = "`purchase_info`.`inv_type` IN ('purchase','transfer') AND `created_by` = ? AND `is_visible` = ?";
        $from = from() ? from() : date('Y-m-d');
        $to = to() ? to() : date('Y-m-d');
        $where_query .= date_range_filter($from, $to);
        $statement = db()->prepare("SELECT * FROM `purchase_info` 
            LEFT JOIN `purchase_price` ON (`purchase_info`.`invoice_id` = `purchase_price`.`invoice_id`)
            WHERE $where_query");
        $statement->execute(array($user_id, 1));
        $invoices = $statement->fetchAll(PDO::FETCH_ASSOC);
        if (!$statement->rowCount() > 0) {
            throw new Exception(trans('error_not_found'));
        }

        include('template/user_invoice_details.php');
        exit();
        
    } catch (Exception $e) { 

        header('HTTP/1.1 422 Unprocessable Entity');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array('errorMsg' => $e->getMessage()));
        exit();
    }
}

// View invoice due details
if (isset($request->get['action_type']) AND $request->get['action_type'] == 'INVOICEDUEDETAILS') {

    try {

        $user_id = isset($request->get['user_id']) ? $request->get['user_id'] : null;
        $where_query = "`purchase_info`.`inv_type`IN ('purchase','transfer') AND `created_by` = ? AND `is_visible` = ? AND `purchase_price`.`due` > 0";
        $from = from() ? from() : date('Y-m-d');
        $to = to() ? to() : date('Y-m-d');
        $where_query .= date_range_filter($from, $to);

        $statement = db()->prepare("SELECT * FROM `purchase_info` 
            LEFT JOIN `purchase_price` ON (`purchase_info`.`invoice_id` = `purchase_price`.`invoice_id`)
            WHERE $where_query");
        $statement->execute(array($user_id, 1));
        $invoices = $statement->fetchAll(PDO::FETCH_ASSOC);
        if (!$statement->rowCount() > 0) {
            throw new Exception(trans('error_not_found'));
        }

        include('template/user_invoice_due_details.php');
        exit();
        
    } catch (Exception $e) { 

        header('HTTP/1.1 422 Unprocessable Entity');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array('errorMsg' => $e->getMessage()));
        exit();
    }
}

// View Invoice
if (isset($request->get['invoice_id']) && isset($request->get['action_type']) && $request->get['action_type'] == 'VIEW') 
{
    $invoice_id = $request->get['invoice_id'];
    $invoice = $invoice_model->getInvoiceInfo($invoice_id);
    $invoice_items = $invoice_model->getInvoiceItems($invoice_id);

    $return_model = registry()->get('loader')->model('purchasereturn');
    $returns = $return_model->getInvoiceItems($invoice_id);

    $payment_model = registry()->get('loader')->model('payment');
    $payments = $payment_model->getPurchasePayments($invoice_id, store_id());
    include ROOT.'/_inc/template/purchase_invoice.php';
    exit();
}

/**
 *===================
 * START DATATABLE
 *===================
 */

// Check, if user has reading permission or not
// If user have not reading permission return an alert message
if (user_group_id() != 1 && !has_permission('access', 'read_purchase_list')) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_read_permission')));
  exit();
}

$Hooks->do_action('Before_Showing_Purchase_Invoice_List');

$where_query = "purchase_info.inv_type IN ('purchase','transfer') AND purchase_info.store_id = " . $store_id;
if (isset($request->get['type']) && ($request->get['type'] != 'undefined') && $request->get['type'] != '') {
    switch ($request->get['type']) {
        case 'due':
            $where_query .= " AND purchase_info.payment_status = 'due'";
            break;
        case 'paid':
            $where_query .= " AND purchase_info.payment_status = 'paid'";
            break;
        case 'transfer':
            $where_query .= " AND purchase_info.inv_type = 'transfer'";
            break;
        case 'inactive':
            $where_query .= " AND purchase_info.is_visible = 0";
            break;
        default:
            $where_query .= " AND purchase_info.is_visible = 1";
            break;
    }
};
// if (from()) {
  $from = from();
  $to = to();
  $where_query .= date_range_filter2($from, $to);
// }

// DB table to use
$table = "(SELECT purchase_info.*, purchase_price.payable_amount, purchase_price.paid_amount, purchase_price.due FROM `purchase_info` 
  LEFT JOIN `purchase_price` ON (purchase_info.invoice_id = purchase_price.invoice_id) 
  WHERE $where_query) as purchase_info";

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
    array( 'db' => 'invoice_id', 'dt' => 'id' ),
    array( 
      'db' => 'inv_type',   
      'dt' => 'inv_type' ,
      'formatter' => function($d, $row) {
        return '<span class="label label-warning">'.ucfirst($row['inv_type']).'</span>';
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
    array( 
      'db' => 'created_at',   
      'dt' => 'created_at' ,
      'formatter' => function($d, $row) {
        return $row['created_at'];
      }
    ),
    array(
        'db' => 'sup_id',
        'dt' => 'sup_name',
        'formatter' => function( $d, $row) {

            $supplier = get_the_supplier($row['sup_id']);
            return '<a href="supplier_profile.php?sup_id=' . $supplier['sup_id'] . '">' . $supplier['sup_name'] . '</a>';
        }
    ),
    array(
        'db' => 'created_by',
        'dt' => 'created_by',
        'formatter' => function( $d, $row) {
            $the_user = get_the_user($row['created_by']);
            if (isset($the_user['id'])) {
                return '<a href="user.php?user_id=' . $the_user['id'] . '&username='.$the_user['username'].'">' . $the_user['username'] . '</a>';
            }
            return;
        }
    ),
    array(
        'db' => 'payable_amount',
        'dt' => 'invoice_amount',
        'formatter' => function($d, $row) {
            return currency_format($row['payable_amount']);
        }
    ),
    array(
        'db' => 'paid_amount',
        'dt' => 'paid_amount',
        'formatter' => function($d, $row) use($invoice_model) {
            return currency_format($row['paid_amount']);
        }
    ),
    array(
        'db' => 'due',
        'dt' => 'due',
        'formatter' => function($d, $row) use($invoice_model) {
            return currency_format($row['due']);
        }
    ),
    array( 'db' => 'payment_status', 'dt' => 'payment_status' ),
    array(
        'db' => 'invoice_id',
        'dt' => 'status',
        'formatter' => function($d, $row) use($invoice_model)  {
            if ($row['payment_status'] == 'due') {
                return '<span class="label label-danger">'.trans('text_unpaid').'</span>';
            } else {
                return '<span class="label label-success">'.trans('text_paid').'</span>';
            }
        }
    ),
    array(
        'db' => 'invoice_id',
        'dt' => 'btn_pay',
        'formatter' => function($d, $row) {
            if ($row['payment_status'] != 'paid') {
                return '<button id="pay_now" class="btn btn-sm btn-block btn-success" title="'.trans('button_pay_now').'" data-loading-text="..."><i class="fa fa-money"></i></button>';
            }
            return '-';
        }
    ),
    array(
        'db' => 'invoice_id',
        'dt' => 'btn_return',
        'formatter' => function($d, $row) {
            return '<button id="return_item" class="btn btn-sm btn-block btn-warning" title="'.trans('button_return').'" data-loading-text="..."><i class="fa fa-minus"></i></button>';
        }
    ),
    array(
        'db' => 'invoice_id',
        'dt' => 'btn_view',
        'formatter' => function($d, $row) {
            return '<button id="view-invoice-btn" class="btn btn-sm btn-block btn-success" title="'.trans('button_view_receipt').'" data-loading-text="..."><i class="fa fa-eye"></i></button>';
        }
    ),
    array(
        'db' => 'invoice_id',
        'dt' => 'btn_edit',
        'formatter' => function($d, $row) {
            return '<button id="edit-invoice-info" class="btn btn-sm btn-block btn-info" title="'.trans('button_edit').'" data-loading-text="..."><span class="fa fa-pencil"></span></button>';     
        }
    ),
    array(
        'db' => 'invoice_id',
        'dt' => 'btn_delete',
        'formatter' => function($d, $row) {
            return '<button class="btn btn-sm btn-block btn-danger" id="delete-invoice" title="'.trans('button_delete').'" data-loading-text="..."><i class="fa fa-trash"></i></button>';

        }
    ),
);

echo json_encode(
    SSP::simple($request->get, $sql_details, $table, $primaryKey, $columns)
);

$Hooks->do_action('After_Showing_Purchase_Invoice_List');

/**
 *===================
 * END DATATABLE
 *===================
 */