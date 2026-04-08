<h4 class="sub-title">
  <?php echo trans('text_update_title'); ?>
</h4>
<form class="form-horizontal" id="brand-form" action="brand.php" method="post">
  <input type="hidden" id="action_type" name="action_type" value="UPDATE">
  <input type="hidden" id="brand_id" name="brand_id" value="<?php echo $brand['brand_id']; ?>">
  <div class="box-body">

    <div class="form-group">
      <label class="col-sm-3 control-label">
        <?php echo sprintf(trans('label_image'),null); ?>
      </label>
      <div class="col-sm-2">
        <div class="preview-thumbnail">
          <a ng-click="POSFilemanagerModal({target:'the_brand_image',thumb:'brand_thumb'})" onClick="return false;" href="" data-toggle="image" id="brand_thumb">
            <?php if (isset($brand['brand_image']) && ((FILEMANAGERPATH && is_file(FILEMANAGERPATH.$brand['brand_image']) && file_exists(FILEMANAGERPATH.$brand['brand_image'])) || (is_file(DIR_STORAGE . 'categories' . $brand['brand_image']) && file_exists(DIR_STORAGE . 'categories' . $brand['brand_image'])))) : ?>
              <img  src="<?php echo FILEMANAGERURL ? FILEMANAGERURL : root_url().'/storage/categories'; ?>/<?php echo $brand['brand_image']; ?>">
            <?php else : ?>
              <img src="../assets/itsolution24/img/noimage.jpg">
            <?php endif; ?>
          </a>
          <input type="hidden" name="brand_image" id="the_brand_image" value="<?php echo isset($brand['brand_image']) ? $brand['brand_image'] : null; ?>">
        </div>
      </div>
    </div>
    
    <div class="form-group">
      <label for="brand_name" class="col-sm-3 control-label">
        <?php echo sprintf(trans('label_name'), null); ?><i class="required">*</i>
     </label>
      <div class="col-sm-8">
        <input type="text" class="form-control" id="brand_name" value="<?php echo $brand['brand_name']; ?>" name="brand_name" ng-init="codeName='<?php echo $brand['code_name'] ? $brand['code_name'] : $brand['brand_name']; ?>'" value="<?php echo $brand['brand_name']; ?>" required>
      </div>
    </div>

    <div class="form-group">
      <label for="code_name" class="col-sm-3 control-label">
        <?php echo trans('label_code_name'); ?><i class="required">*</i>
     </label>
      <div class="col-sm-8">
        <input type="text" class="form-control" id="code_name" value="<?php echo $brand['code_name'] ? $brand['code_name'] : "{{ codeName | strReplace:' ':'_' | lowercase }}"; ?>" name="code_name" required>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-3 control-label">
        <?php echo trans('label_store'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-8 store-selector">
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
                  <input type="checkbox" name="brand_store[]" value="<?php echo $the_store['store_id']; ?>" <?php echo in_array($the_store['store_id'], $brand['stores']) ? 'checked' : null; ?>>
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
        <?php echo sprintf(trans('label_details'), null); ?>
      </label>
      <div class="col-sm-8">
        <textarea class="form-control" id="brand_details" name="brand_details"><?php echo $brand['brand_details']; ?></textarea>
      </div>
    </div>

    <div class="form-group">
      <label for="status" class="col-sm-3 control-label">
        <?php echo trans('label_status'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-8">
        <select id="status" class="form-control" name="status" >
          <option <?php echo isset($brand['status']) && $brand['status'] == '1' ? 'selected' : null; ?> value="1"><?php echo trans('text_active'); ?></option>
          <option <?php echo isset($brand['status']) && $brand['status'] == '0' ? 'selected' : null; ?> value="0"><?php echo trans('text_in_active'); ?></option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="sort_order" class="col-sm-3 control-label">
        <?php echo sprintf(trans('label_sort_order'), null); ?><i class="required">*</i>
      </label>
      <div class="col-sm-8">
        <input type="number" class="form-control" id="sort_order" value="<?php echo $brand['sort_order']; ?>" name="sort_order">
      </div>
    </div>

    <div class="form-group">
      <label for="brand_address" class="col-sm-3 control-label"></label>
      <div class="col-sm-8">
        <button id="brand-update" data-form="#brand-form" data-datatable="#brand-brand-list" class="btn btn-info" name="btn_edit_brand" data-loading-text="Updating...">
          <span class="fa fa-fw fa-pencil"></span>
          <?php echo sprintf(trans('button_update'), null); ?>
        </button>
      </div>
    </div>
    
  </div>
</form>