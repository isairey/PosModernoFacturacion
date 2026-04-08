<?php 
ob_start();
session_start();
include ("../_init.php");

// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}

// Redirect, If User has not Read Permission
if (user_group_id() != 1 && !has_permission('access', 'update_order')) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

if (!$reference_no = $request->get['reference_no']) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/order.php');
}

$order_model = registry()->get('loader')->model('order');
$order =  $order_model->getOrderInfo($reference_no);
$order_items = $order_model->getOrderItems($reference_no);

// Set Document Title
$document->setTitle(trans('title_order_edit').'>'.$reference_no);

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
    <h1>
      <?php echo trans('text_order_edit_title'); ?>
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
          <a href="order.php"><?php echo trans('text_order_title'); ?></a>
      </li>
      <li>
          <?php echo $reference_no;?> 
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
    
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title">
          <span class="fa fa-fw fa-pencil"></span> <?php echo trans('text_order_title'); ?> > <?php echo $reference_no;?>
        </h3>
        <button type="button" class="btn btn-box-tool add-new-btn" data-widget="collapse" data-collapse="true">
          <i class="fa fa-minus' : 'fa-plus'; ?>"></i>
        </button>
      </div>

      <!-- Edit Form -->
      <?php include('../_inc/template/order_edit_form.php'); ?>
      
    </div>

  </section>
  <!-- Content End -->
</div>
<!-- Content Wrapper End -->

<?php include ("footer.php"); ?>