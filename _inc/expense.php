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
if (user_group_id() != 1 && !has_permission('access', 'read_expense')) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_read_permission')));
  exit();
}

$store_id = store_id();
$user_id = user_id();

$ref_prefix = 'EXP';

// Validate post data
function validate_request_data($request) 
{
    // Validate category id
    if (!validateInteger($request->post['category_id'])) {
      throw new Exception(trans('error_category_id'));
    }

    // Validate title
    if (!validateString($request->post['title'])) {
      throw new Exception(trans('error_title'));
    }

    // Validate amount
    if (!validateFloat($request->post['amount'])) {
      throw new Exception(trans('error_amount'));
    }

    // Validate returnable
    if (!validateString($request->post['returnable'])) {
      throw new Exception(trans('error_returnable'));
    }
}

// Create expence
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'CREATE')
{
  try {

    // Check update permission
    if (user_group_id() != 1 && !has_permission('access', 'create_expense')) {
      throw new Exception(trans('error_create_permission'));
    }

    // Validate post data
    validate_request_data($request);

    // Validate attachment
    if(isset($_FILES["image"]["type"]) && $_FILES["image"]["type"])
    {
      if (!$_FILES["image"]["type"] == "image/jpg" || !$_FILES["image"]["type"] == "application/pdf" || $_FILES["image"]["size"] > 1048576) {  // 1MB  
          throw new Exception(trans('error_size_or_type'));
      }

      if ($_FILES["image"]["error"] > 0) {
          throw new Exception("Return Code: " . $_FILES["image"]["error"]);
      }
    }

    $Hooks->do_action('Before_Add_Expense');

    $reference_no = $request->post['reference_no'] ? $ref_prefix . $request->post['reference_no'] : $ref_prefix . unique_id();
    $created_at = date_time();
    $category_id = $request->post['category_id'];
    $title = $request->post['title'];
    $attachment = $request->post['image'];
    $amount = $request->post['amount'];
    $returnable = $request->post['returnable'];
    $note = $request->post['note'];

    // Check for dublicate
    $statement = db()->prepare("SELECT * FROM `expenses` WHERE `reference_no` = ?");
    $statement->execute(array($reference_no));
    $row = $statement->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        throw new Exception(trans('error_reference_no_alrady_exist'));
    }

    // Insert into purchase info
    $statement = db()->prepare("INSERT INTO `expenses` (store_id, reference_no, category_id, title, amount, returnable, note, attachment, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $statement->execute(array($store_id, $reference_no, $category_id, $title, $amount, $returnable, $note, $attachment, $user_id, $created_at));
    $id = db()->lastInsertId();

    // Withdraw
    if (($account_id = store('deposit_account_id')) && $amount > 0) {
      $ref_no = unique_transaction_ref_no('withdraw');
      $exp_category_id = $category_id;
      $title = 'Debit for '.get_the_expense_category($category_id,'category_name').' Expense';
      $details = '';
      $image = 'NULL';
      $withdraw_amount = $amount;
      $transaction_type = 'withdraw';

      $statement = db()->prepare("INSERT INTO `bank_transaction_info` (store_id, account_id, exp_category_id, ref_no, invoice_id, transaction_type, title, details, image, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $statement->execute(array($store_id, $account_id, $exp_category_id, $ref_no, $reference_no, $transaction_type, $title, $details, $image, $user_id, $created_at));
	  $info_id = db()->lastInsertId();
	  
      $statement = db()->prepare("INSERT INTO `bank_transaction_price` (store_id, info_id, ref_no, amount) VALUES (?, ?, ?, ?)");
      $statement->execute(array($store_id, $info_id, $ref_no, $withdraw_amount));

      $statement = db()->prepare("UPDATE `bank_account_to_store` SET `withdraw` = `withdraw` + $withdraw_amount WHERE `store_id` = ? AND `account_id` = ?");
      $statement->execute(array($store_id, $account_id));

      $statement = db()->prepare("UPDATE `bank_accounts` SET `total_deposit` = `total_deposit` + $withdraw_amount WHERE `id` = ?");
      $statement->execute(array($account_id));
    }

    $Hooks->do_action('After_Add_Expense', $id);

    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_success'), 'id' => $id));
    exit();

  } catch (Exception $e) { 

    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();
  }
} 

// Update expense
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'UPDATE')
{
  try {

    // Check update permission
    if (user_group_id() != 1 && !has_permission('access', 'update_expense')) {
      throw new Exception(trans('error_update_permission'));
    }

    // Validate category id
    if (!validateInteger($request->post['category_id'])) {
      throw new Exception(trans('error_category_id'));
    }

    // Validate title
    if (!validateString($request->post['title'])) {
      throw new Exception(trans('error_title'));
    }

    // Validate amount
    if (!validateFloat($request->post['amount'])) {
      throw new Exception(trans('error_amount'));
    }

    // Validate returnable
    if (!validateString($request->post['returnable'])) {
      throw new Exception(trans('error_returnable'));
    }

    $id = $request->post['id'];
    if (empty($id)) {
        throw new Exception(trans('error_id'));
    }

    // Validate attachment
    if(isset($_FILES["image"]["type"]) && $_FILES["image"]["type"])
    {
      if (!$_FILES["image"]["type"] == "image/jpg" || !$_FILES["image"]["type"] == "application/pdf" || $_FILES["image"]["size"] > 1048576) {  // 1MB  
          throw new Exception(trans('error_size_or_type'));
      }

      if ($_FILES["image"]["error"] > 0) {
          throw new Exception("Return Code: " . $_FILES["image"]["error"]);
      }
    }

    $category_id = $request->post['category_id'];
    $title = $request->post['title'];
    $attachment = $request->post['image'];
    $amount = $request->post['amount'];
    $returnable = $request->post['returnable'];
    $note = $request->post['note'];

    $Hooks->do_action('Before_Update_Expense', $request);

    // Update expense
    $statement = db()->prepare("UPDATE `expenses` SET `category_id` = ?, `title` = ?, `amount` = ?, `returnable` = ?, `note` = ?, `attachment` = ?, `created_by` = ? WHERE `id` = ?");
    $statement->execute(array($category_id, $title, $amount, $returnable, $note, $attachment, $user_id, $id));

    // Withdraw
    if (($account_id = store('deposit_account_id')) && $amount > 0) {

      $statement = db()->prepare("SELECT * FROM `expenses` WHERE `id` = ?");
      $statement->execute(array($id));
      $row = $statement->fetch(PDO::FETCH_ASSOC);
      $reference_no = $row['reference_no'];
      if ($row) {
        $statement = db()->prepare("SELECT * FROM `bank_transaction_info` WHERE `invoice_id` = ?");
        $statement->execute(array($reference_no));
        $info = $statement->fetch(PDO::FETCH_ASSOC);
        $ref_no = $info['ref_no'];

        $statement = db()->prepare("DELETE FROM `bank_transaction_info` WHERE `invoice_id` = ? LIMIT 1");
        $statement->execute(array($reference_no));

        $statement = db()->prepare("SELECT * FROM `bank_transaction_price` WHERE `ref_no` = ?");
        $statement->execute(array($ref_no));
        $price = $statement->fetch(PDO::FETCH_ASSOC);
        $withdraw_amount = $price['amount'];

        $statement = db()->prepare("DELETE FROM `bank_transaction_price` WHERE `ref_no` = ? LIMIT 1");
        $statement->execute(array($ref_no));

        $statement = db()->prepare("UPDATE `bank_account_to_store` SET `withdraw` = `withdraw` - $withdraw_amount WHERE `store_id` = ? AND `account_id` = ?");
        $statement->execute(array($store_id, $account_id));

        $statement = db()->prepare("UPDATE `bank_accounts` SET `total_deposit` = `total_deposit` - $withdraw_amount WHERE `id` = ?");
        $statement->execute(array($account_id));
      }


      $ref_no = unique_transaction_ref_no('withdraw');
      $exp_category_id = $category_id;
      $title = 'Debit for '.get_the_expense_category($category_id,'category_name').' Expense';
      $details = '';
      $image = 'NULL';
      $withdraw_amount = $amount;
      $transaction_type = 'withdraw';

      $statement = db()->prepare("INSERT INTO `bank_transaction_info` (store_id, account_id, exp_category_id, ref_no, invoice_id, transaction_type, title, details, image, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $statement->execute(array($store_id, $account_id, $exp_category_id, $ref_no, $reference_no, $transaction_type, $title, $details, $image, $user_id, date_time()));
	  $info_id = db()->lastInsertId();
	  
      $statement = db()->prepare("INSERT INTO `bank_transaction_price` (store_id, info_id, ref_no, amount) VALUES (?, ?, ?, ?)");
	  $statement->execute(array($store_id, $info_id, $ref_no, $withdraw_amount));


      $statement = db()->prepare("UPDATE `bank_account_to_store` SET `withdraw` = `withdraw` + $withdraw_amount WHERE `store_id` = ? AND `account_id` = ?");
      $statement->execute(array($store_id, $account_id));

      $statement = db()->prepare("UPDATE `bank_accounts` SET `total_deposit` = `total_deposit` + $withdraw_amount WHERE `id` = ?");
      $statement->execute(array($account_id));
    }

    $Hooks->do_action('After_Update_Expense', $id);

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

// Delete expense
if($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'DELETE') 
{
  try {

    // Check delete permission
    if (user_group_id() != 1 && !has_permission('access', 'delete_expense')) {
      throw new Exception(trans('error_delete_permission'));
    }

    // Validate invoice id
    if (empty($request->post['id'])) {
      throw new Exception(trans('error_id'));
    }

    $Hooks->do_action('Before_Delete_Expense', $request);

    $id = $request->post['id'];
    $statement = db()->prepare("SELECT `amount` FROM `expenses` WHERE `id` = ?");
    $statement->execute(array($id));
    $expense = $statement->fetch(PDO::FETCH_ASSOC);
    $amount = isset($expense['amount']) ? $expense['amount'] : 0;

    // Delete invoice info
    $statement = db()->prepare("DELETE FROM  `expenses` WHERE `store_id` = ? AND `id` = ? LIMIT 1");
    $statement->execute(array($store_id, $id));

    // Deposit
    $deposit_amount = $amount;
    if (($account_id = store('deposit_account_id')) && $deposit_amount > 0) {
      $ref_no = unique_transaction_ref_no();
      $statement = db()->prepare("SELECT `source_id` FROM `income_sources` WHERE `for_expense_delete` = ?");
      $statement->execute(array(1));
      $source = $statement->fetch(PDO::FETCH_ASSOC);
      $source_id = $source['source_id'];
      $title = 'Deposit for expense delete';
      $details = '';
      $image = 'NULL';
      $transaction_type = 'deposit';

      $statement = db()->prepare("INSERT INTO `bank_transaction_info` (store_id, account_id, source_id, ref_no, transaction_type, title, details, image, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $statement->execute(array($store_id, $account_id, $source_id, $ref_no, $transaction_type, $title, $details, $image, $user_id, date_time()));
        $info_id = db()->lastInsertId();
        
      $statement = db()->prepare("INSERT INTO `bank_transaction_price` (store_id, info_id, ref_no, amount) VALUES (?, ?, ?, ?)");
      $statement->execute(array($store_id, $info_id, $ref_no, $deposit_amount));

      $statement = db()->prepare("UPDATE `bank_account_to_store` SET `deposit` = `deposit` + $deposit_amount WHERE `store_id` = ? AND `account_id` = ?");
      $statement->execute(array($store_id, $account_id));

      $statement = db()->prepare("UPDATE `bank_accounts` SET `total_deposit` = `total_deposit` + $deposit_amount WHERE `id` = ?");
      $statement->execute(array($account_id));
    }

    $Hooks->do_action('After_Delete_Expense', $id);
    
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

// View expense
if (isset($request->get['action_type']) && $request->get['action_type'] == 'SUMMARY') 
{
  $to = date('Y-m-d H:i:s');

  // Summary
    $statement = db()->prepare("SELECT `expenses`.`created_at`, `expenses`.`category_id`, SUM(`expenses`.`amount`) as total, `expense_categorys`.`category_name`, `expense_categorys`.`category_slug`, `expense_categorys`.`category_details`, `expense_categorys`.`parent_id` FROM `expenses` LEFT JOIN `expense_categorys` ON (`expenses`.`category_id` = `expense_categorys`.`category_id`) GROUP BY `expenses`.`category_id` ORDER BY `total` DESC");
    $statement->execute(array());
    $summary = $statement->fetchAll(PDO::FETCH_ASSOC);

  // This Week
    $from = date('Y-m-d H:i:s',strtotime(date("Y-m-d", time()) . " - 7 day"));
    $statement = db()->prepare("SELECT `expenses`.`created_at`, `expenses`.`category_id`, SUM(`expenses`.`amount`) as total, `expense_categorys`.`category_name`, `expense_categorys`.`category_slug`, `expense_categorys`.`category_details`, `expense_categorys`.`parent_id` FROM `expenses` LEFT JOIN `expense_categorys` ON (`expenses`.`category_id` = `expense_categorys`.`category_id`) WHERE `expenses`.`created_at` >= '{$from}' AND `expenses`.`created_at` <= '{$to}' GROUP BY `expenses`.`category_id` ORDER BY `total` DESC");
    $statement->execute(array());
    $week_summary = $statement->fetchAll(PDO::FETCH_ASSOC);

  // This Month
    $from = date('Y-m-d H:i:s',strtotime(date("Y-m-d", time()) . " - 30 day"));
    $statement = db()->prepare("SELECT `expenses`.`created_at`, `expenses`.`category_id`, SUM(`expenses`.`amount`) as total, `expense_categorys`.`category_name`, `expense_categorys`.`category_slug`, `expense_categorys`.`category_details`, `expense_categorys`.`parent_id` FROM `expenses` LEFT JOIN `expense_categorys` ON (`expenses`.`category_id` = `expense_categorys`.`category_id`) WHERE `expenses`.`created_at` >= '{$from}' AND `expenses`.`created_at` <= '{$to}' GROUP BY `expenses`.`category_id` ORDER BY `total` DESC");
    $statement->execute(array());
    $month_summary = $statement->fetchAll(PDO::FETCH_ASSOC);

  // This Year
    $from = date('Y-m-d H:i:s',strtotime(date("Y-m-d", time()) . " - 365 day"));
    $statement = db()->prepare("SELECT `expenses`.`created_at`, `expenses`.`category_id`, SUM(`expenses`.`amount`) as total, `expense_categorys`.`category_name`, `expense_categorys`.`category_slug`, `expense_categorys`.`category_details`, `expense_categorys`.`parent_id` FROM `expenses` LEFT JOIN `expense_categorys` ON (`expenses`.`category_id` = `expense_categorys`.`category_id`) WHERE `expenses`.`created_at` >= '{$from}' AND `expenses`.`created_at` <= '{$to}' GROUP BY `expenses`.`category_id` ORDER BY `total` DESC");
    $statement->execute(array());
    $year_summary = $statement->fetchAll(PDO::FETCH_ASSOC);


    include 'template/expense_summary.php';
    exit();
}

// View expense
if (isset($request->get['id']) && isset($request->get['action_type']) && $request->get['action_type'] == 'VIEW') 
{
    $id = $request->get['id'];
    $statement = db()->prepare("SELECT * FROM `expenses` WHERE `id` = ?");
    $statement->execute(array($id));
    $expense = $statement->fetch(PDO::FETCH_ASSOC);
    include 'template/expense_view.php';
    exit();
}

// Expense edit form
if (isset($request->get['id']) AND isset($request->get['action_type']) && $request->get['action_type'] == 'EDIT') {
    try {
        $id = $request->get['id'];
        if (empty($id)) {
            throw new Exception(trans('error_id'));
        }
        $statement = db()->prepare("SELECT * FROM `expenses` WHERE `id` = ?");
        $statement->execute(array($id));
        $expense = $statement->fetch(PDO::FETCH_ASSOC);
        include 'template/expense_edit_form.php';
        exit();

    } catch (Exception $e) { 

        header('HTTP/1.1 422 Unprocessable Entity');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array('errorMsg' => $e->getMessage()));
        exit();
      }
}

/**
 *===================
 * START DATATABLE
 *===================
 */

$Hooks->do_action('Before_Showing_Expense_List');

$where_query = "store_id = {$store_id}";
if (from()) {
  $from = from();
  $to = to();
  $where_query .= date_range_expense_filter($from, $to);
}

// DB table to use
$table = "(SELECT * FROM expenses 
  WHERE $where_query GROUP by id
  ) as expenses";
 
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
    'db' => 'category_id',   
    'dt' => 'category_name',
    'formatter' => function($d, $row) {
        $parent = '';
        $category = get_the_expense_category($row['category_id']);
        if ($category['parent_id']) {
            $parent = get_the_expense_category($category['parent_id']);
            $parent = $parent['category_name'] .  ' > ';
        }
        $category = get_the_expense_category($row['category_id']);
        return $parent . $category['category_name'];
    }
  ),
  array( 'db' => 'reference_no', 'dt' => 'reference_no' ),
  array( 'db' => 'title', 'dt' => 'title' ),
  array( 
    'db' => 'amount',   
    'dt' => 'amount',
    'formatter' => function($d, $row) {
      return currency_format($row['amount']);
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
    'db'        => 'id',
    'dt'        => 'btn_view',
    'formatter' => function( $d, $row ) {
      return '<button id="view-expense-btn" class="btn btn-sm btn-block btn-info" type="button" title="'.trans('button_viefw').'"><i class="fa fa-fw fa-eye"></i></button>';
    }
  ),
  array(
    'db'        => 'id',
    'dt'        => 'btn_edit',
    'formatter' => function( $d, $row ) {
      return '<button id="edit-expense-btn" class="btn btn-sm btn-block btn-primary" type="button" title="'.trans('button_edit').'"><i class="fa fa-fw fa-pencil"></i></button>';
    }
  ),
  array(
    'db'        => 'id',
    'dt'        => 'btn_delete',
    'formatter' => function( $d, $row ) {
      return '<button id="delete-expense-btn" class="btn btn-sm btn-block btn-danger" type="button" title="'.trans('button_delete').'"><i class="fa fa-fw fa-trash"></i></button>';
    }
  )
); 

echo json_encode(
    SSP::simple($request->get, $sql_details, $table, $primaryKey, $columns)
);

$Hooks->do_action('After_Showing_Expense_List');

/**
 *===================
 * END DATATABLE
 *===================
 */