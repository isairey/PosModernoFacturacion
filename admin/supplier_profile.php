<?php 
ob_start();
session_start();
include ("../_init.php");

// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}

// Redirect, If User has not Read Permission
if (user_group_id() != 1 && !has_permission('access', 'read_supplier_profile')) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

// SUPPLIER MODEL
$supplier_model = registry()->get('loader')->model('supplier');

// FETCH SUPPLIER INFO   
$sup_id = isset($request->get['sup_id']) ? $request->get['sup_id'] : '';
$supplier = $supplier_model->getSupplier($sup_id); 
if (count($supplier) <= 1) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/supplier.php');
}

// Set Document Title
$document->setTitle(trans('title_supplier_profile'));

// Add Script
$document->addScript('../assets/itsolution24/angular/controllers/SupplierProfileController.js');
if (user_group_id() == 1 || has_permission('access', 'read_sell_report')) {
  $document->addScript('../assets/itsolution24/angular/controllers/ReportSupplierSellController.js');
}

// ADD BODY CLASS
$document->setBodyClass('sidebar-collapse supplier-profile');

// Include Header and Footer
include("header.php"); 
include ("left_sidebar.php");
?>

<script type="text/javascript">
  var supplier = <?php echo json_encode($supplier); ?>
</script>

<!-- Content Wrapper Start -->
<div class="content-wrapper">

  <!-- Content Header Start -->
  <section class="content-header">
    <?php include ("../_inc/template/partials/apply_filter.php"); ?>
    <h1>
      <?php echo sprintf(trans('text_supplier_profile_title'), ucfirst($supplier['sup_name'])); ?>
    </h1>
    <ol class="breadcrumb">
      <li>
        <a href="dashboard.php">
          <i class="fa fa-dashboard"></i> 
          <?php echo trans('text_dashboard'); ?>
        </a>
      </li>
      <li>
        <a href="supplier.php">
          <?php echo trans('text_suppliers'); ?>
        </a>
        </li>
      <li class="active">
        <?php echo ucfirst($supplier['sup_name']); ?>
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
      <div class="col-md-4 col-xs-12">
        <div class="box box-widget widget-user">
          <div class="widget-user-header bg-<?php echo $user->getPreference('base_color', 'black'); ?>">
            <h3 class="widget-user-username">
              <?php echo ucfirst($supplier['sup_name']); ?>
            </h3>
            <h5 class="widget-user-desc">
              <?php echo trans('text_since'); ?>: <?php echo format_date($supplier['created_at']); ?>
            </h5>
          </div>
          <div class="widget-user-image">
            <svg class="svg-icon"><use href="#icon-avatar-supplier"></svg>
          </div>
          <div class="box-footer">
            <div class="row">
              <div class="col-md-4 border-right">
                <div class="description-block">
                  <h5 class="description-header">
                    <?php echo $supplier_model->totalInvoice($sup_id); ?>
                  </h5>
                  <span class="description-text">
                    <small><?php echo trans('text_total_invoice'); ?></small>
                  </span>
                </div>
              </div>
              <div class="col-md-5 border-right">
                <div class="description-block">
                  <a id="edit-supplier" class="btn btn-block btn-primary" href="product.php?sup_id=<?php echo $supplier['sup_id']; ?>" title="<?php echo trans('text_supplier_products'); ?>">
                    <i class="fa fa-fw fa-list"></i> <?php echo trans('button_products'); ?>
                  </a>
                </div>
              </div>
              <div class="col-md-3">
                <div class="description-block">
                  <a id="edit-supplier" class="btn btn-block btn-warning" href="supplier.php?sup_id=<?php echo $supplier['sup_id']; ?>&amp;sup_name=<?php echo $supplier['sup_name']; ?>" title="<?php echo trans('button_edit'); ?>">
                    <i class="fa fa-fw fa-edit"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-5 col-xs-12 contact">
        <div class="box box-info">
          <div class="box-header with-border text-center">
            <h3 class="box-title">
              <?php echo trans('text_contact_information'); ?>
            </h3>
          </div>
          <div class="box-body">
            <div class="well text-center p-0">
              <address class="mb-0">
                <?php if (get_preference('invoice_view') == 'indian_gst') : ?>
                  <h4><b><?php echo trans('text_gtin');?>:</b> <?php echo $supplier['gtin'];?></h4>
                <?php endif;?>
                <?php if ($supplier['sup_mobile']) : ?>
                  <h4>
                    <strong>
                      <?php echo trans('label_mobile_phone'); ?>:
                    </strong> 
                    <?php echo $supplier['sup_mobile']; ?>
                  </h4>
                <?php endif; ?>
                <?php if ($supplier['sup_email']) : ?>
                  <h4>
                    <strong>
                      <?php echo trans('label_email'); ?>:
                    </strong>
                    <?php echo $supplier['sup_email']; ?>
                  </h4>
                <?php endif; ?>
                <?php if ($supplier['sup_address']) : ?>
                  <h4>
                    <strong>
                      <?php echo trans('label_address'); ?>:
                    </strong>
                    <?php echo $supplier['sup_address']; ?>
                  </h4>
                <?php endif; ?>
                <?php if ($supplier['sup_details']) : ?>
                  <h4>
                    <strong>
                      <?php echo trans('label_details'); ?>:
                    </strong>
                    <?php echo limit_char($supplier['sup_details'], 100); ?>
                  </h4>
                <?php endif; ?>
              </address>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-3 col-xs-12 balance">
        <div class="info-box">
          <span class="info-box-icon bg-<?php echo $user->getPreference('base_color', 'black'); ?>">
            <i>
              <?php echo get_currency_symbol(); ?>
            </i>
          </span>
          <div class="info-box-content"><h4><?php echo trans('text_balance'); ?></h4>
            <span class="info-box-number">
              <?php echo currency_format($supplier_model->totalAmount($sup_id)); ?>
            </span>

            <?php  if (total_product_of_supplier($sup_id) > 0 && (user_group_id() == 1 || has_permission('access', 'create_purchase_invoice'))) : ?>
              <hr>
              <a id="purchase-btn" data-id="<?php echo $supplier['sup_id']; ?>" data-name="<?php echo $supplier['sup_name']; ?>" href="purchase.php?box_state=open&sup_id=<?php echo $supplier['sup_id']; ?>" class="btn btn-sm btn-info">
                <i class="fa fa-fw fa-plus"></i> 
                <?php echo trans('button_purchase_product'); ?>
              </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12">

        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active">
              <a href="#purchases" data-toggle="tab" aria-expanded="false">
                <?php echo trans('text_purchases'); ?>
              </a>
            </li>
            <?php if (user_group_id() == 1 || has_permission('access', 'read_sell_report')) : ?>
            <li>
              <a href="#sells" data-toggle="tab" aria-expanded="false">
                <?php echo trans('text_sells'); ?>
              </a>
            </li>
            <?php endif; ?>
            <li>
              <a href="#chart" data-toggle="tab" aria-expanded="false">
                <?php echo trans('text_chart'); ?>
              </a>
            </li>
            <li class="pull-right">
              <div class="box-tools">
                <div class="btn-group" style="max-width:280px;">
                  <div class="input-group">
                    <div class="input-group-addon no-print" style="padding: 2px 8px; border-right: 0;">
                      <i class="fa fa-users" id="addIcon" style="font-size: 1.2em;"></i>
                    </div>
                    <select id="sup_id" class="form-control" name="sup_id" >
                      <option value=""><?php echo trans('text_select'); ?></option>
                      <?php foreach (get_suppliers() as $the_supploier) : ?>
                        <option value="<?php echo $the_supploier['sup_id'];?>">
                        <?php echo $the_supploier['sup_name'];?>
                      </option>
                    <?php endforeach;?>
                    </select>
                    <div class="input-group-addon no-print" style="padding: 2px 8px; border-left: 0;">
                      <i class="fa fa-search" id="addIcon" style="font-size: 1.2em;"></i>
                    </div>
                  </div>
                </div>
                <div class="btn-group">
                  <a type="button" class="btn btn-primary" href="purchase_log.php?sup_id=<?php echo $supplier['sup_id'];?>"><?php echo trans('button_transaction_list'); ?></a>
                </div>
              </div>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="purchases">
              <div class="box box-info" ng-controller="SupplierProfileController">
                <div class="box-header">
                  <h3 class="box-title">
                    <?php echo trans('text_purchase_invoice_list'); ?>
                  </h3>
                  <div class="box-tools pull-right">
                    <div class="btn-group">
                      <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
                        <span class="fa fa-fw fa-filter"></span> 
                        <?php if(isset($request->get['type'])) : ?>
                          <?php echo ucfirst($request->get['type']); ?>
                        <?php else : ?>
                          <?php echo trans('button_filter'); ?>
                        <?php endif; ?>
                        <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu" role="menu">
                          <li>
                            <a href="supplier_profile.php?sup_id=<?php echo $request->get['sup_id'];?>">
                              <?php echo trans('button_all_purchase'); ?>
                            </a>
                          </li>
                          <li>
                            <a href="supplier_profile.php?sup_id=<?php echo $request->get['sup_id'];?>&type=due">
                              <?php echo trans('button_due_purchase'); ?>
                            </a>
                          </li>
                          <li>
                            <a href="supplier_profile.php?sup_id=<?php echo $request->get['sup_id'];?>&type=paid">
                              <?php echo trans('button_paid_purchase'); ?>
                            </a>
                          </li>
                          <li>
                            <a href="supplier_profile.php?sup_id=<?php echo $request->get['sup_id'];?>&type=transfer">
                              <?php echo trans('button_stock_transfer'); ?>
                            </a>
                          </li>
                       </ul>
                    </div>
                  </div>
                </div>
                <div class='box-body'>     
                  <?php
                    $hide_colums = "";
                    if (user_group_id() != 1) {
                      if (! has_permission('access', 'purchase_payment')) {
                        $hide_colums .= "9,";
                      }
                      if (! has_permission('access', 'purchase_return')) {
                        $hide_colums .= "10,";
                      }
                      if (! has_permission('access', 'read_purchase_list')) {
                        $hide_colums .= "11,";
                      }
                      if (! has_permission('access', 'update_purchase_invoice_info')) {
                        $hide_colums .= "12,";
                      }
                      if (! has_permission('access', 'delete_purchase_invoice')) {
                        $hide_colums .= "12,";
                      }
                    }
                  ?> 
                  <div class="table-responsive">                     
                    <table id="product-product-list" class="table table-bordered table-striped table-hovered" data-id="<?php echo $supplier['sup_id']; ?>" data-hide-colums="<?php echo $hide_colums; ?>">
                      <thead>
                        <tr class="bg-gray">
                          <th class="w-5">
                            <?php echo trans('label_type'); ?>
                          </th>
                          <th class="w-10">
                            <?php echo trans('label_invoice_id'); ?>
                          </th>
                          <th class="w-15">
                            <?php echo trans('label_datetime'); ?>
                          </th>
                          <th class="w-15">
                            <?php echo trans('label_supplier'); ?>
                          </th>
                          <th class="w-10">
                            <?php echo trans('label_creator'); ?>
                          </th>
                          <th class="w-5">
                            <?php echo trans('label_invoice_amount'); ?> 
                          </th>
                          <th class="w-5">
                            <?php echo trans('label_invoice_paid'); ?>
                          </th>
                          <th class="w-5">
                            <?php echo trans('label_due'); ?>
                          </th>
                          <th class="w-5">
                            <?php echo trans('label_status'); ?>
                          </th>
                          <th class="w-5">
                            <?php echo trans('label_pay'); ?>
                          </th>
                          <th class="w-5">
                            <?php echo trans('label_return'); ?>
                          </th>
                          <th class="w-5">
                            <?php echo trans('label_view'); ?>
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
                            <?php echo trans('label_type'); ?>
                          </th>
                          <th class="w-10">
                            <?php echo trans('label_invoice_id'); ?>
                          </th>
                          <th class="w-15">
                            <?php echo trans('label_datetime'); ?>
                          </th>
                          <th class="w-15">
                            <?php echo trans('label_supplier'); ?>
                          </th>
                          <th class="w-10">
                            <?php echo trans('label_creator'); ?>
                          </th>
                          <th class="w-5">
                            <?php echo trans('label_invoice_amount'); ?> 
                          </th>
                          <th class="w-5">
                            <?php echo trans('label_invoice_paid'); ?>
                          </th>
                          <th class="w-5">
                            <?php echo trans('label_due'); ?>
                          </th>
                          <th class="w-5">
                            <?php echo trans('label_status'); ?>
                          </th>
                          <th class="w-5">
                            <?php echo trans('label_pay'); ?>
                          </th>
                          <th class="w-5">
                            <?php echo trans('label_return'); ?>
                          </th>
                          <th class="w-5">
                            <?php echo trans('label_view'); ?>
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
                  </div>
                </div> 
              </div>
            </div>
            <!-- End purchases Tab -->

            <?php if (user_group_id() == 1 || has_permission('access', 'read_sell_report')) : ?>
            <div class="tab-pane" id="sells">
              <div class="box box-success" ng-controller="ReportSupplierSellController">
                <div class="box-header">
                  <h3 class="box-title">
                    <?php echo trans('text_selling_invoice_list'); ?>
                  </h3>
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
                            <?php echo sprintf(trans('label_sup_name'), null); ?>
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

            <div class="tab-pane" id="chart">
              <?php
              if (from()) {
                $label = 'From ' . from() . ' to ' . to();
              } else {
                $label = 'Date:  ' . date('Y-m-d');
              }
              $labels = array($label); 
              $sells_array = array(supplier_selling_price($sup_id, from(), to()));
              $purchases_array = array(supplier_purchase_price($sup_id, from(), to()));
              ?>
              <canvas id="purchase-sell-comparison"></canvas>
            </div>
            <!-- End Chart Tab -->
          </div>
      </div>
        
      </div>
    </div>
  </section>
  <!-- Content End -->

</div>
<!-- Content Wrapper End -->

<script type="text/javascript"> 
$(function() {
  var labels = <?php echo json_encode($labels); ?>;
  var sellData = <?php echo json_encode($sells_array); ?>;
  var purchaseData = <?php echo json_encode($purchases_array); ?>;
  var ctx = document.getElementById("purchase-sell-comparison");
  var myChart = new Chart(ctx, {
      type: 'bar',
      data: {
          labels: labels,
          datasets: [
              {
                  label: "Selling",
                  borderColor: "#27CDF7",
                  borderWidth: "1",
                  backgroundColor: "#27CDF7",
                  pointHighlightStroke: "rgba(26,179,148,1)",
                  data: sellData
              },
              {
                  label: "purchase",
                  borderColor: "#27CDF7",
                  borderWidth: "1",
                  backgroundColor: "#00A65A",
                  pointHighlightStroke: "rgba(26,179,148,1)",
                  data: purchaseData
              }
          ]
      },
      options: {
          responsive: true,
          tooltips: {
              mode: 'index',
              intersect: false
          },
          hover: {
              mode: 'nearest',
              intersect: true
          },
          barPercentage: 0.5
      }
  });
});
</script>
 <!-- Include Footer -->
<?php include ("footer.php"); ?>