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

// Check, if user has reading permission or not
// If user have not reading permission an alert message
if (user_group_id() != 1 && !has_permission('access', 'read_currency')) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_read_permission')));
  exit();
}

// LOAD CURRENCY MODEL
$currency_model = registry()->get('loader')->model('currency');

// Validate post data
function validate_request_data($request) 
{
  // Validate title
  if(!validateString($request->post['title'])) {
    throw new Exception(trans('error_currency_title'));
  }

  // Validate code
  if(!validateString($request->post['code'])) {
    throw new Exception(trans('error_currency_code'));
  }

  // Validate currency left/rightsymbol
  if(!validateString($request->post['symbol_left']) && !validateString($request->post['symbol_right'])) {
    throw new Exception(trans('error_currency_symbol'));
  }

  // Validate decimal place
  if(!validateInteger($request->post['decimal_place'])) {
    throw new Exception(trans('error_currency_decimal_place'));
  }

  // Validate currency_store
  if (!isset($request->post['currency_store']) || empty($request->post['currency_store'])) {
    throw new Exception(trans('error_store'));
  }

  // Validate status
  if (!is_numeric($request->post['status'])) {
    throw new Exception(trans('error_status'));
  }

  // Sort order validation
  if (!is_numeric($request->post['sort_order'])) {
    throw new Exception(trans('error_sort_order'));
  }
}

// Check currency existance by id
function validate_existance($request, $id = 0)
{
  

  // Check currency title, is exist?
  $statement = db()->prepare("SELECT * FROM `currency` WHERE (`title` = ? OR `code` = ?) AND `currency_id` != ?");
  $statement->execute(array($request->post['title'], $request->post['code'], $id));
  if ($statement->rowCount() > 0) {
    throw new Exception(trans('error_payment_code_or_title_exist'));
  }
}

// Create currency
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'CREATE')
{
  try {

    // Create permission check
    if (user_group_id() != 1 && !has_permission('access', 'create_currency')) {
      throw new Exception(trans('error_read_permission'));
    }
    
    // Validate post data
    validate_request_data($request);

    // Validate existance
    validate_existance($request);

    $Hooks->do_action('Before_Create_Currency', $request);

    // Insert currency into database    
    $currency_id = $currency_model->addCurrency($request->post);

    // get currency
    $currency = $currency_model->getCurrency($currency_id);

    $Hooks->do_action('After_Create_Currency', $currency);

    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_success'), 'id' => $currency_id, 'currency' => $currency));
    exit();

  } catch(Exception $e) { 

    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();
  }
} 

// Update currency
if($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'UPDATE')
{
  try {

    // Check update permission
    if (user_group_id() != 1 && !has_permission('access', 'update_currency')) {
      throw new Exception(trans('error_update_permission'));
    }

    // Validate currency id
    if (empty($request->post['currency_id'])) {
      throw new Exception(trans('error_currency_id'));
    }

    $id = $request->post['currency_id'];

    if (DEMO && $id == 1) {
      throw new Exception(trans('error_update_permission'));
    }

    // Validate post data
    validate_request_data($request);

    // Validate existance
    validate_existance($request, $id);

    $Hooks->do_action('Before_Update_Currency', $request);
    
    // Edit currency        
    $currency = $currency_model->editCurrency($id, $request->post);

    $Hooks->do_action('After_Update_Currency', $currency);

    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_update_success'), 'id' => $id));
    exit();

  } catch (Exception $e) { 

    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();
  }
}

