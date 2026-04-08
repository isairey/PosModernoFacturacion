<?php 
ob_start();
session_start();
include ("../_init.php");

// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}

// Redirect, If User has not Read Permission
if (user_group_id() != 1 && !has_permission('access', 'read_customer_profile')) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

// LOAD CUSTOMER MODEL
registry()->get('loader')->model('customer');
$customer_model = registry()->get('model_customer');

// CHECK CUSTOMER EXIST OF NOT
if (!isset($request->get['customer_id'])) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/customer.php');
}
$customer = $customer_model->getCustomer($request->get['customer_id']);
if (count($customer) <= 1) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/customer.php');
}

// Set Document Title
$document->setTitle(trans('title_customer_profile'));

// Add ScriptS
$document->addScript('../assets/itsolution24/angular/modals/InstallmentPaymentModal.js');
$document->addScript('../assets/itsolution24/angular/modals/InstallmentViewModal.js');
$document->addScript('../assets/itsolution24/angular/modals/CustomerSubstractBalanceModal.js');
$document->addScript('../assets/itsolution24/angular/modals/CustomerAddBalanceModal.js');
$document->addScript('../assets/itsolution24/angular/controllers/CustomerController.js');
$document->addScript('../assets/itsolution24/angular/controllers/CustomerProfileController.js');

// ADD BODY CLASS 
$document->setBodyClass('sidebar-collapse customer-profile'); 

// Include Header and Footer
include 'header.php'; 
include 'left_sidebar.php';  
?>

<style type="text/css">
table#invoice-invoice-list td table td {
    font-size: 10px!important;
  }
</style>

