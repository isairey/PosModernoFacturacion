<?php 
ob_start();
session_start();
require_once dirname(__FILE__) . '/../_init.php';

if (DEMO) {
	die('Disabled in demo');
}

$action = '';
if (isset($request->get['action'])) {
	$action = $request->get['action'];
} elseif (isset($request->post['action'])) {
	$action = $request->post['action'];
} elseif (isset($argv[1])) {
	$action = $argv[1];
}
$type = '';
if (isset($request->get['type'])) {
	$type = $request->get['type'];
} elseif (isset($request->post['type'])) {
	$type = $request->post['type'];
} elseif (isset($argv[2])) {
	$type = $argv[2];
}
$app_id = '';
if (isset($request->get['app_id'])) {
	$app_id = $request->get['app_id'];
} elseif (isset($request->post['app_id'])) {
	$app_id = $request->post['app_id'];
} elseif (isset($argv[3])) {
	$app_id = $argv[3];
}

if (!is_cli()) 
{
	if (!is_loggedin()) {
	  redirect(root_url() . '/index.php?redirect_to=' . url());
	}
	if (user_group_id() != 1 && !has_permission('access', 'reset')) {
	  redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
	}
} else if ($app_id != APPID) {
	echo '---INVALID APP ID' . PHP_EOL;
	exit();
}

if ($type == 'all') {
	$stores = get_stores(true);
} else {
	$stores = isset($request->post['store']) ? $request->post['store'] : array();
}

$errors = array();
if (isset($action) && $action == 'reset')  
{
	if (!empty($stores))
	{
		foreach ($stores as $store_id) 
		{
			if ($type == 'all') {
				$store_id = $store_id['store_id'];
			}
			$tables = array('purchase_info','purchase_item','purchase_payments','purchase_price','purchase_returns','purchase_return_items','customer_transactions','sell_logs','purchase_logs','payments','returns','return_items','selling_info','selling_item','selling_price','quotation_info','quotation_item','quotation_price', 'holding_info','holding_item','holding_price','transfer_items','expenses','bank_transaction_info','bank_transaction_price','sms_schedule','pos_register', 'purchase_logs', 'installment_payments', 'installment_orders');
			for ($i=0; $i < count($tables); $i++) { 
			  $table = $tables[$i];
			  $s1 = db()->prepare("DELETE FROM `{$table}` WHERE `store_id` = '{$store_id}'");
			  $s1->execute(array());
			}

			$s1 = db()->prepare("DELETE FROM `transfers` WHERE `from_store_id` = '{$store_id}' OR `to_store_id` = '{$store_id}'");
	  		$s1->execute(array());

	  		$s1 = db()->prepare("DELETE FROM `transfer_items` WHERE `store_id` = '{$store_id}'");
	  		$s1->execute(array());

			$s1 = db()->prepare("DELETE FROM `login_logs`");
			$s1->execute(array());

			$s1 = db()->prepare("SELECT `customer_id` FROM `customer_to_store` WHERE `store_id` = '{$store_id}'");
			$s1->execute(array());
			$customers = $s1->fetchAll(PDO::FETCH_ASSOC);
			if ($customers) {
				foreach ($customers as $customer) {
					$customer_id = $customer['customer_id'];
					$s1 = db()->prepare("UPDATE `customers` SET `is_giftcard` = 0 WHERE `customer_id` = '{$customer_id}'");
					$s1->execute(array());

					$s1 = db()->prepare("SELECT `card_no` FROM `gift_cards` WHERE `customer_id` = '{$customer_id}'");
					$s1->execute(array());
					$cards = $s1->fetchAll(PDO::FETCH_ASSOC);
					if ($cards) {
						foreach ($cards as $card) {
							$card_id = $card['card_no'];
							$s1 = db()->prepare("DELETE FROM `gift_card_topups` WHERE `card_id` = '{$card_id}'");
			  				$s1->execute(array());
						}
					}
					$s1 = db()->prepare("DELETE FROM `gift_cards` WHERE `customer_id` = '{$customer_id}'");
			  		$s1->execute(array());
				}
			}

			$s1 = db()->prepare("UPDATE `customer_to_store` SET `balance` = 0, `due` = 0 WHERE `store_id` = '{$store_id}'");
			$s1->execute(array());

			$s1 = db()->prepare("UPDATE `supplier_to_store` SET `balance` = 0 WHERE `store_id` = '{$store_id}'");
			$s1->execute(array());


			$s1 = db()->prepare("UPDATE `product_to_store` SET `quantity_in_stock` = 0 WHERE `store_id` = '{$store_id}'");
			$s1->execute(array());

			$s1 = db()->prepare("SELECT `account_id` FROM `bank_account_to_store` WHERE `store_id` = '{$store_id}'");
			$s1->execute(array());
			$accounts = $s1->fetchAll(PDO::FETCH_ASSOC);
			if ($accounts) {
				foreach ($accounts as $account) {
					$id = $account['account_id'];
					$s1 = db()->prepare("UPDATE `bank_accounts` SET `total_deposit` = 0,  `total_withdraw` = 0,  `total_transfer_from_other` = 0,  `total_transfer_to_other` = 0 WHERE `id` = '{$id}'");
					$s1->execute(array());
				}
			}
			$s1 = db()->prepare("UPDATE `bank_account_to_store` SET `deposit` = 0,  `withdraw` = 0,  `transfer_from_other` = 0,  `transfer_to_other` = 0 WHERE `store_id` = '{$store_id}'");
			$s1->execute(array());
		}
		if (!is_cli()) {
			redirect(root_url() . '/admin/reset.php?action=done');
		}
	} else {
		$errors[] = 'Selected store list is empty!';
	}
}
if (is_cli()) {
	if (!empty($errors)) {
		foreach ($errors as $error) {
			echo '---'.$error . PHP_EOL;
		}	

	} else {
		echo '---Done!'. PHP_EOL;	
		exit();	
	}
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo trans('title_reset_your_system');?></title>
	<script src="../assets/jquery/jquery.min.js" type="text/javascript"></script>
	<style type="text/css">
	* {
	  -webkit-box-sizing: border-box;
	  -moz-box-sizing: border-box;
	  box-sizing: border-box;
	}
	#reset-form {
		height: 100%;
		width: 100%;
	}
	#reset-form .title {
		color: #fff;
		font-size: 18px;
		text-align: center;
		margin: 5px;
	}
	#reset-form .alert {
		color: yellow;
		text-align: center;
	}
	#reset-form .container {
		width: 400px;
		min-height: 500px;
		padding: 10px;
		border: 1px solid #000;	
		position: absolute;
		top:0;
		bottom: 0;
		left: 0;
		right: 0;
		margin: auto;
		background: #000;
		border: 1px solid #fff;
		box-shadow: 0 0 10px rgba(0,0,0,0.5);

	}
	#reset-form .container .container-inner {
		padding: 10px;
	}
	#reset-form .container .container-inner .text {
		text-align: justify;
		color: white;
		font-weight: 600;
		font-size: 20px;
		margin: 0;
		padding: 10px;
		border: 1px dotted #ddd;
	}
	#reset-form .store-container {
		margin: 10px;
	}
	#reset-form .store-container .title,
	#reset-form .store-container .label {
		color: #fff;
	}
	#reset-form .store-container .well {
		padding: 10px;
	    background: #fff;
	    border-radius: 20px;
	    border: 2px solid rgb(147, 147, 147);
	}
	#reset-form .buttons {
		padding: 10px;
	}
	#reset-form .buttons .btn {
		display: block;
		color: #fff;
		text-decoration: none;
		font-weight: 700;
		width: 100%;
		text-align: center;
		border-radius: 20px;
		padding: 5px 10px;
		margin: 5px;
	}
	#reset-form .buttons .btn:hover {
		opacity: .9;
	}
	#reset-form .buttons .btn.yes {
		background: red;
		border: none;
		cursor: pointer;
	}
	#reset-form .buttons .btn.no {
		background: green;
	}
	#reset-form .buttons .btn.back {
		float: none;
		width: 100%;
		display: block;
	}
	</style>
