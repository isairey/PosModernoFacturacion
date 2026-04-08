<?php 
ob_start();
session_start();
include ("../_init.php");

// redirect, if user not logged in
if (!is_loggedin()) {
  redirect(store('base_url') . '/index.php?redirect_to=' . url());
}

// redirect, user haven't read permission
if (user_group_id() != 1 && !has_permission('access', 'read_bank_transfer')) {
  redirect(store('base_url') . '/admin/dashboard.php');
}

// Set Document Title
$document->setTitle(trans('title_bank_transfer'));

// Add Script
$document->addScript('../assets/itsolution24/angular/controllers/BankTransferController.js');

include("header.php"); 
include ("left_sidebar.php") ;
?>

<!-- content wrapper start -->
<div class="content-wrapper">

  <!-- content header start -->
  <section class="content-header" ng-controller="BankTransferController">
    <?php include ("../_inc/template/partials/apply_filter.php"); ?>
    <h1>
      <?php echo trans('text_bank_transfer_title'); ?>
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
        <?php echo trans('text_banking_title'); ?>
      </li>
    </ol>
  </section>
  <!-- content header end -->

  <!-- content start -->
  <section class="content">
    <div class="row">
      <!-- banking list section start-->
      <div class="col-xs-12">
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title">
              <?php echo trans('text_list_bank_transfer_title'); ?>
            </h3>
          </div>
          <div class='box-body'>     
            <?php
              $hide_colums = "";
            ?> 
            <div class="table-responsive">                     
              <table id="invoice-invoice-list" class="table table-bordered table-striped table-hovered"data-hide-colums="<?php echo $hide_colums; ?>">
                <thead>
                  <tr class="bg-gray">
                    <th class="w-15">
                      <?php echo sprintf(trans('label_id'),null); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_date'); ?>
                    </th>
                    <th class="w-25">
                      <?php echo trans('label_from_account'); ?>
                    </th>
                    <th class="w-25">
                      <?php echo trans('label_to_account'); ?>
                    </th>
                    <th class="w-20">
                      <?php echo trans('label_amount'); ?>
                    </th>
                  </tr>
                </thead>
                <tfoot>
                  <tr class="success">
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
       <!-- banking list section end-->
    </div>
  </section>
  <!-- content end -->
</div>
<!-- content wrapper end -->

<?php include ("footer.php"); ?>