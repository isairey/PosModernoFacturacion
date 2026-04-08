<?php 
ob_start();
session_start();
include ("../_init.php");

// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}

// Redirect, If User has not Read Permission
if (user_group_id() != 1 && !has_permission('access', 'read_purchase_report')) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

// Set Document Title
$document->setTitle(trans('title_purchase_report'));

// Add Script
$document->addScript('../assets/itsolution24/angular/controllers/ReportPurchaseCategoryWiseController.js');

// ADD BODY CLASS
$document->setBodyClass('sidebar-collapse');

// Include Header and Footer
include("header.php"); 
include ("left_sidebar.php") ;
?>

<!-- Content Wrapper Start -->
<div class="content-wrapper" ng-controller="ReportPurchaseCategoryWiseController">

  <!-- Header Content Start -->
  <section class="content-header">
    <?php include ("../_inc/template/partials/apply_filter.php"); ?>
    <h1>
      <?php echo trans('text_purchase_report_title'); ?>
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
      <li class="active">
        <?php echo trans('text_purchase_report_title'); ?>
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
    
    <div class="row">
      <div class="col-xs-12">
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title">
              <?php echo trans('text_purchase_report_sub_title'); ?>  
            </h3>
            <div class="box-tools pull-right">
              
              <div class="btn-group">
                <button type="button" class="btn btn-info">
                  <span class="fa fa-filter"></span> 
                  <?php if (current_nav() == 'report_purchase_itemwise') : ?>
                    <?php echo trans('button_itemwise'); ?>
                  <?php elseif (current_nav() == 'report_purchase_categorywise') : ?>
                    <?php echo trans('button_categorywise'); ?>
                  <?php elseif (current_nav() == 'report_purchase_supplierwise') : ?>
                    <?php echo trans('button_supplierwise'); ?>
                  <?php else: ?>
                    <?php echo trans('button_filter'); ?>
                  <?php endif; ?>
                </button>
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li>
                      <a href="report_purchase_itemwise.php">
                        <?php echo trans('button_itemwise'); ?>
                      </a>
                    </li>
                    <li>
                      <a href="report_purchase_categorywise.php">
                        <?php echo trans('button_categorywise'); ?>
                      </a>
                    </li>
                    <li>
                      <a href="report_purchase_supplierwise.php">
                        <?php echo trans('button_supplierwise'); ?>
                      </a>
                    </li>
                 </ul>
              </div>

            </div>
          </div>
          <div class="box-body">
            <div class="table-responsive">
              <?php
                $print_columns = '0,1,2,3,4,5';
                if (user_group_id() != 1) {
                  if (! has_permission('access', 'show_purchase_price')) {
                    $print_columns = str_replace('4,', '', $print_columns);
                  }
                }
                $hide_colums = "";
                if (user_group_id() != 1) {
                  if (!has_permission('access', 'show_purchase_price')) {
                    $hide_colums .= "4,";
                  }
                }
              ?>
              <table id="report-report-list" class="table table-bordered table-striped table-hover" data-hide-colums="<?php echo $hide_colums; ?>" data-print-columns="<?php echo $print_columns;?>">
                <thead>
                  <tr class="bg-gray">
                    <th class="w-5">
                      <?php echo trans('label_serial_no'); ?>
                    </th>
                    <th class="w-20">
                      <?php echo trans('label_created_at'); ?>
                    </th>
                    <th class="w-25">
                      <?php echo trans('label_category_name'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_quantity'); ?>
                    </th>
                    <th class="w-20">
                      <?php echo trans('label_purchase_price'); ?>
                    </th>
                    <th class="w-20">
                      <?php echo trans('label_paid_amount'); ?>
                    </th>
                  </tr>
                </thead>
                <tfoot>
                  <tr class="bg-gray">
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
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