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
if (user_group_id() != 1 && !has_permission('access', 'read_pmethod')) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_read_permission')));
  exit();
}

// LOAD PAYMENTMETHOD MODAL
$pmethod_model = registry()->get('loader')->model('pmethod');
$store_id = store_id();
$user_id = user_id();

// Validate post data
function validate_request_data($request) 
{
  // Validate name
  if (!validateString($request->post['pmethod_name'])) {
    throw new Exception(trans('error_pmethod_name'));
  }

  // Validate name
  if (!validateString($request->post['code_name'])) {
    throw new Exception(trans('error_code_name'));
  }

  // Validate name
  if (!validateString($request->post['code_name'])) {
    throw new Exception(trans('error_code_name'));
  }

  // Validate store
  if (!isset($request->post['pmethod_store']) || empty($request->post['pmethod_store'])) {
    throw new Exception(trans('error_store'));
  }

  // Validate status
  if (!is_numeric($request->post['status'])) {
    throw new Exception(trans('error_status'));
  }

  // Validate sort order
  if (!is_numeric($request->post['sort_order'])) {
    throw new Exception(trans('error_sort_order'));
  }
}

// Check, if pmethod method exist or not
function validate_existance($request, $id = 0)
{
  

  // Check, if pmethod method is exist or not
  $statement = db()->prepare("SELECT * FROM `pmethods` WHERE `name` = ? AND `code_name` = ? AND `pmethod_id` != ?");
  $statement->execute(array($request->post['pmethod_name'], $request->post['code_name'], $id));
  if ($statement->rowCount() > 0) {
    throw new Exception(trans('error_pmethod_exist'));
  }
}

// Create pmethod method
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'CREATE')
{
  try {

    // Create permission check
    if (user_group_id() != 1 && !has_permission('access', 'create_pmethod')) {
      throw new Exception(trans('error_read_permission'));
    }
    
    // Validate post data
    validate_request_data($request);

    // Validate existance
    validate_existance($request);

    $Hooks->do_action('Before_Create_PMethod');

    // Insert new pmethod into database
    $pmethod_id = $pmethod_model->addPmethod($request->post);

    // Get pmethod method
    $pmethod = $pmethod_model->getPmethod($pmethod_id);

    $Hooks->do_action('After_Create_PMethod', $pmethod);

    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_success'), 'id' => $pmethod_id, 'pmethod' => $pmethod));
    exit();
  }
  catch(Exception $e) { 
    
    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();
  }
} 

