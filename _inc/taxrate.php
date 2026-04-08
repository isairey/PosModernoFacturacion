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
if (user_group_id() != 1 && !has_permission('access', 'read_taxrate')) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_read_permission')));
  exit();
}

// LOAD BOX MODEL
$taxrate_model = registry()->get('loader')->model('taxrate');

// Validate post data
function validate_request_data($request) {

  // Taxrate name validation
  if (!validateString($request->post['taxrate_name'])) {
      throw new Exception(trans('error_taxrate_name'));
  }

  // Taxrate code validation
  if (!validateString($request->post['code_name'])) {
      throw new Exception(trans('error_code_name'));
  }

  // Taxrate validation
  if (!is_numeric($request->post['taxrate'])) {
      throw new Exception(trans('error_taxrate'));
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

// Check taxrate existance by id
function validate_existance($request, $id = 0)
{
  

  // Check, if taxrate name exist or not
  $statement = db()->prepare("SELECT * FROM `taxrates` WHERE `taxrate_name` = ? AND `taxrate_id` != ?");
  $statement->execute(array($request->post['taxrate_name'], $id));
  if ($statement->rowCount() > 0) {
    throw new Exception(trans('error_taxrate_name_exist'));
  }

  // Check, if taxrate code exist or not
  $statement = db()->prepare("SELECT * FROM `taxrates` WHERE `code_name` = ? AND `taxrate_id` != ?");
  $statement->execute(array($request->post['code_name'], $id));
  if ($statement->rowCount() > 0) {
    throw new Exception(trans('error_code_name_exist'));
  }
}

// Create taxrate
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'CREATE')
{
  try {

    // Check create permission
    if (user_group_id() != 1 && !has_permission('access', 'create_taxrate')) {
      throw new Exception(trans('error_create_permission'));
    }

    // Validate post data
    validate_request_data($request);

    // Validate existance
    validate_existance($request);

    $Hooks->do_action('Before_Create_Taxrate', $request);

    // Add taxrate
    $taxrate_id = $taxrate_model->addTaxrate($request->post);

    // Fetch the taxrate info
    $taxrate = $taxrate_model->getTaxrate($taxrate_id);

    $Hooks->do_action('After_Create_Taxrate', $taxrate);

    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_success'), 'id' => $taxrate_id, 'taxrate' => $taxrate));
    exit();

  } catch (Exception $e) { 
    
    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();
  }
}

// Update taxrate
if ($request->server['REQUEST_METHOD'] == 'POST' AND isset($request->post['action_type']) && $request->post['action_type'] == 'UPDATE')
{
  try {

    // Check update permission
    if (user_group_id() != 1 && !has_permission('access', 'update_taxrate')) {
      throw new Exception(trans('error_update_permission'));
    }

    // Validate taxrate id
    if (empty($request->post['taxrate_id'])) {
      throw new Exception(trans('error_taxrate_id'));
    }

    $id = $request->post['taxrate_id'];

    if (DEMO && $id == 1) {
      throw new Exception(trans('error_update_permission'));
    }

    // Validate post data
    validate_request_data($request);

    // Validate existance
    validate_existance($request, $id);

    $Hooks->do_action('Before_Update_Taxrate', $request);
    
    // Edit taxrate
    $taxrate = $taxrate_model->editTaxrate($id, $request->post);

    $Hooks->do_action('After_Update_Taxrate', $taxrate);
    
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

// Delete taxrate
if ($request->server['REQUEST_METHOD'] == 'POST' AND isset($request->post['action_type']) && $request->post['action_type'] == 'DELETE') {
  try {

    // Check delete permission
    if (user_group_id() != 1 && !has_permission('access', 'delete_taxrate')) {
      throw new Exception(trans('error_delete_permission'));
    }

    // Validate taxrate id
    if (empty($request->post['taxrate_id'])) {
      throw new Exception(trans('error_taxrate_id'));
    }

    $id = $request->post['taxrate_id'];
    $new_taxrate_id = $request->post['new_taxrate_id'];

    // Validate delete action
    if (empty($request->post['delete_action'])) {
      throw new Exception(trans('error_delete_action'));
    }

    if ($request->post['delete_action'] == 'insert_to' && empty($new_taxrate_id)) {
      throw new Exception(trans('error_delete_taxrate_name'));
    }

    $Hooks->do_action('Before_Delete_Taxrate', $request);

    $statement = db()->prepare("UPDATE `product_to_store` SET `taxrate_id` = ? WHERE `taxrate_id` = ?");
    $statement->execute(array($new_taxrate_id, $id));

    $statement = db()->prepare("UPDATE `quotation_item` SET `taxrate_id` = ? WHERE `taxrate_id` = ?");
    $statement->execute(array($new_taxrate_id, $id));

    $statement = db()->prepare("UPDATE `selling_item` SET `taxrate_id` = ? WHERE `taxrate_id` = ?");
    $statement->execute(array($new_taxrate_id, $id));

    $statement = db()->prepare("UPDATE `holding_item` SET `taxrate_id` = ? WHERE `taxrate_id` = ?");
    $statement->execute(array($new_taxrate_id, $id));

    // Delete the taxrate
    $taxrate = $taxrate_model->deleteTaxrate($id);

    $Hooks->do_action('After_Delete_Taxrate', $taxrate);
    
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

// Taxrate create form
if (isset($request->get['action_type']) && $request->get['action_type'] == 'CREATE') 
{
  include 'template/taxrate_create_form.php';
  exit();
}

// Taxrate edit form
if (isset($request->get['taxrate_id']) AND isset($request->get['action_type']) && $request->get['action_type'] == 'EDIT') 
{
  $taxrate = $taxrate_model->getTaxrate($request->get['taxrate_id']);
  include 'template/taxrate_edit_form.php';
  exit();
}


// Taxrate delete form
if (isset($request->get['taxrate_id']) AND isset($request->get['action_type']) && $request->get['action_type'] == 'DELETE') 
{
  $taxrate = $taxrate_model->getTaxrate($request->get['taxrate_id']);
  include 'template/taxrate_del_form.php';
  exit();
}

/**
 *===================
 * START DATATABLE
 *===================
 */

$Hooks->do_action('Before_Showing_Taxrate_List');
 
// DB table to use
$table = "taxrates";
 
// Table's primary key
$primaryKey = 'taxrate_id';
$columns = array(
  array(
      'db' => 'taxrate_id',
      'dt' => 'DT_RowId',
      'formatter' => function( $d, $row ) {
          return 'row_'.$d;
      }
  ),
  array( 'db' => 'taxrate_id', 'dt' => 'taxrate_id' ),
  array( 
    'db' => 'taxrate_name',   
    'dt' => 'taxrate_name' ,
    'formatter' => function($d, $row) {
        return ucfirst($row['taxrate_name']);
    }
  ),
  array( 
    'db' => 'code_name',   
    'dt' => 'code_name' ,
    'formatter' => function($d, $row) {
        return $row['code_name'];
    }
  ),
  array( 
    'db' => 'taxrate',   
    'dt' => 'taxrate' ,
    'formatter' => function($d, $row) {
        return currency_format($row['taxrate']);
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
    'db'        => 'taxrate_id',
    'dt'        => 'btn_edit',
    'formatter' => function( $d, $row ) {
      if (DEMO && $row['taxrate_id'] == 1) {          
        return'<button class="btn btn-sm btn-block btn-default" type="button" disabled><i class="fa fa-pencil"></i></button>';
      }
      return '<button id="edit-taxrate" class="btn btn-sm btn-block btn-primary" type="button" title="'.trans('button_edit').'"><i class="fa fa-fw fa-pencil"></i></button>';
    }
  ),
  array(
    'db'        => 'taxrate_id',
    'dt'        => 'btn_delete',
    'formatter' => function( $d, $row ) {
      if ($row['taxrate_id'] == 1) {
        return '<button class="btn btn-sm btn-block btn-default" type="button" disabled><i class="fa fa-fw fa-trash"></i></button>';
      }
      return '<button id="delete-taxrate" class="btn btn-sm btn-block btn-danger" type="button" title="'.trans('button_delete').'"><i class="fa fa-fw fa-trash"></i></button>';
    }
  )
); 

echo json_encode(
    SSP::simple($request->get, $sql_details, $table, $primaryKey, $columns)
);

$Hooks->do_action('After_Showing_Taxrate_List');

/**
 *===================
 * END DATATABLE
 *===================
 */