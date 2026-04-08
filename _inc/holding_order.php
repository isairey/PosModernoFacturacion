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
$invoice_model = registry()->get('loader')->model('invoice');

// Validate customer post data
function validate_customer_data($request) 
{
  // Validate customer id
  if (!validateInteger($request->post['customer-id'])) {
    throw new Exception(trans('error_customer'));
  }

  // Validate holding order title
  if (empty($request->post['order-title'])) {
    throw new Exception(trans('error_order_title'));
  }
}

// Validate invoice items
function validate_invoice_items($invoice_items)
{
  // Loop through produdt items for validation checking
  foreach ($invoice_items as $product) 
  {
    // Validate product id
    if (!validateInteger($product['item_id'])) {
      throw new Exception(trans('error_invalid_product'));
    }

    // Fetch product item
    $the_product = get_the_product($product['item_id']);

    // Check, product item exist or not
    if (!$the_product) {
      throw new Exception(trans('error_product_not_found'));
    }

    // Check, product item stock availabel or not
    if ($the_product['quantity_in_stock'] <= 0) {
      throw new Exception(trans('error_out_of_stock'));
    }

    // Chcck, requested quantity is greater than of existing quantity or not
    if ($the_product['quantity_in_stock'] < $product['item_quantity']) {
      throw new Exception(trans('error_quantity_exceed'));
    }

    // Validate product name
    if (!validateString($product['item_name'])) {
      throw new Exception(trans('error_invoice_product_name'));
    }

    // Validate product price
    if (!validateFloat($product['item_price'])) {
      throw new Exception(trans('error_invoice_product_price'));
    }

    // Validate product quantity
    if (!validateInteger($product['item_quantity'])) {
      throw new Exception(trans('error_invoice_product_quantity'));
    }

    // Validate product total price
    if (!validateFloat($product['item_total'])) {
      throw new Exception(trans('error_invoice_product_total'));
    }
  }
}

function validate_existance($request) 
{
  
  global $store_id;
  $statement = db()->prepare("SELECT UPPER(`order_title`) FROM `holding_info` WHERE `store_id` = ? AND `order_title` = ?");
  $statement->execute(array($store_id, strtoupper($request->post['order-title'])));
  $row = $statement->fetch(PDO::FETCH_ASSOC);
  if ($row) {
    throw new Exception(trans('error_order_name_exist'));
  }
}

// Delete holding order
if ($request->server['REQUEST_METHOD'] == 'POST' AND $request->get['action_type'] == 'DELETE') {
  try {

    // Check delete permission
    if (user_group_id() != 1 && !has_permission('access', 'delete_holding-order')) {
      throw new Exception(trans('error_delete_permission'));
    }

    // Validate ref no
    if (empty($request->post['ref_no'])) {
      throw new Exception(trans('error_ref_no'));
    }

    $ref_no = $request->post['ref_no'];

    $Hooks->do_action('Before_Delete_Holding_Order', $request);

    // Delete holding order
    $statement = db()->prepare("DELETE FROM `holding_info` WHERE `ref_no` = ?");
    $statement->execute(array($ref_no));
    $statement = db()->prepare("DELETE FROM `holding_item` WHERE `ref_no` = ?");
    $statement->execute(array($ref_no));
    $statement = db()->prepare("DELETE FROM `holding_price` WHERE `ref_no` = ?");
    $statement->execute(array($ref_no));

    $Hooks->do_action('After_Delete_Holding_Order', $ref_no);
    
    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_delete_holding_order_success')));
    exit();

  } catch(Exception $e) { 
    
    $error_message = $e->getMessage();
    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $error_message));
    exit();
  }
}

