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
if (user_group_id() != 1 && !has_permission('access', 'read_user')) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_read_permission')));
  exit();
}

// LOAD USER MODEL
$user_model = registry()->get('loader')->model('user');

// Validate post data
function validate_request_data($request) 
{
  // Validate username
  if (!validateString($request->post['username'])) {
    throw new Exception(trans('error_user_name'));
  }

  // Validate customer date of birth
  if ($request->post['dob']) {
    if (!isItValidDate($request->post['dob'])) {
        throw new Exception(trans('error_date_of_birth'));
    }
  }

  // Validate customer email & mobile
  if (!validateEmail($request->post['email']) && empty($request->post['mobile'])) {
    throw new Exception(trans('error_user_email_or_mobile'));
  }

  // Validate user group id
  if(!validateInteger($request->post['group_id'])) {
    throw new Exception(trans('error_user_group'));
  } 

  if (!isset($request->post['user_store']) || empty($request->post['user_store'])) {
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

// Check, if exist or not
function validate_existance($request, $id = 0)
{
  

  // Check email address
  if ($request->post['email']) {
    $statement = db()->prepare("SELECT * FROM `users` WHERE `email` = ? && `id` != ?");
    $statement->execute(array($request->post['email'], $id));
    if ($statement->rowCount() > 0) {
      throw new Exception(trans('error_email_exist'));
    }
  }

  // Check mobile number
  if ($request->post['mobile']) {
    $statement = db()->prepare("SELECT * FROM `users` WHERE `mobile` = ? && `id` != ?");
    $statement->execute(array($request->post['mobile'], $id));
    if ($statement->rowCount() > 0) {
      throw new Exception(trans('error_mobile_exist'));
    } 
  }
}

// Create user
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'CREATE')
{
  try {

     // Check create permission
    if (user_group_id() != 1 && !has_permission('access', 'create_user')) {
      throw new Exception(trans('error_read_permission'));
    }

    // Validate post data
    validate_request_data($request);

     // Validate existance
    validate_existance($request);

    // Validate password
    if(!validateAlphanumeric($request->post['password'])) {
      throw new Exception(trans('error_user_password'));
    }

    // Check password strongness
    if (($errMsg = checkPasswordStrongness($request->post['password'])) != 'ok') {
      throw new Exception($errMsg);
    } 

    // password matching
    if($request->post['password'] !== $request->post['password1']) {
      throw new Exception(trans('error_user_password_match'));
    }

    $Hooks->do_action('Before_Create_User', $request);

    // Edit user
    $user_id = $user_model->addUser($request->post);

    // get user
    $the_user = $user_model->getUser($user_id);

    $Hooks->do_action('After_Create_User', $the_user);

    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_success'), 'id' => $user_id, 'user' => $the_user));
    exit();

  }  catch (Exception $e) { 

    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();
  }
}

// Update user
if($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'UPDATE')
{
  try {

    // Check update permission
    if (user_group_id() != 1 && !has_permission('access', 'update_user') || DEMO) {
      throw new Exception(trans('error_update_permission'));
    }

    // Validate user id
    if (empty($request->post['id'])) {
      throw new Exception(trans('error_user_id'));
    }

    $id = $request->post['id'];

    if (DEMO && ($id == 1 || $id == 2 || $id == 3)) {
      throw new Exception(trans('error_update_permission'));
    }

    // Validate post data
    validate_request_data($request);

    // Validate existance
    validate_existance($request, $id);

    // for current user current store link can not remove
    if (user_id() == $id && !in_array(store_id(), $request->post['user_store'])) {
      throw new Exception(trans('error_active_store_not_remove'));
    }

    $Hooks->do_action('Before_Update_User', $request);

    // Edit esuer
    $the_user = $user_model->editUser($id, $request->post);

    $Hooks->do_action('After_Update_User', $the_user);

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

// Delete user
if($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'DELETE') 
{
  try {

    // Check delete permission
    if (user_group_id() != 1 && !has_permission('access', 'delete_user') || DEMO) {
      throw new Exception(trans('error_delete_permission'));
    }

    // Validate user id
    if (!validateInteger($request->post['id'])) {
      throw new Exception(trans('error_user_id'));
    }

    $id = $request->post['id'];
    $new_user_id = $request->post['new_user_id'];

    if (DEMO && ($id == 1 || $id == 2 || $id == 3)) {
      throw new Exception(trans('error_delete_permission'));
    }

    if ($id == 1) {
      throw new Exception(trans('error_unable_to_delete'));
    }

    // Validate delete action
    if (empty($request->post['delete_action'])) {
      throw new Exception(trans('error_delete_action'));
    }

    if ($request->post['delete_action'] == 'insert_to' && empty($request->post['new_user_id'])) {
      throw new Exception(trans('error_user_name'));
    }

    $Hooks->do_action('Before_Delete_User', $request);

    $belongs_stores = $user_model->getBelongsStore($id);
    foreach ($belongs_stores as $the_store) {

      // Check if relationship exist or not
      $statement = db()->prepare("SELECT * FROM `user_to_store` WHERE `user_id` = ? AND `store_id` = ?");
      $statement->execute(array($new_user_id, $the_store['store_id']));
      if ($statement->rowCount() > 0) continue;

      // Create relationship
      $statement = db()->prepare("INSERT INTO `user_to_store` SET `user_id` = ?, `store_id` = ?");
      $statement->execute(array($new_user_id, $the_store['store_id']));
    }

    if ($request->post['delete_action'] == 'insert_to') {

      $statement = db()->prepare("UPDATE `login_logs` SET `user_id` = ? WHERE `user_id` = ?");
      $statement->execute(array($new_user_id, $id));
      
      $statement = db()->prepare("UPDATE `selling_info` SET `ref_user_id` = ? WHERE `ref_user_id` = ?");
      $statement->execute(array($new_user_id, $id));

      $statement = db()->prepare("UPDATE `selling_info` SET `created_by` = ? WHERE `created_by` = ?");
      $statement->execute(array($new_user_id, $id));

      $statement = db()->prepare("UPDATE `purchase_info` SET `created_by` = ? WHERE `created_by` = ?");
      $statement->execute(array($new_user_id, $id));
    }
    
    // Delete user
    $the_user = $user_model->deleteUser($id);

    $Hooks->do_action('After_Delete_User', $the_user);

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

// User create form
if (isset($request->get['action_type']) && $request->get['action_type'] == 'CREATE') 
{
  include 'template/user_create_form.php';
  exit();
}

// User edit form
if (isset($request->get['id']) && isset($request->get['action_type']) && $request->get['action_type'] == 'EDIT') {
  
  // Fetch user
  $the_user = $user_model->getUser($request->get['id']);
  include 'template/user_form.php';
  exit();
}

// User delete form
if (isset($request->get['id']) && isset($request->get['action_type']) && $request->get['action_type'] == 'DELETE') {
  
  // Fetch user
  $the_user = $user_model->getUser($request->get['id']);
  include 'template/user_del_form.php';
  exit();
}

/**
 *===================
 * START DATATABLE
 *===================
 */

$Hooks->do_action('Before_Showing_User_List');
 
// DB table to use
$where_query = 'u2s.store_id = ' . store_id();
 
// DB table to use
$table = "(SELECT users.*, u2s.status, u2s.sort_order FROM users 
  LEFT JOIN user_to_store u2s ON (users.id = u2s.user_id) 
  WHERE $where_query GROUP by users.id
  ) as users";
 
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
    'db' => 'username',   
    'dt' => 'username' ,
    'formatter' => function($d, $row) {
        return ucfirst($row['username']);
    }
  ),
  array( 'db' => 'email',  'dt' => 'email' ),
  array( 'db' => 'mobile',   'dt' => 'mobile' ),
  array( 'db' => 'group_id',   'dt' => 'group' ),
  array( 
    'db' => 'group_id',   
    'dt' => 'group' ,
    'formatter' => function($d, $row) {
        $statement = db()->prepare('SELECT name FROM `user_group` WHERE group_id = ?');
        $statement->execute(array($row['group_id']));
        $group = $statement->fetch(PDO::FETCH_ASSOC);
        return ucfirst($group['name']);
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
    'dt' => 'status' ,
    'formatter' => function($d, $row) {
        return $row['status'] 
          ? '<span class="label label-success">'.trans('text_active').'</span>' 
          : '<span class="label label-warning">' .trans('text_inactive').'</span>';
    }
  ),
  array(
      'db' => 'id',
      'dt' => 'btn_profile',
      'formatter' => function( $d, $row ) {
        return '<a href="user_profile.php?id='.$row['id'].'" id="sell-product" class="btn btn-sm btn-block btn-info" type="button" title="'.trans('button_view_profile').'"><i class="fa fa-user"></i></a>';
      }
  ),
  array( 
    'db' => 'id',   
    'dt' => 'btn_edit' ,
    'formatter' => function($d, $row) {
      if (DEMO && ($row['id'] == 2 || $row['id'] == 3 || $row['id'] == 1)) {
        return '<button class="btn btn-sm btn-block btn-default" type="button" disabled><i class="fa fa-fw fa-pencil"></i></button>';
      } 
      return '<button id="edit-user" class="btn btn-sm btn-block btn-primary" type="button" title="'.trans('button_edit').'"><i class="fa fa-fw fa-pencil"></i></button>';
    }
  ),
  array( 
    'db' => 'id',   
    'dt' => 'btn_delete' ,
    'formatter' => function($d, $row) {
        if ((DEMO && ($row['id'] == 2 || $row['id'] == 3)) || $row['id'] == 1 || $row['id'] == user_id()) {
          return '<button class="btn btn-sm btn-block btn-default" type="button" disabled><i class="fa fa-fw fa-trash"></i></button>';
        } 
        return '<button id="delete-user" class="btn btn-sm btn-block btn-danger" type="button" title="'.trans('button_delete').'"><i class="fa fa-fw fa-trash"></i></button>';
    }
  )
); 
 
echo json_encode(
    SSP::simple($request->get, $sql_details, $table, $primaryKey, $columns)
);

$Hooks->do_action('After_Showing_User_List');

/**
 *===================
 * END DATATABLE
 *===================
 */
