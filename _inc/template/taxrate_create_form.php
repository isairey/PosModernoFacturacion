<form id="create-taxrate-form" class="form-horizontal" action="taxrate.php" method="post" enctype="multipart/form-data">
  <input type="hidden" id="action_type" name="action_type" value="CREATE">
  <div class="box-body">
    
    <div class="form-group">
      <label for="taxrate_name" class="col-sm-3 control-label">
        <?php echo trans('label_taxrate_name'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-7">
        <input type="text" class="form-control" id="taxrate_name" name="taxrate_name" ng-model="taxRateName" required>
      </div>
    </div>

    <div class="form-group">
      <label for="code_name" class="col-sm-3 control-label">
        <?php echo trans('label_code_name'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-7">
        <input type="text" class="form-control" id="code_name" value="{{ taxRateName | strReplace:' ':'_' | lowercase }}" name="code_name" required>
      </div>
    </div>

    <div class="form-group">
      <label for="taxrate" class="col-sm-3 control-label">
        <?php echo trans('label_taxrate'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-7">
        <input type="number" class="form-control" id="taxrate" value="<?php echo isset($request->post['taxrate']) ? $request->post['taxrate'] : null; ?>" name="taxrate" required>
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

    <div class="form-group">
      <label class="col-sm-3 control-label"></label>
      <div class="col-sm-7">
        <button class="btn btn-info" id="create-taxrate-submit" type="submit" name="create-taxrate-submit" data-form="#create-taxrate-form" data-loading-text="Saving...">
          <span class="fa fa-fw fa-save"></span> 
          <?php echo trans('button_save'); ?>
        </button>  
        <button type="reset" class="btn btn-danger" id="reset" name="reset">
          <span class="fa fa-fw fa-circle"></span>
          <?php echo trans('button_reset'); ?>
        </button>
      </div>
    </div>
     
  </div>
</form>