if ($request->server['REQUEST_METHOD'] == 'POST' AND $request->get['action_type'] == 'HOLD')
{
  try {

    // Check permission
    if (user_group_id() != 1 && !has_permission('access', 'create_holding_order')) {
      throw new Exception(sprintf(trans('error_permission')));
    }

    validate_customer_data($request);
    validate_existance($request);

    // Validate sub-total
    if (!validateFloat($request->post['sub-total'])) {
      throw new Exception(trans('error_invoice_sub_total'));
    }

    // Validate discount amount
    if (!is_numeric($request->post['discount-amount'])) {
      throw new Exception(trans('error_invoice_discount_amount'));
    }

    // Validate tax amount
    if (!is_numeric($request->post['tax-amount'])) {
      throw new Exception(trans('error_invoice_tax_amount'));
    }

    // Validate shipping amount
    if (!is_numeric($request->post['shipping-amount'])) {
      throw new Exception(trans('error_shipping_amount'));
    }

    // Validate others charge
    if (!is_numeric($request->post['others-charge'])) {
      throw new Exception(trans('error_others_charge'));
    }

    // Validate payable amount
    if (!validateFloat($request->post['payable-amount'])) {
      throw new Exception(trans('error_invoice_payable_amount'));
    }

    // Validate invoice product items
    if (!isset($request->post['product-item']) 
      && (isset($request->post['product-item']) || !is_array($request->post['product-item']))) {
      throw new Exception(trans('error_product_item'));
    }

    $Hooks->do_action('Before_Add_Order_On_Hold', $request);

    $product_items  = $request->post['product-item'];
    $ref_no = unique_id(6);
    $statement = db()->prepare("SELECT * FROM `holding_info` WHERE `store_id` = ? AND `ref_no` = ?");
    $statement->execute(array(store_id(), $ref_no));
    $row = $statement->fetch(PDO::FETCH_ASSOC);
    if ($row) {
      throw new Exception(trans('error_ref_no_exist'));
    }

    validate_invoice_items($product_items);
    $id = $invoice_model->putOrderOnHold($request, $ref_no, $store_id);

    $Hooks->do_action('After_Add_Order_On_Hold', $id);

    header('Content-Type: application/json');
    echo json_encode(array('msg' => 'Order Successfully Added to Holding List!'));
    exit();

  } catch (Exception $e) { 

    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();
  }
}

