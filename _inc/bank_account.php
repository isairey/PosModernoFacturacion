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
// If user have not reading permission return error
if (user_group_id() != 1 && !has_permission('access', 'read_bank_account')) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_read_permission')));
  exit();
}

// LOAD BOX MODEL
$bank_account_model = registry()->get('loader')->model('bankaccount');

// Validate post data
function validate_request_data($request) {

  // Bank account name validation
  if (!validateString($request->post['account_name'])) {
      throw new Exception(trans('error_account_name'));
  }

  // Bank account number validation
  if (!validateString($request->post['account_no'])) {
      throw new Exception(trans('error_account_no'));
  }

  // Contact person validation
  if (!validateString($request->post['contact_person'])) {
      throw new Exception(trans('error_contact_person'));
  }

  // Phone number validation
  if (!validateString($request->post['phone_number'])) {
      throw new Exception(trans('error_phone_number'));
  }

  // Store validation
  if (!isset($request->post['account_store']) || empty($request->post['account_store'])) {
    throw new Exception(trans('error_store'));
  }

  // Status validation
  if (!is_numeric($request->post['status'])) {
    throw new Exception(trans('error_status'));
  }

  // Sort order validation
  if (!is_numeric($request->post['sort_order'])) {
    throw new Exception(trans('error_sort_order'));
  }
}

// Check bank_account existance by id
function validate_existance($request, $id = 0)
{
  

  // Check, if bank_account name exist or not
  $statement = db()->prepare("SELECT * FROM `bank_accounts` WHERE `account_name` = ? AND `id` != ?");
  $statement->execute(array($request->post['account_name'], $id));
  if ($statement->rowCount() > 0) {
    throw new Exception(trans('error_account_name_exist'));
  }

  // Check, if bank_account code exist or not
  $statement = db()->prepare("SELECT * FROM `bank_accounts` WHERE `account_no` = ? AND `id` != ?");
  $statement->execute(array($request->post['account_no'], $id));
  if ($statement->rowCount() > 0) {
    throw new Exception(trans('error_account_no_exist'));
  }
}

// Create bank_account
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'CREATE')
{
  try {

    // Check create permission
    if (user_group_id() != 1 && !has_permission('access', 'create_bank_account')) {
      throw new Exception(trans('error_create_permission'));
    }

    // Validate post data
    validate_request_data($request);

    // Validate existance
    validate_existance($request);

    $Hooks->do_action('Before_Create_Bank_Account');

    // Add bank_account
    $id = $bank_account_model->addBankAccount($request->post);

    // Fetch the bank_account info
    $bank_account = $bank_account_model->getBankAccount($id);

    $Hooks->do_action('After_Create_Bank_Account', $bank_account);

    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_success'), 'id' => $id, 'bank_account' => $bank_account));
    exit();

  } catch (Exception $e) { 
    
    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();
  }
}

// Update bank_account
if ($request->server['REQUEST_METHOD'] == 'POST' AND isset($request->post['action_type']) && $request->post['action_type'] == 'UPDATE')
{
  try {

    // Check update permission
    if (user_group_id() != 1 && !has_permission('access', 'update_bank_account')) {
      throw new Exception(trans('error_update_permission'));
    }

    // Validate bank_account id
    if (empty($request->post['id'])) {
      throw new Exception(trans('error_id'));
    }

    $id = $request->post['id'];

    // Validate post data
    validate_request_data($request);

    // Validate existance
    validate_existance($request, $id);

    $Hooks->do_action('Before_Update_Bank_Account', $request);
    
    // Edit bank_account
    $bank_account = $bank_account_model->editBankAccount($id, $request->post);

    $Hooks->do_action('After_Update_Bank_Account', $bank_account);
    
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

// Delete bank_account
if ($request->server['REQUEST_METHOD'] == 'POST' AND isset($request->post['action_type']) && $request->post['action_type'] == 'DELETE') {
  try {

    // Check delete permission
    if (user_group_id() != 1 && !has_permission('access', 'delete_bank_account')) {
      throw new Exception(trans('error_delete_permission'));
    }

    // Validate bank_account id
    if (empty($request->post['id'])) {
      throw new Exception(trans('error_id'));
    }

    $id = $request->post['id'];
    if ($id == 1) {
      throw new Exception(trans('error_unable_to_delete'));
    }

    $Hooks->do_action('Before_Delete_Bank_Account', $request);

    // Delete the bank_account
    $bank_account = $bank_account_model->deleteBankAccount($id);

    $Hooks->do_action('After_Delete_Bank_Account', $bank_account);
    
    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_delete_success')));
    exit();

  } catch(Exception $e) { 
    
    $error_message = $e->getMessage();
    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $error_message));
    exit();
  }
}

