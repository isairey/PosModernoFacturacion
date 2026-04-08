<?php
$permissions = array(
  'report' => array(
    'read_recent_activities' => 'Recent activities', 
    'read_dashboard_accounting_report' => 'Dashboard Accounting Report', 
    'read_sell_report' => 'Sell Report', 
    'read_overview_report' => 'Overview Report', 
    'read_collection_report' => 'Collec. Report',
    'read_full_collection_report' => 'Full Collec. Report',
    'read_customer_due_collection_report' => 'Customer Due Collection RPT',
    'read_supplier_due_paid_report' => 'Suppler Due Paid RPT',
    'read_analytics' => 'Read Analytics', 
    'read_overview_report' => 'Overview Report',
    'read_purchase_report' => 'Purchase Report',
    'read_purchase_payment_report' => 'Purchase Payment Report',
    'read_purchase_tax_report' => 'Purchase Tax Report',
    'read_sell_payment_report' => 'sell Payment Report',
    'read_sell_tax_report' => 'Sell Tax Report',
    'read_tax_overview_report' => 'Tax Overview Report',
    'read_stock_report' => 'Stock Report',
    'send_report_via_email' => 'Send Report via Email',
  ),
  'accounting' => array(
    'withdraw' => 'Withdraw',
    'deposit' => 'Deposit',
    'transfer' => 'Transfer',
    'read_bank_account' => 'View Bank Account',
    'read_bank_account_sheet' => 'View Bank Account Sheet',
    'read_bank_transfer' => 'View Bank Transfer',
    'read_bank_transactions' => 'View Bank Transactions',
    'read_income_source' => 'View Income Source',
    'create_bank_account' => 'Create Bank Account',
    'update_bank_account' => 'Update Bank Account',
    'delete_bank_account' => 'Delete Bank Account',
    'create_income_source' => 'Create Income Source',
    'update_income_source' => 'Update Income Source',
    'delete_income_source' => 'Delete Income Source',
    'read_income_monthwise' => 'Income Monthwise',
    'read_income_monthwise' => 'Income Monthwise',
    'read_income_and_expense_report' => 'Income & Expense',
    'read_profit_and_loss_report' => 'Profit & Loss',
    'read_cashbook_report' => 'Cashbook',
  ),
  'quotation' => array(
    'read_quotation' => 'Read Quotation List',
    'create_quotation' => 'Create Quotation',
    'update_quotation' => 'Update Quotation',
    'delete_quotation' => 'Delete Quotation',
  ),
  'installment' => array(
    'read_installment' => 'Read Installment List',
    'create_installment' => 'Create Installment',
    'update_installment' => 'Update Installment',
    'delete_installment' => 'Delete Installment',
    'installment_payment' => 'Installment Payment',
    'installment_overview' => 'Installment Overview',
  ),
  'expenditure' => array(
    'read_expense' => 'Read Expense',
    'create_expense' => 'Create Expense',
    'update_expense' => 'Update Expense',
    'delete_expense' => 'Delete Expense',
    'read_expense_category' => 'Read Expense Category',
    'create_expense_category' => 'Create Expense Category',
    'update_expense_category' => 'Update Expense Category',
    'delete_expense_category' => 'Delete Expense Category',
    'read_expense_monthwise' => 'Expense Monthwise',
    'read_expense_summary' => 'Expense Summary',
  ),
  'sell' => array(
    'read_sell_invoice' => 'View Sell Invoice',
    'read_sell_list' => 'View Sell List',
    'create_sell_invoice' => 'Create Sell',
    'update_sell_invoice_info' => 'Update Info',
    'delete_sell_invoice' => 'Delete Sell',
    'sell_payment' => 'Sell Payment',
    'create_sell_due' => 'Create Due',
    'create_sell_return' => 'Create Return',
    'read_sell_return' => 'View Return List',
    'update_sell_return' => 'Update Return',
    'delete_sell_return' => 'Delete Return',
    'sms_sell_invoice' => 'Send Sell Invoice via SMS', 
    'email_sell_invoice' => 'Send Sell Invoice via Email', 
	  'read_sell_log' => 'Read Sell Log',
  ),
  'purchase' => array(
    'create_purchase_invoice' => 'Create Invoice',
    'read_purchase_list' => 'View Invoice List',
    'update_purchase_invoice_info' => 'Update Info',
    'delete_purchase_invoice' => 'Delete Invoice',
    'purchase_payment' => 'Payment',
    'create_purchase_due' => 'Create Due',
    'create_purchase_return' => 'Create Return',
    'read_purchase_return' => 'View Return List',
    'update_purchase_return' => 'Update Return',
    'delete_purchase_return' => 'Delete Return',
    'read_purchase_log' => 'Read Purchase Log',
  ),
  'transfer' => array(
    'read_transfer' => 'Read Transfer',
    'add_transfer' => 'Add Transfer',
    'update_transfer' => 'Update Transfer',
    'delete_transfer' => 'Delete Transfer',
  ),
  'giftcard' => array(
    'read_giftcard' => 'Read Giftcard',
    'add_giftcard' => 'Add Giftcard',
    'update_giftcard' => 'Update Giftcard',
    'delete_giftcard' => 'Delete Giftcard',
    'giftcard_topup' => 'Giftcard Topup',
    'read_giftcard_topup' => 'Read Giftcard Topup',
    'delete_giftcard_topup' => 'Delete Giftcard Topup',
  ),
  'product' => array(
    'read_product' => 'Read Product List',
    'create_product' => 'Create Product', 
    'update_product' => 'Update Product', 
    'delete_product' => 'Delete Product',
    'import_product' => 'Import Product',
    'product_bulk_action' => 'Product Bulk Action',
    'delete_all_product' => 'Delete All Product',
    'read_category' => 'Read Category List',
    'create_category' => 'Create Category', 
    'update_category' => 'Update Category', 
    'delete_category' => 'Delete Category',
    'read_stock_alert' => 'Read Stock Alert',
    'read_expired_product' => 'Read Expired Product List',
    'barcode_print' => ' Barcode Print',
    'restore_all_product' => 'Restore All Product',
  ),
  'supplier' => array(
    'read_supplier' => 'Read Supplier List',
    'create_supplier' => 'Create Supplier',
    'update_supplier' => 'Update Supplier',
    'delete_supplier' => 'Delete Supplier',
    'read_supplier_profile' => 'Read Supplier Profile',
  ),
  'brand' => array(
    'read_brand' => 'Read Brand List',
    'create_brand' => 'Create Brand',
    'update_brand' => 'Update Brand',
    'delete_brand' => 'Delete Brand',
    'read_brand_profile' => 'Read Brand Profile',
  ),
  'storebox' => array(
    'read_box' => 'Read Box',
    'create_box' => 'Create Box',
    'update_box' => 'Update Box',
    'delete_box' => 'Delete Box',
  ),
  'unit' => array(
    'read_unit' => 'Read Unit',
    'create_unit' => 'Create Unit',
    'update_unit' => 'Update Unit',
    'delete_unit' => 'Delete Unit',
  ),
  'taxrate' => array(
    'read_taxrate' => 'Read Taxrate',
    'create_taxrate' => 'Create Taxrate',
    'update_taxrate' => 'Update Taxrate',
    'delete_taxrate' => 'Delete Taxrate',
  ),
  'loan' => array(
    'read_loan' => 'Read Loan',
    'read_loan_summary' => 'Read Loan Summary',
    'take_loan' => 'Take Loan',
    'update_loan' => 'Update Loan',
    'delete_loan' => 'Delete Loan',
    'loan_pay' => 'Loan Pay',
  ),
  'customer' => array(
    'read_customer' => 'Read Customer List',
    'read_customer_profile' => 'Read Customer Profile',
    'create_customer' => 'Create Customer', 
    'update_customer' => 'Update Customer', 
    'delete_customer' => 'Delete Customer',
    'add_customer_balance' => 'Add Balance',
    'substract_customer_balance' => 'Substract Balance',
    'read_customer_transaction' => 'Read Transaction List',
  ),
  'user' => array(
    'read_user' => 'Read User List',
    'create_user' => 'Create User', 
    'update_user' => 'Update User', 
    'delete_user' => 'Delete User', 
    'change_password' => 'Change Password',
  ),
  'usergroup' => array(
    'read_usergroup' => 'Read Usergroup List',
    'create_usergroup' => 'Create Usergroup', 
    'update_usergroup' => 'Update Usergroup', 
    'delete_usergroup' => 'Delete Usergroup', 
  ),
  'currency' => array(
    'read_currency' => 'Read Currency',
    'create_currency' => 'Add Currency',
    'update_currency' => 'Update Currency',
    'change_currency' => 'Change Currency',
    'delete_currency' => 'Delete Currency',
  ),
  'filemanager' => array(
    'read_filemanager' => 'Read Filemanager',
  ),
  'payment_method' => array(
    'read_pmethod' => 'Read Payment Method List',
    'create_pmethod' => 'Create Payment Method',
    'update_pmethod' => 'Update Payment Method',
    'delete_pmethod' => 'Delete Payment Method',
    'updadte_pmethod_status' => 'Active/Inactive',
  ),
  'store' => array(
    'read_store' => 'Read Store List',
    'create_store' => 'Create Store',
    'update_store' => 'Update Store',
    'delete_store' => 'Delete Store',
    'activate_store' => 'Active Store',
    'upload_favicon' => 'Upload Favicon',
    'upload_logo' => 'Upload Logo',
  ),
  'printer' => array(
    'read_printer' => 'View Printer',
    'create_printer' => 'Add Printer',
    'update_printer' => 'Update Printer',
    'delete_printer' => 'Delete Printer',
  ),
  'sms' => array(
    'send_sms' => 'Send SMS',
    'read_sms_report' => 'View SMS Report',
    'read_sms_setting' => 'View SMS Setting',
    'update_sms_setting' => 'Update SMS Setting',
  ),
  'sms' => array(
    'send_email' => 'Send Email',
  ),
  'langauge' => array(
    'read_language' => 'View Language',
    'create_language' => 'Add Language',
    'update_language' => 'Edit Language Info',
    'delete_language' => 'Delete Language',
    'language_translation' => 'Language Translation',
    'delete_language_key' => 'Delete Language key',
  ),
  'settings' => array(
    'receipt_template' => 'Receipt Template',
    'read_user_preference' => 'Read User Preference',
    'update_user_preference' => 'Update User Preference',
    'filtering' => 'Filtering',
    'language_sync' => 'Language Sync',
    'backup' => 'Database Backup',
    'restore' => 'Database Restore',
    'reset' => 'Reset',
    'show_purchase_price' => 'Show Purchase Price',
    'show_profit' => 'Show Profit',
    'show_graph' => 'Show Graph',
  ),
);
?>