<!-- Content Wrapper Start -->
<div class="content-wrapper" ng-controller="CustomerProfileController">

  <!-- Header Content Start -->
  <section class="content-header">
    <?php include ("../_inc/template/partials/apply_filter.php"); ?>
    <h1>
      <?php echo trans('text_profile_title');?>&nbsp;<small>of</small>&nbsp;<?php echo $customer['customer_name'];?>
    </h1>
    <ol class="breadcrumb">
      <li>
        <a href="dashboard.php">
          <i class="fa fa-dashboard"></i> 
          <?php echo trans('text_dashboard'); ?>
        </a>
      </li>
      <li>
        <a href="customer.php">
          <?php echo trans('text_customers'); ?>
        </a>
      </li>
      <li class="active">
        <?php echo $customer['customer_name']; ?>
      </li>
    </ol>
  </section>
  <!-- Header Content End -->

  <!-- Content Start -->
  <section class="content">

    <?php if(DEMO) : ?>
    <div class="box">
      <div class="box-body">
        <div class="alert alert-info mb-0">
          <p><span class="fa fa-fw fa-info-circle"></span> <?php echo $demo_text; ?></p>
        </div>
      </div>
    </div>
    <?php endif; ?>
    
    <div class="row profile-heading">
      <div class="col-sm-4 col-xs-12">
        <div class="box box-widget widget-user">
          <div class="widget-user-header bg-<?php echo $user->getPreference('base_color', 'black'); ?>">
            <h3 class="widget-user-username">
              <?php echo $customer['customer_name']; ?>
            </h3>
            <h5 class="widget-user-desc">
              <?php echo trans('text_since'); ?> 
              <?php echo format_date($customer['created_at']); ?>
            </h5>
          </div>
          <div class="widget-user-image">
            <svg class="svg-icon"><use href="#icon-<?php echo customer_avatar($customer['customer_sex']); ?>"></svg>
          </div>
          <div class="box-footer">
            <div class="row">
              <div class="col-sm-4 border-right">
                <div class="description-block">
                  <h5 class="description-header">
                    <?php echo customer_total_invoice($customer['customer_id']); ?>
                  </h5>
                  <span class="description-text">
                    <small><?php echo trans('text_total_invoice'); ?></small>
                  </span>
                </div>
              </div>
              <div class="col-sm-5 border-right">
                <div class="description-block">
                  <h5 class="description-header">
                    <?php echo currency_format(customer_total_purchase_amount($customer['customer_id'])); ?>
                  </h5>
                  <span class="description-text">
                    <small><?php echo trans('text_total_purchase'); ?></small>
                  </span>
                </div>
              </div>
              <div class="col-sm-3">
                <div class="description-block">
                  <?php if (user_group_id() == 1 || has_permission('access', 'update_customer')) : ?>
                    <button ng-click="customerEdit(<?php echo $customer['customer_id']; ?>, '<?php echo $customer['customer_name']; ?>')" title="<?php echo trans('button_edit'); ?>" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></button>
                  <?php endif; ?>
                  <?php if (user_group_id() == 1 || has_permission('access', 'create_sell_invoice')) : ?>
                  <a id="sell-product" class="btn btn-xs btn-success" target="_blink" href="pos.php?customer_id=<?php echo $customer['customer_id']; ?>" title="<?php echo trans('button_sell'); ?>">
                    <i class="fa fa-shopping-cart"></i>
                  </a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-5 col-xs-12 contact">
        <div class="box box-info">
          <div class="box-header with-border text-center">
            <h3 class="box-title">
              <?php echo trans('text_information'); ?>
            </h3>
          </div>
          <div class="box-body">
            <address class="mb-0 text-center">
              <?php if (get_preference('invoice_view') == 'indian_gst') : ?>
                <p><b><?php echo trans('text_gtin');?>:</b> <?php echo $customer['gtin'] ? $customer['gtin'] : 'N/A';?></p>
              <?php endif;?>
              <?php if ($customer['customer_mobile']) : ?>
              <p>
                <b>
                  <?php echo trans('label_mobile_phone'); ?>:
                </b> 
                <?php echo $customer['customer_mobile']; ?>
              </p>
              <?php endif; ?>
              <?php if ($customer['dob']) : ?>
                <p>
                  <b>
                    <?php echo trans('label_date_of_birth'); ?>:
                  </b> 
                  <?php echo format_only_date($customer['dob']); ?>
                </p>
              <?php endif; ?>   
              <?php if ($customer['customer_address']) : ?>
                <p>
                  <b>
                    <?php echo trans('label_address'); ?>:
                  </b> 
                  <?php echo $customer['customer_address']; ?>
                </p>
              <?php endif; ?>
              <p>
                <b>
                  <?php echo trans('label_giftcard_taken'); ?>:
                </b> 
                <?php echo $customer['is_giftcard'] ? 'Yes' : 'No'; ?> (<?php echo get_currency_symbol();?><?php echo get_customer_giftcard_balance($customer['customer_id']);?>)
              </p>
            </address>
          </div>
        </div>
      </div>
      <div class="col-sm-3 col-xs-12 balance">
        <div class="info-box">
          <span class="info-box-icon bg-<?php echo $user->getPreference('base_color', 'black'); ?>">
            <i>
              <?php echo get_currency_symbol(); ?>
            </i>
          </span>
          <?php $due = $customer['due']; ?>
          <div class="info-box-content">
            <?php if ($due <= 0):?>
              <h4 class="info-box-text text-green">
                <?php echo trans('label_balance'); ?>
              </h4>
              <?php $balance = $customer['balance']; ?>
              <span id="balance" class="info-box-number">
                <?php echo currency_format($balance); ?>
              </span>
            <?php endif;?>
            <hr style="margin-top:0;">
            <h4 class="info-box-text text-red">
              <?php echo trans('label_due'); ?>
            </h4>
            <span id="due" class="info-box-number">
              <?php echo currency_format($due); ?>
            </span>
            <hr style="margin-top:0;">
            <?php if ($due <= 0):?>
              <a ng-click="addCustomerBalance({'customer_id': '<?php echo $customer['customer_id'];?>','customer_name':'<?php echo $customer['customer_name'];?>'});" onclick="return false;" href="customer_profile.php" class="btn btn-block btn-xs btn-success" title="Add Balance">
                <i class="fa fa-fw fa-plus"></i> 
                <?php echo trans('button_add_balance'); ?>
              </a>
              <?php if ($balance > 0):?>
                <hr style="margin-top:0;">
                <a ng-click="substractCustomerBalance({'customer_id': '<?php echo $customer['customer_id'];?>','customer_name':'<?php echo $customer['customer_name'];?>'});" onclick="return false;" href="customer_profile.php" class="btn btn-block btn-xs btn-danger" title="Substract from Balance">
                  <i class="fa fa-fw fa-minus"></i> 
                  <?php echo trans('button_substract_balance'); ?>
                </a>
              <?php endif;?>
            <?php endif;?>
            <?php if ($due > 0) : ?>
              <hr style="margin-top:0;">
              <a href="customer_profile.php?type=due&customer_id=<?php echo $customer['customer_id']; ?>" class="btn btn-block btn-xs btn-warning">
                <i class="fa fa-fw fa-list"></i> 
                <?php echo trans('button_due_invoice_list'); ?>
              </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12">
        <div class="box box-info">
          <div class="box-header">
            <h3 class="box-title">
              <?php echo trans('text_invoice_list'); ?>
            </h3>
            <div class="box-tools pull-right">
              <div class="btn-group" style="max-width:280px;">
                <div class="input-group">
                  <div class="input-group-addon no-print" style="padding: 2px 8px; border-right: 0;">
                    <i class="fa fa-users" id="addIcon" style="font-size: 1.2em;"></i>
                  </div>
                  <select id="customer_id" class="form-control" name="customer_id" >
                    <option value=""><?php echo trans('text_select'); ?></option>
                    <?php foreach (get_customers() as $the_customer) : ?>
                      <option value="<?php echo $the_customer['customer_id'];?>">
                      <?php echo $the_customer['customer_name'];?>
                    </option>
                  <?php endforeach;?>
                  </select>
                  <div class="input-group-addon no-print" style="padding: 2px 8px; border-left: 0;">
                    <i class="fa fa-search" id="addIcon" style="font-size: 1.2em;"></i>
                  </div>
                </div>
              </div>
              <div class="btn-group">
                <a type="button" class="btn btn-info" href="customer_transaction.php?customer_id=<?php echo $customer['customer_id'];?>">
                  <span class="fa fa-fw fa-list"></span> <?php echo trans('button_statement'); ?></a>
              </div>
              <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                  <span class="fa fa-fw fa-filter"></span> 
                    <?php if(isset($request->get['type'])) : ?>
                      <?php echo trans('text_'.$request->get['type']); ?>
                  <?php else : ?>
                    <?php echo trans('button_filter'); ?>
                  <?php endif; ?>
                    &nbsp;<span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                  <li>
                      <a href="customer_profile.php?customer_id=<?php echo $customer['customer_id']; ?>">
                        <?php echo trans('button_today_invoice'); ?>
                      </a>
                    </li>
                    <li>
                      <a href="customer_profile.php?customer_id=<?php echo $customer['customer_id']; ?>&type=all_invoice">
                        <?php echo trans('button_all_invoice'); ?>
                      </a>
                    </li>
                    <li>
                      <a href="customer_profile.php?customer_id=<?php echo $customer['customer_id']; ?>&type=due">
                        <?php echo trans('button_due_invoice'); ?>
                      </a>
                    </li>
                    <li>
                      <a href="customer_profile.php?customer_id=<?php echo $customer['customer_id']; ?>&type=all_due">
                        <?php echo trans('button_all_due_invoice'); ?>
                      </a>
                    </li>
                    <li>
                      <a href="customer_profile.php?customer_id=<?php echo $customer['customer_id']; ?>&type=paid">
                        <?php echo trans('button_paid_invoice'); ?>
                      </a>
                    </li>
                    <li>
                      <a href="customer_profile.php?customer_id=<?php echo $customer['customer_id']; ?>&type=inactive">
                        <?php echo trans('button_inactive_invoice'); ?>
                      </a>
                    </li>
                 </ul>
              </div>
            </div>
          </div>
        	<div class='box-body'>     
            <?php
              $hide_colums = "";
              if (user_group_id() != 1) {
                if (! has_permission('access', 'read_sell_invoice')) {
                  $hide_colums .= "8,";
                }
                if (! has_permission('access', 'sell_payment')) {
                  $hide_colums .= "9,";
                }
              }
            ?> 
            <div class="table-responsive"> 
              <!-- Iinvoice List Start-->
              <table id="invoice-invoice-list" class="table table-bordered table-striped table-hovered" data-id="<?php echo $customer['customer_id']; ?>" data-hide-colums="<?php echo $hide_colums; ?>">
                <thead>
                  <tr class="bg-gray">
                    <th class="w-8">
                      <?php echo trans('label_date'); ?>
                    </th>
                  	<th class="w-15">
                      <?php echo trans('label_invoice_id'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_note'); ?>
                    </th>
                    <th class="w-20">
                      <?php echo trans('label_items'); ?>
                    </th>
                    <th class="w-8">
                      <?php echo trans('label_invoice_amount'); ?>
                    </th>
                    <th class="w-8">
                      <?php echo trans('label_prev_due'); ?>
                    </th>
                    <th class="w-8">
                      <?php echo trans('label_payable'); ?>
                    </th>
                    <th class="w-8">
                      <?php echo trans('label_paid'); ?>
                    </th>
                    <th class="w-8">
                      <?php echo trans('label_due'); ?>
                    </th>
        						<th class="w-5">
                      <?php echo trans('label_view'); ?>
                    </th>
                    <th class="w-5">
                      <?php echo trans('label_pay'); ?>
                    </th>
        					</tr>
                  </tr>
                </thead>
                <tfoot>
                  <tr class="bg-gray">
                    <th class="w-8">
                      <?php echo trans('label_date'); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_invoice_id'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_note'); ?>
                    </th>
                    <th class="w-20">
                      <?php echo trans('label_items'); ?>
                    </th>
                    <th class="w-8">
                      <?php echo trans('label_invoice_amount'); ?>
                    </th>
                    <th class="w-8">
                      <?php echo trans('label_prev_due'); ?>
                    </th>
                    <th class="w-8">
                      <?php echo trans('label_payable'); ?>
                    </th>
                    <th class="w-8">
                      <?php echo trans('label_paid'); ?>
                    </th>
                    <th class="w-8">
                      <?php echo trans('label_due'); ?>
                    </th>
                    <th class="w-5">
                      <?php echo trans('label_view'); ?>
                    </th>
                    <th class="w-5">
                      <?php echo trans('label_pay'); ?>
                    </th>
                  </tr>
                </tfoot>
              </table>		
            </div>
    		  </div> 
        </div>
      </div>
    </div>
  </section>
  <!-- Content End-->
</div>
<!-- Content Wrapper End -->

<script type="text/javascript">
  var customerName = '<?php echo $customer['customer_name']; ?>';
</script>

<?php include ("footer.php"); ?>