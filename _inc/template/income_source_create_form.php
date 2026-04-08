<form id="create-income-source-form" class="form form-horizontal" action="income_source.php" method="post" enctype="multipart/form-data">
  <input type="hidden" id="action_type" name="action_type" value="CREATE">
  <div class="box-body">

      <div class="form-group">
        <label for="source_name" class="col-sm-3 control-label">
          <?php echo trans('label_source_name'); ?>
        </label>
        <div class="col-sm-6">
          <input ng-model="income_sourceName" type="text" class="form-control" id="source_name" value="<?php echo isset($request->post['source_name']) ? $request->post['source_name'] : null; ?>" name="source_name" required>
        </div>
      </div>

      <div class="form-group">
        <label for="source_slug" class="col-sm-3 control-label">
          <?php echo trans('label_source_slug'); ?>
        </label>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="source_slug" value="<?php echo isset($request->post['source_slug']) ? $request->post['source_slug'] : "{{ income_sourceName | strReplace:' ':'_' | lowercase }}"; ?>" name="source_slug" required readonly>
        </div>
      </div>

      <div class="form-group">
        <label for="parent_id" class="col-sm-3 control-label">
          <?php echo trans('label_parent'); ?>
        </label>
        <div class="col-sm-6">
          <select class="form-control select2" name="parent_id">
            <option value="">
              <?php echo trans('text_select'); ?>
            </option>
            <?php foreach (get_income_sources() as $the_income_source) { ?>
              <option value="<?php echo $the_income_source['source_id']; ?>"><?php echo $the_income_source['source_name'] ; ?></option>
            <?php } ?>
         </select>
        </div>
      </div>

      <div class="form-group">
        <label for="source_details" class="col-sm-3 control-label">
          <?php echo trans('label_source_details'); ?>
        </label>
        <div class="col-sm-6">
          <textarea class="form-control" id="source_details" name="source_details"><?php echo isset($request->post['source_details']) ? $request->post['source_details'] : null; ?></textarea>
        </div>
      </div>

      <div class="form-group">
        <label for="status" class="col-sm-3 control-label">
          <?php echo trans('label_status'); ?>
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
          <?php echo trans('label_sort_order'); ?>
        </label>
        <div class="col-sm-6">
          <input type="number" class="form-control" id="sort_order" value="<?php echo isset($request->post['sort_order']) ? $request->post['sort_order'] : 0; ?>" name="sort_order">
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-3 control-label"></label>
        <div class="col-sm-6">
          <button id="create-income-source-submit" data-form="#create-income-source-form" data-datatable="#source-source-list" class="btn btn-info" name="btn_edit_income_source" data-loading-text="Saving...">
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