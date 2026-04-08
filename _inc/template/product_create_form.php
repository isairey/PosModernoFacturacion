<?php
$tab_active = isset($request->get['tab']) ? $request->get['tab'] : 'general';
?>
<style type="text/css">
.image-thumbnail {
  position: relative;
  width: 80px;
  height: 75px;
  overflow: hidden;
  display: inline-block;
}
.image-thumbnail img {
    position: relative;
    width: 100%;
    height: auto;
    max-height: 100%;
}
</style>
<form id="create-product-form" class="form-horizontal" action="product.php?box_state=open" method="post">
  <input type="hidden" id="action_type" name="action_type" value="CREATE">
  <div class="box-body">

    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="<?php echo $tab_active == 'general' ? 'active' : null;?>">
            <a href="#general" data-toggle="tab" aria-expanded="false">
            <?php echo trans('text_general'); ?>
          </a>
        </li>
        <li class="<?php echo $tab_active == 'image' ? 'active' : null;?>">
            <a href="#image" data-toggle="tab" aria-expanded="false">
            <?php echo trans('text_images'); ?>
          </a>
        </li>
      </ul>
      <div class="tab-content">

        <!-- Image Start -->
        <div class="tab-pane<?php echo $tab_active == 'image' ? ' active' : null;?>" id="image">

          <table class="table table-bordered table-condenced table-striped">
            <thead>
              <tr class="bg-gray">
                <td class="w-10 text-center"><?php echo trans('label_serial_no');?></td>
                <td class="w-20 text-center"><?php echo trans('label_image');?></td>
                <td class="w-40"><?php echo trans('label_url');?></td>
                <td class="w-20 text-center"><?php echo trans('label_sort_order');?></td>
                <td class="w-10 text-center"><?php echo trans('label_action');?></td>
              </tr>
            </thead>
            <tbody>
              <tr ng-repeat="img in imgArray" id="{{ $index }}" class="image-item">
                <td class="text-center">{{ img.id }}</td>
                <td class="text-center">
                  <div class="image-thumbnail">
                    <a class="open-filemanager" data-imageid="{{img.id}}" data-toggle="image" id="thumb{{img.id}}" href="#">
                      <img src="../assets/itsolution24/img/noimage.jpg">
                    </a>
                  </div>
                </td>
                <td>
                  <input class="form-control" type="text" name="image[{{img.id}}][url]" id="image{{img.id}}" value="{{ img.url }}" autocomplete="off" readonly>
                </td>
                <td>
                  <input type="text" name="image[{{img.id}}][sort_order]" class="form-control sort_order text-center" value="{{ img.sort_order }}" onclick="this.select();" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onKeyUp="if(this.value<0){this.value='1';}">
                </td>
                <td class="text-center pointer remove-image">
                  <span ng-click="remoteImageItem($index)" class="fa fa-fw fa-close text-red"></span>
                </td>
              </tr>
              <tr>
                <td ng-click="addImageItem(imgSerial);" colspan="5" class="text-center bg-info add-image-row pointer">
                  <span class="fa fa-fw fa-plus text-white"></span>
                </td>
              </tr>
            </tbody>
          </table>
          
        </div>
        <!-- Image End -->


        <!-- General Start -->
        <div class="tab-pane<?php echo $tab_active == 'general' ? ' active' : null;?>" id="general">

          <div class="form-group">
            <label for="p_image" class="col-sm-3 control-label">
              <?php echo trans('label_thumbnail'); ?>
            </label>
            <div class="col-sm-7">
              <div class="preview-thumbnail">
                <a ng-click="POSFilemanagerModal({target:'p_image',thumb:'p_thumb'})" onClick="return false;" href="#" data-toggle="image" id="p_thumb">
                  <img src="../assets/itsolution24/img/noimage.jpg" alt="">
                </a>
                <input type="hidden" name="p_image" id="p_image" value="">
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="p_type" class="col-sm-3 control-label">
              <?php echo trans('label_product_type'); ?><i class="required">*</i>
            </label>
            <div class="col-sm-7">
              <select id="p_type" class="form-control" name="p_type" >
                <option value="standard" selected><?php echo trans('text_standard'); ?></option>
                <option value="service"><?php echo trans('text_service'); ?></option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label for="p_name" class="col-sm-3 control-label">
              <?php echo sprintf(trans('label_name'),trans('text_product')); ?><i class="required">*</i>
            </label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="p_name" value="<?php echo isset($request->post['p_name']) ? $request->post['p_name'] : null; ?>" name="p_name" required>
            </div>
          </div>

          <div class="form-group all">
            <label for="p_code" class="col-sm-3 control-label">
              <?php echo trans('label_pcode'); ?> <i class="required">*</i>
            </label>             
            <div class="col-sm-7">           
              <div class="input-group">
                <input type="text" name="p_code" id="p_code" class="form-control" autocomplete="off" required>
                <span id="random_num" class="input-group-addon pointer random_num">
                    <i class="fa fa-random"></i>
                </span>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="category_id" class="col-sm-3 control-label">
              <?php echo trans('label_category'); ?> <i class="required">*</i>
            </label>
            <div class="col-sm-7">
              <div class="{{ !hideSupAddBtn ? 'input-group' : null }}">
                <select id="category_id" class="form-control select2" name="category_id" required>
                  <option value="">
                    <?php echo trans('text_select'); ?>
                  </option>
                  <?php foreach (get_category_tree(array('filter_fetch_all' => true)) as $category_id => $category_name) { ?>
                    <option value="<?php echo $category_id; ?>"><?php echo $category_name ; ?></option>
                  <?php } ?>
                </select>
                <a ng-hide="hideCategoryAddBtn" class="input-group-addon" ng-click="createNewCategory();" onClick="return false;" href="category.php?box_state=open">
                  <i class="fa fa-plus"></i>
                </a>
              </div>
            </div>
          </div>

          <div ng-hide="hideSupplier" class="form-group">
            <label for="sup_id" class="col-sm-3 control-label">
              <?php echo trans('label_supplier'); ?><i class="required">*</i>
            </label>
            <div class="col-sm-7">
              <div class="{{ !hideSupAddBtn ? 'input-group' : null }}">
                <select id="sup_id" class="form-control" name="sup_id" required>
                  <option value="">
                    <?php echo trans('text_select'); ?>
                  </option>
                  <?php foreach (get_suppliers() as $supplier) : ?>
                    <option value="<?php echo $supplier['sup_id']; ?>">
                      <?php echo $supplier['sup_name'] ; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <a ng-hide="hideSupAddBtn" class="input-group-addon" ng-click="createNewSupplier();" onClick="return false;" href="supplier.php?box_state=open">
                  <i class="fa fa-plus"></i>
                </a>
              </div>
            </div>
          </div>

          <div ng-hide="hideBrand" class="form-group">
            <label for="brand_id" class="col-sm-3 control-label">
              <?php echo trans('label_brand'); ?>
            </label>
            <div class="col-sm-7">
              <div class="{{ !hideBrandAddBtn ? 'input-group' : null }}">
                <select id="brand_id" class="form-control" name="brand_id" required>
                  <option value="">
                    <?php echo trans('text_select'); ?>
                  </option>
                  <?php foreach (get_brands() as $brand) : ?>
                    <option value="<?php echo $brand['brand_id']; ?>">
                      <?php echo $brand['brand_name'] ; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <a ng-hide="hideBrandAddBtn" class="input-group-addon" ng-click="createNewBrand();" onClick="return false;" href="brand.php?box_state=open">
                  <i class="fa fa-plus"></i>
                </a>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="barcode_symbology" class="col-sm-3 control-label">
              <?php echo trans('label_barcode_symbology'); ?><i class="required">*</i>
            </label>
            <div class="col-sm-7">
              <select id="barcode_symbology" class="form-control select2" name="barcode_symbology" required>
                <option value="code25" selected>code25</option>
                <option value="code39">code39</option>
                <option value="code128" selected>code128</option>
                <option value="ean5">ean5</option>
                <option value="ean13">ean13</option>
                <option value="upca">upca</option>
                <option value="upce">upce</option>
              </select>
            </div>
          </div>

          <div ng-hide="hideBox" class="form-group">
            <label for="box_id" class="col-sm-3 control-label">
              <?php echo trans('label_box'); ?>
            </label>
            <div class="col-sm-7">
              <div class="{{ !hideBoxAddBtn ? 'input-group' : null }}">
                <select id="box_id" class="form-control" name="box_id" required>
                  <option value="">
                    <?php echo trans('text_select'); ?>
                  </option>
                  <?php $inc=1;foreach (get_boxes() as $box_row) : ?>
                    <option value="<?php echo $box_row['box_id']; ?>" <?php echo $inc == 1 ? 'selected' : null;?>>
                      <?php echo $box_row['box_name'] ; ?>
                    </option>
                  <?php $inc++;endforeach; ?>
                </select>
                <a ng-hide="hideBoxAddBtn" class="input-group-addon" ng-click="createNewBox();" onClick="return false;" href="box.php?box_state=open">
                  <i class="fa fa-plus"></i>
                </a>
              </div>
            </div>
          </div>

          <div ng-show="!hideExpiredAt" class="form-group<?php echo !get_preference('expiry_yes') ? ' hide' : null;?>">
            <label for="e_date" class="col-sm-3 control-label">
              <?php echo trans('label_expired_date'); ?><i class="required">*</i>
            </label>
            <div class="col-sm-7">
              <input type="date" class="form-control" id="e_date" name="e_date" value="<?php echo date('Y-m-d',strtotime(date("Y-m-d", time()) . " + 365 day"));?>" autocomplete="off" required>
            </div>
          </div>

          <div ng-hide="hideUnit" class="form-group">
            <label for="unit_id" class="col-sm-3 control-label">
              <?php echo trans('label_unit'); ?><i class="required">*</i>
            </label>
            <div class="col-sm-7">
              <div class="{{ !hideUnitAddBtn ? 'input-group' : null }}">
                <select id="unit_id" class="form-control" name="unit_id" required>
                  <option value="">
                    <?php echo trans('text_select'); ?>
                  </option>
                  <?php $inc=1;foreach (get_units() as $unit_row) : ?>
                    <option value="<?php echo $unit_row['unit_id']; ?>">
                      <?php echo $unit_row['unit_name'] ; ?>
                    </option>
                  <?php $inc++;endforeach; ?>
                </select>
                <a ng-hide="hideUnitAddBtn" class="input-group-addon" ng-click="createNewUnit();" onClick="return false;" href="unit.php?box_state=open">
                  <i class="fa fa-plus"></i>
                </a>
              </div>
            </div>
          </div>

          <?php if (get_preference('invoice_view') == 'indian_gst') : ?>
          <div class="form-group all">
            <label for="hsn_code" class="col-sm-3 control-label">
              <?php echo trans('label_hsn_code'); ?>
            </label>             
            <div class="col-sm-7">           
              <input type="text" name="hsn_code" id="hsn_code" class="form-control" autocomplete="off" required>
            </div>
          </div>
          <?php endif; ?>

          <div ng-show="showPurchasePrice" class="form-group">
            <label for="purchase_price" class="col-sm-3 control-label">
              <?php echo trans('label_product_cost'); ?><i class="required">*</i>
            </label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="purchase_price" name="purchase_price" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onKeyUp="if(this.value<0){this.value='1';}" required>
            </div>
          </div>

          <div class="form-group">
            <label for="sell_price" class="col-sm-3 control-label">
              <?php echo trans('label_product_price'); ?><i class="required">*</i>
            </label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="sell_price" name="sell_price" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onKeyUp="if(this.value<0){this.value='1';}" required>
            </div>
          </div>

          <div class="form-group">
            <label for="taxrate_id" class="col-sm-3 control-label">
              <?php echo trans('label_product_tax'); ?><i class="required">*</i>
            </label>
            <div class="col-sm-7">
              <div class="{{ !hideTaxrateAddBtn ? 'input-group' : null }}">
                <select id="taxrate_id" class="form-control" name="taxrate_id" required>
                  <option value="">
                    <?php echo trans('text_select'); ?>
                  </option>
                  <?php foreach (get_taxrates() as $taxrate_row) : ?>
                    <option value="<?php echo $taxrate_row['taxrate_id']; ?>" <?php echo $taxrate_row['taxrate'] == 0 ? 'selected' : null;?>>
                      <?php echo $taxrate_row['taxrate_name'] ; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <a ng-hide="hideTaxrateAddBtn" class="input-group-addon" ng-click="createNewTaxrate();" onClick="return false;" href="taxrate.php?box_state=open">
                  <i class="fa fa-plus"></i>
                </a>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="tax_method" class="col-sm-3 control-label">
              <?php echo trans('label_tax_method'); ?><i class="required">*</i>
            </label>
            <div class="col-sm-7">
              <select id="tax_method" class="form-control" name="tax_method" >
                <option value="inclusive" selected>
                  <?php echo trans('text_inclusive'); ?>
                </option>
                <option value="exclusive">
                  <?php echo trans('text_exclusive'); ?>
                </option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">
              <?php echo trans('label_store'); ?><i class="required">*</i>
            </label>
            <div class="col-sm-7 store-selector">
              <div class="checkbox selector">
                <label>
                  <input type="checkbox" onclick="$('input[name*=\'product_store\']').prop('checked', this.checked);"> Select / Deselect
                </label>
              </div>
              <div class="filter-searchbox">
                  <input ng-model="search_store" class="form-control" type="text" id="search_store" placeholder="<?php echo trans('search'); ?>">
              </div>
              <div class="well well-sm store-well"> 
                <div filter-list="search_store">
                  <?php foreach(get_stores() as $the_store) : ?>                  
                    <div class="checkbox">
                      <label>                         
                        <input type="checkbox" name="product_store[]" value="<?php echo $the_store['store_id']; ?>" <?php echo $the_store['store_id'] == store_id() ? 'checked' : null; ?>>
                        <?php echo $the_store['name']; ?>
                      </label>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>

          <div ng-hide="hideAlertQuantity" class="form-group">
            <label for="alert_quantity" class="col-sm-3 control-label">
              <?php echo trans('label_alert_quantity'); ?><i class="required">*</i>
            </label>
            <div class="col-sm-7">
              <input type="number" class="form-control" id="alert_quantity" value="10" name="alert_quantity" required>
            </div>
          </div>

          <div class="form-group">
            <label for="description" class="col-sm-3 control-label">
              <?php echo trans('label_description'); ?>
            </label>
            <div class="col-sm-7">
              <textarea class="form-control description" id="description" name="description" rows="3"><?php echo isset($request->post['description']) ? $request->post['description'] : null; ?></textarea>
            </div>
          </div>

          <div class="form-group">
            <label for="status" class="col-sm-3 control-label">
              <?php echo trans('label_status'); ?><i class="required">*</i>
            </label>
            <div class="col-sm-7">
              <select id="status" class="form-control" name="status" >
                <option <?php echo isset($request->post['status']) && $request->post['status'] == '1' ? 'selected' : null; ?> value="1">
                  <?php echo trans('text_active'); ?>
                </option>
                <option <?php echo isset($request->post['status']) && $request->post['status'] == '0' ? 'selected' : null; ?> value="0">
                  <?php echo trans('text_inactive'); ?>
                </option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label for="sort_order" class="col-sm-3 control-label">
              <?php echo sprintf(trans('label_sort_order'), null); ?><i class="required">*</i>
            </label>
            <div class="col-sm-7">
              <input type="number" class="form-control" id="sort_order" value="<?php echo isset($request->post['sort_order']) ? $request->post['sort_order'] : 0; ?>" name="sort_order" required>
            </div>
          </div>

        </div>
        <!-- General End -->

        <div class="form-group">
          <label class="col-sm-3 control-label"></label>
          <div class="col-sm-4">
            <button class="btn btn-info btn-block" id="create-product-submit" type="submit" name="create-product-submit" data-form="#create-product-form" data-datatable="product-product-list" data-loading-text="Saving...">
              <span class="fa fa-fw fa-save"></span>
              <?php echo trans('button_save'); ?>
            </button>
          </div>
          <div class="col-sm-3">
            <button type="reset" class="btn btn-warning btn-block" id="reset" name="reset">
              <span class="fa fa-circle-o"></span>
             <?php echo trans('button_reset'); ?></button>
          </div>
        </div>

      </div>
    </div>
  </div>
</form>

<script type="text/javascript">
$(document).ready(function() {
  setTimeout(function() {
    $("#random_num").trigger("click");
  }, 1000);
})
</script>