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
if (user_group_id() != 1 && !has_permission('access', 'read_quotation')) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_read_permission')));
  exit();
}

$store_id     = store_id();

// LOAD INVOICE MODEL
$quotation_model = registry()->get('loader')->model('quotation');

// Validate customer post data
function validate_request_data($request) 
{
  // Validate date
  if (!isItValidDate($request->post['date'])) {
    throw new Exception(trans('error_date'));
  }

  // Validate reference no
  if (!validateString($request->post['reference_no'])) {
    throw new Exception(trans('error_reference_no'));
  }

  // Validate customer id
  if (!validateInteger($request->post['customer_id'])) {
    throw new Exception(trans('error_customer'));
  }

  // Validate status
  if (!validateString($request->post['status'])) {
    throw new Exception(trans('error_status'));
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

  // Validate sub-total
  if (!validateFloat($request->post['total-amount'])) {
    throw new Exception(trans('error_total_amount'));
  }

  // Validate tax amount
  if (!is_numeric($request->post['total-tax'])) {
    throw new Exception(trans('error_tax_amount'));
  }
}

// Validate quotation items
function validate_quotation_items($items)
{
  foreach ($items as $product) 
  {
    // Validate product id
    if (!validateInteger($product['item_id'])) {
      throw new Exception(trans('error_invalid_item_id'));
    }

    // Fetch product item
    $the_product = get_the_product($product['item_id'], null, store_id());

    // Check, product item exist or not
    if (!$the_product) {
      throw new Exception(trans('error_product_not_found'));
    }

    // Validate product name
    if (!validateString($product['item_name'])) {
      throw new Exception(trans('error_product_name'));
    }

    // Validate product price
    if (!validateFloat($product['unit_price'])) {
      throw new Exception(trans('error_unit_price'));
    }

    // Validate product quantity
    if (!validateFloat($product['quantity'])) {
      throw new Exception(trans('error_product_quantity'));
    }
  }
}

if ($request->server['REQUEST_METHOD'] == 'POST' && $request->get['action_type'] == 'CREATE')
{
  try {

    if (user_group_id() != 1 && !has_permission('access', 'create_quotation')) {
      throw new Exception(trans('error_create_permission'));
    }

    validate_request_data($request);

    // Validate invoice items
    if (!isset($request->post['products']) 
      && (isset($request->post['products']) || !is_array($request->post['products']))) {

      throw new Exception(trans('error_product_item'));
    }

    $reference_no   = $request->post['reference_no'];

    $is_installment_order = isset($request->post['is_installment_order']) ? $request->post['is_installment_order'] : '';
    if (INSTALLMENT && $is_installment_order) {
      if (!validateInteger($request->post['installment_duration'])) {
        throw new Exception(trans('error_installment_duration'));
      }

      if (!validateInteger($request->post['installment_interval_count'])) {
        throw new Exception(trans('error_installment_interval_count'));
      }

      if (!validateInteger($request->post['installment_count'])) {
        throw new Exception(trans('error_installment_count'));
      }

      if (!validateFloat($request->post['installment_interest_percentage'])) {
        throw new Exception(trans('error_installment_interest_percentage'));
      }

      if (!validateFloat($request->post['installment_interest_amount'])) {
        throw new Exception(trans('error_installment_interest_amount'));
      }
    }

    $product_items  = $request->post['products'];
    $statement = db()->prepare("SELECT * FROM `quotation_info` WHERE `store_id` = ? AND `reference_no` = ?");
    $statement->execute(array($store_id, $reference_no));
    $quotation_info = $statement->fetch(PDO::FETCH_ASSOC);

    if ($quotation_info) {
      throw new Exception(trans('error_reference_number_exists'));
    }

    validate_quotation_items($product_items);

    $Hooks->do_action('Before_Create_Quotation', $request);

    // Create Quotation
    $reference_no = $quotation_model->createQuotation($request, $store_id);

    // Get Quotation
    $quotation_info = $quotation_model->getQuotationInfo($reference_no);
    $quotation_items = $quotation_model->getQuotationItems($reference_no);

    $Hooks->do_action('After_Create_Quotation', $quotation_info);

    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_quotation_create_success'), 'reference_no' => $reference_no, 'quotation_info' => $quotation_info, 'quotation_items' => $quotation_items, 'id' => $quotation_info['info_id']));
      exit();

  } catch (Exception $e) { 

    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();
  }
}


if ($request->server['REQUEST_METHOD'] == 'POST' && $request->get['action_type'] == 'UPDATE')
{
  try {

    if (user_group_id() != 1 && !has_permission('access', 'update_quotation')) {
      throw new Exception(trans('error_update_permission'));
    }

    validate_request_data($request);

    $reference_no   = $request->post['reference_no'];

    // Validate invoice items
    if (!isset($request->post['products']) 
      && (isset($request->post['products']) || !is_array($request->post['products']))) {

      throw new Exception(trans('error_product_item'));
    }

    $is_installment_order = isset($request->post['is_installment_order']) ? $request->post['is_installment_order'] : '';
    if (INSTALLMENT && $is_installment_order) {
      if (!validateInteger($request->post['installment_duration'])) {
        throw new Exception(trans('error_installment_duration'));
      }

      if (!validateInteger($request->post['installment_interval_count'])) {
        throw new Exception(trans('error_installment_interval_count'));
      }

      if (!validateInteger($request->post['installment_count'])) {
        throw new Exception(trans('error_installment_count'));
      }

      if (!validateFloat($request->post['installment_interest_percentage'])) {
        throw new Exception(trans('error_installment_interest_percentage'));
      }

      if (!validateFloat($request->post['installment_interest_amount'])) {
        throw new Exception(trans('error_installment_interest_amount'));
      }
    }

    $product_items  = $request->post['products'];
    $statement = db()->prepare("SELECT * FROM `quotation_info` WHERE `store_id` = ? AND `reference_no` = ?");
    $statement->execute(array($store_id, $reference_no));
    $quotation_info = $statement->fetch(PDO::FETCH_ASSOC);
    if (!$quotation_info) {
      throw new Exception(trans('error_reference_not_exists'));
    }

    validate_quotation_items($product_items);

    $Hooks->do_action('Before_Update_Quotation', $request);

    // Update Quotation
    $reference_no = $quotation_model->updateQuotation($reference_no, $request, $store_id);

    // Get Quotation
    $quotation_info = $quotation_model->getQuotationInfo($reference_no);
    $quotation_items = $quotation_model->getQuotationItems($reference_no);

    $Hooks->do_action('After_Update_Quotation', $quotation_info);

    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_quotation_update_success'), 'reference_no' => $reference_no, 'quotation_info' => $quotation_info, 'quotation_items' => $quotation_items, 'id' => $quotation_info['info_id']));
    exit();

  } catch (Exception $e) { 

    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();
  }
}


// Delete quotation
if($request->server['REQUEST_METHOD'] == 'POST' && $request->get['action_type'] == 'DELETE')
{
  try {
        
    // Check permission
    if (user_group_id() != 1 && !has_permission('access', 'delete_quotation')) {
      throw new Exception(trans('error_delete_permission'));
    }

    // Validate quotation id
    if (empty($request->post['reference_no'])) {
        throw new Exception(trans('error_reference_no'));
    }

    $reference_no = $request->post['reference_no'];

    // Check, if quotation exist or not
    $quotation_info = $quotation_model->getQuotationInfo($reference_no);
    if (!$quotation_info) {
        throw new Exception(trans('error_quotation_not_found'));
    }

    // Fetch selling quotation item
    $statement = db()->prepare("SELECT * FROM `quotation_item` WHERE `store_id` = ? AND `reference_no` = ?");
    $statement->execute(array($store_id, $reference_no));
    $quotation_items = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Check, if quotation item exist or not
    if (!$statement->rowCount()) {
        throw new Exception(trans('error_quotation_item'));
    }

    $Hooks->do_action('Before_Delete_Quotation', $request);

    // Delete items
    $statement = db()->prepare("DELETE FROM `quotation_item` WHERE `store_id` = ? AND `reference_no` = ?");
    $statement->execute(array($store_id, $reference_no));

    // Delete quotation price info
    $statement = db()->prepare("DELETE FROM  `quotation_price` WHERE `store_id` = ? AND `reference_no` = ? LIMIT 1");
    $statement->execute(array($store_id, $reference_no));

    // Delete quotation info
    $statement = db()->prepare("DELETE FROM  `quotation_info` WHERE `store_id` = ? AND `reference_no` = ? LIMIT 1");
    $statement->execute(array($store_id, $reference_no));

    $Hooks->do_action('After_Delete_Quotation', $request);

    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_delete_success')));
    exit();

  } catch(Exception $e) { 

    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();
  }
}

// View quotation
if (isset($request->get['reference_no']) && $request->get['action_type'] == 'VIEW') 
{
    $reference_no = $request->get['reference_no'];
    $quotation = $quotation_model->getQuotationInfo($reference_no);
    $quotation_items = $quotation_model->getQuotationItems($reference_no);
    include ROOT.'/_inc/template/quotation_view.php';
    exit();
}

// Download as PDF
if (isset($request->get['reference_no']) && $request->get['action_type'] == 'DOWNLOAD_AS_PDF') 
{

  $reference_no = $request->get['reference_no'];
  $quotation = $quotation_model->getQuotationInfo($reference_no);
  $quotation_items = $quotation_model->getQuotationItems($reference_no);

  require_once ROOT.'/_inc/vendor/mpdf/autoload.php';
  $mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8', 
    'format' => [175, 228], 
    'margin_top' => '1', 
    'margin_footer' => '10', 
    'margin_left' => '1', 
    'margin_right' => '15']);
  $mpdf->allow_charset_conversion = true;
  $mpdf->charset_in = 'UTF-8';
  $mpdf->autoScriptToLang = true;
  $mpdf->autoLangToFont = true;

  ob_start();
  include ROOT.'/_inc/template/quotation/header.php';
  $header = ob_get_clean();

  ob_start();
  include ROOT.'/_inc/template/quotation/footer.php';
  $footer = ob_get_clean();

  ob_start();
  include ROOT.'/_inc/template/quotation_view.php';
  $body = ob_get_clean();

  $mpdf->WriteHTML($header.$body.$footer);

  $mpdf->Output($reference_no.'-Quotation.pdf', 'D');
  exit();
}


