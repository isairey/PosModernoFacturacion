<?php 
ob_start();
session_start();
include ("../_init.php");

// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}

// Redirect, If User has not Read Permission
if (user_group_id() != 1 && !has_permission('access', 'read_sell_invoice')) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

if (!isset($request->get['invoice_id'])) { 
  redirect('invoice.php');
}
$invoice_id = $request->get['invoice_id'];

// INVOICE MODEL
$invoice_model = registry()->get('loader')->model('invoice');
$invoice_info = $invoice_model->getInvoiceInfo($invoice_id);
if (!$invoice_info) {
  redirect('invoice.php');
}

$document->setTitle(trans('text_invoice') . ' - ' . $invoice_id);

// SIDEBAR COLLAPSE
$document->setBodyClass('sidebar-collapse');
$document->setBodyClass('invoice-page');

// ADD BODY CLASS
$document->setBodyClass('sidebar-collapse');

// Add Script
$document->addScript('../assets/itsolution24/angular/controllers/InvoiceViewController.js'); 

// Include Header and Footer
include("header.php"); 
include ("left_sidebar.php"); 
?>

<!-- Content Wrapper Start -->
<div class="content-wrapper">

  <!-- Content Header Start -->
  <section class="content-header">
    <h1>
      <?php echo trans('text_invoice_title'); ?> &larr; <?php echo $invoice_id ; ?>
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
        <a href="invoice.php">
          <?php echo trans('text_invoice'); ?>
        </a>
      </li>
      <li class="active">
        <?php echo $invoice_id ; ?>
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
        <div class="box box-info">
        	<div class='box-body'>  

            <div id="invoice" class="row" ng-controller="InvoiceViewController">
              <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">  
                <?php include('../_inc/template/partials/invoice_view.php'); ?>
              </div>
            </div>

    		  </div> 
        </div>
      </div>
    </div>
  </section>
  <!-- Content End-->

</div>
<!-- Content Wrapper End -->

<?php include ("footer.php"); ?>