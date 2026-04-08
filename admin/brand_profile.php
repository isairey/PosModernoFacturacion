<?php 
ob_start();
session_start();
include ("../_init.php");

// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}

// Redirect, If User has not Read Permission
if (user_group_id() != 1 && !has_permission('access', 'read_brand_profile')) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

// SUPPLIER MODEL
$brand_model = registry()->get('loader')->model('brand');

// FETCH SUPPLIER INFO   
$brand_id = isset($request->get['brand_id']) ? $request->get['brand_id'] : '';
$brand = $brand_model->getBrand($brand_id); 
if (count($brand) <= 1) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/brand.php');
}

// Set Document Title
$document->setTitle(trans('title_brand_profile'));

// Add Script
$document->addScript('../assets/itsolution24/angular/controllers/BrandProfileController.js');
if (user_group_id() == 1 || has_permission('access', 'read_sell_report')) {
  $document->addScript('../assets/itsolution24/angular/controllers/ReportBrandSellController.js');
}

// ADD BODY CLASS
$document->setBodyClass('sidebar-collapse brand-profile');

// Include Header and Footer
include("header.php"); 
include ("left_sidebar.php");
?>

<script type="text/javascript">
  var brand = <?php echo json_encode($brand); ?>
</script>

<!-- Content Wrapper Start -->
<div class="content-wrapper">

  <!-- Content Header Start -->
  <section class="content-header">
    <?php include ("../_inc/template/partials/apply_filter.php"); ?>
    <h1>
      <?php echo sprintf(trans('text_brand_profile_title'), ucfirst($brand['brand_name'])); ?>
    </h1>
    <ol class="breadcrumb">
      <li>
        <a href="dashboard.php">
          <i class="fa fa-dashboard"></i> 
          <?php echo trans('text_dashboard'); ?>
        </a>
      </li>
      <li>
        <a href="brand.php">
          <?php echo trans('text_brands'); ?>
        </a>
        </li>
      <li class="active">
        <?php echo ucfirst($brand['brand_name']); ?>
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
    
    <div class="row profile-heading">
      <div class="col-sm-4 col-xs-12">
        <div class="box box-widget widget-user">
          <div class="widget-user-header bg-<?php echo $user->getPreference('base_color', 'black'); ?>">
            <h3 class="widget-user-username">
              <?php echo ucfirst($brand['brand_name']); ?>
            </h3>
            <h5 class="widget-user-desc">
              <?php echo trans('text_since'); ?>: <?php echo format_date($brand['created_at']); ?>
            </h5>
          </div>
          <div class="widget-user-image">
            <svg class="svg-icon"><use href="#icon-avatar-brand"></svg>
          </div>
          <div class="box-footer">
            <div class="row">
              <div class="col-md-6 border-right">
                <div class="description-block">
                  <a id="edit-brand" class="btn btn-block btn-primary" href="product.php?brand_id=<?php echo $brand['brand_id']; ?>" title="<?php echo trans('text_brand_products'); ?>">
                    <i class="fa fa-fw fa-list"></i> <?php echo trans('button_all_products'); ?>
                  </a>
                </div>
              </div>
              <div class="col-md-6">
                <div class="description-block">
                  <a id="edit-brand" class="btn btn-block btn-warning" href="brand.php?brand_id=<?php echo $brand['brand_id']; ?>&amp;brand_name=<?php echo $brand['brand_name']; ?>" title="<?php echo trans('button_edit'); ?>">
                    <i class="fa fa-fw fa-edit"></i> <?php echo trans('button_edit'); ?>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-5 contact">
        <div class="box box-info">
          <div class="box-header with-border text-center">
            <h3 class="box-title">
              <?php echo trans('text_contact_information'); ?>
            </h3>
          </div>
          <div class="box-body">
            <div class="well text-center">
              <address>
                <?php if ($brand['brand_details']) : ?>
                  <h4>
                    <strong>
                      <?php echo trans('label_details'); ?>:
                    </strong>
                    <?php echo limit_char($brand['brand_details'], 100); ?>
                  </h4>
                <?php endif; ?>
              </address>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 balance">
        <div class="info-box">
          <span class="info-box-icon bg-<?php echo $user->getPreference('base_color', 'black'); ?>">
            <i>
              <?php echo get_currency_symbol(); ?>
            </i>
          </span>
          <div class="info-box-content"><h4><?php echo trans('text_total_sell'); ?></h4>
            <span class="info-box-number">
              <?php echo currency_format($brand_model->totalSell($brand_id, from(), to())); ?>
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12">

        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <?php if (user_group_id() == 1 || has_permission('access', 'read_sell_report')) : ?>
            <li class="active">
              <a href="#sells" data-toggle="tab" aria-expanded="false">
                <?php echo trans('text_sells'); ?>
              </a>
            </li>
            <?php endif; ?>
            <li class="pull-right">
              <div class="box-tools">
                <div class="btn-group">
                  <a type="button" class="btn btn-info" href="purchase_log.php?brand_id=<?php echo $brand['brand_id'];?>"><span class="fa fa-fw fa-list"></span> <?php echo trans('button_transaction_list'); ?></a>
                </div>
              </div>
            </li>
          </ul>
          <div class="tab-content">
            <?php if (user_group_id() == 1 || has_permission('access', 'read_sell_report')) : ?>
            <div class="tab-pane active" id="sells">
              <div class="box box-success" ng-controller="ReportBrandSellController">
                <div class="box-header">
                  <h3 class="box-title">
                    <?php echo trans('text_selling_invoice_list'); ?>
                  </h3>
                  <div class="box-tools">
                    <div class="btn-group" style="max-width:280px;">
                      <div class="input-group">
                        <div class="input-group-addon no-print" style="padding: 2px 8px; border-right: 0;">
                          <i class="fa fa-users" id="addIcon" style="font-size: 1.2em;"></i>
                        </div>
                        <select id="brand_id" class="form-control" name="brand_id" >
                          <option value=""><?php echo trans('text_select'); ?></option>
                          <?php foreach (get_brands() as $the_supploier) : ?>
                            <option value="<?php echo $the_supploier['brand_id'];?>">
                            <?php echo $the_supploier['brand_name'];?>
                          </option>
                        <?php endforeach;?>
                        </select>
                        <div class="input-group-addon no-print" style="padding: 2px 8px; border-left: 0;">
                          <i class="fa fa-search" id="addIcon" style="font-size: 1.2em;"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="box-body">
                  <div class="table-responsive">  
                    <?php
                      $print_columns = '0,1,2,3,4,5,6,7,8';
                      if (user_group_id() != 1) {
                        if (! has_permission('access', 'show_purchase_price')) {
                          $print_columns = str_replace('4,', '', $print_columns);
                        }
                        if (! has_permission('access', 'show_profit')) {
                          $print_columns = str_replace(',8', '', $print_columns);
                        }
                      }
                      $hide_colums = "3,";
                      if (user_group_id() != 1) {
                        if (! has_permission('access', 'view_purchase_price')) {
                          $hide_colums .= "4,";
                        }
                        if (! has_permission('access', 'view_profit')) {
                          $hide_colums .= "8,";
                        }
                      }
                    ?>
                    <table id="report-report-list" class="table table-bordered table-striped table-hover"data-hide-colums="<?php echo $hide_colums; ?>" data-print-columns="<?php echo $print_columns;?>">
                      <thead>
                        <tr class="bg-gray">
                          <th class="w-10">
                            <?php echo trans('label_serial_no'); ?>
                          </th>
                          <th class="w-15">
                            <?php echo trans('label_invoice_id'); ?>
                          </th>
                          <th class="w-20">
                            <?php echo trans('label_created_at'); ?>
                          </th>
                          <th class="w-20">
                            <?php echo sprintf(trans('label_brand_name'), null); ?>
                          </th>
                          <th class="w-10">
                            <?php echo trans('label_quantity'); ?>
                          </th>
                          <th class="w-10">
                            <?php echo trans('label_purchase_price'); ?>
                          </th>
                          <th class="w-10">
                            <?php echo trans('label_selling_price'); ?>
                          </th>
                          <th class="w-10">
                            <?php echo trans('label_tax_amount'); ?>
                          </th>
                          <th class="w-10">
                            <?php echo trans('label_discount_amount'); ?>
                          </th>
                          <th class="w-10">
                            <?php echo trans('label_profit'); ?>
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
                          <th></th>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <?php endif; ?>
            <!-- End Sells Tab -->
          </div>
      </div>
        
      </div>
    </div>
  </section>
  <!-- Content End -->

</div>
<!-- Content Wrapper End -->

 <!-- Include Footer -->
<?php include ("footer.php"); ?>