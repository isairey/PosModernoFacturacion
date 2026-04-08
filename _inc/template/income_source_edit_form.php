<h4 class="sub-title">
  <?php echo trans('text_update_title'); ?>
</h4>

<form class="form-horizontal" id="income-source-form" action="income_source.php" method="post">
  <input type="hidden" id="action_type" name="action_type" value="UPDATE">
  <input type="hidden" id="source_id" name="source_id" value="<?php echo $income_source['source_id']; ?>">
  <div class="box-body">
    
      <div class="form-group">
        <label for="source_name" class="col-sm-3 control-label">
          <?php echo trans('label_source_name'); ?>
        </label>
        <div class="col-sm-7">
          <input type="text" class="form-control" id="source_name" ng-model="income_sourceName" ng-init="income_sourceName='<?php echo $income_source['source_name']; ?>'" value="<?php echo $income_source['source_name']; ?>" name="source_name" required>
        </div>
      </div>

      <div class="form-group">
        <label for="source_slug" class="col-sm-3 control-label">
          <?php echo trans('label_source_slug'); ?><i class="required">*</i>
       </label>
        <div class="col-sm-7">
          <input type="text" class="form-control" id="source_slug" value="<?php echo $income_source['source_slug'] ? $income_source['source_slug'] : "{{ income_sourceName | strReplace:' ':'_' | lowercase }}"; ?>" name="source_slug" required>
        </div>
      </div>

      <div class="form-group">
        <label for="parent_id" class="col-sm-3 control-label">
          <?php echo trans('label_parent'); ?>
        </label>
        <div class="col-sm-7">
          <select class="form-control select2" name="parent_id" required>
            <option value="">
              <?php echo trans('text_select'); ?>
            </option>
            <?php foreach (get_income_sources(array('exclude' => $income_source['source_id'])) as $the_income_source) { ?>
                <?php if($income_source['parent_id'] == $the_income_source['source_id']) : ?>
                  <option value="<?php echo $the_income_source['source_id']; ?>" selected><?php echo $the_income_source['source_name'] ; ?></option>
                <?php else: ?>
                  <option value="<?php echo $the_income_source['source_id']; ?>"><?php echo $the_income_source['source_name'] ; ?></option>
                <?php endif; ?>
            <?php } ?>
         </select>
        </div>
      </div>

      <div class="form-group">
        <label for="source_details" class="col-sm-3 control-label">
          <?php echo trans('label_source_details'); ?>
        </label>
        <div class="col-sm-7">
          <textarea class="form-control" id="source_details" name="source_details"><?php echo $income_source['source_details']; ?></textarea>
        </div>
      </div>

      <div class="form-group">
        <label for="status" class="col-sm-3 control-label">
          <?php echo trans('label_status'); ?>
        </label>
        <div class="col-sm-7">
          <select id="status" class="form-control" name="status" >
            <option <?php echo isset($income_source['status']) && $income_source['status'] == '1' ? 'selected' : null; ?> value="1">
              <?php echo trans('text_active'); ?>
            </option>
            <option <?php echo isset($income_source['status']) && $income_source['status'] == '0' ? 'selected' : null; ?> value="0">
              <?php echo trans('text_inactive'); ?>
            </option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label for="sort_order" class="col-sm-3 control-label">
          <?php echo trans('label_sort_order'); ?>
        </label>
        <div class="col-sm-7">
          <input type="number" class="form-control" id="sort_order" value="<?php echo $income_source['sort_order']; ?>" name="sort_order">
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-3 control-label"></label>
        <div class="col-sm-7">
          <button id="income-source-update" data-form="#income-source-form" data-datatable="#source-source-list" class="btn btn-info" name="btn_edit_income_source" data-loading-text="Updating...">
            <span class="fa fa-fw fa-pencil"></span>
            <?php echo trans('button_update'); ?>
          </button>
        </div>
      </div>
  </div>
</form>