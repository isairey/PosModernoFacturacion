<form id="create-brand-form" class="form-horizontal" action="brand.php" method="post" enctype="multipart/form-data">
  <input type="hidden" id="action_type" name="action_type" value="CREATE">
  <div class="box-body">

    <div class="form-group">
      <label for="brand_image" class="col-sm-3 control-label">
        <?php echo sprintf(trans('label_thumbnail'),null); ?>
      </label>
      <div class="col-sm-7">
        <div class="preview-thumbnail">
          <a ng-click="POSFilemanagerModal({target:'brand_image',thumb:'brand_thumb'})" onClick="return false;" href="#" data-toggle="image" id="brand_thumb">
            <img src="../assets/itsolution24/img/noimage.jpg" alt="">
          </a>
          <input type="hidden" name="brand_image" id="brand_image" value="">
        </div>
      </div>
    </div>
    
    <div class="form-group">
      <label for="brand_name" class="col-sm-3 control-label">
        <?php echo sprintf(trans('label_name'), null); ?><i class="required">*</i>
      </label>
      <div class="col-sm-7">
        <input type="text" class="form-control" id="brand_name" name="brand_name" value="<?php echo isset($request->post['brand_name']) ? $request->post['brand_name'] : null; ?>" ng-model="brandName" required>
      </div>
    </div>

    <div class="form-group">
      <label for="code_name" class="col-sm-3 control-label">
        <?php echo trans('label_code_name'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-7">
        <input type="text" class="form-control" id="code_name" value="{{ brandName | strReplace:' ':'_' | lowercase }}" name="code_name" required>
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