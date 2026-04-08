<?php 
ob_start();
session_start();
include ("../_init.php");

// FILEMANAGER MODAL WINDOW FOR AJAX CALLING
if(isset($request->get['ajax'])) 
{
  if (!is_loggedin()) {
    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => trans('error_login')));
    exit();
  }
  
  if (DEMO) {
    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => trans('text_disable_in_demo')));
    exit();
  }

  // check, if user has reading permission or not
  // if user have not reading permission return error
  if (user_group_id() != 1 && !has_permission('access', 'read_filemanager')) {
    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => trans('error_read_permission')));
    exit();
  }

	include('../_inc/template/partials/filemanager_ajax.php');
	exit();
}

if (DEMO) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}  

// Redirect, If User has not Read Permission
if (user_group_id() != 1 && !has_permission('access', 'read_filemanager')) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

// Set Document Title
$document->setTitle(trans('title_filemanager'));

// ADD BODY CLASS
$document->setBodyClass('sidebar-collapse');

// Include Header and Footer
include ("header.php");
include ("left_sidebar.php");
?>

<!-- Content Wrapper Start -->
<div class="content-wrapper">

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
    
  	<div class="filemanger-width">
  		<?php
        include('../_inc/template/partials/filemanager.php');
      ?>
  	</div>
  </section>
  <!-- Content End -->
</div>
<!-- Content Wrapper End -->
    
<?php include ("footer.php"); ?>