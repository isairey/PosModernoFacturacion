<?php 
ob_start();
session_start();
include ("../_init.php");

// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}

// Redirect, If User has not Read Permission
if (user_group_id() != 1 && !has_permission('access', 'read_income_source')) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

// Set Document Title
$document->setTitle(trans('title_income_source'));

// Add Script
$document->addScript('../assets/itsolution24/angular/modals/IncomeSourceEditModal.js');
$document->addScript('../assets/itsolution24/angular/modals/IncomeSourceDeleteModal.js');
$document->addScript('../assets/itsolution24/angular/controllers/IncomeSourceController.js');

// Include Header and Footer
include("header.php"); 
include ("left_sidebar.php");
?>

<!-- Content Wrapper Start -->
<div class="content-wrapper" ng-controller="IncomeSourceController">

  <!-- Content Header Start -->
  <section class="content-header">
    <h1>
      <?php echo trans('text_income_source_title'); ?>
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
        <?php echo trans('text_income_source_title'); ?>
      </li>
    </ol>
  </section>
  <!-- Content Header End -->

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
    
    <?php if (user_group_id() == 1 || has_permission('access', 'create_income_source')) : ?>
      <div class="box box-info<?php echo create_box_state(); ?>">
        <div class="box-header with-border">
          <h3 class="box-title">
            <span class="fa fa-fw fa-plus"></span> <?php echo trans('text_new_income_source_title'); ?>
          </h3>
          <button type="button" class="btn btn-box-tool add-new-btn" data-widget="collapse" data-collapse="true">
            <i class="fa <?php echo !create_box_state() ? 'fa-minus' : 'fa-plus'; ?>"></i>
          </button>
        </div>

        <!-- Add IncomeSource Create Form -->
        <?php include('../_inc/template/income_source_create_form.php'); ?>

      </div>
    <?php endif; ?>

    <div class="row">
      <div class="col-xs-12">
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title">
              <?php echo trans('text_income_source_sub_title'); ?>
            </h3>
          </div>
          <div class="box-body">
            <div class="table-responsive">
              <?php
                $hide_colums = "";
                if (user_group_id() != 1) {
                  if (!has_permission('access', 'update_income_source')) {
                    $hide_colums .= "6,";
                  }
                  if (!has_permission('access', 'delete_income_source')) {
                    $hide_colums .= "7,";
                  }
                }
              ?>  
              
              <!-- IncomeSource List Start -->
              <table id="source-source-list" class="table table-bordered table-striped table-hover" data-hide-colums="<?php echo $hide_colums; ?>">
                <thead>
                  <tr class="bg-gray">
                    <th class="w-5">
                      <?php echo sprintf(trans('label_id'), null); ?>
                    </th>
                    <th class="w-30">
                      <?php echo trans('label_source_name'); ?>
                    </th>
                    <th class="w-5">
                      <?php echo trans('label_total'); ?>
                    </th>
                    <th class="w-5">
                      <?php echo trans('label_sort_order'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_status'); ?>
                    </th>
                    <th class="w-25">
                      <?php echo trans('label_created_at'); ?>
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
                    <th class="w-5">
                      <?php echo sprintf(trans('label_id'), null); ?>
                    </th>
                    <th class="w-30">
                      <?php echo trans('label_source_name'); ?>
                    </th>
                    <th class="w-5">
                      <?php echo trans('label_total'); ?>
                    </th>
                    <th class="w-5">
                      <?php echo trans('label_sort_order'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_status'); ?>
                    </th>
                    <th class="w-25">
                      <?php echo trans('label_created_at'); ?>
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
              <!-- IncomeSource List End -->
              
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