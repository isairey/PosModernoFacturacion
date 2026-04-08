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

$store_id = store_id();
$user_id = user_id();

// Validate post data
function validate_request_data($request) 
{

  // Validate Invoice ID
  if (!validateString($request->post['invoice-id'])) {
     throw new Exception(trans('error_invoice_id'));
  }

  // Validate Customer ID
  if (!validateString($request->post['customer-id'])) {
    throw new Exception(trans('error_customer_id'));
  }

  // Validate Payment Method ID
  if (!validateInteger($request->post['pmethod-id'])) {
    throw new Exception(trans('error_payment_method'));
  }
}

if ($request->server['REQUEST_METHOD'] == 'POST' && $request->get['action_type'] == 'PAYMENT')
{
  try {

    if (user_group_id() != 1 AND !has_permission('access', 'sell_payment')) {
      throw new Exception(trans('error_payment_permission'));
    }

    // Validate post data
    validate_request_data($request);

    $invoice_model = registry()->get('loader')->model('invoice');
    $Hooks->do_action('Before_Payment', $request);

    $invoice_model->duePaid($request->post, $store_id);

    $Hooks->do_action('After_Payment', $request);
    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_sell_due_paid_success')));
    exit();

  } catch (Exception $e) { 
    
    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();
  }

}

// Payment method fields
if (isset($request->get['pmethod_id']) && $request->get['action_type'] == 'FIELD') 
{
  $pmethod_model = registry()->get('loader')->model('pmethod');
	$pmethod_id = $request->get['pmethod_id'];
	$pmethod = $pmethod_model->getPMethod($pmethod_id);
	if ($pmethod && file_exists(ROOT.'/_inc/template/partials/pmethodfield/'.strtolower(str_replace(' ', '_',$pmethod['name'])).'_field.php')) {
		include ROOT.'/_inc/template/partials/pmethodfield/'.strtolower(str_replace(' ', '_',$pmethod['name'])).'_field.php';
	}
  exit();
}


if (isset($request->get['action_type']) && $request->get['action_type'] == 'ORDERDETAILS')
{
  $invoice_id = $request->get['invoice_id'];
  if (!$invoice_id) {
    throw new Exception(trans('error_invoice_id'));
  }
  $order = array();
  $items = array();
  $where_query = "`selling_info`.`store_id` = ? AND `selling_info`.`invoice_id` = '{$invoice_id}'";
  $statement = db()->prepare("SELECT * FROM `selling_info` 
        LEFT JOIN `selling_price` ON (`selling_price`.`invoice_id` = `selling_info`.`invoice_id`)
        WHERE $where_query");
  $statement->execute(array(store_id()));
  $order = $statement->fetch(PDO::FETCH_ASSOC);
  
  $invoice_model = registry()->get('loader')->model('invoice');
  $payment_model = registry()->get('loader')->model('payment');
  $items = $invoice_model->getInvoiceItems($order['invoice_id'], store_id());
  $payments = $payment_model->getPayments($order['invoice_id'], store_id());
  $order['customer_name'] = get_the_customer($order['customer_id'], 'customer_name');
  $order['items']     = $items;
  $order['table']     = '';
  $order['payments']  = $payments;

  header('Content-Type: application/json');
  echo json_encode(array('msg' => trans('text_success'), 'order' => $order));
  exit();
}