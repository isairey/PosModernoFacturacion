<?php 
ob_start();
session_start();
include ("../_init.php");

// Check, if user logged in or not
// If user is not logged in then an alert message
if (!is_loggedin()) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_login')));
  exit();
}

// Check, if user has reading permission or not
// If user have not reading permission an alert message
if (user_group_id() != 1 && !has_permission('access', 'read_income_source')) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_read_permission')));
  exit();
}

// LOAD CATEGORY MODEL
$income_source_model = registry()->get('loader')->model('incomesource');

// Validate post data
function validate_request_data($request) 
{
  // Validate income_source name
  if (!validateString($request->post['source_name'])) {
    throw new Exception(trans('error_source_name'));
  }

  // Validate income_source designation
  if (!validateString($request->post['source_slug'])) {
    throw new Exception(trans('error_source_slug'));
  }

  // Sort order validation
  if (!is_numeric($request->post['status'])) {
    throw new Exception(trans('error_status'));
  }

  // Sort order validation
  if (!is_numeric($request->post['sort_order'])) {
    throw new Exception(trans('error_sort_order'));
  }
}

// Check income_source existance by id
function validate_existance($request, $source_id = 0)
{
  

  // Check email address, if exist or not?
  if (!empty($request->post['source_slug'])) {
    $statement = db()->prepare("SELECT * FROM `income_sources` WHERE `source_slug` = ? AND `source_id` != ?");
    $statement->execute(array($request->post['source_slug'], $source_id));
    if ($statement->rowCount() > 0) {
      throw new Exception(trans('error_income_source_exist'));
    }
  }
}

// Create income_source
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'CREATE')
{
  try {

    // Check permission
    if (user_group_id() != 1 && !has_permission('access', 'create_income_source')) {
      throw new Exception(trans('error_create_permission'));
    }

    // Validate post data
    validate_request_data($request);
    
    // Validte existance
    validate_existance($request);

    $Hooks->do_action('Before_Create_Income_Source');

    // Insert new income_source into databtase
    $source_id = $income_source_model->addIncomeSource($request->post);

    // Fetch income_source info
    $income_source = $income_source_model->getIncomeSource($source_id);

    $Hooks->do_action('After_Create_Income_Source', $income_source);

    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_create_success'), 'id' => $source_id, 'income_source' => $income_source));
    exit();

  } catch (Exception $e) { 

    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();
  }
}

// Update income_source
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'UPDATE')
{
  try {

    // Check update permission
    if (user_group_id() != 1 && !has_permission('access', 'update_income_source')) {
      throw new Exception(trans('error_update_permission'));
    }

    // Validate product id
    if (empty($request->post['source_id'])) {
      throw new Exception(trans('error_id'));
    }

    $source_id = $request->post['source_id'];

    // Validate post data
    validate_request_data($request);

    // Validte existance
    validate_existance($request, $source_id);

    $Hooks->do_action('Before_Update_Income_Source', $request);
    
    // Edit income_source
    $income_source = $income_source_model->editIncomeSource($source_id, $request->post);

    $Hooks->do_action('After_Update_Income_Source', $income_source);

    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_update_income_source_success'), 'id' => $source_id));
    exit();

  } catch (Exception $e) { 
    
    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();
  }
} 

// Delete income_source
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'DELETE') 
{
  try {

    // Check delete permission
    if (user_group_id() != 1 && !has_permission('access', 'delete_income_source')) {
      throw new Exception(trans('error_delete_permission'));
    }

    // Validate income_source id
    if (empty($request->post['source_id'])) {
      throw new Exception(trans('error_id'));
    }

    $source_id = $request->post['source_id'];
    $the_income_source = $income_source_model->getIncomeSource($source_id);

    if (DEMO && $source_id == 1) {
      throw new Exception(trans('error_delete_permission'));
    }

    if (!$the_income_source) {
      throw new Exception(trans('error_id'));
    }

    $new_source_id = $request->post['new_source_id'];

    // Validte delete action
    if (empty($request->post['delete_action'])) {
      throw new Exception(trans('error_delete_action'));
    }

    if ($request->post['delete_action'] == 'insert_to' && empty($new_source_id)) {
      throw new Exception(trans('error_new_source_name'));
    }

    $Hooks->do_action('Before_Delete_Income_Source', $request);

    if ($request->post['delete_action'] == 'insert_to') {
      $income_source_model->replaceWith($new_source_id, $source_id);
    } 

    // Delete income_source
    $income_source = $income_source_model->deleteIncomeSource($source_id);

    $Hooks->do_action('After_Delete_Income_Source', $income_source);

    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_delete_income_source_success'), 'id' => $source_id));
    exit();

  } catch (Exception $e) {

    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();
  }
}

