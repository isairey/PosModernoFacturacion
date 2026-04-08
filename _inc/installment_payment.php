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
if (user_group_id() != 1 && !has_permission('access', 'installment_payment')) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_read_permission')));
  exit();
}

$store_id = store_id();
$user_id = user_id();

// Validate POST Data
function validate_request_data($request) {

  // ID validation
  if (!validateInteger($request->post['id'])) {
      throw new Exception(trans('error_id'));
  }

  // Pay amount validation
  if (!validateFloat($request->post['amount'])) {
      throw new Exception(trans('error_amount'));
  }
}

// Installment Paymemnt
if ($request->server['REQUEST_METHOD'] == 'POST' AND isset($request->post['action_type']) && $request->post['action_type'] == 'PAY')
{
  try {

    // Check update permission
    if (user_group_id() != 1 && !has_permission('access', 'installment_payment')) {
      throw new Exception(trans('error_installment_payment'));
    }

    // Validate box id
    if (empty($request->post['id'])) {
      throw new Exception(trans('error_id'));
    }

    $id = $request->post['id'];
    $statement = db()->prepare("SELECT * FROM `installment_payments` WHERE `id` = ?");
    $statement->execute(array($id));
    $row = $statement->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        throw new Exception(trans('error_record_not_found'));
    }
    $invoice_id = $row['invoice_id'];

    $amount = $request->post['amount'];
    $note = $request->post['note'];
    $payment_status = $row['payment_status'];
    $the_payment_status = 'due';
    $due = $row['due'];

    if($amount > $row['due']) {
        throw new Exception(trans('error_amount_exceed'));
    }

    if ($amount < $row['due']) {
        $due = $row['due'] - $amount;
    }

    if ($amount == $row['due'])
    {
        $due = 0;
        $payment_status = 'paid';
        $the_payment_status = 'paid';
    }

    // Validate post data
    validate_request_data($request);

    $Hooks->do_action('Before_Installment_payment', $request);

    $statement = db()->prepare("UPDATE `installment_payments` SET `payment_date` = ?, `paid` = `paid`+$amount, `note` = ?, `due` = ?, `payment_status` = ? WHERE `id` = ? LIMIT 1");
    $statement->execute(array(date_time(), $note, $due, $payment_status, $id));

    $statement = db()->prepare("UPDATE `installment_orders` SET `last_installment_date` = ?");
    $statement->execute(array(date_time()));

    $statement = db()->prepare("SELECT * FROM `installment_payments` WHERE `invoice_id` = ? AND `payment_status` = ?");
    $statement->execute(array($invoice_id, 'due'));
    if (!$statement->rowCount() > 0) {
        $statement = db()->prepare("UPDATE `installment_orders` SET `payment_status` = ?");
        $statement->execute(array($the_payment_status));
        $statement = db()->prepare("UPDATE `selling_info` SET `payment_status` = ? WHERE `invoice_id` = ? LIMIT 1");
        $statement->execute(array($payment_status, $invoice_id));
    }

    $statement = db()->prepare("UPDATE `selling_price` SET `paid_amount` = `paid_amount`+$amount, `due_paid` = `due_paid`+$amount, `due` = `due`-$amount WHERE `invoice_id` = ?");
    $statement->execute(array($invoice_id));

    $capital = $row['capital'];

    if ($amount > 0) {
      $statement = db()->prepare("INSERT INTO `payments` (type, store_id, invoice_id, pmethod_id, capital, amount, details, note, total_paid, pos_balance, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $statement->execute(array('due_paid', $store_id, $invoice_id, 1, $capital, $amount, '', $note, $amount, 0, $user_id, date_time()));
    }

    // Deposit
    if (($account_id = store('deposit_account_id')) && $amount > 0) {
        $ref_no = unique_transaction_ref_no();
        $source_id = 1;
        $title = 'Deposit for installment';
        $details = '';
        $image = 'NULL';
        $deposit_amount = $amount;
        $transaction_type = 'deposit';

        $statement = db()->prepare("INSERT INTO `bank_transaction_info` (store_id, account_id, source_id, ref_no, invoice_id, transaction_type, title, details, image, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $statement->execute(array($store_id, $account_id, $source_id, $ref_no, $invoice_id, $transaction_type, $title, $details, $image, $user_id, date_time()));
		$info_id = db()->lastInsertId();
		
        $statement = db()->prepare("INSERT INTO `bank_transaction_price` (store_id, info_id, ref_no, amount) VALUES (?, ?, ?, ?)");
        $statement->execute(array($store_id, $info_id, $ref_no, $deposit_amount));

        $statement = db()->prepare("UPDATE `bank_account_to_store` SET `deposit` = `deposit` + $deposit_amount WHERE `store_id` = ? AND `account_id` = ?");
        $statement->execute(array($store_id, $account_id));

        $statement = db()->prepare("UPDATE `bank_accounts` SET `total_deposit` = `total_deposit` + $deposit_amount WHERE `id` = ?");
        $statement->execute(array($account_id));
    }

    $Hooks->do_action('After_Installment_payment', $id);
    
    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_update_installment_payment_success'), 'id' => $id));
    exit();

  } catch (Exception $e) { 

    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();
  }
}

// Payment Form
if (isset($request->get['id']) && isset($request->get['action_type']) && $request->get['action_type'] == 'PAY') 
{
    $id = $request->get['id'];
    $statement = db()->prepare("SELECT * FROM `installment_payments` WHERE `id` = ?");
    $statement->execute(array($id));
    $payment = $statement->fetch(PDO::FETCH_ASSOC);
    if (!$payment) {
        throw new Exception(trans('error_record_not_found'));
    }
    include ROOT.'/_inc/template/installment_payment_form.php';
    exit();
}

// View Details
if (isset($request->get['invoice_id']) && isset($request->get['action_type']) && $request->get['action_type'] == 'VIEW') 
{
    $invoice_id = $request->get['invoice_id'];
    $statement = db()->prepare("SELECT * FROM `installment_orders` 
        LEFT JOIN `selling_info` ON `installment_orders`.`invoice_id` = `selling_info`.`invoice_id` 
        LEFT JOIN `selling_price` ON `installment_orders`.`invoice_id` = `selling_price`.`invoice_id`
        WHERE `installment_orders`.`invoice_id` = ?");
    $statement->execute(array($invoice_id));
    $invoice = $statement->fetch(PDO::FETCH_ASSOC);
   
    $statement = db()->prepare("SELECT * FROM `installment_payments` WHERE `invoice_id` = ?");
    $statement->execute(array($invoice_id));
    $payments = $statement->fetchAll(PDO::FETCH_ASSOC);
    include ROOT.'/_inc/template/installment_view.php';
    exit();
}

/**
 *===================
 * START DATATABLE
 *===================
 */

$Hooks->do_action('Before_Showing_Installment_PaymentList');

$where_query = "installment_payments.store_id = '$store_id'";
if (isset($request->get['type']) && ($request->get['type'] != 'undefined') && $request->get['type'] != '') {
    switch ($request->get['type']) {
        case 'todays_due_payment':
            $where_query .= " AND installment_payments.payment_status = 'due'";
            $day = date('d');
            $month = date('m');
            $year = date('Y');
            $where_query .= " AND DAY(`installment_payments`.`payment_date`) = $day";
            $where_query .= " AND MONTH(`installment_payments`.`payment_date`) = $month";
            $where_query .= " AND YEAR(`installment_payments`.`payment_date`) = $year";
            break;
        case 'expired_due_payment':
            $where_query .= " AND installment_payments.payment_status = 'due'";
            $from = date('Y-m-d H:i:s', strtotime(date('Y-m-d').' '. '00:00:00')); 
            $where_query .= " AND installment_payments.payment_date < '{$from}'";
            break;
        case 'all_due_payment':
            $where_query .= " AND installment_payments.payment_status = 'due'";
            break;
        case 'paid':
            $where_query .= " AND installment_payments.payment_status = 'paid'";
            break;
    }
};
if (from()) {
    $from = from();
    $to = to();
    $where_query .= date_range_installment_payment_filter($from, $to);
}

// DB table to use
$table = "(SELECT installment_payments.* FROM installment_payments
  WHERE {$where_query}) as installment_payments";

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
      'db' => 'payment_date',   
      'dt' => 'payment_date' ,
      'formatter' => function($d, $row) {
        return $row['payment_date'];
      }
    ),
    array(
        'db' => 'invoice_id',
        'dt' => 'invoice_id',
        'formatter' => function( $d, $row) {
            return $row['invoice_id'];
        }
    ),
    array(
        'db' => 'invoice_id',
        'dt' => 'ref_no',
        'formatter' => function( $d, $row) {
            return '<a class="view-installment-btn pointer" title="'.trans('button_view_details').'" data-loading-text="...">'.$row['invoice_id'].'</a>';
        }
    ),
    array(
        'db' => 'note',
        'dt' => 'note',
        'formatter' => function( $d, $row) {
            return $row['note'];
        }
    ),
    array(
        'db' => 'payable',
        'dt' => 'payable',
        'formatter' => function( $d, $row) {
            return currency_format($row['payable']);
        }
    ),
    array(
        'db' => 'paid',
        'dt' => 'paid',
        'formatter' => function( $d, $row) {
            return currency_format($row['paid']);
        }
    ),
    array(
        'db' => 'due',
        'dt' => 'due',
        'formatter' => function( $d, $row) {
            return currency_format($row['due']);
        }
    ),
    array(
        'db' => 'payment_status',
        'dt' => 'payment_status',
        'formatter' => function( $d, $row) {
            return $row['payment_status'] == 'paid' ? '<span class="label label-success">'.ucfirst($row['payment_status']).'</span>' : '<span class="label label-danger">'.ucfirst($row['payment_status']).'</span>';
        }
    ),
    array(
        'db' => 'invoice_id',
        'dt' => 'btn_payment',
        'formatter' => function( $d, $row) {
            if ($row['payment_status'] == 'paid') {
                return '-';
            }
            return '<button class="btn btn-sm btn-block btn-success payment-btn" title="'.trans('button_installment_payment').'" data-loading-text="..."><i class="fa fa-money"></i></button>';
        }
    ),
);

echo json_encode(
    SSP::simple($request->get, $sql_details, $table, $primaryKey, $columns)
);

$Hooks->do_action('After_Showing_Installment_PaymentList');

/**
 *===================
 * END DATATABLE
 *===================
 */