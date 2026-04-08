<?php 
ob_start();
session_start();
include ("../_init.php");

// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}

// Redirect, If User has not Read Permission
if (user_group_id() != 1 && !has_permission('access', 'read_purchase_log')) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

// Set Document Title
$document->setTitle(trans('title_purchase_log'));

// Add Script
$document->addScript('../assets/itsolution24/angular/modals/PurchaseLogViewModal.js');
$document->addScript('../assets/itsolution24/angular/controllers/PurchaseLogController.js');

// ADD BODY CLASS
$document->setBodyClass('sidebar-collapse');

// Include Header and Footer
include("header.php"); 
include ("left_sidebar.php") ;
?>

<!-- Content Wrapper Start -->
<div class="content-wrapper" ng-controller="PurchaseLogController">

  <!-- Content Header Start -->
  <section class="content-header">
    <?php include ("../_inc/template/partials/apply_filter.php"); ?>
    <h1>
      <?php echo trans('text_purchase_log_title'); ?>
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
      <?php if (isset($request->get['sup_id']) && get_the_supplier($request->get['sup_id'])):?>
        <li>
          <a href="supplier_profile.php?sup_id=<?php echo get_the_supplier($request->get['sup_id'],'sup_id');?>">
            <?php echo limit_char(get_the_supplier($request->get['sup_id'],'sup_name'),30); ?>
          </a>
        </li>
      <?php else:?>
        <li>
          <a href="purchase.php"><?php echo trans('text_purchase_title'); ?></a>  
        </li>
      <?php endif;?>
      <li class="active">
        <?php echo trans('text_purchase_log_title'); ?>
      </li>
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
    
    <div class="row">

      <!-- purchaseTransaction List Section Start-->
      <div class="col-xs-12">
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title">
              <?php echo trans('text_purchase_log_list_title'); ?>
            </h3>
            <div class="box-tools pull-right">
              <div class="btn-group" style="max-width:280px;">
                 <div class="input-group">
                  <div class="input-group-addon no-print" style="padding: 2px 8px; border-right: 0;">
                    <i class="fa fa-users" id="addIcon" style="font-size: 1.2em;"></i>
                  </div>
                  <select id="sup_id" class="form-control" name="sup_id" >
                    <option value=""><?php echo trans('text_select'); ?></option>
                    <?php foreach (get_suppliers() as $the_supplier) : ?>
                      <option value="<?php echo $the_supplier['sup_id'];?>">
                      <?php echo $the_supplier['sup_name'];?>
                    </option>
                  <?php endforeach;?>
                  </select>
                  <div class="input-group-addon no-print" style="padding: 2px 8px; border-left: 0;">
                    <i class="fa fa-search" id="addIcon" style="font-size: 1.2em;"></i>
                  </div>
                </div>
              </div>
          </div>
          </div>
          <div class='box-body'>     
            <?php
              $hide_colums = "";
              if (user_group_id() != 1) {
                if (! has_permission('access', 'read_purchase_log')) {
                  $hide_colums .= "7,";
                }
              }
            ?> 
            <div class="table-responsive">                     
              <table id="transaction-transaction-list" class="table table-bordered table-striped table-hovered" data-hide-colums="<?php echo $hide_colums; ?>">
                <thead>
                  <tr class="bg-gray">
                    <th class="w-5">
                      <?php echo trans('label_serial_no'); ?>
                    </th>
                    <th class="w-20">
                      <?php echo trans('label_created_at'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_type'); ?>
                    </th>
                    <th class="w-20">
                      <?php echo trans('label_supplier'); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_pmethod'); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_created_by'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_amount'); ?>
                    </th>
                    <th class="w-5">
                      <?php echo trans('label_view'); ?>
                    </th>
                  </tr>
                </thead>
                <tfoot>
                  <tr class="bg-gray">
                    <th class="w-5">
                      <?php echo trans('label_serial_no'); ?>
                    </th>
                    <th class="w-20">
                      <?php echo trans('label_created_at'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_type'); ?>
                    </th>
                    <th class="w-20">
                      <?php echo trans('label_supplier'); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_pmethod'); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_created_by'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_amount'); ?>
                    </th>
                    <th class="w-5">
                      <?php echo trans('label_view'); ?>
                    </th>
                  </tr>
                </tfoot>
              </table>    
            </div>
          </div>
        </div>
      </div>
       <!-- purchaseTransaction List Section End-->
    </div>
  </section>
  <!-- Content End -->
</div>
<!-- Content Wrapper End -->

<?php include ("footer.php"); ?>