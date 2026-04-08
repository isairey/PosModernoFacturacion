<form id="form-expense" class="form-horizontal" action="expense.php" method="post" enctype="multipart/form-data">
  <input type="hidden" id="action_type" name="action_type" value="UPDATE">
  <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
  <div class="box-body">
    <div class="form-group">
      <label class="col-sm-3 control-label">
        <?php echo sprintf(trans('label_image'),null); ?>
      </label>
      <div class="col-sm-2">
        <div class="preview-thumbnail">
          <a ng-click="POSFilemanagerModal({target:'image',thumb:'product_thumbnail'})" onClick="return false;" href="" data-toggle="image" id="product_thumbnail">
            <?php if (isset($expense['attachment']) && ((FILEMANAGERPATH && is_file(FILEMANAGERPATH.$expense['attachment']) && file_exists(FILEMANAGERPATH.$expense['attachment'])) || (is_file(DIR_STORAGE . 'expenses' . $expense['attachment']) && file_exists(DIR_STORAGE . 'expenses' . $expense['attachment'])))) : ?>
              <img  src="<?php echo FILEMANAGERURL ? FILEMANAGERURL : root_url().'/storage/expenses'; ?>/<?php echo $expense['attachment']; ?>">
            <?php else : ?>
              <img src="../assets/itsolution24/img/noimage.jpg">
            <?php endif; ?>
          </a>
          <input type="hidden" name="image" id="image" value="<?php echo isset($expense['attachment']) ? $expense['attachment'] : null; ?>">
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="reference_no" class="col-sm-3 control-label">
        <?php echo trans('label_reference_no'); ?>
      </label>
      <div class="col-sm-8">
        <?php $reference_no = isset($expense['reference_no']) ? $expense['reference_no'] : null; ?>
        <input type="text" class="form-control" id="reference_no" value="<?php echo $reference_no; ?>" name="reference_no" autofocus <?php echo $reference_no ? 'readonly' : null; ?> autocomplete="off">
      </div>
    </div>
    <div class="form-group">
      <label for="category_id" class="col-sm-3 control-label">
        <?php echo trans('label_category'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-8">
        <select class="form-control select2" name="category_id">
          <option value="">
            <?php echo trans('text_select'); ?>
          </option>
          <?php foreach (get_expense_categorys() as $the_expense_category) { ?>
            <option value="<?php echo $the_expense_category['category_id']; ?>" <?php echo $expense['category_id'] == $the_expense_category['category_id'] ? 'selected' : null;?>><?php echo $the_expense_category['category_name'] ; ?></option>
          <?php } ?>
       </select>
      </div>
    </div>
    <div class="form-group">
      <label for="title" class="col-sm-3 control-label">
        <?php echo trans('label_what_for'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-8">
        <input type="text" id="title" class="form-control" name="title" value="<?php echo $expense['title'];?>">
      </div>
    </div>
    <div class="form-group">
      <label for="amount" class="col-sm-3 control-label">
        <?php echo trans('label_amount'); ?><i class="required">*</i>
       </label>
      <div class="col-sm-8">
        <input type="text" id="amount" class="form-control" name="amount" value="<?php echo format_input_number($expense['amount']);?>" onclick="this.select();" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onKeyUp="if(this.value<0){this.value='1';}">
      </div>
    </div>
    <div class="form-group">
      <label for="returnable" class="col-sm-3 control-label">
        <?php echo trans('label_returnable'); ?>
      </label>
      <div class="col-sm-8">
        <select class="form-control select2" name="returnable">
          <option value="no" <?php echo $expense['returnable'] == 'no' ? 'selected' : null;?>>
            <?php echo trans('text_no'); ?>
          </option>
          <option value="yes" <?php echo $expense['returnable'] == 'yes' ? 'selected' : null;?>>
            <?php echo trans('text_yes'); ?>
          </option>
       </select>
      </div>
    </div>
    <div class="form-group">
      <label for="note" class="col-sm-3 control-label">
        <?php echo trans('label_notes'); ?>
      </label>
      <div class="col-sm-8">
        <textarea name="note" id="note" class="form-control"><?php echo $expense['note']; ?></textarea>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-3 control-label"></label>
      <div class="col-sm-8">            
        <button id="edit-expense-update" class="btn btn-info" data-form="#form-expense" data-datatable="#expense-expense-list" name="submit" data-loading-text="Updating...">
          <i class="fa fa-fw fa-pencil"></i>
          <?php echo trans('button_update'); ?>
        </button>
      </div>
    </div>
  </div>
</form>