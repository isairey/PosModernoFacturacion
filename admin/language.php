<?php 
ob_start();
session_start();
include ("../_init.php");

// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}

// Redirect, If User has not Read Permission
if (user_group_id() != 1 && !has_permission('access', 'language_translation')) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

if (!isset($request->get['lang'])) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

$lang_code = $request->get['lang'];
$lang = get_the_lang($lang_code);
if (!$lang) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

// Set Document Title
$document->setTitle(trans('title_language_translation'));

// Add Script
$document->addScript('../assets/itsolution24/angular/modals/LanguageCreateModal.js');
$document->addScript('../assets/itsolution24/angular/modals/LanguageEditModal.js');
$document->addScript('../assets/itsolution24/angular/controllers/LanguageController.js');

// Include Header and Footer
include("header.php"); 
include ("left_sidebar.php");
?>

<!-- Content Wrapper Start -->
<div class="content-wrapper" ng-controller="LanguageController">

  <!-- Content Header Start -->
  <section class="content-header">
    <h1>
      <?php echo trans('text_language_translation_title'); ?>
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
        <?php echo trans('text_language_translation_title'); ?>
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

    <article class="app-layout">
      <div class="app-container">
        <div class="app-row">
          <header class="app-header" style="background: #000;color: #fff;">
            <h2 class="app-title"><i class="fa fa-fw fa-flag"></i>&nbsp;<?php echo $lang['name'];?> <?php echo trans('text_translations');?>
            <?php if ((user_group_id() == 1 || has_permission('access', 'create_language') )&& !DEMO): ?>
              <div class="btn-group">
                <a ng-click="LanguageCreateModal();" onClick="return false;" class="btn btn-primary" href="#">
                    <span class="fa fa-fw fa-plus"></span>&nbsp;<?php echo trans('button_add_new_language');?>
                </a>
              </div>
            <?php endif;?>
            </h2>
            <div class="box-tools pull-right">
              <?php if ($lang['id'] != 1):?>
                <?php if ((user_group_id() == 1 || has_permission('access', 'update_language')) && !DEMO): ?>
                  <div class="btn-group">
                    <a ng-click="LanguageEditModal('<?php echo $lang['id'];?>', '<?php echo $lang['name'];?>');" onClick="return false;" class="btn btn-success" href="#">
                        <span class="fa fa-fw fa-pencil"></span>&nbsp;<?php echo trans('button_edit');?>
                    </a>
                  </div>
                <?php endif;?>
              <?php endif;?>
              <?php if ($lang['id'] != 1):?>
                <?php if ((user_group_id() == 1 || has_permission('access', 'delete_language')) && !DEMO): ?>
                  <div class="btn-group">
                    <a ng-click="deleteLanguage('<?php echo $lang['id'];?>');" onClick="return false;" class="btn btn-danger" href="#">
                        <span class="fa fa-fw fa-trash"></span>&nbsp;<?php echo trans('button_delete');?>
                    </a>
                  </div>
                <?php endif;?>
              <?php endif;?>
              <div class="btn-group">
                <a class="btn btn-info" href="language.php?key_type=default&lang=<?php echo isset($request->get['lang']) && $request->get['lang'] ? $request->get['lang'] : 'en';?>">
                    <span class="fa fa-fw fa-cog"></span>&nbsp;<?php echo trans('button_default');?>
                </a>
              </div>              
              <div class="btn-group">
                <a class="btn btn-warning" href="language.php?action_type=dublicate_entry&lang=<?php echo isset($request->get['lang']) && $request->get['lang'] ? $request->get['lang'] : 'en';?>">
                    <span class="fa fa-fw fa-copy"></span>&nbsp;<?php echo trans('button_dublicate_entry');?>
                </a>
              </div>
              <div class="btn-group">
                <a class="btn btn-warning" href="language.php?action_type=empty_value&lang=<?php echo isset($request->get['lang']) && $request->get['lang'] ? $request->get['lang'] : 'en';?>">
                    <span class="fa fa-fw fa-circle-o"></span>&nbsp;<?php echo trans('button_empty_value');?>
                </a>
              </div>
            </div>
          </header>
          <aside class="app-col app-sidebar">
            <?php 
            foreach (get_langs() as $the_lang): ?>
              <a class="sidebar-item <?php echo $request->get['lang'] == $the_lang['code'] ? 'active' : null;?>" href="language.php?lang=<?php echo $the_lang['code'];?>">
                <i class="icon fa fa-fw fa-angle-right"></i>
                <span class="text"><?php echo $the_lang['name'];?></span>
              </a>
            <?php endforeach ?>
          </aside>
          <main class="app-col app-content">
            
            <div class="table-responsive">  
              <?php
                $hide_colums = "";
                if (user_group_id() != 1) {
                  if (! has_permission('access', 'language_translation')) {
                    $hide_colums .= "2,";
                  }
                  if (! has_permission('access', 'delete_language_key')) {
                    $hide_colums .= "3,";
                  }
                }
              ?> 
              <table id="language-language-list" class="table table-bordered table-striped table-hover" data-hide-colums="<?php echo $hide_colums; ?>" style="border:2px solid #ddd;">
                <thead>
                  <tr class="bg-gray">
                    <th class="w-25">
                      <?php echo trans('label_key'); ?>
                    </th>
                    <th class="w-55 text-center">
                      <?php echo trans('label_value'); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_translate'); ?>
                    </th>
                    <th class="w-5">
                      <?php echo trans('label_delete', 'default'); ?>
                    </th>
                  </tr>
                </thead>
                <tfoot>
                  <tr class="bg-gray">
                    <th class="w-25">
                      <?php echo trans('label_key'); ?>
                    </th>
                    <th class="w-55 text-center">
                      <?php echo trans('label_value'); ?>
                    </th>
                    <th class="w-15">
                      <?php echo trans('label_translate'); ?>
                    </th>
                    <th class="w-5">
                      <?php echo trans('label_delete', 'default'); ?>
                    </th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </main> 
        </div>
        <div class="clearfix"></div>
      </div>
    </article>
  </section>
  <!-- Content End -->

</div>
<!-- Content Wrapper End -->

<?php include ("footer.php"); ?>