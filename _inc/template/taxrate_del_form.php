<h4 class="sub-title">
  <?php echo trans('text_delete_title'); ?>
</h4>

<form class="form-horizontal" id="taxrate-del-form" action="taxrate.php" method="post">
  <input type="hidden" id="action_type" name="action_type" value="DELETE">
  <input type="hidden" id="taxrate_id" name="taxrate_id" value="<?php echo $taxrate['taxrate_id']; ?>">
  <h4 class="taxrate-title text-center">
    <?php echo trans('text_delete_instruction'); ?>
  </h4>
  <div class="box-body">

    <div class="form-group">
      <label for="insert_to" class="col-sm-4 control-label">
        <?php echo trans('label_insert_content_into'); ?>
      </label>
      <div class="col-sm-6">
        <div class="radio">
            <input type="radio" id="insert_to" value="insert_to" name="delete_action" checked="checked">
            <select name="new_taxrate_id" class="form-control">
                <option value="">
                  <?php echo trans('text_select'); ?>
                </option>
              <?php foreach (get_taxrates() as $the_taxrate) : ?>
                <?php if($the_taxrate['taxrate_id'] == $taxrate['taxrate_id']) continue; ?>
                <option value="<?php echo $the_taxrate['taxrate_id']; ?>">
                  <?php echo $the_taxrate['taxrate_name']; ?>
                </option>
              <?php endforeach; ?>
            </select> 
        </div>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-4 control-label"></label>
      <div class="col-sm-6">
        <button id="taxrate-delete" data-form="#taxrate-del-form" data-datatable="#taxrate-taxrate-list" class="btn btn-danger" name="btn_edit_box" data-loading-text="Deleting...">
          <span class="fa fa-fw fa-trash"></span>
          <?php echo trans('button_delete'); ?>
        </button>
      </div>
    </div>
  </div>
</form>