// Edit holding order
if ($request->server['REQUEST_METHOD'] == 'GET' AND $request->get['action_type'] == 'EDIT') 
{
    try {

      $ref_no = isset($request->get['ref_no']) ? $request->get['ref_no'] : '';
      if (!$ref_no) {
        throw new Exception(trans('error_ref_no'));
      }

      $Hooks->do_action('Before_Edit_Hold_order', $request);

      $statement = db()->prepare("SELECT `holding_info`.*, `holding_price`.*, `customers`.`customer_id`, `customers`.`customer_name`, `customers`.`customer_mobile` as `mobile`, `customers`.`customer_email` FROM `holding_info` 
        LEFT JOIN `holding_price` ON `holding_info`.`ref_no` = `holding_price`.`ref_no` 
        LEFT JOIN `customers` ON `holding_info`.`customer_id` = `customers`.`customer_id` 
        WHERE `holding_info`.`store_id` = ? AND `holding_info`.`ref_no` = ? ORDER BY `holding_info`.`ref_no` DESC");
      $statement->execute(array($store_id, $ref_no));
      $order = $statement->fetch(PDO::FETCH_ASSOC);
      $order['customer'] = get_the_customer($order['customer_id']);

      $statement = db()->prepare("SELECT `holding_item`.*, `products`.`p_type` FROM `holding_item` LEFT JOIN `products` ON (`holding_item`.`item_id` = `products`.`p_id`) WHERE `holding_item`.`store_id` = ? AND `holding_item`.`ref_no` = ? ORDER BY `holding_item`.`ref_no` DESC");
      $statement->execute(array($store_id, $ref_no));
      $items = $statement->fetchAll(PDO::FETCH_ASSOC);

      $Hooks->do_action('After_Edit_Hold_order', $order);

      header('Content-Type: application/json');
      echo json_encode(array('msg' => trans('text_success'), 'order' => $order, 'items' => $items));
      exit();
        
    } catch (Exception $e) { 

      header('HTTP/1.1 422 Unprocessable Entity');
      header('Content-Type: application/json; charset=UTF-8');
      echo json_encode(array('errorMsg' => $e->getMessage()));
      exit();
    }
}

// View holding order details
if ($request->server['REQUEST_METHOD'] == 'GET' AND $request->get['action_type'] == 'HOLDINGORDERDETAILS') {

    try {

      $ref_no = isset($request->get['ref_no']) ? $request->get['ref_no'] : '';
      if (!$ref_no) {
        throw new Exception(trans('error_ref_no'));
      }

      $statement = db()->prepare("SELECT `holding_info`.*, `holding_price`.*, `customers`.`customer_id`, `customers`.`customer_name`, `customers`.`customer_mobile`, `customers`.`customer_email` FROM `holding_info` 
        LEFT JOIN `holding_price` ON `holding_info`.`ref_no` = `holding_price`.`ref_no` 
        LEFT JOIN `customers` ON `holding_info`.`customer_id` = `customers`.`customer_id` 
        WHERE `holding_info`.`store_id` = ? AND `holding_info`.`ref_no` = ? ORDER BY `holding_info`.`info_id` DESC");
      $statement->execute(array($store_id, $ref_no));
      $order = $statement->fetch(PDO::FETCH_ASSOC);
      $order['customer'] = get_the_customer($order['customer_id'], 'customer_name');

      $statement = db()->prepare("SELECT * FROM `holding_item` 
        WHERE `holding_item`.`store_id` = ? AND `holding_item`.`ref_no` = ? ORDER BY `holding_item`.`ref_no` DESC");
      $statement->execute(array($store_id, $ref_no));
      $items = $statement->fetchAll(PDO::FETCH_ASSOC);

      $items = array_map(function($item) {
        $unit_name = get_the_unit(get_the_product($item['item_id'],'unit_id'), 'unit_name');
        return array_merge($item, array('unit_name'=>$unit_name));
      }, $items);

      header('Content-Type: application/json');
      echo json_encode(array('msg' => trans('text_success'), 'order' => $order, 'items' => $items));
      exit();
        
    } catch (Exception $e) { 

      header('HTTP/1.1 422 Unprocessable Entity');
      header('Content-Type: application/json; charset=UTF-8');
      echo json_encode(array('errorMsg' => $e->getMessage()));
      exit();
    }
}

// View holding orders
if ($request->server['REQUEST_METHOD'] == 'GET' AND $request->get['action_type'] == 'HOLDINGORDERDETAILSMODAL') {

    try {

      $statement = db()->prepare("SELECT `holding_info`.*, `holding_price`.*, `customers`.`customer_id`, `customers`.`customer_name`, `customers`.`customer_mobile`, `customers`.`customer_email` FROM `holding_info` 
        LEFT JOIN `holding_price` ON `holding_info`.`ref_no` = `holding_price`.`ref_no` 
        LEFT JOIN `customers` ON `holding_info`.`customer_id` = `customers`.`customer_id` 
        WHERE `holding_info`.`store_id` = ? ORDER BY `holding_info`.`info_id` DESC LIMIT 1,20");
      $statement->execute(array($store_id));
      $orders = $statement->fetchAll(PDO::FETCH_ASSOC);

      ob_start();
      require(DIR_INCLUDE.'template/holding_order_details.php');
      $html = ob_get_contents();
      ob_end_clean();

      header('Content-Type: application/json');
      echo json_encode(array('msg' => trans('text_success'), 'orders' => $orders, 'html' => $html));
      exit();
        
    } catch (Exception $e) { 

      header('HTTP/1.1 422 Unprocessable Entity');
      header('Content-Type: application/json; charset=UTF-8');
      echo json_encode(array('errorMsg' => $e->getMessage()));
      exit();
    }
}