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
if (user_group_id() != 1 && !has_permission('access', 'read_brand')) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_read_permission')));
  exit();
}

// LOAD SUPPLIER MODEL
$brand_model = registry()->get('loader')->model('brand');

// Validate post data
function validate_request_data($request) 
{
  // Validate brand name
  if(!validateString($request->post['brand_name'])) {
    throw new Exception(trans('error_brand_name'));
  }

  // Validate brand code name
  if(!validateString($request->post['code_name'])) {
    throw new Exception(trans('error_code_name'));
  }

  // Validate brand slug
  if(!validateString($request->post['code_name'])) {
    throw new Exception(trans('error_code_name'));
  }

  // Validate store
  if (!isset($request->post['brand_store']) || empty($request->post['brand_store'])) {
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
  

  // Check, if brand name exist or not
  $statement = db()->prepare("SELECT * FROM `brands` WHERE (`brand_name` = ? OR `code_name` = ?) AND `brand_id` != ?");
  $statement->execute(array($request->post['brand_name'], $request->post['code_name'], $id));
  if ($statement->rowCount() > 0) {
    throw new Exception(trans('error_brand_exist'));
  }
}

// Create brand
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'CREATE')
{
  try {

    // Check create permission
    if (user_group_id() != 1 && !has_permission('access', 'create_brand')) {
      throw new Exception(trans('error_create_permission'));
    }

    // Validate post data
    validate_request_data($request);

    // Validate existance
    validate_existance($request);
    
    $statement = db()->prepare("SELECT * FROM `brands` WHERE (`code_name` = ? OR `brand_name` = ?)");
    $statement->execute(array($request->post['code_name'], $request->post['brand_name']));
    $total = $statement->rowCount();
    if ($total>0) {
      throw new Exception(trans('error_brand_exist'));
    }

    $Hooks->do_action('Before_Create_Brand', $request);

    // Insert brand into database
    $brand_id = $brand_model->addBrand($request->post);

    // get brand info
    $brand = $brand_model->getBrand($brand_id);

    $Hooks->do_action('After_Create_Brand', $brand);

    // SET OUTPUT CONTENT TYPE
    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_success'), 'id' => $brand_id, 'brand' => $brand));
    exit();

  } catch (Exception $e) { 
    
    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();

  }
} 

