<?php 
ob_start();
session_start();
include ("../_init.php");

// redirect, if user not logged in
if (!is_loggedin()) {
  redirect(store('base_url') . '/index.php?redirect_to=' . url());
}

// redirect, user haven't read permission
if (user_group_id() != 1 && !has_permission('access', 'read_bank_transactions')) {
  redirect(store('base_url') . '/admin/dashboard.php');
}

// Set Document Title
$document->setTitle(trans('title_bank_transactions'));

// Add Script
$document->addScript('../assets/itsolution24/angular/controllers/BankingController.js');

include("header.php"); 
include ("left_sidebar.php") ;
?>

<!-- content wrapper start -->
<div class="content-wrapper">

  <!-- content header start -->
  <section class="content-header" ng-controller="BankingController">
    <?php include ("../_inc/template/partials/apply_filter.php"); ?>
    <h1>
      <?php echo trans('text_bank_transaction_title'); ?>
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
        <?php echo trans('text_bank_transaction_title'); ?>
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
              <?php echo trans('text_bank_transaction_list_title'); ?>
            </h3>
            <div class="btn-group pull-right">
              <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                <span class="fa fa-fw fa-filter"></span> 
                <?php if (isset($request->get['account_id'])) : ?>
                  <?php echo get_the_bank_account($request->get['account_id'], 'account_name'); ?>
                <?php else : ?>
                  <?php echo trans('button_filtering'); ?>
                <?php endif; ?>
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu" role="menu">
                <li>
                  <a href="<?php echo root_url();?>/admin/bank_transactions.php">
                    <?php echo trans('text_view_all_transactions'); ?>
                  </a>
                </li>
                <?php foreach (get_bank_accounts() as $account) : ?>
                  <li>
                    <a href="<?php echo root_url();?>/admin/bank_transactions.php?account_id=<?php echo $account['id'];?>">
                      <?php echo $account['account_name']; ?>
                    </a>
                  </li>
                <?php endforeach; ?>
               </ul>
            </div>
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
                      <?php echo trans('label_date'); ?>
                    </th>
                    <th class="w-20">
                      <?php echo sprintf(trans('label_id'),null); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_type'); ?>
                    </th>
                    <th class="w-20">
                      <?php echo trans('label_account'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_credit'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_debit'); ?>
                    </th>
                    <th class="w-10">
                      <?php echo trans('label_balance'); ?>
                    </th>
                    <th class="w-5">
                      <?php echo trans('label_view'); ?>
                    </th>
                  </tr>
                </thead>
                <tfoot>
                  <tr class="bg-gray">
                    <th colspan="4"><?php echo trans('label_total');?></th>
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