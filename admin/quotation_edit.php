<?php 
ob_start();
session_start();
include ("../_init.php");

// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}

// Redirect, If User has not Read Permission
if (user_group_id() != 1 && !has_permission('access', 'update_quotation')) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

if (!$reference_no = $request->get['reference_no']) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/quotation.php');
}

$quotation_model = registry()->get('loader')->model('quotation');
$quotation =  $quotation_model->getQuotationInfo($reference_no);
$quotation_items = $quotation_model->getQuotationItems($reference_no);

// Set Document Title
$document->setTitle(trans('title_quotation_edit').'>'.$reference_no);

// Add Script
$document->addScript('../assets/itsolution24/angular/modals/QuotationViewModal.js');
$document->addScript('../assets/itsolution24/angular/controllers/QuotationController.js');

// Include Header and Footer
include("header.php"); 
include ("left_sidebar.php") ;
?>

<!-- Content Wrapper Start -->
<div class="content-wrapper" ng-controller="QuotationController">

  <!-- Content Header Start -->
  <section class="content-header">
    <h1>
      <?php echo trans('text_quotation_edit_title'); ?>
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
          <a href="quotation.php"><?php echo trans('text_quotation_title'); ?></a>
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
          <span class="fa fa-fw fa-pencil"></span> <?php echo trans('text_quotation_title'); ?> > <?php echo $reference_no;?>
        </h3>
        <button type="button" class="btn btn-box-tool add-new-btn" data-widget="collapse" data-collapse="true">
          <i class="fa fa-minus' : 'fa-plus'; ?>"></i>
        </button>
      </div>

      <!-- Edit Form -->
      <?php include('../_inc/template/quotation_edit_form.php'); ?>
      
    </div>

  </section>
  <!-- Content End -->
</div>
<!-- Content Wrapper End -->

<?php include ("footer.php"); ?>