<?php 
ob_start();
session_start();
include ("../_init.php");

// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}

// Redirect, If User has not Read Permission
if (user_group_id() != 1 && !has_permission('access', 'read_analytics')) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

$from = from();
if (!$from) {
  $from = date('Y-m-d');
}

// Set Document Title
$document->setTitle(trans('title_analytics'));

// Add body class
$document->setBodyClass('sidebar-collapse');

// Include Header and Footer
include ("header.php"); 
include ("left_sidebar.php");
?>

<!-- Content Wrapper Start -->
<div class="content-wrapper">

  <!-- Content Header Start -->
  <section class="content-header">
    <h1>
      <?php echo trans('text_analytics_title'); ?>
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
        <?php echo trans('text_analytics_title'); ?>
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
      <div class="col-md-3 col-sm-3 col-xs-12">
        <?php include('../_inc/template/partials/top_products.php'); ?>
      </div> 
      <div class="col-md-3 col-sm-3 col-xs-12">
        <?php include('../_inc/template/partials/top_customers.php'); ?>
      </div> 
      <div class="col-md-3 col-sm-3 col-xs-12">
        <?php include('../_inc/template/partials/top_suppliers.php'); ?>
      </div> 
      <div class="col-md-3 col-sm-3 col-xs-12">
        <?php include('../_inc/template/partials/top_brands.php'); ?>
      </div>  
    </div>

    <div class="box">
    <div class="box-body">
    <div class="row">
      <div class="col-md-7">
        <?php include ROOT.'/_inc/template/partials/report_cashbook_summary.php';?>
        <div class="details text-center mt-5">
          <a href="report_cashbook.php"><?php echo trans('button_details');?> &rarr;</a>
        </div>
      </div>
      <div class="col-md-5"">
        <?php include ROOT.'/_inc/template/partials/progress_group.php';?>
      </div>
    </div>
    </div>
    </div>

    <div class="box">
    <div class="box-body">
      <div class="panel panel-info mb-0">
        <div class="panel-heading bg-blue">
          <h2 class="panel-title"><?php echo trans('title_customer_analytics');?></h2>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-6">
              <div class="nav-tabs-custom mb-0">
                <ul class="nav nav-tabs">
                  <li class="active">
                      <a href="#birthday_today" data-toggle="tab" aria-expanded="false">
                      <?php echo trans('text_birthday_today'); ?>
                    </a>
                    <!--<li>-->
                    <!--  <a href="#birthday_coming" data-toggle="tab" aria-expanded="false">-->
                    <!--  <?php echo trans('text_birthday_coming'); ?>-->
                    <!--</a>-->
                  </li>
                </ul>
                <div class="tab-content" style="height:215px;overflow-y:scroll;">
                  <div class="tab-pane active" id="birthday_today">
                    <div class="table-responsive">
                      <table class="table table-bordered table-striped table-condensed mb-0">
                        <thead>
                          <tr class="bg-gray">
                            <td class="w-50 text-center"><?php echo trans('label_customer');?></td>
                            <td class="w-40 text-center"><?php echo trans('label_member_since');?></td>
                            <td class="w-10 text-center"><?php echo trans('label_view');?></td>
                          </tr>
                        </thead>
                        <tbody>
                        <?php if ($customers = get_today_birthday_customers()):
                        foreach ($customers as $customer):?>
                          <tr>
                           <td class="text-center"><?php echo $customer['customer_name'];?></td>
                           <td class="text-center"><?php echo $customer['created_at'];?></td>
                           <td class="text-center">
                             <a href="customer_profile.php?customer_id=<?php echo $customer['customer_id'];?>"><span class="fa fa-link"></span></a>
                           </td>
                          </tr>
                        <?php endforeach;?>
                        <?php else:?>
                          <tr>
                            <td class="text-center text-red" colspan="3" style="height:165px;vertical-align:middle;"><i><?php echo trans('text_not_found');?></i></td>
                          </tr>
                        <?php endif;?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <!--<div class="tab-pane" id="birthday_coming">-->
                  <!--    <p class="text-red">This feature is unavailable!</p>-->
                  <!--</div>-->
                </div>
              </div>
            </div>
            <div class="col-md-6"">
              <div id="best-customer" class="small-box bg-info" style="box-shadow:none;">
                <div class="inner">
                  <h3 class="title">
                    <?php echo trans('text_best_customer'); ?>
                  </h3>
                  <h2 class="name">
                    <?php if (best_customer('customer_name')) : ?>
                      <a href="customer_profile.php?customer_id=<?php echo best_customer('customer_id'); ?>">
                        <?php echo best_customer('customer_name'); ?>
                      </a>
                    <?php else : ?>
                      No Customer Yet!
                    <?php endif; ?>
                  </h2>
                  <div class="amount">
                    <?php echo trans('text_purchase'); ?> 
                    <?php 
                      $total = best_customer('total');
                      echo '<strong>'.get_currency_symbol().currency_format($total).'</strong>';
                    ?>
                  </div>
                  <?php if (best_customer('customer_mobile')) : ?>
                    <div class="contact">
                      <i><?php echo trans('label_mobile'); ?>: <?php echo best_customer('customer_mobile'); ?></i>
                    </div>
                  <?php endif; ?>
                  <?php if (best_customer('customer_email')) : ?>
                    <div class="contact">
                      <i><?php echo sprintf(trans('label_email'), null); ?>: <?php echo best_customer('customer_email'); ?></i>
                     </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>

    <?php if (user_group_id() == 1 || has_permission('access', 'read_income_and_expense_report')) : ?>
    <div class="row">
      <div class="col-md-12">
          <?php include ROOT.'/_inc/template/partials/income_expense_graph.php'; ?>
      </div>
    </div>
    <?php endif; ?>

    <div class="box">
    <div class="box-body">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-info mb-0 r-0">
          <div class="panel-heading bg-blue r-0">
            <h2 class="panel-title"><?php echo trans('title_login_logs');?></h2>
          </div>
          <div class="panel-body" style="padding:0;max-height: 250px;overflow-y:scroll;">
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-condensed">
                <thead>
                  <tr class="bg-gray">
                    <td class="w-35 text-center"><?php echo trans('label_username');?></td>
                    <td class="w-25 text-center"><?php echo trans('label_ip');?></td>
                    <td class="w-30 text-center"><?php echo trans('label_date');?></td>
                    <td class="w-10 text-center"><?php echo trans('label_status');?></td>
                  </tr>
                </thead>
                <tbody>
                <?php
                $statement = db()->prepare("SELECT `username`, `ip`, `status`, `created_at` FROM `login_logs` ORDER BY `id` DESC LIMIT 0, 50");
                $statement->execute(array());
                $logs = $statement->fetchAll(PDO::FETCH_ASSOC);
                foreach ($logs as $log):?>
                  <tr>
                   <td class="text-center"><?php echo $log['username'];?></td>
                   <td class="text-center"><?php echo $log['ip'];?></td>
                   <td class="text-center"><?php echo $log['created_at'];?></td>
                   <td class="text-center"><?php echo $log['status'] == 'success' ? '<label class="label label-success">'.trans('label_logged_in').'</abel>' : '<label class="label label-danger">'.trans('label_failed').'</abel>';?></td>
                  </tr>
                <?php endforeach;?>
                </tbody>
              </table>
            </div>
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