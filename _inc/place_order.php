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
if (user_group_id() != 1 && !has_permission('access', 'create_sell_invoice')) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_read_permission')));
  exit();
}

$store_id     = store_id();

// LOAD INVOICE MODEL
$invoice_model = registry()->get('loader')->model('invoice');

// Validate customer post data
function validate_customer_request_data($request) 
{
  // Validate pmethod id
  if ($request->post['paid-amount'] > 0) {
    if (!validateInteger($request->post['pmethod-id'])) {
      throw new Exception(trans('error_pmethod'));
    }
  }

  // Validate customer id
  if (!validateInteger($request->post['customer-id'])) {
    throw new Exception(trans('error_invoice_customer'));
  }
}

// Validate invoice items
function validate_invoice_items($invoice_items)
{
  foreach ($invoice_items as $product) 
  {
    // Validate product type
    if (!validateString($product['p_type'])) {
      throw new Exception(trans('error_invoice_product_type'));
    }

    // Validate product id
    if (!validateInteger($product['item_id'])) {
      throw new Exception(trans('error_invalid_product_id'));
    }

    // Fetch product item
    $the_product = get_the_product($product['item_id'], null, store_id());

    // Check, product item exist or not
    if (!$the_product) {
      throw new Exception(trans('error_product_not_found'));
    }

    if ($product['p_type'] != 'service') {
      // Check, product item stock availabel or not
      if ($the_product['quantity_in_stock'] <= 0) {
        throw new Exception(trans('error_out_of_stock'));
      }
      // Chcck, requested quantity is greater than of existing quantity or not
      if ($the_product['quantity_in_stock'] < $product['item_quantity']) {
        throw new Exception(trans('error_quantity_exceed'));
      }
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
    if (!validateFloat($product['item_quantity'])) {
      throw new Exception(trans('error_invoice_product_quantity'));
    }

    // Validate product total price
    if (!validateFloat($product['item_total'])) {
      throw new Exception(trans('error_invoice_product_total'));
    }
  }
}

if ($request->server['REQUEST_METHOD'] == 'POST')
{
  try {

    // $invoice_id   = isset($request->post['invoice-id']) ? $request->post['invoice-id'] : null;

    validate_customer_request_data($request);

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

    // Validate payable amount
    if (!validateFloat($request->post['payable-amount'])) {
      throw new Exception(trans('error_invoice_payable_amount'));
    }

    // Validate invoice items
    if (!isset($request->post['product-item']) 
      && (isset($request->post['product-item']) || !is_array($request->post['product-item']))) {

      throw new Exception(trans('error_product_item'));
    }

    $due = ((float)$request->post['payable-amount'] - (float)$request->post['paid-amount']) > 0 ? (float)$request->post['payable-amount'] - (float)$request->post['paid-amount'] : 0;
    if (user_group_id() != 1 && ($due > 0 && !has_permission('access', 'create_sell_due'))) {
      throw new Exception(trans('error_create_due_permission'));
    }

    if (INSTALLMENT && $request->post['is_installment_order']) {
      if (!validateInteger($request->post['installment_duration'])) {
        throw new Exception(trans('error_installment_duration'));
      }

      if (!validateInteger($request->post['installment_interval_count'])) {
        throw new Exception(trans('error_installment_interval_count'));
      }

      if (!is_numeric($request->post['installment_count'])) {
        throw new Exception(trans('error_installment_count'));
      }

      if (!is_numeric($request->post['installment_interest_percentage'])) {
        throw new Exception(trans('error_installment_interest_percentage'));
      }

      if (!is_numeric($request->post['installment_interest_amount'])) {
        throw new Exception(trans('error_installment_interest_amount'));
      }
    }

    $product_items  = $request->post['product-item'];

    // Check invoice create permission
    if (user_group_id() != 1 && !has_permission('access', 'create_sell_invoice')) {
      throw new Exception(sprintf(trans('error_create_permission')));
    }

    validate_invoice_items($product_items);

    $Hooks->do_action('Before_Place_POS_Order', $request);

    $invoice_id = $invoice_model->createInvoice($request, $store_id);
    $invoice_info = $invoice_model->getInvoiceInfo($invoice_id);
    $invoice_items = $invoice_model->getInvoiceItems($invoice_id);

    $Hooks->do_action('After_Place_POS_Order', $invoice_info);

  	header('Content-Type: application/json');
  	echo json_encode(array('msg' => trans('text_invoice_create_success'), 'invoice_id' => $invoice_id, 'invoice_info' => $invoice_info, 'invoice_items' => $invoice_items));
	  exit();

  } catch (Exception $e) { 

    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();
  }
}