</head>
<body>
<?php if (isset($request->get['action']) && $request->get['action'] == 'done'):?>
	<div id="reset-form">
	<div class="container">
		<div class="container-inner">
			<h2 class="alert success">Reset Successfully done!</h2>
		</div>
		<div class="buttons">
			<a class="btn back" href="<?php echo root_url();?>/admin/dashboard.php">&larr;Back to Dashboard</a>
		</div>
	</div>
	</div>
<?php else:?>
<form action="<?php echo root_url();?>/admin/reset.php?action=reset" method="POST">
	<div id="reset-form">
	<div class="container">
		<div class="container-inner">
			<?php if (!empty($errors)):?>
				<?php foreach ($errors as $error):?>
					<div class="alert alert-warning"><?php echo $error;?></div>
				<?php endforeach;?>				
			<?php endif;?>
			<h2 class="title">DATA RESET <br><?php echo store('name');?></h2>
			<h4 class="text">You are going to reset your Modern POS. This action will delete all data that can not be restored again. If you still proceed this action then click on 'Yes' otherwise click on 'No' button.</h4>
		</div>
		<div class="store-container">
	    	<h4 class="title">SELECT STORE</h4>
	      	<div class="store-selector">
		        <div class="checkbox selector">
		          <label class="label">
		            <input type="checkbox" onclick="$('input[name*=\'store\']').prop('checked', this.checked);"> Select / Deselect
		          </label>
		        </div>
		        <div class="well"> 
		          <?php foreach(get_stores() as $the_store):?>                    
		            <div class="checkbox">
		              <label>                         
		                <input type="checkbox" name="store[]" value="<?php echo $the_store['store_id']; ?>"<?php echo $the_store['store_id'] == store_id() ? ' checked' : null;?>>
		                <?php echo $the_store['name']; ?>
		              </label>
		            </div>
		          <?php endforeach; ?>
			    </div>
	      	</div>
      	</div>
		<div class="buttons">
			<button class="btn yes" type="submit">YES</button>
			<a class="btn no" href="<?php echo root_url();?>/admin/dashboard.php">NO</a>
		</div>
	</div>
	</div>
</form>
<?php endif;?>

</body>
</html>