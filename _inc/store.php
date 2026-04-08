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
if (user_group_id() != 1 && !has_permission('access', 'read_store')) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_read_permission')));
  exit();
}

// LOAD STORE MODEL
$store_model = registry()->get('loader')->model('store');

// Validate post data
function validate_request_data($request) 
{

  // Validate store name
  if (!validateString($request->post['name'])) {
    throw new Exception(trans('error_name'));
  }

  // Validate store code name
  if (!validateString($request->post['code_name'])) {
    throw new Exception(trans('error_code_name'));
  }

  // Validate store mobile number 
  if (empty($request->post['mobile']) || !valdateMobilePhone($request->post['mobile'])) {
    throw new Exception(trans('error_mobile'));
  }

  // Validate store email
  if (!validateEmail($request->post['email'])) {
    throw new Exception(trans('error_email'));
  }

  // Validate store country
  if (!validateString($request->post['country'])) {
    throw new Exception(trans('error_country'));
  }

    // Validate store zip code
  if (empty($request->post['zip_code'])) {
    throw new Exception(trans('error_zip_code'));
  }

    // Validate store cashiar name
  if (!validateInteger($request->post['cashier_id'])) {
    throw new Exception(trans('error_cashier_name'));
  }

    // Validate store address
  if (!validateString($request->post['address'])) {
    throw new Exception(trans('error_addreess'));
  }

  // Validate store sort_order
  if (is_null($request->post['sort_order'])) {
    throw new Exception(trans('error_position'));
  }

  // Validate timezone
  if (!isset($request->post['preference']['timezone']) || !validateString($request->post['preference']['timezone'])) {
    throw new Exception(trans('error_preference_timezone'));
  }

  // Validate receipt printer
  if ($request->post['remote_printing'] == 1 && !$request->post['receipt_printer']) {
    throw new Exception(trans('error_receipt_printer'));
  }

  // Validate invoice edit lifespan
  if ($request->post['preference']['invoice_edit_lifespan'] < 0 || !is_numeric($request->post['preference']['invoice_edit_lifespan'])) {
    throw new Exception(trans('error_preference_invoice_edit_lifespan'));
  }

  // Validate invoice delete lifespan
  if ($request->post['preference']['invoice_delete_lifespan'] < 0 || !is_numeric($request->post['preference']['invoice_delete_lifespan'])) {
    throw new Exception(trans('error_preference_invoice_delete_lifespan'));
  }

  // Validate invoice edit lifespan unit
  if (!isset($request->post['preference']['invoice_edit_lifespan_unit']) || !validateString($request->post['preference']['invoice_edit_lifespan_unit'])) {
    throw new Exception(trans('error_preference_invoice_edit_lifespan_unit'));
  }

  // Validate after sell page
  if (!validateString($request->post['preference']['after_sell_page'])) {
    throw new Exception(trans('error_preference_after_sell_page'));
  }

  // Validate tax
  if (!is_numeric($request->post['preference']['tax']) || $request->post['preference']['tax'] < 0) {
    throw new Exception(trans('error_preference_tax'));
  }

  // Validate datatable item limit
  if (!is_numeric($request->post['preference']['datatable_item_limit']) || $request->post['preference']['datatable_item_limit'] < 0) {
    throw new Exception(trans('error_preference_datatable_item_limit'));
  }

  // // Validate email from
  // if (!validateString($request->post['preference']['email_from'])) {
  //   throw new Exception(trans('error_preference_email_from'));
  // }

  // // Validate email address
  // if (!validateString($request->post['preference']['email_address'])) {
  //   throw new Exception(trans('error_preference_email_address'));
  // }

  // // Validate email driver
  // if (!validateString($request->post['preference']['email_driver']) || !in_array($request->post['preference']['email_driver'], array('mail_function', 'send_mail', 'smtp_server'))) {
  //   throw new Exception(trans('error_preference_email_driver'));
  // }

  // // Validate sendmail path
  // if ($request->post['preference']['email_driver'] == 'send_mail' && !validateString($request->post['preference']['send_mail_path'])) {
  //   throw new Exception(trans('error_preference_send_mail_path'));
  // }

  // // Validate smtp host
  // if ($request->post['preference']['email_driver'] == 'smtp_server' && !validateString($request->post['preference']['smtp_host'])) {
  //   throw new Exception(trans('error_preference_smtp_host'));
  // }

  // // Validate smtp username
  // if ($request->post['preference']['email_driver'] == 'smtp_server' && !validateEmail($request->post['preference']['smtp_username'])) {
  //   throw new Exception(trans('error_preference_smtp_username'));
  // }

  // // Validate smtp password
  // if ($request->post['preference']['email_driver'] == 'smtp_server' && !validateString($request->post['preference']['smtp_password'])) {
  //   throw new Exception(trans('error_preference_smtp_password'));
  // }

  // // Validate smtp port
  // if ($request->post['preference']['email_driver'] == 'smtp_server' && !validateString($request->post['preference']['smtp_port'])) {
  //   throw new Exception(trans('error_preference_smtp_port'));
  // }

  // // Validate smtp ssl_tls
  // if ($request->post['preference']['email_driver'] == 'smtp_server' && (!validateString($request->post['preference']['ssl_tls']) || !in_array($request->post['preference']['ssl_tls'], array('tls', 'ssl')))) {
  //   throw new Exception(trans('error_preference_ssl_tls'));
  // }

  // Validate receipt tempala
  if (isset($request->post['preference']['receipt_template']) && !validateString($request->post['preference']['receipt_template'])) {
    throw new Exception(trans('error_preference_receipt_template'));
  }
}

