<?php 
ob_start();
session_start();
include ("../_init.php");

// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}

// Redirect, If User has not Read Permission
if (user_group_id() != 1 && !has_permission('access', 'read_user_profile')) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

// LOAD USER MODEL
registry()->get('loader')->model('user');
$this_user_model = registry()->get('model_user');

// FETCH USER INFO
$this_user = $this_user_model->getUser($request->get['id']);
if (count($this_user) <= 1) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/user.php');
}

// Set Document Title
$document->setTitle(trans('title_user_profile'));

// Add ScriptS
$document->addScript('../assets/itsolution24/angular/controllers/UserController.js');
$document->addScript('../assets/itsolution24/angular/controllers/UserProfileController.js');
$document->addScript('../assets/itsolution24/angular/controllers/ReportUserSellController.js');
$document->addScript('../assets/itsolution24/angular/controllers/UserLoginLogController.js');

// ADD BODY CLASS 
$document->setBodyClass('sidebar-collapse user-profile'); 

// Include Header and Footer
include 'header.php'; 
include 'left_sidebar.php';  
?>

<!-- Content Wrapper Start -->
<div class="content-wrapper" ng-controller="UserProfileController">

  <!-- Header Content Start -->
  <section class="content-header">
    <?php include ("../_inc/template/partials/apply_filter.php"); ?>
    <h1>
      <?php echo sprintf(trans('text_profile_title'), $this_user['username']); ?>
    </h1>
    <ol class="breadcrumb">
      <li>
        <a href="dashboard.php">
          <i class="fa fa-dashboard"></i> 
          <?php echo trans('text_dashboard'); ?>
        </a>
      </li>
      <li>
        <a href="user.php">
          <?php echo trans('text_users'); ?>
        </a>
      </li>
      <li class="active">
        <?php echo $this_user['username']; ?>
      </li>
    </ol>
  </section>
  <!-- Header Content End -->

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
      <!-- Profile Part Start -->
      <div class="col-sm-4 col-xs-12">
        <div class="box box-widget widget-user">
          <div class="widget-user-header bg-<?php echo $user->getPreference('base_color', 'black'); ?>">
            <h3 class="widget-user-username">
              <?php echo $this_user['username']; ?>
            </h3>
            <h5 class="widget-user-desc">
              <?php echo trans('text_since'); ?> 
              <?php echo format_date($this_user['created_at']); ?>
            </h5>
          </div>
          <div class="widget-user-image">
            <?php if (get_the_user(user_id(), 'user_image') && ((FILEMANAGERPATH && is_file(FILEMANAGERPATH.get_the_user(user_id(), 'user_image')) && file_exists(FILEMANAGERPATH.get_the_user(user_id(), 'user_image'))) || (is_file(DIR_STORAGE . 'users' . get_the_user(user_id(), 'user_image')) && file_exists(DIR_STORAGE . 'users' . get_the_user(user_id(), 'user_image'))))) : ?>
                  <img  src="<?php echo FILEMANAGERURL ? FILEMANAGERURL : root_url().'/storage/users'; ?>/<?php echo get_the_user(user_id(), 'user_image'); ?>">
            <?php else : ?>
              <svg class="svg-icon"><use href="#icon-<?php echo user_avatar($this_user['sex']); ?>"></svg>
            <?php endif; ?>
          </div>
          <div class="box-footer">
            <div class="row">
              <div class="col-sm-6 border-right">
                <div class="description-block">
                  <h5 class="description-header">
                    <?php echo user_total_invoice($this_user['id']); ?>
                  </h5>
                  <span class="description-text">
                    <small><?php echo trans('text_total_invoice'); ?></small>
                  </span>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="description-block">
                  <?php if (user_group_id() == 1 || has_permission('access', 'update_user')) : ?>
                    <button ng-click="userEdit(<?php echo $this_user['id']; ?>, '<?php echo $this_user['username']; ?>')" title="<?php echo trans('button_edit'); ?>" class="btn btn-sm btn-info"><i class="fa fa-fw fa-pencil"></i> <?php echo trans('button_edit');?></button>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <!-- Profile Part End -->

    <!-- Contact Info Part Start -->
      <div class="col-sm-5 col-xs-12 contact">
        <div class="box box-info">
          <div class="box-header with-border text-center">
            <h3 class="box-title">
              <?php echo trans('text_contact_information'); ?>
            </h3>
          </div>
          <div class="box-body">
            <div class="well text-center">
              <address>
                <?php if ($this_user['mobile']) : ?>
                <h4>
                  <strong>
                    <?php echo trans('label_phone'); ?>:
                  </strong> 
                  <?php echo $this_user['mobile']; ?>
                </h4>
                <?php endif; ?>
                <?php if ($this_user['email']) : ?>
                  <h4>
                    <strong>
                      <?php echo trans('label_email'); ?>:
                    </strong> 
                    <?php echo $this_user['email']; ?>
                  </h4>
                <?php endif; ?> 
                <?php if ($this_user['dob']) : ?>
                  <h4>
                    <strong>
                      <?php echo trans('label_date_of_birth'); ?>:
                    </strong> 
                    <?php echo $this_user['dob']; ?>
                  </h4>
                <?php endif; ?>   
                <?php if ($this_user['address']) : ?>
                  <h4>
                    <strong>
                      <?php echo trans('label_address'); ?>:
                    </strong> 
                    <?php echo $this_user['address']; ?>
                  </h4>
                <?php endif; ?>  
              </address>
            </div>
          </div>
        </div>
      </div>
      <!-- Contact Info Part End -->

      <!-- Balance Part Start -->
      <div class="col-sm-3 col-xs-12 collection">
        <div class="info-box">
          <span class="info-box-icon bg-<?php echo $user->getPreference('base_color', 'black'); ?>">
            <i>
              <?php echo get_currency_symbol(); ?>
            </i>
          </span>
          <div class="info-box-content">
            <h2 class="info-box-text">
              <?php echo trans('label_collection');?>
            </h2>
            <span id="user-collection" class="info-box-number">
              <?php echo currency_format(user_total_purchase_amount($this_user['id'])); ?>
            </span>
          </div>
        </div>
      </div>
      <!-- Balance Part End -->
    </div>
    <div class="row">
      <div class="col-xs-12">

        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active">
              <a href="#sell_report" data-toggle="tab" aria-expanded="false">
                <?php echo trans('text_sell_report'); ?>
              </a>
            </li>
            <!-- <li class="">
              <a href="#purchase_report" data-toggle="tab" aria-expanded="false">
                <?php echo trans('text_purchase_report'); ?>
              </a>
            </li>
            <li class="">
              <a href="#payment_report" data-toggle="tab" aria-expanded="false">
                <?php echo trans('text_payment_report'); ?>
              </a>
            </li> -->
            <li class="">
              <a href="#login_log" data-toggle="tab" aria-expanded="false">
                <?php echo trans('text_login_log'); ?>
              </a>
            </li>
            <li class="pull-right">
              <div class="box-tools pull-right">
                <div class="btn-group" style="max-width:280px;">
                  <div class="input-group">
                    <div class="input-group-addon no-print" style="padding: 2px 8px; border-right: 0;">
                      <i class="fa fa-users" id="addIcon" style="font-size: 1.2em;"></i>
                    </div>
                    <select id="user_id" class="form-control" name="user_id" >
                      <option value=""><?php echo trans('text_select'); ?></option>
                      <?php foreach (get_users() as $the_user) : ?>
                        <option value="<?php echo $the_user['id'];?>">
                        <?php echo $the_user['username'];?>
                      </option>
                    <?php endforeach;?>
                    </select>
                    <div class="input-group-addon no-print" style="padding: 2px 8px; border-left: 0;">
                      <i class="fa fa-search" id="addIcon" style="font-size: 1.2em;"></i>
                    </div>
                  </div>
                </div>
                <div class="btn-group">
                  <a type="button" class="btn btn-warning" href="report_collection.php?id=<?php echo $this_user['id'];?>">
                    <span class="fa fa-fw fa-list"></span> <?php echo trans('button_collection_report'); ?></a>
                </div>
                <!-- <div class="btn-group">
                  <a type="button" class="btn btn-info" href="user_log.php?id=<?php echo $this_user['id'];?>">
                    <span class="fa fa-fw fa-list"></span> <?php echo trans('button_log'); ?></a>
                </div> -->
                <div class="btn-group">
                  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    <span class="fa fa-fw fa-filter"></span> 
                      <?php if(isset($request->get['type'])) : ?>
                        <?php echo trans('text_'.$request->get['type']); ?>
                    <?php else : ?>
                      <?php echo trans('button_filter'); ?>
                    <?php endif; ?>
                      &nbsp;<span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li>
                        <a href="user_profile.php?id=<?php echo $this_user['id']; ?>">
                          <?php echo trans('button_today_invoice'); ?>
                        </a>
                      </li>
                      <li>
                        <a href="user_profile.php?id=<?php echo $this_user['id']; ?>&type=all_invoice">
                          <?php echo trans('button_all_invoice'); ?>
                        </a>
                      </li>
                      <li>
                        <a href="user_profile.php?id=<?php echo $this_user['id']; ?>&type=due">
                          <?php echo trans('button_due_invoice'); ?>
                        </a>
                      </li>
                      <li>
                        <a href="user_profile.php?id=<?php echo $this_user['id']; ?>&type=all_due">
                          <?php echo trans('button_all_due_invoice'); ?>
                        </a>
                      </li>
                      <li>
                        <a href="user_profile.php?id=<?php echo $this_user['id']; ?>&type=paid">
                          <?php echo trans('button_paid_invoice'); ?>
                        </a>
                      </li>
                      <li>
                        <a href="user_profile.php?id=<?php echo $this_user['id']; ?>&type=inactive">
                          <?php echo trans('button_inactive_invoice'); ?>
                        </a>
                      </li>
                   </ul>
                </div>
              </div>
            </li>
          </ul>
          <div class="tab-content">

            <!-- Sell List Start -->
            <div class="tab-pane active" id="sell_report" ng-controller="ReportUserSellController">

              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title">
                    <?php echo trans('text_invoice_list'); ?>
                  </h3>
                </div>
                <div class='box-body'>     
                  <?php
                    $hide_colums = "";
                    if (user_group_id() != 1) {
                      if (! has_permission('access', 'read_sell_invoice')) {
                        $hide_colums .= "8,";
                      }
                      if (! has_permission('access', 'sell_payment')) {
                        $hide_colums .= "9,";
                      }
                    }
                  ?> 
                  <div class="table-responsive"> 
                    <table id="invoice-invoice-list" class="table table-bordered table-striped table-hovered" data-id="<?php echo $this_user['id']; ?>" data-hide-colums="<?php echo $hide_colums; ?>">
                      <thead>
                        <tr class="bg-gray">
                          <th class="w-10">
                            <?php echo trans('label_date'); ?>
                          </th>
                          <th class="w-15">
                            <?php echo trans('label_invoice_id'); ?>
                          </th>
                          <th class="w-15">
                            <?php echo trans('label_note'); ?>
                          </th>
                          <th class="w-30">
                            <?php echo trans('label_items'); ?>
                          </th>
                          <th class="w-5">
                            <?php echo trans('label_total_item'); ?>
                          </th>
                          <th class="w-8">
                            <?php echo trans('label_payable'); ?>
                          </th>
                          <th class="w-8">
                            <?php echo trans('label_paid_amount'); ?>
                          </th>
                          <th class="w-8">
                            <?php echo trans('label_due'); ?>
                          </th>
                          <th class="w-5">
                            <?php echo trans('label_view'); ?>
                          </th>
                          <th class="w-5">
                            <?php echo trans('label_pay'); ?>
                          </th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr class="bg-gray">
                          <th class="w-10">
                            <?php echo trans('label_date'); ?>
                          </th>
                          <th class="w-15">
                            <?php echo trans('label_invoice_id'); ?>
                          </th>
                          <th class="w-15">
                            <?php echo trans('label_note'); ?>
                          </th>
                          <th class="w-30">
                            <?php echo trans('label_items'); ?>
                          </th>
                          <th class="w-5">
                            <?php echo trans('label_total_item'); ?>
                          </th>
                          <th class="w-8">
                            <?php echo trans('label_payable'); ?>
                          </th>
                          <th class="w-8">
                            <?php echo trans('label_paid_amount'); ?>
                          </th>
                          <th class="w-8">
                            <?php echo trans('label_due'); ?>
                          </th>
                          <th class="w-5">
                            <?php echo trans('label_view'); ?>
                          </th>
                          <th class="w-5">
                            <?php echo trans('label_pay'); ?>
                          </th>
                        </tr>
                      </tfoot>
                    </table>    
                  </div>
                </div> 
              </div>

            </div>
            <!-- Sell List End -->

            <!-- Sell List Start -->
            <div class="tab-pane" id="login_log" ng-controller="UserLoginLogController">

              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title">
                    <?php echo trans('text_login_log'); ?>
                  </h3>
                </div>
                <div class='box-body'>     
                  <?php
                    $hide_colums = "";
                  ?> 
                  <div class="table-responsive"> 
                    <table id="loginlog-loginlog-list" class="table table-bordered table-striped table-hovered" data-hide-colums="<?php echo $hide_colums; ?>">
                      <thead>
                        <tr class="bg-gray">
                          <th class="w-10">
                            <?php echo trans('label_serial_no'); ?>
                          </th>
                          <th class="w-30">
                            <?php echo trans('label_username'); ?>
                          </th>
                          <th class="w-30">
                            <?php echo trans('label_ip_address'); ?>
                          </th>
                          <th class="w-30">
                            <?php echo trans('label_time'); ?>
                          </th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr class="bg-gray">
                          <th class="w-10">
                            <?php echo trans('label_serial_no'); ?>
                          </th>
                          <th class="w-30">
                            <?php echo trans('label_username'); ?>
                          </th>
                          <th class="w-30">
                            <?php echo trans('label_ip_address'); ?>
                          </th>
                          <th class="w-30">
                            <?php echo trans('label_time'); ?>
                          </th>
                        </tr>
                      </tfoot>
                    </table>    
                  </div>
                </div> 
              </div>

            </div>
            <!-- Sell List End -->

          </div>
        </div>

      </div>
    </div>
  </section>
  <!-- Content End-->
</div>
<!-- Content Wrapper End -->

<script type="text/javascript">
  var userName = '<?php echo $this_user['username']; ?>';
</script>

<?php include ("footer.php"); ?>