/**
 *===================
 * START DATATABLE
 *===================
 */

$Hooks->do_action('Before_Showing_Quotation_List');

$where_query = "quotation_info.store_id = '{$store_id}' AND is_order = 0";
if (isset($request->get['type']) && ($request->get['type'] != 'undefined') && $request->get['type'] != '') {
    switch ($request->get['type']) {
        case 'sent':
            $where_query .= " AND quotation_info.status = 'sent'";
            break;
        case 'pending':
            $where_query .= " AND quotation_info.status = 'pending'";
            break;
        case 'complete':
            $where_query .= " AND quotation_info.status = 'complete'";
            break;
        default:
            # code...
            break;
    }
};
if ($request->get['type'] != 'all_due' && $request->get['type'] != 'all_invoice') {
    $from = from();
    $to = to();
    $where_query .= date_range_quotation_filter($from, $to);
}

// DB table to use
$table = "(SELECT quotation_info.*, quotation_price.payable_amount FROM `quotation_info` 
  LEFT JOIN `quotation_price` ON (quotation_info.reference_no = quotation_price.reference_no) 
  WHERE $where_query) as quotation_info";

// Table's primary key
$primaryKey = 'info_id';

$columns = array(
    array(
      'db' => 'info_id',
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
            $o = $row['reference_no'];        
            return $o;
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
            return '';
        }
    ),
    array(
        'db' => 'customer_id',
        'dt' => 'customer_name',
        'formatter' => function( $d, $row) {
            $customer = get_the_customer($row['customer_id']);
            if (isset($customer['customer_id'])) {
              return '<a href="customer_profile.php?customer_id=' . $customer['customer_id'] . '">' . $customer['customer_name'] . '</a>';
            }
            return '';
        }
    ),
    array(
        'db' => 'payable_amount',
        'dt' => 'payable_amount',
        'formatter' => function( $d, $row) {
          return currency_format($row['payable_amount']);
        }
    ),
    array(
        'db' => 'status',
        'dt' => 'status',
        'formatter' => function( $d, $row) {
          return $row['status'];
        }
    ),
    array(
        'db' => 'reference_no',
        'dt' => 'action',
        'formatter' => function($d, $row){
          $o = '<div class="btn-group btn-block">
            <button type="button" class="btn btn-block btn-primary dropdown-toggle" data-toggle="dropdown">
              <span class="fa fa-fw fa-cog"></span> '.trans('button_action').' <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu" style="right:0;left:auto;">';
            if (user_group_id() == 1 || has_permission('access', 'read_quotation')) {
              $o .= '<li><a id="view-quotation-btn" href="quotation.php">'.trans('button_details').'</a></li>';
            }
            if ($row['status'] != 'complete' && user_group_id() == 1 || has_permission('access', 'update_quotation')) {
              $o .= '<li><a href="quotation_edit.php?reference_no='.$row['reference_no'].'"> '.trans('button_edit').'</a></li>';
            }
            if ($row['status'] != 'complete' && user_group_id() == 1 || has_permission('access', 'create_sell_invoice')) {
              $o .= '<li><a href="pos.php?qref='.$row['reference_no'].'">'.trans('button_create_sell').'</a></li>';
            }
            if ($row['status'] != 'complete' && user_group_id() == 1 || has_permission('access', 'delete_quotation')) {
              $o .= '<li><a id="delete-quotation" href="quotation.php" data-loading-text="Processing...">'.trans('button_delete').'</a></li>';
            }
          $o .= '</ul></div>';
          return $o;
        }
    ),
);

echo json_encode(
    SSP::simple($request->get, $sql_details, $table, $primaryKey, $columns)
);

$Hooks->do_action('After_Showing_Quotation_List');

/**
 *===================
 * END DATATABLE
 *===================
 */