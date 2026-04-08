<?php 
ob_start();
session_start();
include ("../_init.php");

// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}

// Redirect, If User has not Read Permission
if (user_group_id() != 1 && !has_permission('access', 'read_giftcard_topup')) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

// Set Document Title
$document->setTitle(trans('title_giftcard_topup'));

// Add Script
$document->addScript('../assets/itsolution24/angular/controllers/GiftcardTopupController.js');

// Include Header and Footer
include("header.php"); 
include ("left_sidebar.php") ;
?>

<!-- Content Wrapper Start -->
<div class="content-wrapper" ng-controller="GiftcardTopupController">

  <!-- Content Header Start -->
  <section class="content-header">
    <?php include ("../_inc/template/partials/apply_filter.php"); ?>
    <h1>
      <?php echo trans('text_giftcard_topup_title'); ?>
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
        <a href="giftcard.php"><?php echo trans('text_giftcard_title'); ?></a>  
      </li>
      <li class="active">
        <?php echo trans('text_topup_title'); ?>
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
              <?php echo trans('text_giftcard_topup_list_title'); ?>
            </h3>
          </div>
          <div class="box-body">
            <div class="table-responsive">  
              <?php
                  $hide_colums = "";
                  if (user_group_id() != 1) {
                    if (! has_permission('access', 'delete_topup_delete')) {
                      $hide_colums .= "4,";
                    }
                  }
                ?>  
              <table id="topup-topup-list" class="table table-bordered table-striped table-hover" data-hide-colums="<?php echo $hide_colums; ?>">
                <thead>
                  <tr class="bg-gray">
                    <th class="w-25" >
                      <?php echo trans('label_date'); ?>
                    </th>
                    <th class="w-25" >
                      <?php echo trans('label_card_no'); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_amount'); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_created_by'); ?>
                    </th>
                    <th class="w-5">
                      <?php echo trans('label_delete'); ?>
                    </th>
                  </tr>
                </thead>
                <tfoot>
                  <tr class="bg-gray">
                    <th class="w-25" >
                      <?php echo trans('label_date'); ?>
                    </th>
                    <th class="w-25" >
                      <?php echo trans('label_card_no'); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_amount'); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_created_by'); ?>
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