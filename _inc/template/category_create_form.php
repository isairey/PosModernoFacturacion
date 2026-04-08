<form id="create-category-form" class="form form-horizontal" action="category.php" method="post" enctype="multipart/form-data">
  <input type="hidden" id="action_type" name="action_type" value="CREATE">
  <div class="box-body">

    <div class="form-group">
      <label for="category_image" class="col-sm-3 control-label">
        <?php echo sprintf(trans('label_thumbnail'),null); ?>
      </label>
      <div class="col-sm-7">
        <div class="preview-thumbnail">
          <a ng-click="POSFilemanagerModal({target:'category_image',thumb:'category_thumb'})" onClick="return false;" href="#" data-toggle="image" id="category_thumb">
            <img src="../assets/itsolution24/img/noimage.jpg" alt="">
          </a>
          <input type="hidden" name="category_image" id="category_image" value="">
        </div>
      </div>
    </div>

      <div class="form-group">
        <label for="category_name" class="col-sm-3 control-label">
          <?php echo trans('label_category_name'); ?><i class="required">*</i>
        </label>
        <div class="col-sm-6">
          <input ng-model="categoryName" type="text" class="form-control" id="category_name" value="" name="category_name" required>
        </div>
      </div>

      <div class="form-group">
        <label for="category_slug" class="col-sm-3 control-label">
          <?php echo trans('label_category_slug'); ?><i class="required">*</i>
        </label>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="category_slug" value="{{ categoryName | strReplace:' ':'_' | lowercase }}" name="category_slug" required>
        </div>
      </div>

      <div class="form-group">
        <label for="parent_id" class="col-sm-3 control-label">
          <?php echo trans('label_parent'); ?>
        </label>
        <div class="col-sm-6">
          <select class="form-control select2" name="parent_id">
            <option value="0">
              <?php echo trans('text_select'); ?>
            </option>
            <?php foreach (get_categorys() as $the_category) { ?>
              <option value="<?php echo $the_category['category_id']; ?>"><?php echo $the_category['category_name'] ; ?></option>
            <?php } ?>
         </select>
        </div>
      </div>

      <div class="form-group">
      <label class="col-sm-3 control-label">
        <?php echo trans('label_store'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-6 store-selector">
        <div class="checkbox selector">
          <label>
            <input type="checkbox" onclick="$('input[name*=\'category_store\']').prop('checked', this.checked);"> Select / Deselect
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
                <input type="checkbox" name="category_store[]" value="<?php echo $the_store['store_id']; ?>" <?php echo $the_store['store_id'] == store_id() ? 'checked' : null; ?>>
                <?php echo $the_store['name']; ?>
              </label>
            </div>
          <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>

      <div class="form-group">
        <label for="category_details" class="col-sm-3 control-label">
          <?php echo trans('label_category_details'); ?>
        </label>
        <div class="col-sm-6">
          <textarea class="form-control" id="category_details" name="category_details"><?php echo isset($request->post['category_details']) ? $request->post['category_details'] : null; ?></textarea>
        </div>
      </div>

      <div class="form-group">
        <label for="status" class="col-sm-3 control-label">
          <?php echo trans('label_status'); ?><i class="required">*</i>
        </label>
        <div class="col-sm-6">
          <select id="status" class="form-control" name="status" >
            <option value="1">
              <?php echo trans('text_active'); ?>
            </option>
            <option value="0">
              <?php echo trans('text_inactive'); ?>
            </option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label for="sort_order" class="col-sm-3 control-label">
          <?php echo trans('label_sort_order'); ?><i class="required">*</i>
        </label>
        <div class="col-sm-6">
          <input type="number" class="form-control" id="sort_order" value="<?php echo isset($request->post['sort_order']) ? $request->post['sort_order'] : 0; ?>" name="sort_order">
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-3 control-label"></label>
        <div class="col-sm-6">
          <button id="create-category-submit" data-form="#create-category-form" data-datatable="#category-category-list" class="btn btn-info" name="btn_edit_category" data-loading-text="Saving...">
            <span class="fa fa-fw fa-pencil"></span>
            <?php echo trans('button_save'); ?>
          </button>
          <button type="reset" class="btn btn-danger" id="reset" name="reset"><span class="fa fa-fw fa-circle-o"></span>
            <?php echo trans('button_reset'); ?>
          </button>
        </div>
      </div>

  </div>
  <!-- end .box-body -->
</form>