// Check store existance by id
function validate_existance($request, $id = 0)
{
  

  // Check, if store name exist or not
  $statement = db()->prepare("SELECT * FROM `stores` WHERE `name` = ? AND `store_id` != ?");
  $statement->execute(array($request->post['name'], $id));
  if ($statement->rowCount() > 0) {
    throw new Exception(trans('error_store_exist'));
  }
}

// Create store
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'CREATE')
{
  try {

    // Check create permission
    if (user_group_id() != 1 && !has_permission('access', 'create_store')) {
      throw new Exception(trans('error_read_permission'));
    }
    
    // Validate post data
    validate_request_data($request);

    // Validate logo url
    if (!isset($request->post['logo']) || !validateString($request->post['logo'])) {
      $request->post['logo'] = null;
    }

    // Validate favicon url
    if (!isset($request->post['favicon']) || !validateString($request->post['favicon'])) {
      $request->post['favicon'] = null;
    }

    // Validate currency
    if (empty($request->post['currency'])) {
      throw new Exception(trans('error_currency'));
    }

    // Validate payment method
    if (empty($request->post['pmethod'])) {
      throw new Exception(trans('error_pmethod'));
    }

    // Validate existance
    validate_existance($request);

    $Hooks->do_action('Before_Create_Store', $request);

    // Insert new store into database
    $store_id = $store_model->addStore($request->post);
    $store_model->editPreference($store_id, $request->post['preference']);

    // Add product to store
    if (!empty($request->post['product'])) {
      foreach ($request->post['product'] as $product_id) {

        // Fetch product info
        $product_info = get_the_product($product_id);

        //--- Category to store ---//

          $statement = db()->prepare("SELECT * FROM `category_to_store` WHERE `store_id` = ? AND `ccategory_id` = ?");
          $statement->execute(array($store_id, $product_info['category_id']));
          $category = $statement->fetch(PDO::FETCH_ASSOC);
          if (!$category) {
             $statement = db()->prepare("INSERT INTO `category_to_store` SET `ccategory_id` = ?, `store_id` = ?");
              $statement->execute(array((int)$product_info['category_id'], (int)$store_id));
          } 

        //--- Box to store ---//

          $statement = db()->prepare("SELECT * FROM `box_to_store` WHERE `store_id` = ? AND `box_id` = ?");
          $statement->execute(array($store_id, $product_info['box_id']));
          $box = $statement->fetch(PDO::FETCH_ASSOC);
          if (!$box) {
             $statement = db()->prepare("INSERT INTO `box_to_store` SET `box_id` = ?, `store_id` = ?");
              $statement->execute(array((int)$product_info['box_id'], (int)$store_id));
          } 

      //--- Supplier to store ---//

          $statement = db()->prepare("SELECT * FROM `supplier_to_store` WHERE `store_id` = ? AND `sup_id` = ?");
          $statement->execute(array($store_id, $product_info['sup_id']));
          $supplier = $statement->fetch(PDO::FETCH_ASSOC);
          if (!$supplier) {
            $statement = db()->prepare("INSERT INTO `supplier_to_store` SET `sup_id` = ?, `store_id` = ?");
            $statement->execute(array((int)$product_info['sup_id'], (int)$store_id));
          }

        //--- Create product link ---//

          $statement = db()->prepare("INSERT INTO `product_to_store` SET `product_id` = ?, `store_id` = ?, `sup_id` = ?, `box_id` = ?, `e_date` = ?, `p_date` = ?");
          $statement->execute(array((int)$product_id, (int)$store_id, (int)$product_info['sup_id'], (int)$product_info['box_id'], $product_info['e_date'], date('Y-m-d')));

      }
    }

    // Add account to store
    $statement = db()->prepare("INSERT INTO `bank_account_to_store` SET `account_id` = ?, `store_id` = ?");
    $statement->execute(array(1, $store_id));
    $statement = db()->prepare("UPDATE `stores` SET `deposit_account_id` = ? WHERE `store_id` = ?");
    $statement->execute(array(1, $store_id));

    // Add postemplate to store
    foreach ($request->post['postemplate'] as $template_id) {
      $statement = db()->prepare("INSERT INTO `pos_template_to_store` SET `store_id` = ?, `ttemplate_id` = ?, `is_active` = ?");
      $statement->execute(array((int)$store_id, (int)$template_id, 1));
    }

    // Add printer to store
    foreach ($request->post['printer'] as $printer_id) {
      $statement = db()->prepare("INSERT INTO `printer_to_store` SET `store_id` = ?, `pprinter_id` = ?");
      $statement->execute(array((int)$store_id, (int)$printer_id));
    }

    // Add currency to store
    foreach ($request->post['currency'] as $currency_id) {
      $statement = db()->prepare("INSERT INTO `currency_to_store` SET `currency_id` = ?, `store_id` = ?");
      $statement->execute(array((int)$currency_id, (int)$store_id));
    }

    // Add payment method to store
    foreach ($request->post['pmethod'] as $pmethod_id) {
      $statement = db()->prepare("INSERT INTO `pmethod_to_store` SET `ppmethod_id` = ?, `store_id` = ?");
      $statement->execute(array((int)$pmethod_id, (int)$store_id));
    }

    // Add walking customer to the store
    $statement = db()->prepare("INSERT INTO `customer_to_store` SET `customer_id` = ?, `store_id` = ?");
    $statement->execute(array(1, $store_id));

    // Add cashier to the store
    $statement = db()->prepare("INSERT INTO `user_to_store` SET `user_id` = ?, `store_id` = ?");
    $statement->execute(array($request->post['cashier_id'], $store_id));

    // Add admin to the store
    $statement = db()->prepare("INSERT INTO `user_to_store` SET `user_id` = ?, `store_id` = ?");
    $statement->execute(array(1, $store_id));

    // Add current user to the store
    $statement = db()->prepare("INSERT INTO `user_to_store` SET `user_id` = ?, `store_id` = ?");
    $statement->execute(array(user_id(), $store_id));

    $Hooks->do_action('After_Create_Store', $store_id);

    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_create_success'), 'id' => $store_id));
    exit();

  } catch(Exception $e) {
    
    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();
  }
} 

