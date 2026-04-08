<?php
ob_start();
session_start();
include ("../_init.php");

// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}

// Redirect, If User has not Read Permission
if (user_group_id() != 1 && !has_permission('access', 'read_collection_report')) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

// Set Document Title
$document->setTitle(trans('title_collection_report'));
$document->setBodyClass('sidebar-collapse');

// Add Script
$document->addScript('../assets/itsolution24/angular/controllers/ReportCollectionController.js');
$document->addScript('../assets/itsolution24/angular/modals/UserInvoiceDetailsModal.js');
$document->addScript('../assets/itsolution24/angular/modals/DueCollectionDetailsModal.js');

// ADD BODY CLASS
$document->setBodyClass('sidebar-collapse');

// Include Header and Footer
include("header.php"); 
include ("left_sidebar.php");
?>

<!-- Content Wrapper Start -->
<div class="content-wrapper">

  <!--  Content Header Start -->
  <section class="content-header">
    <?php include ("../_inc/template/partials/apply_filter.php"); ?>
    <h1>
      <?php echo trans('text_collection_report_title'); ?>
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
        <?php echo trans('text_collection_report_title'); ?>
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
      <?php if (user_group_id() == 1 || has_permission('access', 'read_collection_report')) : ?>
          <!-- Collection Report Start -->
          <div id="collection-report" class="col-md-12">
            <div class="box box-info">
              <div class="box-header with-border" style="padding: 12px 10px;">
                <h3 class="box-title">
                  <?php echo trans('text_collection_report'); ?>
                </h3>
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
                </div>
              </div>
              <div class="dashboard-widget box-body">
                <?php include('../_inc/template/partials/report_collection.php'); ?>
              </div>
            </div>
          </div>
          <!--Collection Report End -->
          <?php endif; ?>

      </div>
  </section>
  <!-- Content End -->

</div>
<!-- Content Wrapper End -->

<?php include ("footer.php"); ?>