// Delete currency
if($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'DELETE') 
{
  try {

    // Check delete permission
    if (user_group_id() != 1 && !has_permission('access', 'delete_currency')) {
      throw new Exception(trans('error_delete_permission'));
    }

    // validte currency id
    if (empty($request->post['currency_id'])) {
      throw new Exception(trans('error_currency_id'));
    }

    $id = $request->post['currency_id'];

    if (DEMO && $id == 1) {
      throw new Exception(trans('error_delete_permission'));
    }

    // active currency can not be deleted
    if ($id == currency_id()) {
      throw new Exception(trans('error_delete_active_currency'));
    }

    $Hooks->do_action('Before_Delete_Currency', $request);

    // Delete currency
    $currency = $currency_model->deleteCurrency($id);

    $Hooks->do_action('After_Delete_Currency', $currency);
    
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

// Currency edit form
if (isset($request->get['currency_id']) && isset($request->get['action_type']) && $request->get['action_type'] == 'EDIT') {
    
    $currency_id = (int)$request->get['currency_id'];
    // Fetch currency info
    $currency = $currency_model->getCurrency($currency_id);
    include 'template/currency_form.php';
    exit();
}


/**
 *===================
 * START DATATABLE
 *===================
 */

$Hooks->do_action('Before_Showing_Currency_List');
 
$where_query = 'c2s.store_id = '.store_id();
 
// DB table to use
$table = "(SELECT currency.*, c2s.status, c2s.sort_order FROM currency 
  LEFT JOIN currency_to_store c2s ON (currency.currency_id = c2s.currency_id) 
  WHERE $where_query GROUP by currency.currency_id
  ) as currency";
 
// Table's primary key
$primaryKey = 'currency_id';

$columns = array(
  array(
      'db' => 'currency_id',
      'dt' => 'DT_RowId',
      'formatter' => function( $d, $row ) {
          return 'row_'.$d;
      }
  ),
  array( 'db' => 'currency_id', 'dt' => 'currency_id' ),
  array( 
    'db' => 'title',   
    'dt' => 'title' ,
    'formatter' => function($d, $row) {
        return ucfirst($row['title']);
    }
  ),
  array( 'db' => 'code',  'dt' => 'code' ),
  array( 'db' => 'symbol_left',  'dt' => 'symbol_left' ),
  array( 'db' => 'symbol_right',  'dt' => 'symbol_right' ),
  array( 'db' => 'decimal_place',  'dt' => 'decimal_place' ),
  array( 
    'db' => 'status',   
    'dt' => 'status' ,
    'formatter' => function($d, $row) {
        return $row['status'] == 1 ? '<span class="label label-info">'.trans('text_enabled').'</span>' : '<span class="label label-warning">'.trans('text_disabled').'</span>';
    }
  ),
  array( 
    'db' => 'status',   
    'dt' => 'btn_edit' ,
    'formatter' => function($d, $row) {
      if (DEMO && $row['currency_id'] == 1) {          
        return'<button class="btn btn-sm btn-block btn-default" type="button" disabled><i class="fa fa-pencil"></i></button>';
      }
      return '<button id="edit-currency" class="btn btn-sm btn-block btn-primary" type="button" title="'.trans('button_edit').'"><i class="fa fa-fw fa-pencil"></i></button>';
    }
  ),
  array( 
    'db' => 'status',   
    'dt' => 'btn_delete' ,
    'formatter' => function($d, $row) {
      if (DEMO && $row['currency_id'] == 1) {          
        return'<button class="btn btn-sm btn-block btn-default" type="button" disabled><i class="fa fa-trash"></i></button>';
      }
      if ($row['currency_id'] == currency_id()) {
        return '<button id="delete-currency" class="btn btn-sm btn-block btn-default" type="button" disabled><i class="fa fa-fw fa-trash" title="'.trans('button_delete').'"></i></button>';
      }

      return '<button id="delete-currency" class="btn btn-sm btn-block btn-danger" type="button"><i class="fa fa-fw fa-trash" title="'.trans('button_delete').'"></i></button>';
    }
  ),
  array( 
    'db' => 'code',   
    'dt' => 'btn_activate' ,
    'formatter' => function($d, $row) use($currency) {
        $button = "";
        if ($row['status'] == 1) {
            if ($currency->getCode() == $row['code']) {
                $button = '<button class="btn btn-sm  btn-block btn-info" type="button" disabled><i class="fa fa-fw fa-check"></i>'.trans('button_activated').'</button>';
            } else {
                $button = '<button  type="button" class="btn btn-sm btn-block btn-success currency-change" data-code="'.$row['code'].'" data-loading-text="Applying..."><i class="fa fa-fw fa-check"></i>'.trans('button_activate').'</button>';
            }
        }
        return $button;
    }
  )
); 

echo json_encode(
    SSP::simple($request->get, $sql_details, $table, $primaryKey, $columns)
);

$Hooks->do_action('After_Showing_Currency_List');

/**
 *===================
 * END DATATABLE
 *===================
 */