<h4 class="sub-title">
  <?php echo trans('text_update_title'); ?>
</h4>

<form class="form-horizontal" id="user-group-form" action="user_group.php" method="post">
  
  <input type="hidden" id="action_type" name="action_type" value="UPDATE">
  <input type="hidden" id="group_id" name="group_id" value="<?php echo $usergroup['group_id']; ?>">
  
  <div class="box-body">
    <div class="form-group">
      <label for="name" class="col-sm-3 control-label">
        <?php echo sprintf(trans('label_name'), null); ?>
      </label>
      <div class="col-sm-7">
        <input type="text" class="form-control" id="name" ng-model="usergroupName" ng-init="usergroupName='<?php echo $usergroup['name']; ?>'" value="<?php echo $usergroup['name']; ?>" name="name" required>
      </div>
    </div>

    <div class="form-group">
      <label for="slug" class="col-sm-3 control-label">
        <?php echo sprintf(trans('label_slug'), null); ?><i class="required">*</i>
      </label>
      <div class="col-sm-7">
        <?php if ( $usergroup['slug'] == 'admin' ||  $usergroup['slug'] == 'cashier'): ?>
          <input type="hidden" class="form-control" id="slug" name="slug" value="<?php echo $usergroup['slug'];?>">
          <h4><b><?php echo $usergroup['slug'];?></b></h4>
        <?php else:?>
          <input type="text" class="form-control" id="slug" value="<?php echo $usergroup['slug'] ? $usergroup['slug'] : "{{ categoryName | strReplace:' ':'_' | lowercase }}"; ?>" name="slug" required<?php echo $usergroup['slug'] == 'admin' ||  $usergroup['slug'] == 'cashier' ? ' disabled' : null;?>>
        <?php endif;?>
      </div>
    </div>

    <hr>

    <div class="form-group mb-0">
      <div class="col-sm-12">
        <h4 class="pull-left">
          <b><?php echo trans('text_permission'); ?></b>
        </h4>
        <button data-form="#user-group-form" data-datatable="#user-group-list" class="btn btn-info btn-lg pull-right user-group-update" name="btn_edit_user" data-loading-text="Updating...">
          <span class="fa fa-fw fa-pencil"></span>
          <?php echo trans('button_update'); ?>
        </button>
      </div>
    </div>

    <hr>

    <?php $the_permissions = unserialize($usergroup['permission']); ?>

    <div class="form-group permission-list">
      <?php foreach ($permissions as $type => $lists) : ?>
      <div class="col-sm-3">
        <h4>
          <input type="checkbox" id="<?php echo $type; ?>_action" onclick="$('.<?php echo $type; ?>').prop('checked', this.checked);">
          <label for="<?php echo $type; ?>_action">
            <?php echo str_replace('_', ' ', $type); ?>
          </label>
        </h4>
        <div class="filter-searchbox">
            <input ng-model="search_<?php echo $type; ?>" class="form-control" type="text" placeholder="<?php echo trans('search'); ?>">
        </div>
        <div class="well well-sm permission-well">
          <div filter-list="search_<?php echo $type; ?>">
            <?php foreach ($lists as $key => $name) : ?>
              <div>
                <input type="checkbox" class="<?php echo $type; ?>" id="<?php echo $key; ?>" value="true" name="access[<?php echo $key; ?>]"<?php echo isset($the_permissions['access'][$key]) ? ' checked' : null; ?>>
                <label for="<?php echo $key; ?>"><?php echo ucfirst($name); ?></label>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="box-footer">
    <div class="form-group">
      <div class="col-sm-12 text-center">
        <button data-form="#user-group-form" data-datatable="#user-group-list" class="btn btn-lg btn-info user-group-update" name="btn_edit_user" data-loading-text="Updating...">
          <span class="fa fa-fw fa-pencil"></span>
          <?php echo trans('button_update'); ?>
        </button>
      </div>
    </div>
  </div>
</form>