// Update pmethod method
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'UPDATE')
{
  try {

    // Check delete permision
    if (user_group_id() != 1 && !has_permission('access', 'update_pmethod')) {
      throw new Exception(trans('error_update_permission'));
    }

    // Validate product id
    if (empty($request->post['pmethod_id'])) {
      throw new Exception(trans('error_pmethod_id'));
    }

    $id = $request->post['pmethod_id'];

    if (DEMO && $id == 1) {
      throw new Exception(trans('error_update_permission'));
    }

    // Validate post data
    validate_request_data($request);

    // Validate existance
    validate_existance($request, $id);

    $Hooks->do_action('Before_Update_PMethod', $request);
    
    // Edit pmethod method
    $pmethod = $pmethod_model->editPmethod($id, $request->post);

    $Hooks->do_action('After_Update_PMethod', $pmethod);

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


// Delete pmethod method
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'DELETE') 
{
  try {

    // Check delete permission
    if (user_group_id() != 1 && !has_permission('access', 'delete_pmethod')) {
      throw new Exception(trans('error_delete_permission'));
    }

    // Validate pmethod id
    if (!validateInteger($request->post['pmethod_id'])) {
      throw new Exception(trans('error_pmethod_id'));
    }

    $id = $request->post['pmethod_id'];
    $new_pmethod_id = $request->post['new_pmethod_id'];

    if (DEMO && $id == 1) {
      throw new Exception(trans('error_delete_permission'));
    }

    if ($request->post['delete_action'] == 'insert_to' && !validateInteger($new_pmethod_id)) {
      throw new Exception(trans('error_pmethod_name'));
    }

    $Hooks->do_action('Before_Delete_PMethod', $request);

    $belongs_stores = $pmethod_model->getBelongsStore($id);
    foreach ($belongs_stores as $the_store) {

      // Check if relationship exist or not
      $statement = db()->prepare("SELECT * FROM `pmethod_to_store` WHERE `ppmethod_id` = ? AND `store_id` = ?");
      $statement->execute(array($new_pmethod_id, $the_store['store_id']));
      if ($statement->rowCount() > 0) continue;

      // Create relationship
      $statement = db()->prepare("INSERT INTO `pmethod_to_store` SET `ppmethod_id` = ?, `store_id` = ?");
      $statement->execute(array($new_pmethod_id, $the_store['store_id']));
    }

    if ($request->post['delete_action'] == 'insert_to') {

      $statement = db()->prepare("UPDATE `selling_info` SET `pmethod_id` = ? WHERE `pmethod_id` = ?");
      $statement->execute(array($new_pmethod_id, $id));

      $statement = db()->prepare("UPDATE `sell_logs` SET `pmethod_id` = ? WHERE `pmethod_id` = ?");
      $statement->execute(array($new_pmethod_id, $id));

      $statement = db()->prepare("UPDATE `purchase_logs` SET `pmethod_id` = ? WHERE `pmethod_id` = ?");
      $statement->execute(array($new_pmethod_id, $id));

      $statement = db()->prepare("UPDATE `customer_transactions` SET `pmethod_id` = ? WHERE `pmethod_id` = ?");
      $statement->execute(array($new_pmethod_id, $id));

      $statement = db()->prepare("UPDATE `installment_payments` SET `pmethod_id` = ? WHERE `pmethod_id` = ?");
      $statement->execute(array($new_pmethod_id, $id));

      $statement = db()->prepare("UPDATE `payments` SET `pmethod_id` = ? WHERE `pmethod_id` = ?");
      $statement->execute(array($new_pmethod_id, $id));

      $statement = db()->prepare("UPDATE `purchase_payments` SET `pmethod_id` = ? WHERE `pmethod_id` = ?");
      $statement->execute(array($new_pmethod_id, $id));
    }

    // Delete pmethod method
    $pmethod = $pmethod_model->deletePmethod($id);

    $Hooks->do_action('Before_Delete_PMethod', $pmethod);
    
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

// Create pmethod method
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'UPDATESTATUS')
{
  try {

    // Check delete permission
    if (user_group_id() != 1 && !has_permission('access', 'updadte_pmethod_status')) {
      throw new Exception(trans('error_update_permission'));
    }

    // Validate id
    if (!validateInteger($request->post['id'])) {
      throw new Exception(trans('error_id'));
    }

    // Validate status
    if (!validateString($request->post['status'])) {
      throw new Exception(trans('error_status'));
    }
    $status = $request->post['status'] == 'chacked' ? 1 : 0;

    $statement = db()->prepare("UPDATE `pmethod_to_store` SET `status` = ? WHERE `store_id` = ? AND `ppmethod_id` = ?");
    $statement->execute(array($status, $store_id, $request->post['id']));

    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_update_success')));
    exit();

  } catch (Exception $e) { 

    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();
  }
}

// pmethod method edit form
if (isset($request->get['pmethod_id']) AND isset($request->get['action_type']) && $request->get['action_type'] == 'EDIT') {
    // Fetch pmethod method
    $pmethod = $pmethod_model->getPmethod($request->get['pmethod_id']);
    include 'template/pmethod_form.php';
    exit();
}

// pmethod method delete form
if (isset($request->get['pmethod_id']) AND isset($request->get['action_type']) && $request->get['action_type'] == 'DELETE') {
    // Fetch pmethod method
    $pmethod = $pmethod_model->getPmethod($request->get['pmethod_id']);
    $Hooks->do_action('Before_PMethod_Delete_Form', $pmethod);
    include 'template/pmethod_del_form.php';
    $Hooks->do_action('After_PMethod_Delete_Form', $pmethod);
    exit();
}

/**
 *===================
 * START DATATABLE
 *===================
 */

$Hooks->do_action('Before_Showing_PMethod_List');

$where_query = "p2s.store_id = {$store_id}";
 
// DB table to use
$table = "(SELECT pmethods.*, p2s.status, p2s.sort_order FROM pmethods 
  LEFT JOIN pmethod_to_store p2s ON (pmethods.pmethod_id = p2s.ppmethod_id) 
  WHERE $where_query
  ) as pmethods";
 
// Table's primary key
$primaryKey = 'pmethod_id';
 
$columns = array(
  array(
      'db' => 'pmethod_id',
      'dt' => 'DT_RowId',
      'formatter' => function( $d, $row ) {
          return 'row_'.$d;
      }
  ),
  array( 'db' => 'pmethod_id', 'dt' => 'pmethod_id' ),
  array( 'db' => 'code_name', 'dt' => 'code_name' ),
  array( 
    'db' => 'name',   
    'dt' => 'name' ,
    'formatter' => function($d, $row) {
        return $row['name'];
    }
  ),
  array( 'db' => 'sort_order', 'dt' => 'sort_order' ),
  array( 'db' => 'details', 'dt' => 'details' ),
  array( 
    'db' => 'status',   
    'dt' => 'status' ,
    'formatter' => function($d, $row) {
        $check_status = $row['status'] == 1 ? ' checked=checked ' : '';
        $html = '<div class="onoffswitch-small" id="'.$row['pmethod_id'].'">';
        $html .= '<input type="checkbox" id="myonoffswitch'.$row['pmethod_id'].'" class="onoffswitch-small-checkbox" name="paypal_demo"'.$check_status.'data-url="'.root_url().'/_inc/pmethod.php" data-datatable="pmethod-pmethod-list">';
        $html .= '<label for="myonoffswitch'.$row['pmethod_id'].'" class="onoffswitch-small-label">';
        $html .= '<span class="onoffswitch-small-inner"></span>';
        $html .= '<span class="onoffswitch-small-switch"></span>';
        $html .= '</label>';
        $html .= '</div>';
        return $html;

    }
  ),
  array( 
    'db' => 'status',   
    'dt' => 'btn_edit' ,
    'formatter' => function($d, $row) {
      if (in_array($row['code_name'], array('cod', 'credit', 'gift_card', 'bkash', 'visa_card'))) {
        return'<button class="btn btn-sm btn-block btn-default" type="button" disabled><i class="fa fa-pencil"></i></button>';
      }
      return '<button id="edit-pmethod" class="btn btn-sm btn-block btn-primary" type="button" title="'.trans('button_edit').'"><i class="fa fa-fw fa-pencil"></i></button>';
    }
  ),
  array( 
    'db' => 'status',   
    'dt' => 'btn_delete' ,
    'formatter' => function($d, $row) {
      if (in_array($row['code_name'], array('cod', 'credit', 'gift_card', 'bkash', 'visa_card'))) {
        return'<button class="btn btn-sm btn-block btn-default" type="button" disabled><i class="fa fa-trash"></i></button>';
      }
      return '<button id="delete-pmethod" class="btn btn-sm btn-block btn-danger" type="button" title="'.trans('button_delete').'"><i class="fa fa-fw fa-trash"></i></button>';
    }
  )
);

echo json_encode(
  SSP::simple($request->get, $sql_details, $table, $primaryKey, $columns)
);

$Hooks->do_action('After_Showing_PMethod_List');

/**
 *===================
 * END DATATABLE
 *===================
 */