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
if (user_group_id() != 1 && !has_permission('access', 'read_giftcard_topup')) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_read_permission')));
  exit();
}

$store_id = store_id();
$user_id = user_id();

// LOAD GIFTCARD MODEL
$giftcard_model = registry()->get('loader')->model('giftcard');

// Delete giftcard_topup
if ($request->server['REQUEST_METHOD'] == 'POST' AND isset($request->post['action_type']) && $request->post['action_type'] == 'DELETE') {
  try {

    // Check delete permission
    if (user_group_id() != 1 && !has_permission('access', 'delete_giftcard_topup')) {
      throw new Exception(trans('error_delete_permission'));
    }

    // Validate topup id
    if (empty($request->post['id'])) {
      throw new Exception(trans('error_topup_id'));
    }
    $id = $request->post['id'];

    // Validate topup card_no
    if (empty($request->post['card_no'])) {
      throw new Exception(trans('error_topup_card_no'));
    }

    // Validate topup amount
    if (empty($request->post['amount'])) {
      throw new Exception(trans('error_topup_amount'));
    }
    $topup_amount = (float)str_replace(',','',$request->post['amount']);

    $card_no = $request->post['card_no'];
    $giftcard = $giftcard_model->getGiftcard($card_no);
    if (!$giftcard) {
      throw new Exception(trans('error_card_not_found'));
    }

    if ($giftcard['balance'] < $request->post['amount']) {
      throw new Exception(trans('error_insufficient_balance'));
    }

    $Hooks->do_action('Before_Delete_Giftcard_Topup', $giftcard);

    // Delete and update card balance (decreate card balance)
    $statement = db()->prepare("UPDATE `gift_cards` SET `balance` = `balance`-$topup_amount WHERE `card_no` = ?");
    $statement->execute(array($card_no));

    $statement = db()->prepare("DELETE FROM `gift_card_topups` WHERE `id` = ? LIMIT 1");
    $statement->execute(array($id));

    // Substract bank transaction
    if (($account_id = store('deposit_account_id')) && $topup_amount > 0) {
      $ref_no = unique_transaction_ref_no('withdraw');
      $statement = db()->prepare("SELECT `category_id` FROM `expense_categorys` WHERE `topup_delete` = ?");
      $statement->execute(array(1));
      $category = $statement->fetch(PDO::FETCH_ASSOC);
      $exp_category_id = $category['category_id'];
      $statement = db()->prepare("SELECT `source_id` FROM `income_sources` WHERE `for_topup` = ?");
      $statement->execute(array(1));
      $source = $statement->fetch(PDO::FETCH_ASSOC);
      $source_id = $source['source_id'];
      $title = 'Debit while deleting topup';
      $details = '';
      $image = 'NULL';
      $withdraw_amount = $topup_amount;
      $transaction_type = 'withdraw';

      $statement = db()->prepare("INSERT INTO `bank_transaction_info` (store_id, is_substract, account_id, source_id, exp_category_id, ref_no, invoice_id, transaction_type, title, details, image, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $statement->execute(array($store_id, 1, $account_id, $source_id, $exp_category_id, $ref_no, $giftcard['id'], $transaction_type, $title, $details, $image, $user_id, date_time()));
	  $info_id = db()->lastInsertId();
	  
      $statement = db()->prepare("INSERT INTO `bank_transaction_price` (store_id, info_id, ref_no, amount) VALUES (?, ?, ?, ?)");
	  $statement->execute(array($store_id, $info_id, $ref_no, $withdraw_amount));

      $statement = db()->prepare("UPDATE `bank_account_to_store` SET `withdraw` = `withdraw` + $withdraw_amount WHERE `store_id` = ? AND `account_id` = ?");
      $statement->execute(array($store_id, $account_id));

      $statement = db()->prepare("UPDATE `bank_accounts` SET `total_deposit` = `total_deposit` + $withdraw_amount WHERE `id` = ?");
      $statement->execute(array($account_id));
    }

    $Hooks->do_action('After_Delete_Giftcard_Topup');

    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_topup_delete_success')));
    exit();

  } catch(Exception $e) { 
    
    $error_message = $e->getMessage();
    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $error_message));
    exit();
  }
}

/**
 *===================
 * START DATATABLE
 *===================
 */

$Hooks->do_action('Before_Showing_Giftcard_Topup_List');

$where_query = "1=1";

if (from()) {
  $from = from();
  $to = to();
  $where_query .= date_range_giftcard_topup_filter($from, $to);
}

// DB table to use
$table = "(SELECT gift_card_topups.* FROM gift_card_topups 
        WHERE $where_query) as gift_card_topups";
 
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
    'db' => 'date',   
    'dt' => 'date' ,
    'formatter' => function($d, $row) {
        return $row['date'];
    }
  ),
  array( 
    'db' => 'card_id',   
    'dt' => 'card_no' ,
    'formatter' => function($d, $row) {
        return $row['card_id'];
    }
  ),
  array( 
    'db' => 'amount',   
    'dt' => 'amount' ,
    'formatter' => function($d, $row) {
        return currency_format($row['amount']);
    }
  ),
  array( 
    'db' => 'created_by',   
    'dt' => 'created_by' ,
    'formatter' => function($d, $row) {
        return get_the_user($row['created_by'], 'username');
    }
  ),
  array(
    'db'        => 'id',
    'dt'        => 'btn_delete',
    'formatter' => function( $d, $row ) {
      return '<button id="delete-giftcard-topup" class="btn btn-sm btn-block btn-danger" type="button" title="'.trans('button_delete').'"><i class="fa fa-fw fa-trash"></i></button>';
    }
  ),
); 

echo json_encode(
    SSP::simple($request->get, $sql_details, $table, $primaryKey, $columns)
);

$Hooks->do_action('After_Showing_Giftcard_Topup_List');

/**
 *===================
 * END DATATABLE
 *===================
 */