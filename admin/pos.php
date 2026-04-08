<?php 
ob_start();
session_start();
include ("../_init.php");

// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}

// REDIRECT, IF USER HAVE'T READ PERMISSION
if (user_group_id() != 1 && !has_permission('access', 'create_sell_invoice')) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
} 

$panel_position = $user->getPreference('pos_side_panel') ? $user->getPreference('pos_side_panel') : 'right';
// ADD BODY CLASS
$document->setBodyClass($panel_position.'-panel');

$body_class = $document->getBodyClass();

// FETCH PRINTER
$printer_id = store('receipt_printer');
$statement = $db->prepare("SELECT * FROM `printers` LEFT JOIN `printer_to_store` p2s ON (`printers`.`printer_id`=`p2s`.`pprinter_id`) WHERE `printer_id` = ?");
$statement->execute(array($printer_id));
$printer = $statement->fetch(PDO::FETCH_ASSOC);

// FETCH ORDER PRINTERS
$order_printers = array();
$order_printer_ids = json_decode(store('order_printers'));
if ($order_printer_ids) {
	foreach ($order_printer_ids as $id) {
		$statement = $db->prepare("SELECT * FROM `printers` WHERE `printer_id` = ?");
		$statement->execute(array($id));
		$order_printers[] = $statement->fetch(PDO::FETCH_ASSOC);
	}
}
?>
<!DOCTYPE html>
<html lang="<?php echo $document->langTag($active_lang);?>" ng-app="angularApp">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=9">
	<title>
		<?php echo trans('title_pos'); ?> &raquo; <?php echo store('name'); ?>	
	</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="google" content="notranslate">
    
	<!-- Favicon -->
    <?php if (store('favicon')): ?>
		<link rel="shortcut icon" href="../assets/itsolution24/img/logo-favicons/<?php echo store('favicon'); ?>">
	<?php else: ?>
		<link rel="shortcut icon" href="../assets/itsolution24/img/logo-favicons/nofavicon.png">
	<?php endif; ?>

	<!-- ALL CSS -->

	<?php if (DEMO || USECOMPILEDASSET) : ?>

		<link href="../assets/itsolution24/cssmin/pos.css" type="text/css" rel="stylesheet">

	<?php else : ?>
		
	    <!-- Bootstrap CSS -->
	    <link href="../assets/bootstrap/css/bootstrap.css" type="text/css" rel="stylesheet">

	    <!-- jquery UI CSS -->
        <link type="text/css" href="../assets/jquery-ui/jquery-ui.min.css" type="text/css" rel="stylesheet">

	    <!-- Font Awesome CSS -->
	    <link href="../assets/font-awesome/css/font-awesome.css" type="text/css" rel="stylesheet">

	    <!-- Datepicker3 CSS -->
		<link href="../assets/datepicker/datepicker3.css" type="text/css" rel="stylesheet">

		<!-- Bootstrap Timepicker CSS -->
		<link href="../assets/timepicker/bootstrap-timepicker.min.css" type="text/css" rel="stylesheet">

	    <!-- Perfect Scrollbar CSS -->
	    <link href="../assets/perfectScroll/css/perfect-scrollbar.css" type="text/css" rel="stylesheet">

	    <!-- Select2 CSS -->
	    <link href="../assets/select2/select2.min.css" type="text/css" rel="stylesheet">

	    <!-- Toastr CSS -->
	    <link href="../assets/toastr/toastr.min.css" type="text/css" rel="stylesheet">

	    <!-- jQuery ContextMenu CSS -->
	    <link  href="../assets/contextMenu/dist/jquery.contextMenu.min.css" type="text/css" rel="stylesheet">

		<!-- Filemanager CSS -->
	    <link href="../assets/itsolution24/css/filemanager/dialogs.css" type="text/css" rel="stylesheet">
	    <link href="../assets/itsolution24/css/filemanager/main.css" type="text/css" rel="stylesheet">

	    <!-- Theme CSS -->
	    <link href="../assets/itsolution24/css/theme.css" type="text/css" rel="stylesheet">

	    <!-- Skin Black CSS -->
	    <link href="../assets/itsolution24/css/skins/skin-black.css" type="text/css" rel="stylesheet">

	    <!-- Skin Blue CSS -->
	    <link href="../assets/itsolution24/css/skins/skin-blue.css" type="text/css" rel="stylesheet">

	    <!-- Skin Green CSS -->
	    <link href="../assets/itsolution24/css/skins/skin-green.css" type="text/css" rel="stylesheet">

	    <!-- Skin Red CSS -->
	    <link href="../assets/itsolution24/css/skins/skin-red.css" type="text/css" rel="stylesheet">

	    <!-- Skin Yellow CSS -->
	    <link href="../assets/itsolution24/css/skins/skin-yellow.css" type="text/css" rel="stylesheet">

	    <!-- Main CSS -->
	    <link href="../assets/itsolution24/css/main.css" type="text/css" rel="stylesheet">

		<!-- Skeleton CSS -->
		<link href="../assets/itsolution24/css/pos/skeleton.css" rel="stylesheet" type="text/css">

		<!-- Main CSS -->
		<link href="../assets/itsolution24/css/pos/pos.css" rel="stylesheet" type="text/css">

		<!-- Responsive CSS -->
		<link href="../assets/itsolution24/css/pos/responsive.css" rel="stylesheet" type="text/css">

	<?php endif ?>

	<!-- This is Mandatory -->
	<style type="text/css">
		body::after { 
			content: ""; background: url(../assets/itsolution24/img/pos/patterns/<?php echo $user->getPreference('pos_pattern') ? $user->getPreference('pos_pattern') : 'armysuit.jpg'; ?>) repeat repeat;opacity: 0.4;filter: alpha(opacity=40);top: 0;left: 0;bottom: 0;right: 0;position: absolute;z-index: -1;
		}
		.modal-lg .modal-content {
			border-color: #ffffff;
		}
	</style>

	<!-- JS -->
	<script type="text/javascript"> 
		var baseUrl = "<?php echo trim(root_url(),'/'); ?>";
		var lang = "<?php echo $active_lang;?>";
		var adminDir = "<?php echo ADMINDIRNAME; ?>";
		var user = <?php echo json_encode(array_diff_key(get_the_user(user_id()), array_flip(array('password','raw_password','pass_reset_code','reset_code_time')))); ?>;
	    var settings = <?php echo json_encode(array_diff_key(get_all_preference(), array_flip(array('smtp_password','ftp_password')))); ?>;
        var store = <?php echo json_encode(array_diff_key(store(), array_flip(array('smtp_password','ftp_password','preference')))); ?>;
	    var isInstallment = <?php echo INSTALLMENT && (user_group_id() == 1 || has_permission('access', 'create_installment'))? 1 : 0;?>;
	    var deviceType = '<?php echo device_type(); ?>';
	    var filemanager = '<?php echo get_preference('ftp_hostname') && get_preference('ftp_username') ? 'ftp' : 'local'; ?>';
	    var orderPrinters = <?php echo json_encode($order_printers); ?>;
	    var printer = <?php echo json_encode($printer); ?>;
	    var slideDirection = '<?php echo $user->getPreference('pos_side_panel') == 'left' ? 'right' : 'left'; ?>';
	    var sendReportEmail = '<?php echo user_group_id() == 1 || has_permission('access', 'send_report_via_email');?>';
	</script>

