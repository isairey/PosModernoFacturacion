<?php 
ob_start();
session_start();
include '../_init.php';

// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}

// Redirect, If User has not Read Permission
if (user_group_id() != 1 && !has_permission('access', 'read_product')) {
	redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

// LOAD PRODUCT MODEL
$product_model = registry()->get('loader')->model('product');

// FETCH PRODUCT INFO
$p_id = isset($request->get['p_id']) ? $request->get['p_id'] : '';
$product = $product_model->getProduct($p_id);
if (count($product) <= 1) {
	redirect(root_url() . '/'.ADMINDIRNAME.'/product.php');
}

// Set Document Title
$document->setTitle(trans('title_product'));

// Add Script
// if (user_group_id() == 1 || has_permission('access', 'read_sell_report')) {
// 	$document->addScript('../assets/itsolution24/angular/controllers/ReportProductSellController.js');
// }
if (user_group_id() == 1 || has_permission('access', 'read_purchase_report')) {
	$document->addScript('../assets/itsolution24/angular/controllers/ReportProductPurchaseController.js');
}

// Include Header and Footer
include("header.php"); 
include ("left_sidebar.php"); 
?>

<!-- Content Wrapper Start -->
<div class="content-wrapper">
  	<!-- Content Header Start -->
	<section class="content-header">
		<?php include ("../_inc/template/partials/apply_filter.php"); ?>
		<h1>
			<?php echo trans('text_product'); ?> &raquo; <?php echo $product['p_name'];?>
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
				<a href="product.php">
					<?php echo trans('text_products'); ?>
				</a>
			</li>
			<li class="active">
				<?php echo $product['p_name'];?>
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
			<div class="col-xs-12">
			    <div class="nav-tabs-custom">
	                <ul class="nav nav-tabs">
	                    <li class="active">
	                    	<a href="#details" data-toggle="tab" aria-expanded="false">
	                    		<?php echo trans('text_details'); ?>
	                    	</a>
	                    </li>
			            <?php if (user_group_id() == 1 || has_permission('access', 'read_purchase_report')) : ?>
		                <li class="">
	                    	<a href="#stock" data-toggle="tab" aria-expanded="false">
	                    		<?php echo trans('text_stock_register'); ?>
		                    </a>
		                </li>
			            <?php endif; ?>
		                <li class="">
	                    	<a href="#chart" data-toggle="tab" aria-expanded="false">
	                    		<?php echo trans('text_chart'); ?>
		                    </a>
		                </li>
		                <li class="box-tools pull-right">
		                	<div class="btn-group">
				                <a href="report_sell_itemwise.php?p_id=<?php echo $product['p_id'];?>&p_name=<?php echo $product['p_name'];?>" type="button" class="btn btn-success" title="<?php echo trans('button_sell_report');?>">
				                  	<span class="fa fa-fw fa-file"></span> <?php echo trans('button_sell_report');?>
				                </a>
				            </div>
		                	<div class="btn-group">
				                <a href="product.php?p_id=<?php echo $product['p_id'];?>&p_name=<?php echo $product['p_name'];?>" type="button" class="btn btn-primary" title="<?php echo trans('button_edit');?>">
				                  	<span class="fa fa-fw fa-pencil"></span>
				                </a>
				            </div>
		                </li>
	                </ul>
	                <div class="tab-content">
	                    <div class="tab-pane active" id="details">
	                        <?php include '../_inc/template/product_view_form.php'; ?>
	                    </div>
	                    <!-- End Details Tab -->

	                    <?php if (user_group_id() == 1 || has_permission('access', 'read_purchase_report')) : ?>
	                    <div class="tab-pane" id="stock" ng-controller="ReportProductPurchaseController">
                    		<div class="box-header">
					            <h3 class="box-title">
					              <?php echo trans('text_purchase_report_sub_title'); ?>  
					            </h3>
					        </div>
					        <div class="box-body">
					            <div class="table-responsive">  
					            	<?php
					                  $hide_colums = "";
					                  if (user_group_id() != 1) {
					                    if (! has_permission('access', 'view_purchase_price')) {
					                      $hide_colums .= "3,";
					                    }
					                  }
					                ?>
					              <table id="purchasereport-purchasereport-list" class="table table-bordered table-striped table-hover" data-hide-colums="<?php echo $hide_colums; ?>">
					                <thead>
					                  <tr class="bg-gray">
					                    <th class="w-5">
					                      <?php echo trans('label_serial_no'); ?>
					                    </th>
					                    <th class="w-25">
					                      <?php echo trans('label_created_at'); ?>
					                    </th>
					                    <th class="w-40">
					                      <?php echo sprintf(trans('label_invoice_id'), 
					                      trans('label_product')); ?>
					                    </th>
					                    <th class="w-5">
					                      <?php echo trans('label_purchase_price'); ?>
					                    </th>
					                    <th class="w-5">
					                      <?php echo trans('label_selling_price'); ?>
					                    </th>
					                    <th class="w-5">
					                      <?php echo trans('label_quantity'); ?>
					                    </th>
					                    <th class="w-5">
					                      <?php echo trans('label_sold'); ?>
					                    </th>
					                    <th class="w-5">
					                      <?php echo trans('label_available'); ?>
					                    </th>
					                    <th class="w-5">
					                      <?php echo trans('label_status'); ?>
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
					                  </tr>
					                </tfoot>
					              </table>
					            </div>
					        </div>
	                    </div>
		                <?php endif; ?>
	                    <!-- End Stock Tab -->

	                    <div class="tab-pane" id="chart">
	                    	<?php
	                    	if (from()) {
	                    		$label = 'From ' . from() . ' to ' . to();
	                    	} else {
	                    		$label = 'Date:  ' . date('Y-m-d');
	                    	}
	                    	$labels = array($label); 
	                    	$sells_array = array(product_selling_price($p_id, from(), to()));
	                    	$purchases_array = array(product_purchase_price($p_id, from(), to()));
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

<?php include ("footer.php"); ?>