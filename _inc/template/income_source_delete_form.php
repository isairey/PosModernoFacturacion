<h4 class="sub-title">
  <?php echo trans('text_delete_title'); ?>
</h4>

<form class="form-horizontal" id="income-source-del-form" action="income_source.php" method="post">
  
  <input type="hidden" id="action_type" name="action_type" value="DELETE">
  <input type="hidden" id="source_id" name="source_id" value="<?php echo $income_source['source_id']; ?>">
  
  <h4 class="box-title text-center">
    <?php echo trans('text_delete_instruction'); ?>
  </h4>
  <div class="box-body">

    <div class="form-group">
      <label for="insert_to" class="col-sm-4 control-label">
        <?php echo trans('label_insert_content_to'); ?>
      </label>
      <div class="col-sm-6">
        <div class="radio">
          <input type="radio" id="insert_to" value="insert_to" name="delete_action" checked="checked">
          <select name="new_source_id" class="form-control select2">
            <option value="">
              <?php echo trans('text_select'); ?>
            </option>
            <?php foreach (get_income_sources() as $the_income_source) : ?>
              <?php if($the_income_source['source_id'] == $income_source['source_id']) continue ?>
              <option value="<?php echo $the_income_source['source_id']; ?>">
                <?php echo $the_income_source['source_name']; ?>
              </option>
            <?php endforeach; ?>
          </select> 
        </div>
      </div>
    </div>
    
    <br><br>

    <div class="form-group">
      <div class="col-sm-12 text-center">
        <button id="income-source-delete" data-form="#income-source-del-form" data-datatable="#source-source-list" class="btn btn-danger" name="btn_edit_income_source" data-loading-text="Deleting...">
          <span class="fa fa-fw fa-trash"></span>
          <?php echo trans('button_delete'); ?>
        </button>
      </div>
    </div>
    
  </div>
</form>