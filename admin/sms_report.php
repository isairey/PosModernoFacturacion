<?php 
ob_start();
session_start();
include ("../_init.php");

// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}

// Redirect, If User has not Read Permission
if (user_group_id() != 1 && !has_permission('access', 'read_sms_report')) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

// Set Document Title
$document->setTitle(trans('title_sms_report'));

// Add Script
$document->addScript('../assets/itsolution24/angular/modals/SMSResendModal.js');
$document->addScript('../assets/itsolution24/angular/controllers/SMSReportController.js');

// ADD BODY CLASS
$document->setBodyClass('sidebar-collapse');

// Include Header and Footer
include("header.php"); 
include ("left_sidebar.php") ;
?>

<!-- Content Wrapper Start -->
<div class="content-wrapper" ng-controller="SMSReportController">

  <!-- Content Header Start -->
  <section class="content-header">
    <?php include ("../_inc/template/partials/apply_filter.php"); ?>
    <h1>
      <?php echo trans('text_sms_report_title'); ?>
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
        <?php echo trans('text_sms_report_title'); ?>
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
    
    <div class="row">
      <div class="col-xs-12">
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title">
              <?php echo trans('text_sms_history_title'); ?>
            </h3>
            <div class="box-tools pull-right">
              <div class="btn-group">
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                  <span class="fa fa-filter"></span> 
                  <?php if (isset($request->get['type']) && $request->get['type'] == 'pending') : ?>
                    <?php echo trans('text_pending'); ?>
                  <?php elseif (isset($request->get['type']) && $request->get['type'] == 'delivered') : ?>
                    <?php echo trans('text_delivered'); ?>
                  <?php elseif (isset($request->get['type']) && $request->get['type'] == 'failed') : ?>
                    <?php echo trans('text_failed'); ?>
                  <?php else : ?>
                    <?php echo trans('text_all'); ?>
                  <?php endif; ?>
                  <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li>
                      <a href="sms_report.php">
                        <?php echo trans('button_all'); ?>
                      </a>
                    </li>
                    <li>
                      <a href="sms_report.php?type=pending">
                        <?php echo trans('button_pending'); ?>
                      </a>
                    </li>
                    <li>
                      <a href="sms_report.php?type=delivered">
                        <?php echo trans('button_delivered'); ?>
                      </a>
                    </li>
                    <li>
                      <a href="sms_report.php?type=failed">
                        <?php echo trans('button_failed'); ?>
                      </a>
                    </li>
                 </ul>
              </div>
            </div>
          </div>
          <div class="box-body">
            <div class="table-responsive">  
              <?php
                  $hide_colums = "";
                ?>
              <table id="sms-sms-list" class="table table-bordered table-striped table-hover" data-hide-colums="<?php echo $hide_colums; ?>">
                <thead>
                  <tr class="bg-gray">
                    <th class="w-5">
                      <?php echo trans('label_serial_no'); ?>
                    </th>
                    <th class="w-20">
                      <?php echo trans('label_schedule_at'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_campaign_name'); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_people_name'); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_mobile_number'); ?>
                    </th>
                    <th class="w-5">
                      <?php echo trans('label_process_status'); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_response_text'); ?>
                    </th>
                    <th class="w-5">
                      <?php echo trans('label_delivered'); ?>
                    </th>
                    <th class="w-5">
                      <?php echo trans('label_resend'); ?>
                    </th>
                  </tr>
                </thead>
                <tfoot>
                  <tr class="bg-gray">
                    <th class="w-5">
                      <?php echo trans('label_serial_no'); ?>
                    </th>
                    <th class="w-20">
                      <?php echo trans('label_schedule_at'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_campaign_name'); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_people_name'); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_mobile_number'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_process_status'); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_response_text'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_delivered'); ?>
                    </th>
                    <th class="w-5">
                      <?php echo trans('label_resend'); ?>
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