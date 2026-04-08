<?php
ob_start();
session_start();
include ("../_init.php");

// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}

// Redirect, If User has not Read Permission
if (user_group_id() != 1 && !has_permission('access', 'installment_payment')) {
  redirect(root_url() . '/admin/dashboard.php');
}

// Set Document Title
$document->setTitle(trans('title_installment_payment'));

// Add Script
$document->addScript('../assets/itsolution24/angular/modals/InstallmentViewModal.js');
$document->addScript('../assets/itsolution24/angular/modals/InstallmentPaymentModal.js');
$document->addScript('../assets/itsolution24/angular/controllers/InstallmentPaymentController.js');

// Include Header and Footer
include("header.php"); 
include ("left_sidebar.php");
?>

<!-- Content Wrapper Start -->
<div class="content-wrapper" ng-controller="InstallmentPaymentController">

	<!-- Content Header Start -->
	<section class="content-header">
		<?php include ("../_inc/template/partials/apply_filter.php"); ?>
		<h1>
		    <?php echo trans('text_installment_payment_title'); ?>
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
		        <a href="installment.php">
		          <?php echo trans('text_installment'); ?>
		        </a>
		    </li>
		    <li>
				<?php if (isset($request->get['type']) && $request->get['type']=='due'): ?>
					<a href="installment_payment.php"><?php echo trans('text_installment_payment_title'); ?></a>	
				<?php else: ?>
					<?php echo trans('text_installment_payment_title'); ?>	
				<?php endif; ?>
			</li>
			<?php if (isset($request->get['type']) && $request->get['type']=='due'): ?>
				<li class="active">
					<?php echo trans('text_due_incoice'); ?>	
				</li>
			<?php endif; ?>

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
		    <div class="col-xs-12">
		      	<div class="box box-info">
		      		<div class="box-header">
				        <h3 class="box-title">
				        	<?php echo trans('text_installment_payment_list_title'); ?>
				        </h3>
				        <div class="box-tools pull-right">
			                <div class="btn-group">
				                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
				                	<span class="fa fa-fw fa-filter"></span> 
				                  	<?php if(isset($request->get['type'])) : ?>
				                  		<?php echo trans('text_'.$request->get['type']); ?>
					                <?php else : ?>
					                	<?php echo trans('button_filter'); ?>
					                <?php endif; ?>
				                    <span class="caret"></span>
				                </button>
				                <ul class="dropdown-menu" role="menu">
				                    <li>
				                    	<a href="installment_payment.php?type=all_payment">
				                    		<?php echo trans('button_all_payment'); ?>
				                    	</a>
				                    </li>
				                    <li>
				                    	<a href="installment_payment.php?type=todays_due_payment">
				                    		<?php echo trans('button_todays_due_payment'); ?>
				                    	</a>
				                    </li>
				                    <li>
				                    	<a href="installment_payment.php?type=all_due_payment">
				                    		<?php echo trans('button_all_due_payment'); ?>
				                    	</a>
				                    </li>
				                    <li>
				                    	<a href="installment_payment.php?type=expired_due_payment">
				                    		<?php echo trans('button_expired_due_payment'); ?>
				                    	</a>
				                    </li>
				                 </ul>
			                </div>
			            </div>
				     </div>
			      	<div class='box-body'>  
						<div class="table-responsive"> 
						<?php
				            $hide_colums = "";
				            if (user_group_id() != 1) {
								if (! has_permission('access', 'installment_payment')) {
									$hide_colums .= "6,";
								}
							}
				          ?>  

				          <!-- Installment Payment List Start -->
						  <table id="payment-payment-list"  class="table table-bordered table-striped table-hover" data-hide-colums="<?php echo $hide_colums; ?>">
						    <thead>
						      	<tr class="bg-gray">
						      		<th class="w-20">
							        	<?php echo trans('label_payment_date'); ?>
							        </th>
							        <th class="w-20">
							        	<?php echo trans('label_invoice_id'); ?>
							        </th>
							        <th class="w-25">
							        	<?php echo trans('label_note'); ?>
							        </th>
							        <th class="w-10">
							        	<?php echo trans('label_payable'); ?>
							        </th>
							        <th class="w-10">
							        	<?php echo trans('label_paid'); ?>
							        </th>
							        <th class="w-10">
							        	<?php echo trans('label_status'); ?>
							        </th>
							        <th class="w-5">
							        	<?php echo trans('button_payment'); ?> 
							        </td>
						      	</tr>
						    </thead>
						     <tfoot>
			               		<tr class="bg-gray">
							        <th class="w-20">
							        	<?php echo trans('label_payment_date'); ?>
							        </th>
							        <th class="w-20">
							        	<?php echo trans('label_invoice_id'); ?>
							        </th>
							        <th class="w-25">
							        	<?php echo trans('label_note'); ?>
							        </th>
							        <th class="w-16">
							        	<?php echo trans('label_payable'); ?>
							        </th>
							        <th class="w-10">
							        	<?php echo trans('label_paid'); ?>
							        </th>
							        <th class="w-10">
							        	<?php echo trans('label_status'); ?>
							        </th>
							        <th class="w-5">
							        	<?php echo trans('button_payment'); ?> 
							        </td>
			               		</tr>
		            		</tfoot>
						  </table>
						  <!-- Installment Payment List End -->
						</div>  
			  		</div>
		      	</div>
		    </div>
	    </div>
	</section>
	<!-- Content End -->
</div>
<!-- Content Wrapper End -->

<?php include ("footer.php"); ?>