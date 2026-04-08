<?php 
ob_start();
session_start();
include ("../_init.php");

// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}

// Redirect, If User has not Read Permission
if (!get_preference('expiry_yes') || (user_group_id() != 1 && !has_permission('access', 'read_expired_product'))) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

// Set Document Title
$document->setTitle(trans('title_expired'));

// Add Script
$document->addScript('../assets/itsolution24/angular/modals/ProductEditModal.js');
$document->addScript('../assets/itsolution24/angular/controllers/ExpiredProductController.js');

// Include Header and Footer
include("header.php"); 
include ("left_sidebar.php") ;
?>

<!-- Content Wrapper Start -->
<div class="content-wrapper" ng-controller="ExpiredProductController">

  <!-- Header Content Start -->
  <section class="content-header">
    <h1>
      <?php echo trans('text_expired_title'); ?>
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
        <a href="product.php"><?php echo trans('text_products'); ?></a>  
      </li>
      <li class="active">
        <?php echo trans('text_expired_title'); ?>
      </li>
    </ol>
  </section>
  <!--Header Content End -->

  <!-- Start Content -->
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
      <div class="col-md-12">
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">
                <?php echo trans('text_expired_listing_title');?>
              </h3>
              <div class="box-tools pull-right">
                <div class="btn-group">
                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                    <span class="fa fa-fw fa-filter"></span> 
                      <?php if(isset($request->get['type'])) : ?>
                        <?php echo ucfirst(str_replace(array('_','-'),' ', $request->get['type'])); ?>
                    <?php else : ?>
                      <?php echo trans('button_filter'); ?>
                    <?php endif; ?>
                      <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li>
                        <a href="expired.php">
                          <?php echo trans('button_expired'); ?>
                        </a>
                      </li>
                    <li>
                        <a href="expired.php?type=expiring_soon">
                          <?php echo trans('button_expiring_soon'); ?>
                        </a>
                      </li>
                   </ul>
                </div>
            </div>
            </div>
            <div class="box-body">
              <div class="table-responsive">

                <?php
                  $print_columns = '0,1,2,3,4,5,6,7';
                  if (user_group_id() != 1) {
                    if (! has_permission('access', 'show_purchase_price')) {
                      $print_columns = str_replace('5,', '', $print_columns);
                    }
                  }
                  $hide_colums = "";
                  if (user_group_id() != 1) {
                    if (! has_permission('access', 'view_purchase_price')) {
                      $hide_colums .= "5,";
                    }
                  }
                ?>
                
                <!-- Product List End -->
                <table id="product-product-list" class="table table-bordered table-striped table-hover" data-hide-colums="<?php echo $hide_colums; ?>" data-print-columns="<?php echo $print_columns;?>">
                  <thead>
                    <tr class="bg-gray">
                      <th class="w-5">
                        <?php echo sprintf(trans('label_serial_no'),null); ?>
                      </th>
                      <th class="w-25">
                        <?php echo sprintf(trans('label_name'), trans('label_product')); ?>
                      </th>
                      <th class="w-10">
                        <?php echo trans('label_supplier'); ?>
                      </th>
                      <th class="w-10">
                        <?php echo trans('label_mobile'); ?>
                      </th>
                      <th class="w-10">
                        <?php echo trans('label_box'); ?>
                      </th>
                      <th class="w-5">
                        <?php echo trans('label_purchase_price'); ?>
                      </th>
                      <th class="w-5">
                        <?php echo trans('label_quantity'); ?>
                      </th>
                      <th class="w-25">
                        <?php echo trans('label_expired_date'); ?>
                      </th>
                      <th class="w-5">
                        <?php echo trans('label_edit'); ?>
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
                      <th></th>
                      <th></th>
                      <th></th>
                    </tr>
                  </tfoot>
                </table>
                <!-- Product Product List End-->
              </div>
            </div>
          </div>
        </div>
      </div>
  </section>
  <!-- Content Header End -->
</div>
<!-- Content Wrapper End -->

<?php include ("footer.php"); ?>