// BankAccount create form
if (isset($request->get['action_type']) && $request->get['action_type'] == 'CREATE') 
{
  include 'template/bank_account_create_form.php';
  exit();
}

// BankAccount edit form
if (isset($request->get['account_id']) AND isset($request->get['action_type']) && $request->get['action_type'] == 'EDIT') 
{
  // Fetch account info
  $account = $bank_account_model->getBankAccount($request->get['account_id']);
  include 'template/bank_account_edit_form.php';
  exit();
}


// BankAccount delete form
if (isset($request->get['account_id']) AND isset($request->get['action_type']) && $request->get['action_type'] == 'DELETE') 
{
  // Fetch account info
  $account = $bank_account_model->getBankAccount($request->get['account_id']);
  include 'template/bank_account_del_form.php';
  exit();
}

/**
 *===================
 * START DATATABLE
 *===================
 */

$Hooks->do_action('Before_Showing_Bank_Account_List');

$where_query = 'ba2s.store_id = ' . store_id();
 
// DB table to use
$table = "(SELECT bank_accounts.*, ba2s.status, ba2s.sort_order FROM bank_accounts 
  LEFT JOIN bank_account_to_store ba2s ON (bank_accounts.id = ba2s.account_id) 
  WHERE $where_query GROUP by bank_accounts.id
  ) as bank_accounts";
 
// Table's primary key
$primaryKey = 'id';

$columns = array(
  array(
      'db' => 'id',
      'dt' => 'DT_RowId',
      'formatter' => function( $d, $row ) {
          return 'row_'.$d;
      }
  ),
  array( 'db' => 'id', 'dt' => 'id' ),
  array( 
    'db' => 'account_name',   
    'dt' => 'account_name' ,
    'formatter' => function($d, $row) {
        return $row['account_name'];
    }
  ),
  array( 
    'db' => 'account_details',   
    'dt' => 'account_details' ,
    'formatter' => function($d, $row) {
        return $row['account_details'];
    }
  ),
  array( 
    'db' => 'status',   
    'dt' => 'status',
    'formatter' => function($d, $row) {
      return $row['status'] 
        ? '<span class="label label-success">'.trans('text_active').'</span>' 
        : '<span class="label label-warning">' .trans('text_inactive').'</span>';
    }
  ),
  array(
    'db'        => 'id',
    'dt'        => 'btn_edit',
    'formatter' => function( $d, $row ) {
      return '<button id="edit-account" class="btn btn-sm btn-block btn-primary" type="button" title="'.trans('button_edit').'"><i class="fa fa-fw fa-pencil"></i></button>';
    }
  ),
  array(
    'db'        => 'id',
    'dt'        => 'btn_delete',
    'formatter' => function( $d, $row ) {
      if ($row['id'] == 1) {
        return '<button class="btn btn-sm btn-block btn-default" type="button" title="'.trans('button_delete').'" disabled><i class="fa fa-fw fa-trash"></i></button>';
      }
      return '<button id="delete-account" class="btn btn-sm btn-block btn-danger" type="button" title="'.trans('button_delete').'"><i class="fa fa-fw fa-trash"></i></button>';
    }
  )
); 

echo json_encode(
    SSP::simple($request->get, $sql_details, $table, $primaryKey, $columns)
);

$Hooks->do_action('After_Showing_Bank_Account_List');