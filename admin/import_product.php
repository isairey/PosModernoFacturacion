<?php 
ob_start();
session_start();
include ("../_init.php");

// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}

// Redirect, If User has not Read Permission
if (user_group_id() != 1 && !has_permission('access', 'import_product')) {
	redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

$message = '';
$document->setTitle(trans('title_import_product'));

require('../_inc/vendor/spreadsheet-reader/php-excel-reader/excel_reader2.php');
require('../_inc/vendor/spreadsheet-reader/SpreadsheetReader.php');

include("header.php");
include ("left_sidebar.php");

function syncImage($product_id, $img_array)
{
	$statement = db()->prepare("DELETE FROM `product_images` WHERE `product_id` = ?");
	$statement->execute(array($product_id));
	foreach ($img_array as $img) {
		if ($img) {
			$statement = db()->prepare("INSERT INTO `product_images` SET `product_id` = ?, `url` = ?");
			$statement->execute(array($product_id, $img));
		}
	}
}

if (isset($request->post['submit'])) 
{
	try {

		if (user_group_id() != 1 && !has_permission('access', 'import_product') || DEMO) {
	      throw new Exception(trans('error_permission'));
	    }
		if (!$_FILES['filename']['name']) 
		{
			throw new Exception(trans('error_invalid_file'));
		}
		if ($_FILES["filename"]["type"] != "application/vnd.ms-excel") 
		{
			throw new Exception(trans('error_invalid_file'));
		} 
		if(isset($_FILES["filename"]["type"]))
		{
			$validextensions = array("xls");
			$temporary = explode(".", $_FILES["filename"]["name"]);
			$file_extension = end($temporary);
			
			if (in_array($file_extension, $validextensions)) {
				if ($_FILES["filename"]["error"] > 0) {
					throw new Exception("Return Code: " . $_FILES['filename']['error']);
				} else {
					$temp = explode(".", $_FILES["filename"]["name"]);
					$newfilename = 'products.' . end($temp);
					$sourcePath = $_FILES["filename"]["tmp_name"];
					$targetPath = "../storage/".$newfilename;
					if(!move_uploaded_file($sourcePath, $targetPath)) {
						throw new Exception(trans('error_upload'));
					}
				}
			} else {
				throw new Exception(trans('error_invalid_file'));
			}
		}

		$file_path = realpath(__DIR__.'/../').DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'products.xls';
		if(!file_exists($file_path)) 
		{
			throw new Exception(trans('error_invalid_file'));
		}

		$p_date = date('Y-m-d');
		$expired_date = date('Y-m-d H:i:s', strtotime("+1 year", time()));
		$insert_status = array();
		$update_status = array();
		$total_item_no = 0;

		$Hooks->do_action('Before_Import_Product', $request);

		$Reader = new SpreadsheetReader($file_path);
		$Sheets = $Reader->Sheets();
		foreach ($Sheets as $Index => $Name)
		{
			$Reader->ChangeSheet($Index);			
			switch ($Name) {
				case 'Product':
					foreach ($Reader as $Row)
					{
						if ($Row[1] == 'ProductName' || !$Row[1]) continue;

						$pro_data['product_name'] = $Row[1];
						$pro_data['product_type'] = $Row[2];
						$pro_data['code'] = isset($Row[3]) ? $Row[3] : '';
						$pro_data['hsn_code'] = isset($Row[4]) ? $Row[4] : '';
						$pro_data['barcode_symbology'] = $Row[5];
						$store_code_names = explode(',', $Row[6]);
						if (count($store_code_names) < 1) {
							$store_code_names = array_unique(array($store_code_names));
						}
						$pro_data['category_id'] = get_category_id_by_slug($Row[7]);
						$pro_data['unit_id'] = get_unit_id_by_code($Row[8]);
						$pro_data['taxrate_id'] = get_taxrate_id_by_code($Row[9]);
						$pro_data['tax_method'] = isset($Row[10]) ? $Row[10] : 'inclusive';
						$pro_data['sup_id'] = get_supplier_id_by_code($Row[11]);
						$pro_data['brand_id'] = get_brand_id_by_code($Row[12]);
						$pro_data['box_id'] = get_box_id_by_code($Row[13]);
						$pro_data['alert_quantity'] = isset($Row[14]) ? (float)$Row[14] : 10;
						$pro_data['cost_price'] = isset($Row[15]) ? (float)$Row[15] : 0;
						$pro_data['sell_price'] = isset($Row[16]) ? (float)$Row[16] : 0;
						$pro_data['description'] = isset($Row[17]) ? $Row[17] : '';
						$pro_data['status'] = isset($Row[18]) ? (int)$Row[18] : 1;
						$pro_data['thumbnail'] = isset($Row[19]) ? $Row[19] : '';
						$img_array = isset($Row[20]) ? $Row[20] : array();
						// dd($pro_data);
						if (!$pro_data['product_name']) {
							throw new Exception(trans('error_product_name'));
						}
						if (!$pro_data['code']) {
							throw new Exception(trans('error_product_code'));
						}
						foreach ($store_code_names as $store_code) {
							if (!get_store_id_by_code($store_code)) {
								throw new Exception(trans('store_code '.$store_code.' is not valid!').' ('.$pro_data['product_name'].'-'.$pro_data['code'].')');
							}
						}

						if (!in_array($pro_data['product_type'], array('standard', 'service'))) {
							throw new Exception(trans('error_invalid_product_type').' ('.$pro_data['product_name'].'-'.$pro_data['code'].')');
						}
						if (!in_array($pro_data['barcode_symbology'], array('code25','code39','code128','ean5','ean13','upca','upce'))) {
							throw new Exception(trans('error_invalid_barcode_symbology').' ('.$pro_data['product_name'].'-'.$pro_data['code'].')');
						}
						if (!$pro_data['category_id']) {
							throw new Exception(trans('error_invalid_category_slug').' ('.$pro_data['product_name'].'-'.$pro_data['code'].')');
						}
						if (!$pro_data['unit_id']) {
							throw new Exception(trans('error_invalid_unit_code').' ('.$pro_data['product_name'].'-'.$pro_data['code'].')');
						}
						if (!$pro_data['taxrate_id']) {
							throw new Exception(trans('error_invalid_taxrate_code').' ('.$pro_data['product_name'].'-'.$pro_data['code'].')');
						}
						if (!in_array($pro_data['tax_method'], array('inclusive','exclusive'))) {
							throw new Exception(trans('error_invalid_tax_method').' ('.$pro_data['product_name'].'-'.$pro_data['code'].')');
						}
						if (!$pro_data['sup_id']) {
							throw new Exception(trans('error_invalid_supplier_code').' ('.$pro_data['product_name'].'-'.$pro_data['code'].')');
						}
						if (!$pro_data['brand_id']) {
							throw new Exception(trans('error_invalid_brand_code').' ('.$pro_data['product_name'].'-'.$pro_data['code'].')');
						}
						if (!$pro_data['box_id']) {
							throw new Exception(trans('error_invalid_box_code').' ('.$pro_data['product_name'].'-'.$pro_data['code'].')');
						}
						if (!$pro_data['alert_quantity'] < 0) {
							throw new Exception(trans('error_invalid_alert_quantity').' ('.$pro_data['product_name'].'-'.$pro_data['code'].')');
						}
						if (!$pro_data['sell_price']) {
							throw new Exception(trans('error_invalid_sell_price').' ('.$pro_data['product_name'].'-'.$pro_data['code'].')');
						}
						if ($pro_data['product_type'] == 'service' && $pro_data['cost_price'] <= 0) {
							throw new Exception(trans('error_invalid_cost_price').' ('.$pro_data['product_name'].'-'.$pro_data['code'].')');
						}
						if (!empty($img_array)) {
							$img_array = explode('|', $img_array);
						}
						$statement = db()->prepare("SELECT * FROM `products` WHERE `p_name` = ?");
      					$statement->execute(array($pro_data['product_name']));
      					$product = $statement->fetch(PDO::FETCH_ASSOC);
      					if (!$product) {
      						$p_code = $pro_data['code'];
      						if (!$p_code) {
      							$p_code = randomNumber(8);
	      						$p = 1;
		      					while ($p) {
		      						$p_code = randomNumber(8);
		      						$statement = db()->prepare("SELECT * FROM `products` WHERE `p_code` = ?");
			      					$statement->execute(array($p_code));
			      					$p = $statement->fetch(PDO::FETCH_ASSOC);
		      					}
      						}

							$statement = db()->prepare("INSERT INTO `products` (`p_type`, `p_code`, `hsn_code`, `barcode_symbology`, `p_name`, `category_id`, `unit_id`, `p_image`, `description`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
			        		$statement = $statement->execute(array($pro_data['product_type'], $p_code, $pro_data['hsn_code'], $pro_data['barcode_symbology'], $pro_data['product_name'], $pro_data['category_id'], $pro_data['unit_id'], $pro_data['thumbnail'], $pro_data['description']));
			        		$product_id = db()->lastInsertId();
							if ($product_id) {
								foreach ($store_code_names as $store_code) {
									$store_id = get_store_id_by_code($store_code);
									$statement = db()->prepare("INSERT INTO `product_to_store` (product_id, store_id, purchase_price, sell_price, alert_quantity, sup_id, brand_id, box_id, taxrate_id, tax_method, e_date, p_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			        				$statement = $statement->execute(array($product_id, $store_id, $pro_data['cost_price'], $pro_data['sell_price'], $pro_data['alert_quantity'], $pro_data['sup_id'], $pro_data['brand_id'], $pro_data['box_id'], $pro_data['taxrate_id'], $pro_data['tax_method'], $expired_date, $p_date, $pro_data['status']));
								}
								$insert_status[] = 'ok';
							} else {
								$insert_status[] = 'error';
							}
							$total_item_no++;
						} elseif ($product) {
							$product_id = $product['p_id'];
							$p_code = $pro_data['code'];
      						if (!$p_code) {
      							$p_code = randomNumber(8);
	      						$p = 1;
		      					while ($p) {
		      						$p_code = randomNumber(8);
		      						$statement = db()->prepare("SELECT * FROM `products` WHERE `p_code` = ? AND `p_id` != ?");
			      					$statement->execute(array($p_code, $product_id));
			      					$p = $statement->fetch(PDO::FETCH_ASSOC);
		      					}
      						}
							
							$statement = db()->prepare("UPDATE `products` SET  `p_type` = ?, `p_code` = ?, `hsn_code` = ?, `barcode_symbology` = ?, `p_name` = ?, `category_id` = ?, `unit_id` = ?, `p_image` = ?, `description` = ? WHERE `p_id` = ?");
			      			$statement->execute(array($pro_data['product_type'], $p_code, $pro_data['hsn_code'], $pro_data['barcode_symbology'], $pro_data['product_name'], $pro_data['category_id'], $pro_data['unit_id'], $pro_data['thumbnail'], $pro_data['description'], $product_id));

							if ($statement) {
								$statement = db()->prepare("DELETE FROM `product_to_store` WHERE `product_id` = ?");
								$statement->execute(array($product_id));
								foreach ($store_code_names as $store_code) {
									$store_id = get_store_id_by_code($store_code);
									$statement = db()->prepare("INSERT INTO `product_to_store` (product_id, store_id, purchase_price, sell_price, alert_quantity, sup_id, brand_id, box_id, taxrate_id, tax_method, e_date, p_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			        				$statement = $statement->execute(array($product_id, $store_id, $pro_data['cost_price'], $pro_data['sell_price'], $pro_data['alert_quantity'], $pro_data['sup_id'], $pro_data['brand_id'], $pro_data['box_id'], $pro_data['taxrate_id'], $pro_data['tax_method'], $expired_date, $p_date, $pro_data['status']));
								}
								$update_status[] = 'ok';
							} else {
								$update_status[] = 'error';
							}
							$total_item_no++;
						}
						if ($product_id) {
							if ($img_array) {
								syncImage($product_id, $img_array);
							}
						}
					}
					break;
				default:
					throw new Exception('xls Sheet (Product) is not valid!');
					break;
			}
		}

		$success = 0;
		$error = 0;
		$message = '';
		$message .= '<div><span class="fa fa-fw fa-info-circle"></span> Total Item: ' . $total_item_no . '</div>';
		if ( count($insert_status) > 0 ) {
			for ($i=0; $i < count($insert_status); $i++) { 
				if ( $insert_status[$i] == 'ok' ) {
					$success++;
				}
				if ( $insert_status[$i] == 'error' ) {
					$error++;
				}
			} 
			$message .= '<p><strong>Insert Status</strong></p>';
			$message .= '<ul>';
			$message .= '<li>Total Inserted: ' . $success . '</li>';
			$message .= '<li>Error in: ' . $error . '</li>';
			$message .= '</ul>';
		}

		if (count($update_status) > 0) {
			for ($i=0; $i < count($update_status); $i++) 
			{ 
				if ($update_status[$i]=='ok') {
					$success++;
				}
				if ($update_status[$i]=='error') {
					$error++;
				}
			}
			$message .= '<p><strong>Update Status</strong></p>';
			$message .= '<ul>';
			$message .= '<li>Total Updated: ' . $success . '</li>';
			$message .= '<li>Unchanged in: ' . $error . '</li>';
			$message .= '</ul>';
		}

		$Hooks->do_action('After_Import_Product', $request);
	}
	catch(Exception $e) { 
	    $error_message = $e->getMessage();
	}
} ?>

<!-- Content Wrapper Start -->
<div class="content-wrapper">

	<!-- Content Header Start -->
	<section class="content-header">
		<h1>
		  <?php echo sprintf(trans('text_import_title'), trans('text_product')); ?>
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
		        <a href="product.php"><?php echo trans('text_products'); ?></a>  
		    </li>
			<li class="active">
			  	<?php echo sprintf(trans('text_import_title'), trans('text_product')); ?>
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
	        <div class="alert alert-danger mb-0">
	          <p><span class="fa fa-fw fa-info-circle"></span> Product import feature is disabled in demo version</p>
	        </div>
	      </div>
	    </div>
	    <?php endif; ?>
    
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-no-border">

					<?php if ($message):?>
					<div class="alert alert-info mb-0 r-0">
						<?php echo $message ; ?>
					</div>
					<?php endif;?>

					<?php if (isset($error_message)): ?>
					<div class="alert alert-danger mb-0 r-0">
					    <p>
					    	<span class="fa fa-warning"></span> 
					    	<?php echo $error_message ; ?>
					    </p>
					</div>
					<?php elseif (isset($success_message)): ?>
					<div class="alert alert-success mb-0 r-0">
					    <p>
					    	<span class="fa fa-check"></span> 
					    	<?php echo $success_message ; ?>
					    </p>
					</div>
					<?php endif; ?>

					<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
						<div class="box-body">

							<div class="well well-small">
								<div class="text-warning">
									<div>The first line in downloaded .xls file should remain as it is. Please do not change the order of columns. Please make sure the (*.xls) file is UTF-8 encoded. The images should be uploaded in storage/products/ (or where you pointed) folder. The System will check that if a row exists then update, if not exist then insert.
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="col-sm-3">&nbsp;</div>
								<div class="col-sm-9">
							    	<?php echo trans('text_download_sample_format_file'); ?>
								    <a href="../storage/pos-products.xls" id="download_demo">
								    	<span class="fa fa-fw fa-download"></span> 
								    	<?php echo trans('button_download'); ?>
								    </a>
							 	</div>
							</div>

						  	<div class="form-group">
						    	<label for="filename" class="col-sm-3 control-label">
						    		<?php echo trans('text_select_xls_file'); ?>
						    	</label>
						        <div class="col-sm-5">	            
									<input type="file" class="form-control" name="filename" id="filename" accept=".xls" required>
						        </div>
						 	</div>
						 	<br>
						    <div class="form-group">
						        <div class="col-sm-5 col-sm-offset-3">
							        <button type="submit" class="btn btn-block btn-success" name="submit">
							        	<span class="fa fa-fw fa-upload"></span> 
							          	<?php echo trans('button_import'); ?>
							        </button>
						        </div>
						    </div>
						</div>
				  	</form>
				</div>
			</div>
		</div>
	</section>
	<!-- Content End -->

</div>
<!-- Content Wrapper End -->

<?php include ("footer.php"); ?>