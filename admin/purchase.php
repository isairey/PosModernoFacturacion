<?php 
ob_start();
session_start();
include ("../_init.php");

// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}

// Redirect, If User has not Read Permission
if (user_group_id() != 1 && !has_permission('access', 'read_purchase_list')) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

// Set Document Title
$document->setTitle(trans('title_purchase'));

// Add Script
$document->addScript('../assets/itsolution24/angular/controllers/PurchaseController.js');

// ADD BODY CLASS
$document->setBodyClass('sidebar-collapse');

// Include Header and Footer
include("header.php"); 
include ("left_sidebar.php") ;
?>

<!-- Content Wrapper Start -->
<div class="content-wrapper" ng-controller="PurchaseController">

  <!-- Content Header Start -->
  <section class="content-header">
    <?php include ("../_inc/template/partials/apply_filter.php"); ?>
    <h1>
      <?php echo trans('text_purchase_title'); ?>
      <small>
        <?php echo store('name'); ?>
      </small>
    </h1>
    <ol class="breadcrumb">
      <li>
        <a href="dashboard.php">
          <i class="fa fa-dashboard"></i> 
          <?php echo trans('text_dashboard'); ?>
        </a>
      </li>
      <li>
        <?php if (isset($request->get['box_state']) && $request->get['box_state']=='open'): ?>
          <a href="purchase.php"><?php echo trans('text_purchase_title'); ?></a>  
        <?php else: ?>
          <?php echo trans('text_purchase_title'); ?>  
        <?php endif; ?>
      </li>
      <?php if (isset($request->get['box_state']) && $request->get['box_state']=='open'): ?>
        <li class="active">
          <?php echo trans('text_add'); ?> 
        </li>
      <?php endif; ?>
    </ol>
  </section>
  <!-- Content Header end -->

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
    
    <?php if (user_group_id() == 1 || has_permission('access', 'create_purchase_invoice')) : ?>
      <div class="box box-info<?php echo create_box_state(); ?>">
        <div class="box-header with-border">
          <h3 class="box-title">
            <span class="fa fa-fw fa-plus"></span> <?php echo trans('text_new_purchase_title'); ?>
          </h3>
          <button type="button" class="btn btn-box-tool add-new-btn" data-widget="collapse" data-collapse="true">
            <i class="fa <?php echo !create_box_state() ? 'fa-minus' : 'fa-plus'; ?>"></i>
          </button>
        </div>

        <!-- Add Purchase Create Form -->
        <?php include('../_inc/template/purchase_create_form.php'); ?>
        
      </div>
    <?php endif; ?>

    <div class="row">
      <div class="col-xs-12">
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title">
              <?php echo trans('text_purchase_list_title'); ?>
            </h3>
            <div class="box-tools pull-right">
              <div class="btn-group">
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                  <span class="fa fa-fw fa-filter"></span> 
                  <?php if(isset($request->get['type'])) : ?>
                      <?php echo trans('text_'.$request->get['type']); ?>
                  <?php else: ?>
                    <?php echo trans('button_filter'); ?>
                  <?php endif; ?>
                    &nbsp;<span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                  <li>
                      <a href="purchase.php">
                        <?php echo trans('button_today_invoice'); ?>
                      </a>
                    </li>
                    <li>
                      <a href="purchase.php?type=all_invoice">
                        <?php echo trans('button_all_invoice'); ?>
                      </a>
                    </li>
                    <li>
                      <a href="purchase.php?type=due">
                        <?php echo trans('button_due_invoice'); ?>
                      </a>
                    </li>
                    <li>
                      <a href="purchase.php?type=all_due">
                        <?php echo trans('button_all_due_invoice'); ?>
                      </a>
                    </li>
                    <li>
                      <a href="purchase.php?type=paid">
                        <?php echo trans('button_paid_invoice'); ?>
                      </a>
                    </li>
                    <li>
                      <a href="purchase.php?type=inactive">
                        <?php echo trans('button_inactive_invoice'); ?>
                      </a>
                    </li>
                 </ul>
              </div>
          </div>
          </div>
          <div class='box-body'>     
            
            <div class="table-responsive"> 
              <?php
              $hide_colums = "";
              if (user_group_id() != 1) {
                if (! has_permission('access', 'purchase_payment')) {
                  $hide_colums .= "8,";
                }
                if (! has_permission('access', 'purchase_return')) {
                  $hide_colums .= "9,";
                }
                if (! has_permission('access', 'read_purchase_list')) {
                  $hide_colums .= "1101,";
                }
                if (! has_permission('access', 'update_purchase_invoice_info')) {
                  $hide_colums .= "11,";
                }
                if (! has_permission('access', 'delete_purchase_invoice')) {
                  $hide_colums .= "12,";
                }
              }
              ?>  
              <table id="invoice-invoice-list"  class="table table-bordered table-striped table-hover" data-hide-colums="<?php echo $hide_colums; ?>">
                <thead>
                  <tr class="bg-gray">
                    <th class="w-10">
                      <?php echo trans('label_datetime'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_invoice_id'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_supplier'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_creator'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_amount'); ?> 
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_invoice_paid'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_due'); ?>
                    </th>
                    <th class="w-5">
                      <?php echo trans('label_status'); ?>
                    </th>
                    <th class="w-5">
                      <?php echo trans('label_pay'); ?>
                    </th>
                    <th class="w-5">
                      <?php echo trans('label_return'); ?>
                    </th>
                    <th class="w-5">
                      <?php echo trans('label_view'); ?>
                    </th>
                    <th class="w-5">
                      <?php echo trans('label_edit'); ?>
                    </th>
                    <th class="w-5">
                      <?php echo trans('label_delete'); ?>
                    </th>
                  </tr>
                </thead>
                <tfoot>
                  <tr class="bg-gray">
                    <th class="w-10">
                      <?php echo trans('label_datetime'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_invoice_id'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_supplier'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_creator'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_amount'); ?> 
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_invoice_paid'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_due'); ?>
                    </th>
                    <th class="w-5">
                      <?php echo trans('label_status'); ?>
                    </th>
                    <th class="w-5">
                      <?php echo trans('label_pay'); ?>
                    </th>
                    <th class="w-5">
                      <?php echo trans('label_return'); ?>
                    </th>
                    <th class="w-5">
                      <?php echo trans('label_view'); ?>
                    </th>
                    <th class="w-5">
                      <?php echo trans('label_edit'); ?>
                    </th>
                    <th class="w-5">
                      <?php echo trans('label_delete'); ?>
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
  <!-- Content End -->
</div>
<!-- Content Wrapper End -->

<?php include ("footer.php"); ?>