</head>
<body  id="pos-page" class="pos sidebar-mini <?php echo $body_class; ?>" ng-controller="PosController">
<div class="hidden"><?php include('../assets/itsolution24/img/iconmin/icon.svg');?></div>
<?php include('../_inc/template/pos_skeleton.php'); ?>
	<!-- POS Content-Wrapper Start -->
	<div class="pos-content-wrapper">
		
		<div id="vertial-toolbar">
			<?php if (user_group_id() == 1 || has_permission('access', 'add_giftcard')) : ?>
			<span ng-click="GiftcardCreateModal();" class="toolbar-icon bg-orange mt-5" title="<?php echo trans('text_gift_card');?>">
				<span class="expand bg-orange"><?php echo trans('button_sell_gift_card'); ?></span>
				<svg class="svg-icon"><use href="#icon-card"></svg>
			</span>
			<?php endif; ?>
		</div>

		<?php include('../_inc/template/partials/top.php'); ?>

		<!-- Content Wrapper Start -->
		<div class="content-area">
			<div class="row-group">
				<div class="content-row">

					<!-- All Product List Section Start-->
					<div id="left-panel" class="pos-content" style="<?php echo $user->getPreference('pos_side_panel') == 'left' ? 'float:right' : null; ?>">
						<div class="contents">
							<div id="searchbox">
								<input ng-change="showProductList()" onClick="this.select();" type="text" id="product-name" name="product-name" ng-model="productName" placeholder="<?php echo trans('text_search_product'); ?>"  autofocus>
								<svg class="svg-icon search-btn"><use href="#icon-pos-search"></svg>
								<div class="category-search">
									<select class="form-control select2" name="category-search-select" id="category-search-select">
							          	<option value=""><?php echo sprintf(trans('text_view_all'), 'Products'); ?></option>
							          	<?php foreach (get_category_tree(array('filter_fetch_all' => true)) as $category_id => $category_name) : 
							          		if (get_total_valid_category_item($category_id) <= 0) { continue; } ?>
							          		<option value="<?php echo $category_id; ?>"><?php echo $category_name; ?> (<?php echo get_total_valid_category_item($category_id); ?>)</option>
							          	<?php endforeach; ?>
							        </select>
								</div>
							</div>
							<div id="item-list">
								<!-- <div class="pos-product-pagination pagination-top"></div> -->
								<div ng-show="showLoader" class="ajax-loader">
									<img src="../assets/itsolution24/img/loading2.gif">
								</div>
								<div class="add-new-product-wrapper" data-ng-class="{'show': showAddProductBtn}">
									<div class="add-new-product">
										<div class="add-new-product-btn">
											<button ng-click="createNewProduct()" class="btn btn-lg btn-danger" style="width:auto;">
												<span class="fa fa-fw fa-plus"></span>
												<span><?php echo trans('button_add_product'); ?></span>
											</button>
											<a ng-click="OpenPurchaseProductModal();" class="btn btn-lg btn-danger" style="width:auto;">
												<span class="fa fa-fw fa-money"></span>
												<span><?php echo trans('button_add_purchase'); ?></span>
											</a>
										</div>
									</div>
								</div>
								<div ng-repeat="products in productArray" id="{{ $index }}" class="btn btn-flat item">
									<div ng-click="addItemToInvoice(products.p_id,products)" class="item-inner">
										<div class="item-img">
											<img ng-src="{{ products.p_image }}" alt="{{ products.p_name }}">
										</div>
										<span class="item-info" data-id="{{ products.p_id }}" data-name="{{ products.p_name }}">
											<span>
												{{ products.p_name | cut:true:20:' ...' }}
											</span>
										</span>
										<span class="item-mask nowrap" title="{{ products.p_name }}">
											<svg class="svg-icon"><use href="#icon-add"></svg>
											<span><?php echo trans('label_add_to_cart'); ?></span>
										</span>
										<span ng-show="products.p_type=='service'"class="ibadge">Service</span>
									</div>
								</div>
								<div class="pos-product-pagination pagination-bottom"></div>
							</div>
							<div id="total-amount">
								<div class="total-amount-inner">
									<span class="currency-symbol">
										<?php echo get_currency_symbol(); ?>
									</span> 
									<span class="main-amount">
										{{ totalPayable | formatDecimal:2 }}
									</span>
								</div>
								<div id="salesman">
									<input type="hidden" name="salesman_id" value="<?php echo user_id();?>">
									<!--
									<select id="salesman_id" name="salesman_id"> 
										<option value=""><?php //echo trans('text_select_salesman');?></option>
										<?php //foreach (get_salesmans() as $salesman) : ?>
											<option value="<?php //echo $salesman['id']; ?>" <?php //echo store('salesman_id') == $salesman['id'] ? 'selected' : null; ?>>
												<?php //echo $salesman['username']; ?>
											</option>
										<?php //endforeach; ?>
									</select>
									-->
								</div>
								<a id="invoice-note" ng-click="addInvoiceNote()" data-note="" title="<?php echo trans('text_add_note'); ?>">

									<span class="fa fa-fw fa-comments-o"></span>
								</a>
							</div>
						</div>
					</div>
					<!-- All Product Section End -->

					<!--Invoive Section Start-->
					<div id="right-panel" class="pos-content" style="<?php echo $user->getPreference('pos_side_panel') == 'left' ? 'float:left' : null; ?>">
						<div class="invoice-area">
							<div class="well well-sm">
								
								<!-- Customer Area Start-->
								<div id="people-area">
									<input ng-change="showCustomerList()" onClick="this.select();" type="text" id="customer-name" name="customer-name" ng-model="customerName" ng-disabled="isEditMode" autocomplete="off">
									<input type="hidden" name="customer-id" value="{{ customerId }}">
									<div class="customer-icon">
										<a ng-click="showCustomerList(true)" onClick="return false;" href="#">
											<svg class="svg-icon"><use href="#icon-pos-customer"></svg>
										</a>
									</div>
									<div class="edit-icon pointer">
										<span ng-click="CustomerEditModal();" class="fa fa-edit"></span>
										<span id="add-customer-mobile-number-handler" class="fa fa-mobile" style="font-size:18px;margin-left:5px;"></span>
										<input id="customer-mobile-number" type="hidden" name="customer-mobile-number">
									</div>
									<div ng-click="createNewCustomer();" class="add-icon">
										<svg class="svg-icon"><use href="#icon-pos-plus"></svg>
									</div>
									<div class="previous-due">
										<div class="previous-due-inner">
											<h4>
												<?php echo trans('label_due'); ?>
												<a ng-show="dueAmount" href="customer_profile.php?customer_id={{ customerId }}&type=all_due" target="_blink">
													<span id="dueAmount">
														{{ dueAmount| formatDecimal:2 }}
													</span>
												</a>
												<div ng-show="!dueAmount">
													<span id="dueAmount">
														{{ dueAmount| formatDecimal:2 }}
													</span>
												</div>
											</h4>
										</div>
									</div>
									<div ng-hide="hideCustomerDropdown" id="customer-dropdown" class="slidedown-menu">
										<div class="slidedown-header">
										</div>
										<div class="slidedown-body">
											<ul class="customer-list list-unstyled">
												<li ng-repeat="customers in customerArray">
													<a href="#" ng-click="addCustomer(customers);" onclick="return false;"><span class="fa fa-fw fa-user"></span>{{ customers.customer_name }} ({{ customers.customer_mobile || customers.customer_email }})
													</a>
												</li>
											</ul>
										</div>
									</div>
								</div>
								<!-- Customer Area Start-->

								<!-- Invoice Item Start-->
								<div id="invoice-item">
									<!-- Selected Product List Title Start -->
									<table id="invoice-item-head" class="table table-striped">
										<thead>
											<tr class="bg-gray">
												<th>
													<?php echo trans('label_quantity'); ?>	
												</th> 
												<th>
													<?php echo trans('label_product'); ?>
												</th>
												<th>
													<?php echo trans('label_price'); ?>
												</th>
												<th>
													<?php echo trans('label_subtotal'); ?>
												</th>
												<th>&nbsp; </th>
											</tr>
										</thead>
									</table>
									<!-- Selected Product List Title Start -->

									<!-- Selected Product List Section Start-->
									<div id="invoice-item-list">
										<table class="table table-hovered">
											<tbody>
												<tr ng-repeat="items in itemArray" class="invoice-item">
													<td class="product-quantity" id="invoice-item-{{ items.id }}">
														<input type="hidden" name="p_type" value="{{ items.pType }}">
														<button class="btn btn-xs btn-up" ng-click="addItemToInvoice(items.id)" title="Increase">
															<span class="fa fa-angle-up"></span>
														</button>
														<input type="text" name="item_price_{{ items.id }}" class="item_quantity text-center" id="item_quantity_{{ items.id }}" value="{{ items.quantity }}" data-itemid="{{ items.id }}" onClick="this.select();" ondrop="return false;" onpaste="return false;" style="width:40px;max-width:40px;border-radius: 50px;border: 1px solid #ddd;padding-top:0;padding-bottom:0;">
														<span style="font-size:12px;"><i>{{ items.unitName }}</i></span>
														<button class="btn btn-xs btn-down increasebtn{{ items.id }}" ng-click="DecreaseItemFromInvoice(items.id)" title="Decrease">
															<span class="fa fa-angle-down"></span>
														</button>
													</td>
													<td class="product-name">
														<span>{{ items.name }}</span>
													</td>
													<td class="product-price">
														<?php if (get_preference('change_item_price_while_billing') == 1) : ?>
															<input type="text" class="text-center item_price" id="item_price_{{ items.id }}" name="item_price_{{ items.id }}" value="{{ items.price | formatDecimal:2 }}" data-itemid="{{ items.id }}" onClick="this.select();" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" style="max-width:80px;padding:5px;border-radius: 20px;border:2px solid #ddd;">
														<?php else : ?>
															{{ items.price | formatDecimal:2 }}
														<?php endif; ?>
													</td>
													<td class="product-subtotal">
														{{ items.subTotal | formatDecimal:2 }}
													</td>
													<td class="product-delete text-red pointer" ng-click="removeItemFromInvoice($index, items.id)">
														<span class="fa fa-close"></span>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<!-- Selected Product List Section End-->

									<!-- Selected Product Calculation Section Start-->
									<div id="invoice-calculation" class="clearfix">
										<table class="table">
											<tbody>
												<tr class="bg-gray">
													<td width="30%">
														<?php echo trans('label_total_items'); ?>
													</td>
													<td class="text-right" width="20%">
														{{ totalItem }} ({{ totalQuantity }})
													</td>
													<td width="30%">
														<?php echo trans('label_total'); ?>
													</td>
													<td class="text-right" width="20%">
														{{ totalAmount  | formatDecimal:2 }}
													</td>
												</tr>
												<tr class="pay-top">
													<td>
														<?php echo trans('label_discount'); ?>
													</td>
													<td class="text-right">
														<input id="discount-input" ng-change="addDiscount()" onClick="this.select();" type="text" name="discount-amount" ng-model="discountInput" ondrop="return false;" onpaste="return false;" autocomplete="off">
													</td>
													<td>
														<?php echo trans('label_tax_amount'); ?> (%)
													</td>
													<td class="text-right">
														<input ng-init="taxInput=<?php echo get_preference('tax'); ?>" ng-change="addTax()" onClick="this.select();" type="text" name="tax-amount" ng-model="taxInput" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" autocomplete="off">
													</td>
												</tr>
												<tr>
													<td>
														<?php echo trans('label_shipping_charge'); ?>
													</td>
													<td class="text-right">
														<input class="text-center shipping" ng-change="addShipping()" onClick="this.select();" type="text" name="shipping-amount" ng-model="shippingInput" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" autocomplete="off">
													</td>
													<td>
														<?php echo trans('label_others_charge'); ?>
													</td>
													<td class="text-right">
														<input class="text-center others-charge" ng-change="addOthersCharge()" onClick="this.select();" type="text" name="others-charge" ng-model="othersChargeInput" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" autocomplete="off">
													</td>
												</tr>
												<tr class="bg-gray">
													<td colspan="3">
														<?php echo trans('label_total_payable'); ?>
													</td>
													<td class="text-right">
														{{ totalPayable  | formatDecimal:2 }}
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<!-- Selected Product Calculation Section End-->
								</div>
								<!-- Invoice Item End-->

								<!-- Action Button Section Start-->
								<div id="pay-button" class="text-center">
									<div class="btn-group btn-group-justified">
										<div class="btn-group">
											<button ng-click="payNow()" onClick="return false;" class="btn btn-success" data-loading-text="Processing..." title="Payment">
												<span class="fa fa-fw fa-money"></span> 
												<?php echo trans('button_pay'); ?>
											</button>
										</div>
										<div class="btn-group">
											<button ng-click="HoldingOrderModal()" on-click="return false;" class="btn btn-danger" data-loading-text="Processing..." title="Order Holdinbg">
												<span class="fa fa-fw fa-crosshairs"></span> 
												<?php echo trans('button_hold'); ?>
											</button>
										</div>
									</div>
								</div>
								<!-- Action Button Section End-->
								
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
					<!-- Invoice Section End -->

				</div>
			</div>
		</div>
		<!-- Content Wrapper End -->

	</div>   
	<!-- POS Content Wrapper End -->

	<!-- Rightbar Toggle Handler -->
	<div id="minicart">
		<div class="minicart-content">
			<div class="heading">
				<div class="title"></div>
			</div>
			<div class="body">
				<div class="items">{{ totalItem }} ({{ totalQuantity }})</div>
			</div>
			<div class="footer"></div>
		</div>
	</div>

	<!-- Scrolling Sidebar Start -->
    <aside class="scrolling-sidebar scrolling-sidebar-dark">
        <h2 class="scrolling-sidebar-title r-0"><?php echo trans('text_reports');?></h2>
        <?php 
        $statement = $db->prepare("SELECT * FROM `shortcut_links` WHERE `type` = ? AND `status` = ? ORDER BY `sort_order` ASC");
        $statement->execute(array('report', 1));
        $shortcut_links = $statement->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="searchbox text-center" style="margin: 3px 5px 2px 5px;">
          <input ng-model="searchList" class="form-control r-50" type="search" name="search" placeholder="<?php echo trans('placeholder_search_here');?>" style="border:2px solid #999;">
        </div>
        <ul filter-list="searchList" class="list-group" style="padding: 0 10px 10px; 10px">
        <?php $inc=0;foreach ($shortcut_links as $link) : $btnColor=$inc % 2 == 0 ? 'success' : 'success'?>
          <?php if (user_group_id() == 1 || has_permission('access', $link['permission_slug'])) :?>
            <li class="list-group-item" style="padding:2px;">
                <a class="btn btn-<?php echo $btnColor;?> btn-block" style="font-size:16px;text-align:left;border-radius:0;pading:3px;" href="<?php echo root_url().$link['href'];?>"><span class="fa fa-fw <?php echo $link['icon'];?>"></span> <?php echo $link['title'];?></a>
            </li>
          <?php endif;?>
        <?php $inc++;endforeach;?>
        </ul>                    
    </aside>
    <div class="scrolling-sidebar-bg"></div>
    <div class="scrolling-sidebar-mask"></div>
    <!-- Scrolling Sidebar End -->

	<?php if (DEMO || USECOMPILEDASSET) : ?>

		<script src="../assets/itsolution24/jsmin/pos.js" type="text/javascript"></script>

	<?php else : ?>

		<!-- jQuery JS  -->
	    <script src="../assets/jquery/jquery.min.js" type="text/javascript"></script> 

	    <!-- jQuery Ui JS -->
        <script src="../assets/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>

	    <!-- Bootstrap JS -->
	    <script src="../assets/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

	    <!-- Edit Area -->
        <script src="../assets/edit-area/edit_area_full.js" type="text/javascript"></script>

		<!-- Angular JS -->
	    <script src="../assets/itsolution24/angularmin/angular.js" type="text/javascript"></script> 

	    <!-- AngularApp JS -->
	    <script src="../assets/itsolution24/angular/angularApp.js" type="text/javascript"></script>

	    <!-- Filemanager JS -->
	    <script src="../assets/itsolution24/angularmin/filemanager.js" type="text/javascript"></script>

	    <!-- Angular JS Modal -->
		<script src="../assets/itsolution24/angularmin/modal.js" type="text/javascript"></script>

		<!-- Bootstrap Datepicker JS -->
		<script src="../assets/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>

		<!-- Bootstrap Timepicker JS -->
		<script src="../assets/timepicker/bootstrap-timepicker.min.js" type="text/javascript" ></script>

		<!-- Select2 JS -->
		<script src="../assets/select2/select2.min.js" type="text/javascript"></script>

		<!-- Perfect Scroolbar JS -->
		<script src="../assets/perfectScroll/js/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>

		<!-- Sweet ALert JS -->
		<script src="../assets/sweetalert/sweetalert.min.js" type="text/javascript"></script>

		<!-- Toastr JS -->
		<script src="../assets/toastr/toastr.min.js" type="text/javascript"></script>

		<!-- Accounting JS -->
		<script src="../assets/accounting/accounting.min.js" type="text/javascript"></script>

		<!-- Underscore JS -->
		<script src="../assets/underscore/underscore.min.js" type="text/javascript"></script>	

		<!-- Context Menue JS -->
		<script src="../assets/contextMenu/dist/jquery.contextMenu.min.js"></script>

		<!-- IE JS -->
		<script src="../assets/itsolution24/js/ie.js" type="text/javascript"></script>

		<!-- Common JS -->
		<script src="../assets/itsolution24/js/common.js" type="text/javascript"></script>

		<!-- Main JS -->
		<script src="../assets/itsolution24/js/main.js" type="text/javascript"></script>

		<!-- POS Main JS -->
		<script src="../assets/itsolution24/js/pos/pos.js" type="text/javascript"></script>

<?php endif; ?>

<script src="../assets/itsolution24/angular/modals/AddInvoiceNoteModal.js" type="text/javascript"></script>
<script src="../assets/itsolution24/angular/modals/AddCustomerMobileNumberModal.js" type="text/javascript"></script>
<script src="../assets/itsolution24/angular/modals/HoldingOrderModal.js" type="text/javascript"></script>
<script src="../assets/itsolution24/angular/modals/HoldingOrderDetailsModal.js" type="text/javascript"></script>
<script src="../assets/itsolution24/angular/controllers/PosController.js" type="text/javascript"></script>
<noscript>
    <div class="global-site-notice noscript">
        <div class="notice-inner">
            <p><strong>JavaScript seems to be disabled in your browser.</strong><br>You must have JavaScript enabled in
                your browser to utilize the functionality of #MODERN POS.</p>
        </div>
    </div>
</noscript>
</body>
</html>