// Update brand
if($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'UPDATE')
{
  try {

    // Check update permission
    if (user_group_id() != 1 && !has_permission('access', 'update_brand')) {
      throw new Exception(trans('error_update_permission'));
    }

    // Validate product id
    if (empty($request->post['brand_id'])) {
      throw new Exception(trans('error_brand_id'));
    }

    $id = $request->post['brand_id'];

    // Validate post data
    validate_request_data($request);

    // Validate existance
    validate_existance($request, $id);

    $Hooks->do_action('Before_Update_Brand', $request);

    // Edit brand
    $brand = $brand_model->editBrand($id, $request->post);

    $Hooks->do_action('After_Update_Brand', $brand);

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

// Delete brand
if($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'DELETE') 
{
  try {

    // Check delete permission
    if (user_group_id() != 1 && !has_permission('access', 'delete_brand')) {
      throw new Exception(trans('error_delete_permission'));
    }

    // Validate brand id
    if (empty($request->post['brand_id'])) {
      throw new Exception(trans('error_brand_id'));
    }

    $id = $request->post['brand_id'];
    $new_brand_id = $request->post['new_brand_id'];

    // Validate delete action
    if (empty($request->post['delete_action'])) {
      throw new Exception(trans('error_delete_action'));
    }

    if ($request->post['delete_action'] == 'insert_to' && empty($new_brand_id)) {
      throw new Exception(trans('error_brand_name'));
    }

    $Hooks->do_action('Before_Delete_Brand', $request);

    $belongs_stores = $brand_model->getBelongsStore($id);
    foreach ($belongs_stores as $the_store) {

      // Check if relationship exist or not
      $statement = db()->prepare("SELECT * FROM `brand_to_store` WHERE `brand_id` = ? AND `store_id` = ?");
      $statement->execute(array($new_brand_id, $the_store['store_id']));
      if ($statement->rowCount() > 0) continue;

      // Create relationship
      $statement = db()->prepare("INSERT INTO `brand_to_store` SET `brand_id` = ?, `store_id` = ?");
      $statement->execute(array($new_brand_id, $the_store['store_id']));
    }

    if ($request->post['delete_action'] == 'insert_to') 
    {
      $statement = db()->prepare("UPDATE `holding_item` SET `brand_id` = ? WHERE `brand_id` = ?");
      $statement->execute(array($new_brand_id, $id));

      $statement = db()->prepare("UPDATE `quotation_item` SET `brand_id` = ? WHERE `brand_id` = ?");
      $statement->execute(array($new_brand_id, $id));

      $statement = db()->prepare("UPDATE `product_to_store` SET `brand_id` = ? WHERE `brand_id` = ?");
      $statement->execute(array($new_brand_id, $id));

      $statement = db()->prepare("UPDATE `selling_item` SET `brand_id` = ? WHERE `brand_id` = ?");
      $statement->execute(array($new_brand_id, $id));
    } 

    // Delete brand
    $brand = $brand_model->deleteBrand($id);

    $Hooks->do_action('After_Delete_Brand', $brand);
    
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

// brand create form
if (isset($request->get['action_type']) && $request->get['action_type'] == 'CREATE') 
{
  $Hooks->do_action('Before_Brand_Create_Form');
  include 'template/brand_create_form.php';
  $Hooks->do_action('After_Brand_Create_Form');
  exit();
}

// brand edit form
if (isset($request->get['brand_id']) && isset($request->get['action_type']) && $request->get['action_type'] == 'EDIT') {
    
  // Fetch brand info
  $brand = $brand_model->getBrand($request->get['brand_id']);
  $Hooks->do_action('Before_Brand_Edit_Form', $brand);
  include 'template/brand_form.php';
  $Hooks->do_action('After_Brand_Edit_Form', $brand);
  exit();
}

// brand delete form
if (isset($request->get['brand_id']) && isset($request->get['action_type']) && $request->get['action_type'] == 'DELETE') {

  // Fetch brand info
  $brand = $brand_model->getBrand($request->get['brand_id']);
  $Hooks->do_action('Before_Brand_Delete_Form');
  include 'template/brand_del_form.php';
  $Hooks->do_action('Before_Brand_Delete_Form');
  exit();
}

/**
 *===================
 * START DATATABLE
 *===================
 */
$Hooks->do_action('Before_Showing_Brand_List');

$where_query = 'b2s.store_id = ' . store_id();
 
// DB table to use
$table = "(SELECT brands.*, b2s.status, b2s.sort_order FROM brands 
  LEFT JOIN brand_to_store b2s ON (brands.brand_id = b2s.brand_id) 
  WHERE $where_query GROUP by brands.brand_id
  ) as brands";
 
// Table's primary key
$primaryKey = 'brand_id';

$columns = array(
  array(
      'db' => 'brand_id',
      'dt' => 'DT_RowId',
      'formatter' => function( $d, $row ) {
          return 'row_'.$d;
      }
  ),
  array( 'db' => 'brand_id', 'dt' => 'brand_id' ),
  array( 
    'db' => 'brand_name',   
    'dt' => 'brand_name' ,
    'formatter' => function($d, $row) {
        return ucfirst($row['brand_name']);
    }
  ),
  array( 'db' => 'code_name',   'dt' => 'code_name' ),
  array( 
    'db' => 'brand_id',   
    'dt' => 'total_product' ,
    'formatter' => function($d, $row) {
      return total_product_of_brand($row['brand_id']);
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
    'db' => 'brand_id',   
    'dt' => 'btn_view' ,
    'formatter' => function($d, $row) {
        return '<a id="view-brand" class="btn btn-sm btn-block btn-info" href="brand_profile.php?brand_id='.$row['brand_id'].'" title="'.trans('button_view_profile').'"><i class="fa fa-fw fa-user"></i></a>';
    }
  ),
  array( 
    'db' => 'brand_id',   
    'dt' => 'btn_edit' ,
    'formatter' => function($d, $row) {
      if (DEMO && $row['brand_id'] == 1) {          
        return'<button class="btn btn-sm btn-block btn-default" type="button" disabled><i class="fa fa-pencil"></i></button>';
      }
      return '<button id="edit-brand" class="btn btn-sm btn-block btn-primary" type="button" title="'.trans('button_edit').'"><i class="fa fa-fw fa-pencil"></i></button>';
    }
  ),
  array( 
    'db' => 'brand_id',   
    'dt' => 'btn_delete' ,
    'formatter' => function($d, $row) {
      if (DEMO && $row['brand_id'] == 1) {          
        return'<button class="btn btn-sm btn-block btn-default" type="button" disabled><i class="fa fa-trash"></i></button>';
      }
      return '<button id="delete-brand" class="btn btn-sm btn-block btn-danger" type="button" title="'.trans('button_delete').'"><i class="fa fa-fw fa-trash"></i></button>';
    }
  )
);
 
echo json_encode(
  SSP::simple($request->get, $sql_details, $table, $primaryKey, $columns)
);

$Hooks->do_action('After_Showing_Brand_List');

/**
 *===================
 * END DATATABLE
 *===================
 */