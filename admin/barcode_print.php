<?php 
ob_start();
session_start();
include ("../_init.php");

// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}

// Redirect, If User has not Read Permission
if (user_group_id() != 1 && !has_permission('access', 'barcode_print')) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

// Set Document Title
$document->setTitle(trans('title_barcode'));

// Add Style
$document->addStyle('../assets/itsolution24/css/barcode.css', 'stylesheet', 'all');

// Add Script
$document->addScript('../assets/itsolution24/angular/controllers/BarcodePrintController.js');

// ADD BODY CLASS
$document->setBodyClass('sidebar-collapse');

// Include Header and Footer
include("header.php"); 
include ("left_sidebar.php") ;
?>

<!-- Content Wrapper Start -->
<div class="content-wrapper" ng-controller="BarcodePrintController">

  <!-- Content Header Start -->
  <section class="content-header">
    <h1>
      <?php echo trans('text_barcode_title'); ?>
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
        <?php if (isset($request->get['box_state']) && $request->get['box_state']=='open'): ?>
          <a href="barcode_print.php"><?php echo trans('text_barcode_title'); ?></a>  
        <?php else: ?>
          <?php echo trans('text_barcode_title'); ?>  
        <?php endif; ?>
      </li>
      <?php if (isset($request->get['box_state']) && $request->get['box_state']=='open'): ?>
        <li class="active">
          <?php echo trans('text_add'); ?> 
        </li>
      <?php endif; ?>
    </ol>
  </section>
  <!-- Content Header end -->

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
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title">
              <?php echo trans('text_barcode_generate_title'); ?>
            </h3>
          </div>
          <div class='box-body'> 
            <form id="form-barcode-generate" class="form-horizontal" action="barcode_print.php#barcode-con" method="post">
              <div class="well well-sm">
                <div class="well well-sm bg-gray r-50">
                  <div class="form-group mb-0">
                    <label for="add_item" class="col-sm-3 control-label">
                      <?php echo trans('label_add_product'); ?>
                    </label>
                    <div class="col-sm-6">
                      <div class="input-group wide-tip">
                        <div class="input-group-addon paddinglr-10">
                          <i class="fa fa-barcode addIcon fa-2x"></i>
                        </div>
                        <input type="text" name="add_item" value="" class="form-control input-lg autocomplete-product" id="add_item" data-type="p_name" onkeypress="return event.keyCode != 13;" onclick="this.select();" placeholder="<?php echo trans('placeholder_search_product'); ?>" autocomplete="off" tabindex="1">
                      </div>
                    </div>  
                  </div> 
                </div> 

                <div class="row">
                  <div class="col-md-12">
                    <div class="table-responsive">
                      <table id="product-table" class="table table-striped table-bordered">
                        <thead>
                          <tr class="bg-info">
                            <th class="w-50 text-center">
                              <?php echo trans('label_product_name_with_code'); ?>
                            </th>
                            <th class="w-20 text-center">
                              <?php echo trans('label_available'); ?>
                            </th>
                            <th class="w-20 text-center">
                              <?php echo trans('label_quantity'); ?>
                            </th>
                            <th class="w-10 text-center">
                              <?php echo trans('label_delete'); ?>
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (isset($request->post['products']) && !empty($request->post['products'])) : 
                          foreach ($request->post['products'] as $item): $product = get_the_product($item['item_id']); ?>
                            <tr id="<?php echo $product['p_id'];?>" class="<?php echo $product['p_id'];?> success" data-item-id="<?php echo $product['p_id'];?>">
                              <td class="text-center" style="min-width:100px;" data-title="Product Name">
                                <input name="products[<?php echo $product['p_id'];?>][item_id]" type="hidden" class="item-id" value="<?php echo $product['p_id'];?>">
                                <input name="products[<?php echo $product['p_id'];?>][item_name]" type="hidden" class="item-name" value="<?php echo $product['p_name'];?>">
                                <span class="name" id="name-<?php echo $product['p_id'];?>"><?php echo $product['p_name'];?>-<?php echo $product['p_code'];?></span>
                              </td>
                              <td class="text-center" style="padding:2px;" data-title="Available"><?php echo format_input_number($product['quantity_in_stock']);?></td>
                              <td style="padding:2px;" data-title="Quantity">
                                <input class="form-control input-sm text-center quantity" name="products[<?php echo $product['p_id'];?>][quantity]" type="number" value="<?php echo $item['quantity'];?>" data-id="<?php echo $product['p_id'];?>" id="quantity-<?php echo $product['p_id'];?>" onclick="this.select();" onkeyup="if(this.value<=0){this.value=1;}">
                              </td>
                              <td class="text-center">
                                <i class="fa fa-close text-red pointer remove" data-id="<?php echo $product['p_id'];?>" title="Remove"></i>
                              </td>
                            </tr>
                          <?php endforeach;?>
                          <?php endif;?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <div class="well well-sm r-50" style="background:#ddd;">
                  <div class="form-group">
                    <label for="per_page" class="col-sm-3 control-label">
                      <?php echo trans('label_page_layout'); ?>
                    </label>
                    <div class="col-sm-6">
                      <select name="per_page" class="form-control" id="per_page">
                        <option value=""><?php echo trans('text_select');?></option>
                        <option value="40" <?php echo isset($request->post['per_page']) && $request->post['per_page'] == 40 ? 'selected' : 'selected';?>>40 per sheet (a4) (1.799" x 1.003")</option>
                        <option value="30" <?php echo isset($request->post['per_page']) && $request->post['per_page'] == 30 ? 'selected' : null;?>>30 per sheet (2.625" x 1")</option>
                        <option value="24" <?php echo isset($request->post['per_page']) && $request->post['per_page'] == 24 ? 'selected' : null;?>>24 per sheet (a4) (2.48" x 1.334")</option>
                        <option value="20" <?php echo isset($request->post['per_page']) && $request->post['per_page'] == 20 ? 'selected' : null;?>>20 per sheet (4" x 1")</option>
                        <option value="18" <?php echo isset($request->post['per_page']) && $request->post['per_page'] == 18 ? 'selected' : null;?>>18 per sheet (a4) (2.5" x 1.835")</option>
                        <option value="14" <?php echo isset($request->post['per_page']) && $request->post['per_page'] == 14 ? 'selected' : null;?>>14 per sheet (4" x 1.33")</option>
                        <option value="12" 
                          <?php if(isset($request->post['per_page']) && $request->post['per_page'] == 12) {
                            echo 'selected';
                          };?>
                        >12 per sheet (a4) (2.5" x 2.834")</option>
                        <option value="10" <?php echo isset($request->post['per_page']) && $request->post['per_page'] == 10 ? 'selected' : null;?>>10 per sheet (4" x 2")</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group mb-0">
                    <label for="expiration_system" class="col-sm-3 control-label"><?php echo trans('label_fields');?></label>
                    <div class="col-sm-6">
                        <div class="checkbox">
                          <label><input type="checkbox" name="fields[site_name]" value="1" 

                            <?php if(isset($request->post['fields']['site_name']) && $request->post['fields']['site_name']) {
                              echo 'checked';
                            } elseif (isset($request->post['fields'])) {
                              echo '';
                            } else {
                              echo 'checked';
                            }?>

                            >Site name</label>&nbsp;&nbsp;&nbsp;
                          <label><input type="checkbox" name="fields[product_name]" value="1" 

                            <?php if(isset($request->post['fields']['product_name']) && $request->post['fields']['product_name']) {
                              echo 'checked';
                            } elseif (isset($request->post['fields'])) {
                              echo '';
                            } else {
                              echo 'checked';
                            }?>

                            >Product name</label>&nbsp;&nbsp;&nbsp;
                          <label><input type="checkbox" name="fields[price]" value="1" 

                            <?php if(isset($request->post['fields']['price']) && $request->post['fields']['price']) {
                              echo 'checked';
                            } elseif (isset($request->post['fields'])) {
                              echo '';
                            } else {
                              echo 'checked';
                            }?>

                            >Price</label>&nbsp;&nbsp;&nbsp;
                          <label><input type="checkbox" name="fields[currency]" value="1" 

                            <?php if(isset($request->post['fields']['currency']) && $request->post['fields']['currency']) {
                              echo 'checked';
                            } elseif (isset($request->post['fields'])) {
                              echo '';
                            } else {
                              echo 'checked';
                            }?>

                            >Currency</label>&nbsp;&nbsp;&nbsp;
                          <label><input type="checkbox" name="fields[unit]" value="1" 

                            <?php if(isset($request->post['fields']['unit']) && $request->post['fields']['unit']) {
                              echo 'checked';
                            } elseif (isset($request->post['fields'])) {
                              echo '';
                            } else {
                              echo '';
                            }?>

                            >Unit</label>&nbsp;&nbsp;&nbsp;
                          <label><input type="checkbox" name="fields[category]" value="1" 

                            <?php if(isset($request->post['fields']['category']) && $request->post['fields']['category']) {
                              echo 'checked';
                            } elseif (isset($request->post['fields'])) {
                              echo '';
                            } else {
                              echo '';
                            }?>

                            >Category</label>&nbsp;&nbsp;&nbsp;
                          <label><input type="checkbox" name="fields[product_image]" value="1" 

                            <?php if(isset($request->post['fields']['product_image']) && $request->post['fields']['product_image']) {
                              echo 'checked';
                            } elseif (isset($request->post['fields'])) {
                              echo '';
                            } else {
                              echo 'checked';
                            }?>

                            >Product Image</label>
                        </div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-3 col-sm-offset-3 text-center">            
                    <button id="barcode-generate" class="btn btn-info btn-block" data-form="#form-barcode-generate" name="submit" data-loading-text="Generating...">
                      <i class="fa fa-fw fa-cog"></i>
                      <?php echo trans('button_generate'); ?>
                    </button>
                  </div>
                  <div class="col-sm-3 text-center">            
                    <a href="barcode_print.php"  class="btn btn-danger btn-block">
                      <span class="fa fa-fw fa-circle-o"></span>
                      <?php echo trans('button_reset'); ?>
                    </a>
                  </div>
                </div>
              </div>
            </form>
            <div id="barcode-con">
              <?php 
              if(isset($request->post['products'])):
                $per_page = $request->post['per_page'];
                if (!$per_page) {
                  redirect(root_url() . '/admin/barcode_print.php');
                }
                $page_layout = '';
                switch ($per_page) {
                  case '10':
                    $page_layout = '';
                    break;
                  case '12':
                    $page_layout = 'a4';
                    break;
                  case '14':
                    $page_layout = '';
                    break;
                  case '18':
                    $page_layout = 'a4';
                    break;
                  case '20':
                    $page_layout = '';
                    break;
                  case '24':
                    $page_layout = 'a4';
                    break;
                  case '30':
                    $page_layout = '';
                    break;
                  case '40':
                    $page_layout = 'a4';
                    break;
                  default:
                    $page_layout = '';
                    break;
                }
                // Barcode
                $generator = barcode_generator();
                $barcode_generator = barcode_generator();
                ?>

                  <div class="text-center">
                    <div class="btn-group">
                      <button class="btn btn-warning" onClick="window.printContent('barcode-con', {title:'<?php echo trans('text_barcode_print');?>',screenSize:'fullScreen', cssLink:'<link type=\'text/css\' href=\'../assets/itsolution24/css/barcode.css\' type=\'text/css\' rel=\'stylesheet\'>'});"><span class="fa fa-print"></span> <?php echo trans('button_print');?></button>
                    </div>
                  </div>

                  <div class="barcode barcode<?php echo $page_layout;?>">
                  <?php $inc=1;foreach ($request->post['products'] as $prod): $product = get_the_product($prod['item_id']);
                  $symbology = $product['barcode_symbology'] ? $product['barcode_symbology'] : 'code39';
                  $symbology = barcode_symbology($generator, $symbology);?>
                    <?php for ($i=0; $i < $prod['quantity']; $i++): ?>
                      <div class="item style<?php echo $per_page;?>">
                        <div class="item-inner">
                          <?php if (isset($request->post['fields']['product_image']) && $request->post['fields']['product_image']):?>
                            <span class="product_image">
                                <?php if (isset($product['p_image']) && ((FILEMANAGERPATH && is_file(FILEMANAGERPATH.$product['p_image']) && file_exists(FILEMANAGERPATH.$product['p_image'])) || (is_file(DIR_STORAGE . 'products' . $product['p_image']) && file_exists(DIR_STORAGE . 'products' . $product['p_image'])))) : ?>
                                <img  src="<?php echo FILEMANAGERURL ? FILEMANAGERURL : root_url().'/storage/products'; ?>/<?php echo $product['p_image']; ?>" style="width:60px;height:auto;">
                              <?php else : ?>
                                <img src="../assets/itsolution24/img/noimage.jpg">
                              <?php endif; ?>
                            </span>
                          <?php endif;?>
                          <?php if (isset($request->post['fields']['site_name']) && $request->post['fields']['site_name']):?>
                            <div style="margin-bottom:3px;">
                              <span class="barcode_site"><?php echo store('name');?></span>
                            </div>
                          <?php endif;?>
                          <?php if (isset($request->post['fields']['product_name']) && $request->post['fields']['product_name']):?>
                            <div style="font-size:14px;line-height:1.333;font-weight:700;">
                              <span class="barcode_name"><?php echo $product['p_name'];?></span>
                            </div>
                          <?php endif;?>
                          <?php if (isset($request->post['fields']['unit']) && $request->post['fields']['unit']):?>
                            <span class="barcode_unit"><?php echo trans('label_unit');?>: <?php echo get_the_unit($product['unit_id'],'unit_name');?></span>, 
                          <?php endif;?>
                          <?php if (isset($request->post['fields']['category']) && $request->post['fields']['category']):?>
                            <span class="barcode_category"><?php echo trans('label_category');?>: <?php echo get_the_category($product['category_id'],'category_name');?></span> 
                          <?php endif;?>
                          <?php if (isset($request->post['fields']['price']) && $request->post['fields']['price']):?>
                            <div style="font-size:20px;line-height:1.333;font-weight:700;">
                              <!-- <span class="barcode_price"><?php echo trans('label_price');?>:  -->
                              <?php if (isset($request->post['fields']['currency']) && $request->post['fields']['currency']):?>
                              <?php echo get_currency_code();?> 
                              <?php endif;?>
                              <?php echo $product['sell_price'];?></span> 
                            </div>
                          <?php endif;?>
                          <span class="barcode_image" style="margin-top:5px;">
                              <img src="data:image/png;base64,<?php echo base64_encode($generator->getBarcode($product['p_code'], $symbology, 1));?>" alt="</php echo $product['p_code'];?>" class="bcimg">
                          </span>
                        </div>
                      </div>
                    <?php 
                    if (($inc % $per_page) == 0):?>
                        </div>
                        <div class="barcode barcode<?php echo $page_layout;?>">
                    <?php endif;
                    $inc++;endfor;?>
                  <?php endforeach;?>
                </div>
                
                <div class="text-center">
                  <div class="btn-group">
                    <button class="btn btn-warning" onClick="window.printContent('barcode-con', {title:'<?php echo trans('text_barcode_print');?>',screenSize:'fullScreen', cssLink:'<link type=\'text/css\' href=\'../assets/itsolution24/css/barcode.css\' type=\'text/css\' rel=\'stylesheet\'>'});"><span class="fa fa-print"></span> <?php echo trans('button_print');?></button>
                  </div>
                </div>

              <?php endif;?>
            </div>
          </div>
          <!-- .box-body -->
        </div>
      </div>
    </div>
  </section>
</div>
<!-- Content Wrapper End -->

<?php include ("footer.php"); ?>