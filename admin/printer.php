<?php 
ob_start();
session_start();
include ("../_init.php");

// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}

// Redirect, If User has not Read Permission
if (user_group_id() != 1 && !has_permission('access', 'read_printer')) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

// Set Document Title
$document->setTitle(trans('title_printer'));

// Add Script
$document->addScript('../assets/itsolution24/angular/controllers/PrinterController.js');

// Include Header and Footer
include("header.php"); 
include ("left_sidebar.php");
?>

<!-- Content Wrapper Start -->
<div class="content-wrapper" ng-controller="PrinterController">

  <!-- Content Header Start -->
  <section class="content-header">
    <h1>
      <?php echo trans('text_printer_title'); ?>
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
        <?php echo trans('text_printer_title'); ?>
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
    
    <?php if (user_group_id() == 1 || has_permission('access', 'create_printer')) : ?>
      <div class="box box-info<?php echo create_box_state(); ?>">
        <div class="box-header with-border">
          <h3 class="box-title">
            <span class="fa fa-fw fa-plus"></span> <?php echo trans('text_new_printer_title'); ?>
          </h3>
          <button type="button" class="btn btn-box-tool add-new-btn" data-widget="collapse" data-collapse="true">
            <i class="fa <?php echo !create_box_state() ? 'fa-minus' : 'fa-plus'; ?>"></i>
          </button>
        </div>
        <?php if (isset($error_message)): ?>
          <div class="alert alert-danger">
            <p>
              <span class="fa fa-warning"></span> 
              <?php echo $error_message ; ?>
            </p>
          </div>
        <?php elseif (isset($success_message)): ?>
          <div class="alert alert-success">
            <p>
              <span class="fa fa-check"></span> 
              <?php echo $success_message ; ?>
            </p>
          </div>
        <?php endif; ?>

        <!-- Add Printer Create Form -->
        <?php include('../_inc/template/printer_create_form.php'); ?>

      </div>
    <?php endif; ?>

    <div class="row">
      <div class="col-xs-12">
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title">
              <?php echo trans('text_printer_list_title'); ?>
            </h3>
          </div>
          <div class="box-body">
            <div class="table-responsive">  
              <?php
                $hide_colums = "";
                if (user_group_id() != 1) {
                  if (!has_permission('access', 'update_printer')) {
                    $hide_colums .= "7,";
                  }
                  if (!has_permission('access', 'delete_printer')) {
                    $hide_colums .= "8,";
                  }
                }
              ?> 
              
              <!-- Printer List Start -->
              <table id="printer-printer-list" class="table table-bordered table-striped table-hover" data-hide-colums="<?php echo $hide_colums; ?>">
                <thead>
                  <tr class="bg-gray">
                    <th class="w-5">
                      <?php echo sprintf(trans('label_serial_no'), null); ?>
                    </th>
                    <th class="w-20">
                      <?php echo trans('label_title'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_type'); ?>
                    </th>
                    <th class="w-25">
                      <?php echo trans('label_path'); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_ip_address'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_port'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_status'); ?>
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
                      <?php echo trans('label_serial_no'); ?>
                    </th>
                    <th class="w-20">
                      <?php echo trans('label_title'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_type'); ?>
                    </th>
                    <th class="w-25">
                      <?php echo trans('label_path'); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_ip_address'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_port'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_status'); ?>
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
              <!-- Printer List End -->
              
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