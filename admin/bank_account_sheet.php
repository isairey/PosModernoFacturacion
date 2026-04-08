<?php 
ob_start();
session_start();
include ("../_init.php");

// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}

// Redirect, If User has not Read Permission
if (user_group_id() != 1 && !has_permission('access', 'read_bank_account_sheet')) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

// Set Document Title
$document->setTitle(trans('title_bank_account_sheet'));

// Add Script
$document->addScript('../assets/itsolution24/angular/controllers/BankAccountSheetController.js');

// Include Header and Footer
include("header.php"); 
include ("left_sidebar.php") ;
?>

<!-- Content Wrapper Start -->
<div class="content-wrapper" ng-controller="BankAccountSheetController">

  <!-- Content Header Start -->
  <section class="content-header">
    <h1>
      <?php echo trans('text_bank_account_sheet_title'); ?>
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
        <a href="bank_account.php"><?php echo trans('text_bank_account_title'); ?></a>  
      </li>
      <li class="active">
        <?php echo trans('text_bank_account_sheet_title'); ?>
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
      <!-- BankAccount List Start -->
      <div class="col-xs-12">
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title">
              <?php echo trans('text_bank_account_sheet_list_title'); ?>
            </h3>
          </div>
          <div class="box-body">
            <div class="table-responsive">  
              <?php
                  $hide_colums = "";
                ?>  
              <table id="account-list" class="table table-bordered table-striped table-hover" data-hide-colums="<?php echo $hide_colums; ?>">
                <thead>
                  <tr class="bg-gray">
                    <th class="w-5" >
                      <?php echo trans('label_account_id'); ?>
                    </th>
                    <th class="w-25" >
                      <?php echo trans('label_account_name'); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_credit'); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_debit'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_transfer_to_other'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_transfer_from_other'); ?>
                    </th>
                    <th class="w-20">
                      <?php echo trans('label_balance'); ?>
                    </th>
                  </tr>
                </thead>
                <tfoot>
                  <tr class="bg-gray">
                    <th class="text-center" colspan="2">
                      <?php echo trans('label_total'); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_deposit'); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_withdraw'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_transfer_to_other'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_transfer_from_other'); ?>
                    </th>
                    <th class="w-20">
                      <?php echo trans('label_balance'); ?>
                    </th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- BankAccount List End -->
    </div>
  </section>
  <!-- Content End -->
  
</div>
<!-- Content Wrapper End -->

<?php include ("footer.php"); ?>