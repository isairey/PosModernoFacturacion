<?php 
ob_start();
session_start();
include ("../_init.php");

// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}

// Redirect, If User has not Read Permission
if (user_group_id() != 1 && !has_permission('access', 'read_order')) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

// Set Document Title
$document->setTitle(trans('title_order'));

// Add Script
$document->addScript('../assets/itsolution24/angular/modals/OrderViewModal.js');
$document->addScript('../assets/itsolution24/angular/controllers/OrderController.js');

// Include Header and Footer
include("header.php"); 
include ("left_sidebar.php") ;
?>

<!-- Content Wrapper Start -->
<div class="content-wrapper" ng-controller="OrderController">

  <!-- Content Header Start -->
  <section class="content-header">
    <?php include ("../_inc/template/partials/apply_filter.php"); ?>
    <h1>
      <?php echo trans('text_order_title'); ?>
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
          <a href="order.php"><?php echo trans('text_order_title'); ?></a>  
        <?php else: ?>
          <?php echo trans('text_order_title'); ?>  
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

    <div class="row">
      <div class="col-xs-12">
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title">
              <?php echo trans('text_order_list_title'); ?>
            </h3>
            <div class="box-tools pull-right">
              <div class="btn-group">
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                  <span class="fa fa-fw fa-filter"></span> 
                    <?php if(isset($request->get['type'])) : ?>
                      <?php echo ucfirst($request->get['type']); ?>
                  <?php else : ?>
                    <?php echo trans('button_filter'); ?>
                  <?php endif; ?>
                    &nbsp;<span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                  <li>
                      <a href="order.php">
                        <?php echo trans('button_all'); ?>
                      </a>
                    </li>
                    <li>
                      <a href="order.php?type=sent">
                        <?php echo trans('button_sent'); ?>
                      </a>
                    </li>
                    <li>
                      <a href="order.php?type=pending">
                        <?php echo trans('button_pending'); ?>
                      </a>
                    </li>
                    <li>
                      <a href="order.php?type=complete">
                        <?php echo trans('button_complete'); ?>
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
                if (!has_permission('access', 'read_order') && !has_permission('access', 'update_order') && !has_permission('access', 'create_sell_invoice') && !has_permission('access', 'delete_order')) {
                  $hide_colums .= "6,";
                }
              }
            ?> 
            <div class="xtable-responsive">                     
              <table id="order-order-list" class="table table-bordered table-striped table-hovered" data-hide-colums="<?php echo $hide_colums; ?>">
                <thead>
                  <tr class="bg-gray">
                    <th class="w-20">
                      <?php echo trans('label_date'); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_reference_no'); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_biller'); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_customer'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_total'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_status'); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_action'); ?>
                    </th>
                  </tr>
                </thead>
                <tfoot>
                  <tr class="bg-gray">
                    <th class="w-20">
                      <?php echo trans('label_date'); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_reference_no'); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_biller'); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_customer'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_total'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_status'); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_action'); ?>
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