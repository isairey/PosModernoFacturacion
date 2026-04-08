<form id="form-expense" class="form-horizontal" action="expense.php" method="post" enctype="multipart/form-data">
  <input type="hidden" id="action_type" name="action_type" value="CREATE">
  <input type="hidden" id="sup_id" name="sup_id" value="1">
  <div class="box-body">

    <div class="form-group">
      <label for="image" class="col-sm-3 control-label">
        <?php echo trans('label_attachment'); ?>
      </label>
      <div class="col-sm-7">
        <div class="preview-thumbnail">
          <a ng-click="POSFilemanagerModal({target:'image',thumb:'image_thumb'})" onClick="return false;" href="#" data-toggle="image" id="image_thumb">
            <img src="../assets/itsolution24/img/noimage.jpg">
          </a>
          <input type="hidden" name="image" id="image" value="">
        </div>
      </div>
    </div>

    <div class="form-group">
      <label for="reference_no" class="col-sm-3 control-label">
        <?php echo trans('label_reference_no'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-6">
        <input type="text" class="form-control" id="reference_no" value="" name="reference_no" autofocus autocomplete="off">
      </div>
    </div>

    <div class="form-group">
      <label for="category_id" class="col-sm-3 control-label">
        <?php echo trans('label_category'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-6">
        <select class="form-control select2" name="category_id" id="category_id">
          <option value="">
            <?php echo trans('text_select'); ?>
          </option>
          <?php foreach (get_expense_categorys() as $the_expense_category) { ?>
            <option value="<?php echo $the_expense_category['category_id']; ?>"><?php echo $the_expense_category['category_name'] ; ?></option>
          <?php } ?>
       </select>
      </div>
    </div>

    <div class="form-group">
      <label for="title" class="col-sm-3 control-label">
        <?php echo trans('label_what_for'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-6">
        <input type="text" id="title" class="form-control" name="title">
      </div>
    </div>

    <div class="form-group">
      <label for="amount" class="col-sm-3 control-label">
        <?php echo trans('label_amount'); ?><i class="required">*</i>
       </label>
      <div class="col-sm-6">
        <input type="text" id="amount" class="form-control" name="amount" onclick="this.select();" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onKeyUp="if(this.value<0){this.value='1';}">
      </div>
    </div>

    <div class="form-group">
      <label for="returnable" class="col-sm-3 control-label">
        <?php echo trans('label_returnable'); ?>
      </label>
      <div class="col-sm-6">
        <select class="form-control select2" name="returnable" id="returnable">
          <option value="no">
            <?php echo trans('text_no'); ?>
          </option>
          <option value="yes">
            <?php echo trans('text_yes'); ?>
          </option>
       </select>
      </div>
    </div>

    <div class="form-group">
      <label for="note" class="col-sm-3 control-label">
        <?php echo trans('label_notes'); ?>
      </label>
      <div class="col-sm-6">
        <textarea name="note" id="note" class="form-control"></textarea>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-3 control-label"></label>
      <div class="col-sm-6">            
        <button id="create-expense-submit" class="btn btn-info" data-form="#form-expense" data-datatable="#expense-expense-list" name="submit" data-loading-text="Saving...">
          <i class="fa fa-fw fa-pencil"></i>
          <?php echo trans('button_save'); ?>
        </button>
        <button type="reset" class="btn btn-danger" id="reset" name="reset">
          <span class="fa fa-fw fa-circle-o"></span>
          <?php echo trans('button_reset'); ?>
        </button>
      </div>
    </div>
    
  </div>
</form>