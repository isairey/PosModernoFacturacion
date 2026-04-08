<?php 
ob_start();
session_start();
include realpath(__DIR__.'/../').'/_init.php';

// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}

// dd(checkValidationServerConnection());
// dd(!checkValidationServerConnection() || !checkEnvatoServerConnection());

// Set Document Title
$document->setTitle(trans('title_dashboard'));

// Add Script
$document->addScript('../assets/itsolution24/angular/controllers/DashboardController.js');
$document->addScript('../assets/itsolution24/angular/controllers/ReportCollectionController.js');
$document->addScript('../assets/itsolution24/angular/modals/QuotationViewModal.js');

// ADD BODY CLASS
$document->setBodyClass('dashboard'); 
$banking_model = registry()->get('loader')->model('banking');

// Include Header and Footer
include ("header.php");
include ("left_sidebar.php");
?>

<!-- Content Wrapper Start -->
<div class="content-wrapper" ng-controller="DashboardController">

  <!-- Content Header Start -->
  <section class="content-header">
    <?php include ("../_inc/template/partials/apply_filter.php"); ?>
    <h1>
      <?php echo trans('text_dashboard'); ?>
      <small>
        <?php echo store('name'); ?>
      </small>
    </h1>
  </section>
  <!-- ContentH eader End -->

  <!-- Content Start -->
  <section class="content">

    <?php if(DEMO || settings('is_update_available')) : ?>
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-body">
            <?php if (settings('is_update_available')) : ?>
            <div class="alert alert-warning mb-0">
              <p><span class="fa fa-fw fa-info-circle"></span> Version <span class="label label-info"><?php echo settings('update_version');?></span> is available now. <a href="<?php echo settings('update_link');?>" target="_blink">Read changelog & update instructions here</a></p>
            </div>
            <?php endif; ?>
            <?php if (DEMO) : ?>
            <div class="alert alert-info mb-0">
              <p><span class="fa fa-fw fa-info-circle"></span> <?php echo $demo_text; ?></p>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <div class="hidden-xs action-button-sm">
    <?php include '../_inc/template/partials/action_buttons.php'; ?>
    </div>

    <hr>
    
    <!-- Small Boxes Start -->
    <div class="row">
      <div class="col-md-3 col-xs-6">
        <div id="invoice-count" class="small-box bg-green">
          <div class="inner">
            <h4>
              <i><?php echo trans('text_total_invoice'); ?></i> <span class="total-invoice"><?php echo number_format(total_invoice(from(), to())); ?></span>
            </h4>
            <h4>
              <i><?php echo trans('text_total_invoice_today'); ?></i> <span class="total-invoice"><?php echo number_format(total_invoice_today()); ?></span>
            </h4>
          </div>
          <div class="icon">
            <i class="fa fa-pencil"></i>
          </div>
          <?php if (user_group_id() == 1 || has_permission('access', 'read_customer')) : ?>
            <a href="invoice.php" class="small-box-footer">
              <?php echo trans('text_details'); ?> 
              <i class="fa fa-arrow-circle-right"></i>
            </a>
          <?php else:?>
            <a href="#" class="small-box-footer">
              &nbsp;
            </a>
          <?php endif;?>
        </div>
      </div>
      <div class="col-md-3 col-xs-6">
        <div id="customer-count" class="small-box bg-red">
          <div class="inner">
            <h4>
              <i><?php echo trans('text_total_customer'); ?></i> <span class="total-customer"><?php echo number_format(total_customer(from(), to())); ?></span>
            </h4>
            <h4>
              <i><?php echo trans('text_total_customer_today'); ?></i> <span class="total-customer"><?php echo number_format(total_customer_today()); ?></span>
            </h4>
          </div>
          <div class="icon">
            <i class="fa fa-users"></i>
          </div>
          <?php if (user_group_id() == 1 || has_permission('access', 'read_customer')) : ?>
            <a href="customer.php" class="small-box-footer">
              <?php echo trans('text_details'); ?> 
              <i class="fa fa-arrow-circle-right"></i>
            </a>
          <?php else:?>
            <a href="#" class="small-box-footer">
              &nbsp;
            </a>
          <?php endif;?>
        </div>
      </div>
      <div class="col-md-3 col-xs-6">
        <div id="supplier-count" class="small-box bg-purple">
          <div class="inner">
            <h4>
              <i><?php echo trans('text_total_supplier'); ?></i> <span class="total-suppier"><?php echo total_supplier(from(), to()); ?></span>
            </h4>
            <h4>
              <i><?php echo trans('text_total_supplier_today'); ?></i> <span class="total-suppier"><?php echo total_supplier_today(); ?></span>
            </h4>
          </div>
          <div class="icon">
            <i class="fa fa-fw fa-shopping-cart"></i>
          </div>
          <?php if (user_group_id() == 1 || has_permission('access', 'read_supplier')) : ?>
            <a href="supplier.php" class="small-box-footer">
              <?php echo trans('text_details'); ?> 
              <i class="fa fa-arrow-circle-right"></i>
            </a>
          <?php else:?>
            <a href="#" class="small-box-footer">
              &nbsp;
            </a>
          <?php endif;?>
        </div>
      </div>
      <div class="col-md-3 col-xs-6">
        <div id="product-count" class="small-box bg-yellow">
          <div class="inner">
            <h4>
              <i><?php echo trans('text_total_product'); ?></i> <span class="total-product"><?php echo number_format(total_product(from(), to())); ?></span>
            </h4>
            <h4>
              <i><?php echo trans('text_total_product_today'); ?></i> <span class="total-product"><?php echo number_format(total_product_today()); ?></span>
            </h4>
          </div>
          <div class="icon">
            <i class="fa fa-star"></i>
          </div>
          <?php if (user_group_id() == 1 || has_permission('access', 'read_product')) : ?>
            <a href="product.php" class="small-box-footer">
              <?php echo trans('text_details'); ?> 
              <i class="fa fa-arrow-circle-right"></i>
            </a>
          <?php else:?>
            <a href="#" class="small-box-footer">
              &nbsp;
            </a>
          <?php endif;?>
        </div>
      </div>
    </div>
    <!--Small Box End -->

    <?php if (user_group_id() == 1 || has_permission('access', 'read_recent_activities')) : ?>
    <?php if (user_group_id() == 1 || has_permission('access', 'read_sell_list') || has_permission('access', 'read_quotation') || has_permission('access', 'read_purchase_list') || has_permission('access', 'read_transfer') || has_permission('access', 'read_customer') || has_permission('access', 'read_supplier')):?>
    <div class="row">
      <div class="col-md-12">
        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title"><?php echo trans('text_recent_activities'); ?></h3>
          </div>
          <div class="box-body">
            <?php include('../_inc/template/partials/recent_activities.php'); ?>
          </div>
        </div>
      </div>
    </div> 
    <?php endif;?>
    <?php endif;?>

    <!-- Accounting Start -->
    <?php if (user_group_id() == 1 || has_permission('access', 'read_dashboard_accounting_report')) : ?>
    <div class="row">
      <div class="col-md-12">
        <div class="box box-info mb-0">
          <div class="box-body deposit-today">
            <div class="row">
              <div class="col-sm-6 col-xs-6">
                <div class="description-block border-right">
                  <h2 class="description-header"><?php echo currency_format(get_bank_deposit_amount(date('Y-m-d'), date('Y-m-d')));?></h2>
                  <h4 class="description-text"><?php echo trans('text_deposit_today'); ?></h4>
                </div>
              </div>
              <div class="col-sm-6 col-xs-6">
                <div class="description-block border-right">
                  <h2 class="description-header"><?php echo currency_format(get_bank_withdraw_amount(date('Y-m-d'), date('Y-m-d')));?></h2>
                  <h4 class="description-text"><?php echo trans('text_withdraw_today'); ?></h4>
                </div>
              </div>
            </div>
          </div>
          <div class="box-footer">
            <div class="row">
              <div class="pr-15 col-md-6 col-xs-12">
                <div class="box box-default banking-box">
                  <div class="box-header with-border">
                    <h3 class="box-title"><?php echo trans('text_recent_deposit'); ?></h3>
                  </div>
                  <div class="box-body">
                    <div class="table-responsive" style="min-height:150px">
                      <table class="table table-striped no-margin">
                        <thead>
                          <tr class="bg-gray">
                            <th class="w-35 text-center"><?php echo trans('label_date'); ?></th>
                            <th class="w-45"><?php echo trans('label_description'); ?></th>
                            <th class="w-20 text-right"><?php echo trans('label_amount'); ?></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if ($transactions = $banking_model->getTransactions('deposit', store_id(), 3)) : ?>
                            <?php foreach ($transactions as $row) : ?>
                              <tr>
                                <td class="w-35 text-center"><?php echo $row['created_at'];?></td>
                                <td class="w-45"><a class="view-deposit" data-refno="<?php echo $row['ref_no'];?>" href="#" tyle="white-space:nowrap;max-width:100%;overflow:hidden;display:inline-block;"><?php echo $row['title'];?></a></td>
                                <td class="w-20 text-right"><?php echo currency_format($row['amount']);?></td>
                              </tr>
                            <?php endforeach; ?>
                          <?php endif; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="box-footer clearfix text-center">
                    <?php if (user_group_id() == 1 || has_permission('access', 'read_bank_transactions')) : ?>
                      <a href="<?php echo root_url();?>/admin/bank_transactions.php"><?php echo trans('button_view_all'); ?> &rarr;</a>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <div class="pr-15 pl-5 col-md-6 col-xs-12">
                <div class="box box-default banking-box">
                  <div class="box-header with-border">
                    <h3 class="box-title"><?php echo trans('text_recent_withdraw'); ?></h3>
                  </div>
                  <div class="box-body">
                    <div class="table-responsive" style="min-height:150px">
                      <table class="table table-striped no-margin">
                        <thead>
                          <tr class="bg-gray">
                            <th class="w-35 text-center"><?php echo trans('label_date'); ?></th>
                            <th class="w-45"><?php echo trans('label_description'); ?></th>
                            <th class="w-20 text-right"><?php echo trans('label_amount'); ?></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if ($transactions = $banking_model->getTransactions('withdraw', store_id(), 3)) : ?>
                            <?php foreach ($transactions as $row) : ?>
                              <tr>
                                <td class="w-35 text-center"><?php echo $row['created_at'];?></td>
                                <td class="w-45"><a class="view-withdraw" data-refno="<?php echo $row['ref_no'];?>" href="#" style="white-space:nowrap;max-width:100%;overflow:hidden;display:inline-block;"><?php echo $row['title'];?></a></td>
                                <td class="w-20 text-right"><?php echo currency_format($row['amount']);?></td>
                              </tr>
                            <?php endforeach; ?>
                          <?php endif; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="box-footer clearfix text-center">
                    <?php if (user_group_id() == 1 || has_permission('access', 'read_bank_transactions')) : ?>
                      <a href="<?php echo root_url();?>/admin/bank_transactions.php" ><?php echo trans('button_view_all'); ?> &rarr;</a>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>  
    <!-- Accounting End -->

    <hr>

    <?php if (user_group_id() == 1 || has_permission('access', 'read_income_and_expense_report')) : ?>
      <div class="row">
        <div class="col-md-12 tour-item">
          <?php include ROOT.'/_inc/template/partials/income_expense_graph.php'; ?>
        </div>
      </div>
    <?php endif; ?>
  </section>
  <!-- Content End -->

</div>
<!-- Content Wrapper End -->
    
<?php include ("footer.php"); ?>