// View invoice details
if (isset($request->get['action_type']) AND $request->get['action_type'] == 'INVOICEDETAILS') {

    try {

      $source_id = isset($request->get['source_id']) ? $request->get['source_id'] : null;
      $where_query = "((`selling_info`.`invoice_type` = 'sell' AND `selling_info`.`edit_count` < 1) OR `selling_info`.`invoice_type` = 'sell_edit')  AND `selling_item`.`id` = ?  AND `invoice_status` = ?";
      $from = from() ? from() : date('Y-m-d');
      $to = to() ? to() : date('Y-m-d');
      $where_query .= date_range_filter($from, $to);

      $statement = db()->prepare("SELECT `selling_info`.*, `selling_item`.`id`, SUM(`selling_item`.`item_total_price`) AS `item_total_price`, SUM(`selling_item`.`item_discount`) AS `item_discount` FROM `selling_item` 
          LEFT JOIN `selling_info` ON (`selling_item`.`invoice_id` = `selling_info`.`invoice_id`)
          WHERE $where_query GROUP BY `selling_item`.`invoice_id`");
      $statement->execute(array($source_id, 1));
      $the_invoices = $statement->fetchAll(PDO::FETCH_ASSOC);
      if (!$statement->rowCount() > 0) {
          throw new Exception(trans('error_not_found'));
      }

      $invoices = array();
      $from = date('Y-m-d H:i:s', strtotime($from.' '.'00:00:00')); 
      $to = date('Y-m-d H:i:s', strtotime($to.' '.'23:59:59'));
      foreach ($the_invoices as $invoice) {
        if (!$invoice['ref_invoice_id']) {
          $invoices[$invoice['invoice_id']] = $invoice;
          continue;
        }
        $ref_invoice = get_the_invoice($invoice['ref_invoice_id']);
        if ($from == $to) {
            if (date('Y-m-d', strtotime($ref_invoice['created_at'])) == date('Y-m-d')) {
                $invoices[$ref_invoice['invoice_id']] = $invoice;
            }
        } elseif ((date('Y-m-d H:i:s', strtotime($ref_invoice['created_at'])) >= $from) && (date('Y-m-d H:i:s', strtotime($ref_invoice['created_at'])) <= $to)) {
            $invoices[$ref_invoice['invoice_id']] = $invoice;
        }
      }

      include('template/income_source_invoice_details.php');
      exit();
        
    } catch (Exception $e) { 

      header('HTTP/1.1 422 Unprocessable Entity');
      header('Content-Type: application/json; charset=UTF-8');
      echo json_encode(array('errorMsg' => $e->getMessage()));
      exit();
    }
}

// Expense_source create form
if (isset($request->get['action_type']) && $request->get['action_type'] == 'CREATE') {
  include 'template/income_source_create_form.php';
  exit();
}

// Expense_source edit form
if (isset($request->get['source_id']) AND isset($request->get['action_type']) && $request->get['action_type'] == 'EDIT') {
  $income_source = $income_source_model->getIncomeSource($request->get['source_id']);
  include 'template/income_source_edit_form.php';
  exit();
}

// Expense_source delete form
if (isset($request->get['source_id']) AND isset($request->get['action_type']) && $request->get['action_type'] == 'DELETE') {
  $income_source = $income_source_model->getIncomeSource($request->get['source_id']);
  include 'template/income_source_delete_form.php';
  exit();
}

/**
 *===================
 * START DATATABLE
 *===================
 */

$Hooks->do_action('Before_Showing_Income_Source_List');

$where_query = 'is_hide != 1';

// DB table to use
$table = "(SELECT * FROM income_sources 
  WHERE $where_query GROUP by source_id
  ) as income_sources";
 
// Table's primary key
$primaryKey = 'source_id';

$columns = array(
  array(
      'db' => 'source_id',
      'dt' => 'DT_RowId',
      'formatter' => function( $d, $row ) {
          return 'row_'.$d;
      }
  ),
  array( 'db' => 'source_id', 'dt' => 'source_id' ),
  array( 'db' => 'parent_id', 'dt' => 'parent_id' ),
  array( 
    'db' => 'source_name',   
    'dt' => 'source_name',
    'formatter' => function($d, $row) {
      $name = '';
      $parent = get_the_income_source($row['parent_id']);
      if (isset($parent['source_name'])) {
        $name = $parent['source_name'] .  ' > ';
      }
      return $name . $row['source_name'];
    }
  ),
  array( 'db' => 'source_slug', 'dt' => 'source_slug' ),
  array( 
    'db' => 'source_id',   
    'dt' => 'total_item',
    'formatter' => function($d, $row) use($income_source_model) {
      return $income_source_model->totalItem($row['source_id']);
    }
  ),
  array( 
    'db' => 'sort_order',   
    'dt' => 'sort_order',
    'formatter' => function($d, $row) {
      return $row['sort_order'];
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
    'db' => 'created_at',   
    'dt' => 'created_at' ,
    'formatter' => function($d, $row) {
        return $row['created_at'];
    }
  ),
  array(
    'db'        => 'source_id',
    'dt'        => 'btn_edit',
    'formatter' => function( $d, $row ) {
      return '<button id="edit-income-source" class="btn btn-sm btn-block btn-primary" type="button" title="'.trans('button_edit').'"><i class="fa fa-fw fa-pencil"></i></button>';
    }
  ),
  array(
    'db'        => 'source_id',
    'dt'        => 'btn_delete',
    'formatter' => function( $d, $row ) {
      return '<button id="delete-income-source" class="btn btn-sm btn-block btn-danger" type="button" title="'.trans('button_delete').'"><i class="fa fa-fw fa-trash"></i></button>';
    }
  )
); 

echo json_encode(
    SSP::simple($request->get, $sql_details, $table, $primaryKey, $columns)
);

$Hooks->do_action('After_Showing_Income_Source_List');

/**
 *===================
 * END DATATABLE
 *===================
 */