// Update store
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'UPDATE')
{
  try {

    // Check update permission
    if (user_group_id() != 1 && !has_permission('access', 'update_store')) {
      throw new Exception(trans('error_update_permission'));
    }

    // Validate store id
    if (!validateInteger($request->post['store_id'])) {
      throw new Exception(trans('error_store_id'));
    }

    $id = $request->post['store_id'];

    if (DEMO && $id == 1) {
      throw new Exception(trans('error_update_permission'));
    }

    // Validate post data
    validate_request_data($request);

    // Validate existance
    validate_existance($request, $id);

    $Hooks->do_action('Before_Update_Store', $request);
    
    // Edit store
    $store_model->editStore($id, $request->post);
    $the_store = $store_model->editPreference($id, $request->post['preference']);

    $Hooks->do_action('After_Update_Store', $the_store);

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

// Delete store
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'DELETE') 
{
  try {

    // Check delete permission
    if (user_group_id() != 1 && !has_permission('access', 'delete_store') || DEMO) {
      throw new Exception(trans('error_delete_permission'));
    }

    // Validate store id
    if (!validateInteger($request->post['store_id'])) {
      throw new Exception(trans('error_store_id'));
    }

    $id = $request->post['store_id'];
    $new_store_id = $request->post['new_store_id'];

    if (DEMO && $id == 1) {
      throw new Exception(trans('error_delete_permission'));
    }

    // Store id 1 can not be deleted
    if ($id == 1) {
      throw new Exception(trans('error_store_delete'));
    }

    // Active store can not be deleted
    if (store_id() == $id) {
      throw new Exception(trans('error_active_store_delete'));
    }

    // Validate delete action
    if ($request->post['delete_action'] == 'insert_to' && !validateInteger($new_store_id)) {
      throw new Exception(trans('error_store_name'));
    }

    $Hooks->do_action('Before_Delete_Store', $request);

    $action_type = $request->post['delete_action'];

    switch ($action_type) {
      case 'delete':

        foreach (get_all_tables() as $table) 
        {
          $the_table = $table[0];
          if ($the_table == 'stores') {
            continue;
          }
          if ($the_table == 'transfers') {
            $statement = db()->prepare("DELETE FROM {$the_table} WHERE `from_store_id` = ? || `to_store_id` = ?");
            $statement->execute(array($id, $id));
            continue;
          }
          if ($the_table == 'customer_to_store') {
            $statement = db()->prepare("SELECT `customer_id` FROM `customer_to_store` WHERE `store_id` = ?");
            $statement->execute(array($id));
            $customers = $statement->fetchAll(PDO::FETCH_ASSOC);

            // GIFT CARD
            foreach ($customers as $customer) 
            {
              $statement = db()->prepare("SELECT `id` FROM `gift_cards` WHERE `customer_id` = ?");
              $statement->execute(array($customer['customer_id']));
              $card = $statement->fetch(PDO::FETCH_ASSOC);

              $statement = db()->prepare("DELETE FROM `gift_card_topups` WHERE `card_id` = ?");
              $statement->execute(array($card['id']));

              $statement = db()->prepare("DELETE FROM `gift_cards` WHERE `customer_id` = ?");
              $statement->execute(array($customer['customer_id']));
            }
          }
          if (count(db()->query("SHOW COLUMNS FROM `{$the_table}` LIKE 'store_id'")->fetchAll())) {
            $statement = db()->prepare("DELETE FROM `{$the_table}` WHERE `store_id` = ?");
            $statement->execute(array($id));
          }
        }

        break;

      case 'insert_to':

        foreach (get_all_tables() as $table) 
        {
          $the_table = $table[0];
          if ($the_table == 'stores') {
            continue;
          }
          if ($the_table == 'transfers') 
          {
            $statement = db()->prepare("UPDATE `{$the_table}` SET `from_store_id` = ? WHERE `from_store_id` = ?");
            $statement->execute(array($new_store_id, $id));

            $statement = db()->prepare("UPDATE `{$the_table}` SET `to_store_id` = ? WHERE `to_store_id` = ?");
            $statement->execute(array($new_store_id, $id));
            continue;
          }
          if (count(db()->query("SHOW COLUMNS FROM `{$the_table}` LIKE 'store_id'")->fetchAll())) {
            $statement = db()->prepare("UPDATE `{$the_table}` SET `store_id` = ? WHERE `store_id` = ?");
            $statement->execute(array($new_store_id, $id));
          }
        }
        
        break;
    }

    // Delete store
    $the_store = $store_model->deleteStore($id);

    $Hooks->do_action('After_Delete_Store', $the_store);
    
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

// Store delete form
if (isset($request->get['store_id']) AND isset($request->get['action_type']) && $request->get['action_type'] == 'DELETE') {
    // Fetch store
    $store_info = $store_model->getStore($request->get['store_id']);
    $Hooks->do_action('Before_Store_Delete_Form', $store_info);
    include 'template/store_del_form.php';
    $Hooks->do_action('After_Store_Delete_Form', $store_info);
    exit();
}

/**
 *===================
 * START DATATABLE
 *===================
 */
 
 $Hooks->do_action('Before_Showing_Store_List');

// DB table to use
$where_query = '1=1';

if (!is_admin()) {
  $where_query = 'u2s.user_id = ' . user_id();
}
 
// DB table to use
$table = "(SELECT stores.* FROM stores 
  LEFT JOIN user_to_store u2s ON (stores.store_id = u2s.store_id) 
  WHERE $where_query GROUP by stores.store_id
  ) as stores";
 
// Table's primary key
$primaryKey = 'store_id';
 
$columns = array(
  array(
      'db' => 'store_id',
      'dt' => 'DT_RowId',
      'formatter' => function( $d, $row ) {
        return 'row_'.$d;
      }
  ),
  array( 'db' => 'store_id', 'dt' => 'store_id' ),
  array( 
    'db' => 'name',   
    'dt' => 'name' ,
    'formatter' => function($d, $row) {
        return $row['name'];
    }
  ),
  array( 'db' => 'country', 'dt' => 'country' ),
  array( 'db' => 'address', 'dt' => 'address' ),
  array( 'db' => 'sort_order', 'dt' => 'sort_order' ),
  array( 'db' => 'created_at', 'dt' => 'created_at' ),
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
      if ($row['status'] == 1) {
        return  '<span class="label label-info">'.trans('text_active').'</span>';
      }
      return '<span class="label label-warning">'.trans('text_inactivate').'</span>';
    }
  ),
  array(
    'db' => 'status',   
    'dt' => 'btn_edit' ,
    'formatter' => function($d, $row) {
      if (DEMO && $row['store_id'] == 1) {          
        return'<button class="btn btn-sm btn-block btn-default" type="button" disabled><i class="fa fa-pencil"></i></button>';
      }
      return '<a id="edit-store" class="btn btn-sm btn-block btn-primary" href="store_single.php?store_id='.$row['store_id'].'" title="'.trans('button_edit').'"><i class="fa fa-fw fa-pencil"></i></a>';
    }
  ),
  array( 
    'db' => 'status',   
    'dt' => 'btn_delete' ,
    'formatter' => function($d, $row) {

      if ((DEMO && $row['store_id'] == 2) || $row['store_id'] == 1 || store_id() == $row['store_id']) {
        return '<button class="btn btn-sm btn-block btn-default" type="button" title="'.trans('button_delete').'" disabled><i class="fa fa-fw fa-trash"></i></button>';
      }

      return '<button id="delete-store" class="btn btn-sm btn-block btn-danger" type="button" title="'.trans('button_delete').'"><i class="fa fa-fw fa-trash"></i></button>';
    }
  ),
  array( 
    'db' => 'status',   
    'dt' => 'btn_action' ,
    'formatter' => function($d, $row) {
      $store_id = $row['store_id'];
      if (store_id() ==  $store_id) {
        return '<button class="btn btn-sm btn-block btn-success" type="button" title="'.trans('button_activated').'" disabled><i class="fa fa-fw fa-check"></i></button>';
      } else {
        return '<a class="btn btn-sm btn-block btn-info activate-store" href="store.php?active_store_id='.$store_id.'" title="'.trans('button_activate').'"><i class="fa fa-fw fa-check"></i> '.trans('button_activate').'</button>';
      }
    }
  )
);

echo json_encode(
  SSP::simple($request->get, $sql_details, $table, $primaryKey, $columns)
);

$Hooks->do_action('After_Showing_Store_List');

/**
 *===================
 * END DATATABLE
 *===================
 */