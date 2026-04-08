<div class="form-horizontal">
  <div class="box-body">
    <div class="form-group">
      <label for="p_image" class="col-sm-3 control-label">
        <?php echo sprintf(trans('label_image'),null); ?>
      </label>
      <div class="col-sm-1">
        <div class="preview-thumbnail">
          <a onClick="return false;" id="p_thumb" href="#">
            <?php if (isset($product['p_image']) && ((FILEMANAGERPATH && is_file(FILEMANAGERPATH.$product['p_image']) && file_exists(FILEMANAGERPATH.$product['p_image'])) || (is_file(DIR_STORAGE . 'products' . $product['p_image']) && file_exists(DIR_STORAGE . 'products' . $product['p_image'])))) : ?>
              <img  src="<?php echo FILEMANAGERURL ? FILEMANAGERURL : root_url().'/storage/products'; ?>/<?php echo $product['p_image']; ?>">
            <?php else : ?>
              <img src="../assets/itsolution24/img/noimage.jpg">
            <?php endif; ?>
          </a>
        </div>

      </div>
    </div>

    <div class="form-group">
      <label for="p_name" class="col-sm-3 control-label">
        <?php echo sprintf(trans('label_name'),null); ?>
      </label>
      <div class="col-sm-7">
        <input type="text" class="form-control" id="p_name" value="<?php echo $product['p_name']; ?>" name="p_name" readonly>
      </div>
    </div>

    <div class="form-group all">
      <label for="p_code" class="col-sm-3 control-label">
        <?php echo trans('label_pcode'); ?>
      </label>             
      <div class="col-sm-7">           
        <input type="text" name="p_code" value="<?php echo $product['p_code']; ?>" class="form-control" id="xp_code" readonly>
      </div>
    </div>

    <div class="form-group">
      <label for="category_id" class="col-sm-3 control-label">
        <?php echo trans('label_category'); ?>
      </label>
      <div class="col-sm-7">
        <select class="form-control select2" name="category_id" disabled>
          <option value="">
            <?php echo trans('text_select'); ?>
          </option>
          <?php foreach (get_category_tree(array('filter_fetch_all' => true)) as $category_id => $category_name) { ?>
              <?php if($product['category_id'] == $category_id) : ?>
                <option value="<?php echo $category_id; ?>" selected><?php echo $category_name ; ?></option>
              <?php else: ?>
                <option value="<?php echo $category_id; ?>"><?php echo $category_name ; ?></option>
              <?php endif; ?>
          <?php } ?>
       </select>
      </div>
    </div>

    <div class="form-group">
      <label for="sup_id" class="col-sm-3 control-label">
        <?php echo trans('label_supplier'); ?>
      </label>
      <div class="col-sm-7">
        <select class="form-control" name="sup_id" readonly disabled>
          <option value="">
            <?php echo trans('label_select'); ?>
          </option>
          <?php foreach(get_suppliers() as $supplier) {
              if($supplier['sup_id'] == $product['sup_id']) { ?>
                <option value="<?php echo $supplier['sup_id']; ?>" selected><?php echo $supplier['sup_name']; ?></option><?php
              } else { ?>
                <option value="<?php echo $supplier['sup_id']; ?>"><?php echo $supplier['sup_name']; ?></option><?php
              }
            }
          ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="brand_id" class="col-sm-3 control-label">
        <?php echo trans('label_brand'); ?>
      </label>
      <div class="col-sm-7">
        <select class="form-control" name="brand_id" readonly disabled>
          <option value="">
            <?php echo trans('text_select'); ?>
          </option>
          <?php foreach(get_brands() as $brand) {
              if($brand['brand_id'] == $product['brand_id']) { ?>
                <option value="<?php echo $brand['brand_id']; ?>" selected>
                  <?php echo $brand['brand_name']; ?>
                </option>
              <?php
              } else { ?>
                <option value="<?php echo $brand['brand_id']; ?>">
                  <?php echo $brand['brand_name']; ?>
                </option>
              <?php
              }
            }
          ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="barcode_symbology" class="col-sm-3 control-label">
        <?php echo trans('label_barcode_symbology'); ?>
      </label>
      <div class="col-sm-7">
        <select id="barcode_symbology" class="form-control select2" name="barcode_symbology" readonly disabled>
          <option value="code25" <?php echo  $product['barcode_symbology'] == 'code25' ? 'selected' : null;?>>code25</option>
          <option value="code39" <?php echo  $product['barcode_symbology'] == 'code39' ? 'selected' : null;?>>code39</option>
          <option value="code128" <?php echo  $product['barcode_symbology'] == 'code128' ? 'selected' : null;?>>code128</option>
          <option value="ean8" <?php echo  $product['barcode_symbology'] == 'ean8' ? 'selected' : null;?>>ean8</option>
          <option value="ean13" <?php echo  $product['barcode_symbology'] == 'ean13' ? 'selected' : null;?>>ean13</option>
          <option value="upca" <?php echo  $product['barcode_symbology'] == 'upca' ? 'selected' : null;?>>upca</option>
          <option value="upce" <?php echo  $product['barcode_symbology'] == 'upce' ? 'selected' : null;?>>upce</option>
        </select>
      </div>
    </div>

    <?php if (user_group_id() == 1 || has_permission('access', 'show_purchase_price')) : ?>
      <div class="form-group">
        <label for="purchase_price"  class="col-sm-3 control-label">
          <?php echo trans('label_purchase_price'); ?>
        </label>
        <div class="col-sm-7">
          <input type="number" step="0.01" class="form-control" id="purchase_price" value="<?php echo currency_format($product['purchase_price']); ?>" name="purchase_price" readonly>
        </div>
      </div>
    <?php endif; ?>

    <div class="form-group">
      <label for="sell_price" class="col-sm-3 control-label">
        <?php echo trans('label_sell_price'); ?>
      </label>
      <div class="col-sm-7">
        <input type="number" step="0.01" class="form-control" id="sell_price" value="<?php echo currency_format($product['sell_price']); ?>" name="sell_price" readonly>
      </div>
    </div>

    <div class="form-group">
      <label for="quantity_in_stock" class="col-sm-3 control-label">
        <?php echo trans('label_stock'); ?>
       </label>
      <div class="col-sm-7">
        <input type="number" class="form-control" id="quantity_in_stock" value="<?php echo format_input_number($product['quantity_in_stock']); ?>" name="quantity_in_stock" readonly>
      </div>
    </div>  

    <div class="form-group">
      <label for="e_date" class="col-sm-3 control-label">
        <?php echo trans('label_expired_date'); ?>
       </label>
      <div class="col-sm-7">
        <input type="date" class="form-control" id="e_date" value="<?php echo $product['e_date']; ?>" name="e_date" readonly>
      </div>
    </div>

    <div class="form-group">
      <label for="alert_quantity" class="col-sm-3 control-label">
        <?php echo trans('label_alert_quantity'); ?>
      </label>
      <div class="col-sm-7">
        <input type="number" class="form-control" id="alert_quantity" name="alert_quantity" value="<?php echo format_input_number($product['alert_quantity']); ?>" readonly>
      </div>
    </div>

    <div class="form-group">
      <label for="unit_id" class="col-sm-3 control-label">
        <?php echo trans('label_unit'); ?>
      </label>
      <div class="col-sm-7">
        <select class="form-control" name="unit_id" readonly disabled>
            <option value="">
              <?php echo trans('text_select'); ?>
            </option>
            <?php foreach(get_units() as $unit_row) {
                if($unit_row['unit_id'] == $product['unit_id']) { ?>
                  <option value="<?php echo $unit_row['unit_id']; ?>" selected><?php echo $unit_row['unit_name']; ?></option><?php
                } else {
                  ?>
                  <option value="<?php echo $unit_row['unit_id']; ?>">
                    <?php echo $unit_row['unit_name']; ?>
                  </option>
                <?php
                }
              }
            ?>
        </select>
      </div>
    </div>

    <?php if (get_preference('invoice_view') == 'indian_gst') : ?>
    <div class="form-group all">
      <label for="hsn_code" class="col-sm-3 control-label">
        <?php echo trans('label_hsn_code'); ?>
      </label>             
      <div class="col-sm-7">           
        <input type="text" name="hsn_code" id="hsn_code" class="form-control" value="<?php echo $product['hsn_code']; ?>" autocomplete="off" readonly>
      </div>
    </div>
    <?php endif; ?>

    <div class="form-group">
      <label for="taxrate_id" class="col-sm-3 control-label">
        <?php echo trans('label_product_tax'); ?>
      </label>
      <div class="col-sm-7">
        <select class="form-control" name="taxrate_id" readonly disabled>
            <option value="">
              <?php echo trans('text_select'); ?>
            </option>
            <?php foreach(get_taxrates() as $taxrate_row) {
                if($taxrate_row['taxrate_id'] == $product['taxrate_id']) { ?>
                  <option value="<?php echo $taxrate_row['taxrate_id']; ?>" selected><?php echo $taxrate_row['taxrate_name']; ?></option><?php
                } else {
                  ?>
                  <option value="<?php echo $taxrate_row['taxrate_id']; ?>">
                    <?php echo $taxrate_row['taxrate_name']; ?>
                  </option>
                <?php
                }
              }
            ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="tax_method" class="col-sm-3 control-label">
        <?php echo trans('label_tax_method'); ?>
      </label>
      <div class="col-sm-7">
        <select id="tax_method" class="form-control" name="tax_method" readonly disabled>
          <option <?php echo isset($product['tax_method']) && $product['tax_method'] == 'inclusive' ? 'selected' : null; ?> value="inclusive">
            <?php echo trans('text_inclusive'); ?>
          </option>
          <option <?php echo isset($product['tax_method']) && $product['tax_method'] == 'exclusive' ? 'selected' : null; ?> value="exclusive">
            <?php echo trans('text_exclusive'); ?>
          </option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="box_id" class="col-sm-3 control-label">
        <?php echo trans('label_box'); ?>
      </label>
      <div class="col-sm-7">
        <select class="form-control" name="box_id" readonly disabled>
           <?php foreach(get_boxes() as $box) {
                if($box['box_id'] == $product['box_id']) { ?>
                  <option value="<?php echo $box['box_id']; ?>" selected>
                    <?php echo $box['box_name']; ?>
                  </option>
                <?php
                } else { ?>
                  <option value="<?php echo $box['box_id']; ?>">
                    <?php echo $box['box_name']; ?>
                  </option>
                  <?php
                }
              }
            ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="e_date" class="col-sm-3 control-label">
        <?php echo trans('label_description'); ?>
      </label>
      <div class="col-sm-7">
        <textarea class="form-control" id="description" name="description" rows="3" readonly><?php echo $product['description']; ?></textarea>
      </div>
    </div>

    <div class="form-group">
      <label for="status" class="col-sm-3 control-label">
        <?php echo trans('label_status'); ?>
      </label>
      <div class="col-sm-7">
        <select id="status" class="form-control" name="status" readonly disabled>
          <option <?php echo isset($product['status']) && $product['status'] == '1' ? 'selected' : null; ?> value="1">
            <?php echo trans('text_active'); ?>
          </option>
          <option <?php echo isset($product['status']) && $product['status'] == '0' ? 'selected' : null; ?> value="0">
            <?php echo trans('text_inactive'); ?>
          </option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="sort_order" class="col-sm-3 control-label">
        <?php echo sprintf(trans('label_sort_order'), null); ?>
      </label>
      <div class="col-sm-7">
        <input type="number" class="form-control" id="sort_order" value="<?php echo $product['sort_order']; ?>" name="sort_order" readonly>
      </div>
    </div>

  </div>
</div>