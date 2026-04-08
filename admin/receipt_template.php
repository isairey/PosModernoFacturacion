<?php 
ob_start();
session_start();
include '../_init.php';

// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}

// Redirect, If User has not Read Permission
if (user_group_id() != 1 && !has_permission('access', 'receipt_template')) {
	redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

$template_id = isset($request->get['template_id']) ? $request->get['template_id'] : 1;

// Set Document Title
$document->setTitle(trans('title_receipt_template'));

// Add Script
$document->addScript('../assets/edit-area/edit_area_full.js');
$document->addScript('../assets/itsolution24/angular/controllers/StoreActionController.js');
$document->addScript('../assets/itsolution24/js/upload.js');

// Include Header and Footer
include ("header.php");
include ("left_sidebar.php");
?>

<!-- Content Wrapper Start -->
<div class="content-wrapper" ng-controller="StoreActionController">

	<!-- Content Header Start-->
	<section class="content-header">
		<h1>
			<?php echo trans('text_receipt_tempalte_title'); ?>
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
				<a href="store_single.php?tab=pos-setting">
					<?php echo trans('title_pos_setting'); ?>
				</a>
			</li>
			<li class="active">
				<?php echo trans('text_receipt_template'); ?>
			</li>
		</ol>
	</section>
	<!-- Content Header End-->

	<!-- Content Start-->
	<section class="content">

		<?php if(DEMO) : ?>
	    <div class="box">
	      <div class="box-body">
	        <div class="alert alert-info mb-0">
	          <p><span class="fa fa-fw fa-info-circle"></span> <?php echo $demo_text; ?></p>
	        </div>
	        <div class="alert alert-warning mb-0">
	          <p><span class="fa fa-fw fa-info-circle"></span> Email & FTP settings are disabled in demo version</p>
	        </div>
	      </div>
	    </div>
	    <?php endif; ?>
	    
		<article class="app-layout">
			<div class="app-container">
				<div class="app-row">
					<header class="app-header bg-gray">
						<h2 class="app-title"><i class="fa fa-fw fa-adjust"></i><?php echo trans('text_receipt_tempalte_sub_title');?></h2>
						<div class="box-tools pull-right">
							<div class="btn-group">
				                <a type="button" class="btn btn-sm btn-info" href="preview_receipt.php?template_id=<?php echo $template_id;?>">
				                  	<span class="fa fa-fw fa-eye"></span>&nbsp;<?php echo trans('button_preview');?> &rarr;
				                </a>
			                </div>
						</div>
					</header>
					<aside class="app-col app-sidebar">
						<?php 
						foreach (get_postemplates() as $template): ?>
							<a class="sidebar-item <?php echo $template_id == $template['template_id'] ? 'active' : null;?>" href="receipt_template.php?template_id=<?php echo $template['template_id'];?>">
								<i class="icon fa fa-fw fa-angle-right"></i>
								<span class="text"><?php echo $template['template_name'];?></span>
							</a>
						<?php endforeach ?>
					</aside>
					<main class="app-col app-content">
						
						<h2 class="title"><b><?php echo trans('text_tempalte_content_title');?></b></h2>
						<textarea class="template-content-editor" id="template-content-editor" name="postemplatecontent" data-id="<?php echo $template_id;?>" style="width:100%;"><?php echo get_the_postemplate($template_id,'template_content');?></textarea>

						<h2 class="title"><b><?php echo trans('text_tempalte_css_title');?></b></h2>
						<textarea class="template-css-editor" id="template-css-editor" name="postemplatecss" data-id="<?php echo $template_id;?>" style="width:100%;"><?php echo get_the_postemplate($template_id,'template_css');?></textarea>
			

						<div class="tags">
							<h4><b><?php echo trans('text_template_tags');?></b>. Usage:<code>{{ logo }}</code></h4>
							<?php 
							$template_tags = get_postemplate_empty_data();
							foreach ($template_tags as $key => $val):?> 
								<?php if (is_array($val)):?>
									<h4><b class="text-red"><?php echo ucfirst(str_replace('_', ' ', $key));?></b> Loop Tags. Usage:<code>{{ <?php echo $key;?> }} {{ sl }} {{ /<?php echo $key;?> }}</code></h4>
									<?php foreach ($val as $k => $v): ?>
										<kbd>{{ <?php echo implode(', ', array_keys($v));?> }}</kbd>
									<?php endforeach;?>
								<?php else:?>
									<kbd>{{ <?php echo $key;?> }}</kbd>
								<?php endif; ?>	
							<?php endforeach;?>
						</div>

						
					</main>	
				</div>
				<div class="clearfix"></div>
			</div>
		</article>

	</section>
	<!-- Content End-->

</div>
<!-- Content Wrapper End -->

<?php include ("footer.php"); ?>