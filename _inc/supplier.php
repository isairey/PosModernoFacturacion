<?php 
ob_start();
session_start();
include ("../_init.php");

// Check, if your logged in or not
// If user is not logged in then return an alert message
if (!is_loggedin()) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_login')));
  exit();
}

// Check, if user has reading permission or not
// If user have not reading permission return an alert message
if (user_group_id() != 1 && !has_permission('access', 'read_supplier')) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_read_permission')));
  exit();
}

// LOAD SUPPLIER MODEL
$supplier_model = registry()->get('loader')->model('supplier');

// Validate post data
function validate_request_data($request) 
{
  // Validate supplier name
  if(!validateString($request->post['sup_name'])) {
    throw new Exception(trans('error_sup_name'));
  }

  // Validate supplier code name
  if(!validateString($request->post['code_name'])) {
    throw new Exception(trans('error_code_name'));
  }

  // Validate supplier email or mobile
  if (!validateEmail($request->post['sup_email']) && empty($request->post['sup_mobile'])) {
    throw new Exception(trans('error_supplier_email_or_mobile'));
  }

  // Validate suppleir address
  if(empty($request->post['sup_address'])) {
    throw new Exception(trans('error_sup_address'));
  }

  if (get_preference('invoice_view') == 'indian_gst') {
    // Validate supplier state
    if (!validateString($request->post['sup_state'])) {
      throw new Exception(trans('error_sup_state'));
    }
  }

  // Validate store
  if (!isset($request->post['supplier_store']) || empty($request->post['supplier_store'])) {
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

// Check, if already exist or not
function validate_existance($request, $id = 0)
{
  

  // Check, if supplier name exist or not
  if (!empty($request->post['sup_name'])) {
    $statement = db()->prepare("SELECT * FROM `suppliers` WHERE `sup_name` = ? AND `sup_id` != ?");
    $statement->execute(array($request->post['sup_name'], $id));
    if ($statement->rowCount() > 0) {
      throw new Exception(trans('error_supplier_name_exist'));
    }
  }

  // Check, if email address exist or not
  if (!empty($request->post['sup_email'])) {
    $statement = db()->prepare("SELECT * FROM `suppliers` WHERE `sup_email` = ? AND `sup_id` != ?");
    $statement->execute(array($request->post['sup_email'], $id));
    if ($statement->rowCount() > 0) {
      throw new Exception(trans('error_email_exist'));
    }
  }

  // Check, if mobile number exist or not
  if (!empty($request->post['sup_mobile'])) {
    $statement = db()->prepare("SELECT * FROM `suppliers` WHERE `sup_mobile` = ? AND `sup_id` != ?");
    $statement->execute(array($request->post['sup_mobile'], $id));
    if ($statement->rowCount() > 0) {
      throw new Exception(trans('error_mobile_exist'));
    }
  }
}

// Create supplier
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'CREATE')
{
  try {

    // Check create permission
    if (user_group_id() != 1 && !has_permission('access', 'create_supplier')) {
      throw new Exception(trans('error_create_permission'));
    }

    // Validate post data
    validate_request_data($request);

    // Validate existance
    validate_existance($request);
    
    $statement = db()->prepare("SELECT * FROM `suppliers` WHERE `sup_name` = ?");
    $statement->execute(array($request->post['sup_name']));
    $total = $statement->rowCount();
    if ($total>0) {
      throw new Exception(trans('error_supplier_exist'));
    }

    $Hooks->do_action('Before_Create_Supplier', $request);

    // Insert supplier into database
    $supplier_id = $supplier_model->addSupplier($request->post);

    // get supplier info
    $supplier = $supplier_model->getSupplier($supplier_id);

    $Hooks->do_action('After_Create_Supplier', $supplier);

    // SET OUTPUT CONTENT TYPE
    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_success'), 'id' => $supplier_id, 'supplier' => $supplier));
    exit();

  } catch (Exception $e) { 
    
    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();

  }
} 


// Update supplier
if($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'UPDATE')
{
  try {

    // Check update permission
    if (user_group_id() != 1 && !has_permission('access', 'update_supplier')) {
      throw new Exception(trans('error_update_permission'));
    }

    // Validate product id
    if (empty($request->post['sup_id'])) {
      throw new Exception(trans('error_sup_id'));
    }

    $id = $request->post['sup_id'];

    if (DEMO && $id == 1) {
      throw new Exception(trans('error_update_permission'));
    }

    // Validate post data
    validate_request_data($request);

    // Validate existance
    validate_existance($request, $id);

    $Hooks->do_action('Before_Update_Supplier', $request);

    // Edit supplier
    $supplier = $supplier_model->editSupplier($id, $request->post);

    $Hooks->do_action('After_Update_Supplier', $supplier);

    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_update_success'), 'id' => $id));
    exit();
    
  } catch(Exception $e) { 

    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();
  }
} 

// Delete supplier
if($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'DELETE') 
{
  try {

    // Check delete permission
    if (user_group_id() != 1 && !has_permission('access', 'delete_supplier')) {
      throw new Exception(trans('error_delete_permission'));
    }

    // Validate supplier id
    if (empty($request->post['sup_id'])) {
      throw new Exception(trans('error_supplier_id'));
    }

    $id = $request->post['sup_id'];
    $new_sup_id = $request->post['new_sup_id'];

    if (DEMO && $id == 1) {
      throw new Exception(trans('error_delete_permission'));
    }

    // Validate delete action
    if (empty($request->post['delete_action'])) {
      throw new Exception(trans('error_delete_action'));
    }

    if ($request->post['delete_action'] == 'insert_to' && empty($new_sup_id)) {
      throw new Exception(trans('error_supplier_name'));
    }

    $Hooks->do_action('Before_Delete_Supplier', $request);

    $belongs_stores = $supplier_model->getBelongsStore($id);
    foreach ($belongs_stores as $the_store) {
        $statement = db()->prepare("SELECT * FROM `supplier_to_store` WHERE `sup_id` = ? AND `store_id` = ?");
        $statement->execute(array($new_sup_id, $the_store['store_id']));
        if (!$statement->rowCount() > 0) {
          $statement = db()->prepare("INSERT INTO `supplier_to_store` SET `sup_id` = ?, `store_id` = ?");
          $statement->execute(array($new_sup_id, $the_store['store_id']));
        };
        $balance = (float)get_supplier_balance($id, $the_store['store_id']);
        
        $statement = db()->prepare("UPDATE `supplier_to_store` SET `balance` = `balance` + $balance WHERE `sup_id` = ? AND `store_id` = ?");
        $statement->execute(array($new_sup_id, $the_store['store_id']));
    }

    if ($request->post['delete_action'] == 'insert_to') 
    {
      $statement = db()->prepare("UPDATE `holding_item` SET `sup_id` = ? WHERE `sup_id` = ?");
      $statement->execute(array($new_sup_id, $id));

      $statement = db()->prepare("UPDATE `purchase_logs` SET `sup_id` = ? WHERE `sup_id` = ?");
      $statement->execute(array($new_sup_id, $id));

      $statement = db()->prepare("UPDATE `purchase_returns` SET `sup_id` = ? WHERE `sup_id` = ?");
      $statement->execute(array($new_sup_id, $id));

      $statement = db()->prepare("UPDATE `quotation_item` SET `sup_id` = ? WHERE `sup_id` = ?");
      $statement->execute(array($new_sup_id, $id));

      $statement = db()->prepare("UPDATE `product_to_store` SET `sup_id` = ? WHERE `sup_id` = ?");
      $statement->execute(array($new_sup_id, $id));

      $statement = db()->prepare("UPDATE `purchase_info` SET `sup_id` = ? WHERE `sup_id` = ?");
      $statement->execute(array($new_sup_id, $id));

      $statement = db()->prepare("UPDATE `selling_item` SET `sup_id` = ? WHERE `sup_id` = ?");
      $statement->execute(array($new_sup_id, $id));
    } 

    // Delete supplier
    $supplier = $supplier_model->deleteSupplier($id);

    $Hooks->do_action('After_Delete_Supplier', $supplier);
    
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

// Supplier create form
if (isset($request->get['action_type']) && $request->get['action_type'] == 'CREATE') 
{
  include 'template/supplier_create_form.php';
  exit();
}

// Supplier edit form
if (isset($request->get['sup_id']) && isset($request->get['action_type']) && $request->get['action_type'] == 'EDIT') {
    
  // Fetch supplier info
  $supplier = $supplier_model->getSupplier($request->get['sup_id']);
  include 'template/supplier_form.php';
  exit();
}

// Supplier delete form
if (isset($request->get['sup_id']) && isset($request->get['action_type']) && $request->get['action_type'] == 'DELETE') {

  // Fetch supplier info
  $supplier = $supplier_model->getSupplier($request->get['sup_id']);
  include 'template/supplier_del_form.php';
  exit();
}


/**
 *===================
 * START DATATABLE
 *===================
 */

$Hooks->do_action('Before_Showing_Supplier_List');

$where_query = 's2s.store_id = ' . store_id();
 
// DB table to use
$table = "(SELECT suppliers.*, s2s.status, s2s.sort_order FROM suppliers 
  LEFT JOIN supplier_to_store s2s ON (suppliers.sup_id = s2s.sup_id) 
  WHERE $where_query GROUP by suppliers.sup_id
  ) as suppliers";
 
// Table's primary key
$primaryKey = 'sup_id';

$columns = array(
  array(
      'db' => 'sup_id',
      'dt' => 'DT_RowId',
      'formatter' => function( $d, $row ) {
          return 'row_'.$d;
      }
  ),
  array( 'db' => 'sup_id', 'dt' => 'sup_id' ),
  array( 
    'db' => 'sup_name',   
    'dt' => 'sup_name' ,
    'formatter' => function($d, $row) {
        return $row['sup_name'];
    }
  ),
  array( 'db' => 'sup_mobile',   'dt' => 'sup_mobile' ),
  array( 
    'db' => 'sup_id',   
    'dt' => 'total_product' ,
    'formatter' => function($d, $row) use($supplier_model) {
        return total_product_of_supplier($row['sup_id']);
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
    'db' => 'status',   
    'dt' => 'status',
    'formatter' => function($d, $row) {
      return $row['status'] 
        ? '<span class="label label-success">'.trans('text_active').'</span>' 
        : '<span class="label label-warning">' .trans('text_inactive').'</span>';
    }
  ),
  array( 
    'db' => 'sup_id',   
    'dt' => 'btn_purchase' ,
    'formatter' => function($d, $row) {
        if (total_product_of_supplier($row['sup_id']) <= 0) {
          return '<button class="btn btn-sm btn-block btn-default" disabled><i class="fa fa-fw fa-shopping-cart"></i></button>';
        }
        return '<a href="purchase.php?box_state=open&sup_id='.$row['sup_id'].'" id="purchase-btn" class="btn btn-sm btn-block btn-success" title="'.trans('button_purchase_product').'"><i class="fa fa-fw fa-shopping-cart"></i></a>';
    }
  ),
  array( 
    'db' => 'sup_id',   
    'dt' => 'btn_view' ,
    'formatter' => function($d, $row) {
        return '<a id="view-supplier" class="btn btn-sm btn-block btn-info" href="supplier_profile.php?sup_id='.$row['sup_id'].'" title="'.trans('button_view_profile').'"><i class="fa fa-fw fa-user"></i></a>';
    }
  ),
  array( 
    'db' => 'sup_id',   
    'dt' => 'btn_edit' ,
    'formatter' => function($d, $row) {
      return '<button id="edit-supplier" class="btn btn-sm btn-block btn-primary" type="button" title="'.trans('button_edit').'"><i class="fa fa-fw fa-pencil"></i></button>';
    }
  ),
  array( 
    'db' => 'sup_id',   
    'dt' => 'btn_delete' ,
    'formatter' => function($d, $row) {
      return '<button id="delete-supplier" class="btn btn-sm btn-block btn-danger" type="button" title="'.trans('button_delete').'"><i class="fa fa-fw fa-trash"></i></button>';
    }
  )
);

echo json_encode(
  SSP::simple($request->get, $sql_details, $table, $primaryKey, $columns)
);

$Hooks->do_action('After_Showing_Supplier_List');

/**
 *===================
 * END DATATABLE
 *===================
 */