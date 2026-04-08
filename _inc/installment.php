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
if (user_group_id() != 1 && !has_permission('access', 'read_installment')) {
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

// Delete installment
if($request->server['REQUEST_METHOD'] == 'POST' && $request->post['action_type'] == 'DELETE')
{
    try {
        
        // Check permission
        if (user_group_id() != 1 && !has_permission('access', 'delete_installment')) {
          throw new Exception(trans('error_delete_permission'));
        }

        // Validate invoice id
        if (empty($request->post['invoice_id'])) {
            throw new Exception(trans('error_invoice_id'));
        }

        $invoice_id = $request->post['invoice_id'];

        // Check, if invoice exist or not
        $statement = db()->prepare("SELECT * FROM `selling_info` WHERE `store_id` = ? AND `invoice_id` = ?");
        $statement->execute(array($store_id, $invoice_id));
        $selling_info = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$selling_info) {
            throw new Exception(trans('error_invoice_not_found'));
        }

        // Check invoice delete duration
        $selling_date_time = strtotime($selling_info['created_at']);
        if (invoice_delete_lifespan() > $selling_date_time) {
          throw new Exception(trans('error_delete_duration_expired'));
        }

        // Fetch selling invoice item
        $statement = db()->prepare("SELECT * FROM `selling_item` WHERE `store_id` = ? AND `invoice_id` = ?");
        $statement->execute(array($store_id, $invoice_id));
        $selling_items = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Check, if invoice item exist or not
        if (!$statement->rowCount()) {
            throw new Exception(trans('error_selling_item'));
        }

        $Hooks->do_action('Before_Delete_Installment', $request);

        // Delete payments
        $statement = db()->prepare("DELETE FROM  `payments` WHERE `store_id` = ? AND `invoice_id` = ?");
        $statement->execute(array($store_id, $invoice_id));

        // Delete returns
        $statement = db()->prepare("DELETE FROM  `returns` WHERE `store_id` = ? AND `invoice_id` = ?");
        $statement->execute(array($store_id, $invoice_id));

        // Delete return items
        $statement = db()->prepare("DELETE FROM  `return_items` WHERE `store_id` = ? AND `invoice_id` = ?");
        $statement->execute(array($store_id, $invoice_id));

        // Delete items
        $statement = db()->prepare("DELETE FROM `selling_item` WHERE `store_id` = ? AND `invoice_id` = ?");
        $statement->execute(array($store_id, $invoice_id));

        // Delete invoice price info
        $statement = db()->prepare("DELETE FROM  `selling_price` WHERE `store_id` = ? AND `invoice_id` = ? LIMIT 1");
        $statement->execute(array($store_id, $invoice_id));

        // Delete invoice info
        $statement = db()->prepare("DELETE FROM  `selling_info` WHERE `store_id` = ? AND `invoice_id` = ? LIMIT 1");
        $statement->execute(array($store_id, $invoice_id));

        // Delete installment orders
        $statement = db()->prepare("DELETE FROM  `installment_orders` WHERE `store_id` = ? AND `invoice_id` = ? LIMIT 1");
        $statement->execute(array($store_id, $invoice_id));

        // Delete installment payments
        $statement = db()->prepare("DELETE FROM  `installment_payments` WHERE `store_id` = ? AND `invoice_id` = ? LIMIT 1");
        $statement->execute(array($store_id, $invoice_id));

        $Hooks->do_action('After_Delete_Installment', $request);

        header('Content-Type: application/json');
        echo json_encode(array('msg' => trans('text_installment_delete_success')));
        exit();

    } catch(Exception $e) { 

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

$Hooks->do_action('Before_Showing_Installment_List');

$where_query = "installment_orders.store_id = '$store_id'";
if (isset($request->get['type']) && ($request->get['type'] != 'undefined') && $request->get['type'] != '') {
    switch ($request->get['type']) {
        case 'due':
            $where_query .= " AND installment_orders.payment_status = 'due'";
            break;
        case 'paid':
            $where_query .= " AND installment_orders.payment_status = 'paid'";
            break;
        default:
            # code...
            break;
    }
};
if (from()) {
    $from = from();
    $to = to();
    $where_query .= date_range_installment_filter($from, $to);
}

// DB table to use
$table = "(SELECT installment_orders.*, selling_info.customer_id FROM `installment_orders` 
  LEFT JOIN `selling_info` ON (installment_orders.invoice_id = selling_info.invoice_id) 
  WHERE $where_query) as installment_orders";

// Table's primary key
$primaryKey = 'id';

$columns = array(
    array( 'db' => 'id', 'dt' => 'id' ),
    array( 
      'db' => 'created_at',   
      'dt' => 'created_at' ,
      'formatter' => function($d, $row) {
        return $row['created_at'];
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
        'db' => 'customer_id',
        'dt' => 'customer_name',
        'formatter' => function( $d, $row) {
            return '<a href="customer_profile.php?customer_id=' . $row['customer_id'] . '">' . get_the_customer($row['customer_id'],'customer_name') . '</a>';
        }
    ),
    array(
        'db' => 'duration',
        'dt' => 'duration',
        'formatter' => function( $d, $row) {
            return $row['duration'] . ' Days';
        }
    ),
    array(
        'db' => 'interval_count',
        'dt' => 'interval_count',
        'formatter' => function( $d, $row) {
            return $row['interval_count'] . ' Days';
        }
    ),
    array(
        'db' => 'installment_count',
        'dt' => 'installment_count',
        'formatter' => function( $d, $row) {
            return $row['installment_count'];
        }
    ),
    array(
        'db' => 'id',
        'dt' => 'btn_view',
        'formatter' => function($d, $row) {
            return '<button class="view-installment-btn btn btn-sm btn-block btn-warning" title="'.trans('button_view_details').'" data-loading-text="..."><i class="fa fa-eye"></i></button>';
        }
    ),
    array(
        'db' => 'id',
        'dt' => 'btn_delete',
        'formatter' => function($d, $row) {
            return '<button class="delete-installment-btn btn btn-sm btn-block btn-danger" title="'.trans('button_delete').'" data-loading-text="..."><i class="fa fa-trash"></i></button>';
        }
    ),
);

echo json_encode(
    SSP::simple($request->get, $sql_details, $table, $primaryKey, $columns)
);

$Hooks->do_action('After_Showing_Installment_List');

/**
 *===================
 * END DATATABLE
 *===================
 */