<form id="create-brand-form" class="form-horizontal" action="brand.php" method="post" enctype="multipart/form-data">
  <input type="hidden" id="action_type" name="action_type" value="CREATE">
  <div class="box-body">
    
    <div class="form-group">
      <label for="brand_name" class="col-sm-3 control-label">
        <?php echo sprintf(trans('label_name'), null); ?><i class="required">*</i>
      </label>
      <div class="col-sm-7">
        <input type="text" class="form-control" id="brand_name" name="brand_name" value="<?php echo isset($request->post['brand_name']) ? $request->post['brand_name'] : null; ?>" required>
      </div>
    </div>

    <div class="form-group">
      <label for="brand_email" class="col-sm-3 control-label">
        <?php echo trans('label_email'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-7">
        <input type="email" class="form-control" id="brand_email" name="brand_email" value="<?php echo isset($request->post['brand_email']) ? $request->post['brand_email'] : null; ?>" required>
      </div>
    </div>

    <div class="form-group">
      <label for="brand_mobile" class="col-sm-3 control-label">
        <?php echo trans('label_mobile'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-7">
        <input type="text" class="form-control" id="brand_mobile" name="brand_mobile" value="<?php echo isset($request->post['brand_mobile']) ? $request->post['brand_mobile'] : null; ?>" required>
      </div>
    </div>

    <div class="form-group">
      <label for="brand_address" class="col-sm-3 control-label">
        <?php echo sprintf(trans('label_address'), null); ?><i class="required">*</i>
      </label>
      <div class="col-sm-7">
        <textarea class="form-control" id="brand_address"  name="brand_address" value="<?php echo isset($request->post['brand_address']) ? $request->post['brand_address'] : null; ?>" required></textarea>
      </div>
    </div>

    <div class="form-group">
      <label for="brand_city" class="col-sm-3 control-label">
        <?php echo sprintf(trans('label_city'), null); ?>
      </label>
      <div class="col-sm-7">
        <input type="text" class="form-control" id="brand_city" value="<?php echo isset($request->post['brand_city']) ? $request->post['brand_city'] : null; ?>" name="brand_city">
      </div>
    </div>

    <?php if (get_preference('invoice_view') == 'indian_gst') : ?>
    <div class="form-group">
      <label for="brand_state" class="col-sm-3 control-label">
        <?php echo sprintf(trans('label_state'), null); ?>
      </label>
      <div class="col-sm-7">
        <?php echo stateSelector(isset($request->post['brand_state']) ? $request->post['brand_state'] : null, 'brand_state', 'brand_state'); ?>
      </div>
    </div>
    <?php else : ?>
      <div class="form-group">
        <label for="brand_state" class="col-sm-3 control-label">
          <?php echo sprintf(trans('label_state'), null); ?>
        </label>
        <div class="col-sm-7">
          <input type="text" class="form-control" id="brand_state" value="<?php echo isset($request->post['brand_state']) ? $request->post['brand_state'] : null; ?>" name="brand_state">
        </div>
      </div>
    <?php endif; ?>

    <div class="form-group">
      <label for="country" class="col-sm-3 control-label">
        <?php echo trans('label_country'); ?>
      </label>
      <div class="col-sm-7">
        <?php echo countrySelector(isset($request->post['brand_country']) ? $request->post['brand_country'] : null, 'brand_country', 'brand_country'); ?>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-3 control-label">
        <?php echo trans('label_store'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-7 store-selector">
        <div class="checkbox selector">
          <label>
            <input type="checkbox" onclick="$('input[name*=\'brand_store\']').prop('checked', this.checked);"> Select / Deselect
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
                  <input type="checkbox" name="brand_store[]" value="<?php echo $the_store['store_id']; ?>" <?php echo $the_store['store_id'] == store_id() ? 'checked' : null; ?>>
                  <?php echo $the_store['name']; ?>
                </label>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label for="brand_details" class="col-sm-3 control-label">
        <?php echo trans('label_details'); ?>
      </label>
      <div class="col-sm-7">
        <textarea class="form-control" id="brand_details"  name="brand_details" value="<?php echo isset($request->post['brand_details']) ? $request->post['brand_details'] : null; ?>" required></textarea>
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
            <?php echo trans('text_in_active'); ?>
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

    <div class="form-group">
      <label class="col-sm-3 control-label"></label>
      <div class="col-sm-7">
        <button class="btn btn-info" id="create-brand-submit" type="submit" name="create-brand-submit" data-form="#create-brand-form" data-loading-text="Saving...">
          <span class="fa fa-fw fa-save"></span>
          <?php echo trans('button_save'); ?>
        </button>
        <button type="reset" class="btn btn-danger" id="reset" name="reset"><span class="fa fa-fw fa-circle-o"></span>
          <?php echo trans('button_reset'); ?>
        </button>
      </div>
    </div>
    
  </div>
</form>