<form id="loan-edit-form" class="form-horizontal" action="loan.php" method="post" enctype="multipart/form-data">
  
  <input type="hidden" id="action_type" name="action_type" value="UPDATE">
  <input type="hidden" id="loan_id" name="loan_id" value="<?php echo $loan['loan_id']; ?>">
  
  <div class="box-body">

    <div class="form-group">
      <label for="loan_from" class="col-sm-3 control-label">
        <?php echo trans('label_loan_from'); ?>
      </label>
      <div class="col-sm-8">
        <select class="form-control select2" name="loan_from">
          <option value="">
            <?php echo trans('text_select'); ?>
          </option>
          <option value="bank" <?php echo $loan['loan_from'] == 'bank' ? 'selected' : null;?>>Bank</option>
          <option value="ngo" <?php echo $loan['loan_from'] == 'ngo' ? 'selected' : null;?>>NGO</option>
          <option value="person" <?php echo $loan['loan_from'] == 'person' ? 'selected' : null;?>>Person</option>
          <option value="others" <?php echo $loan['loan_from'] == 'others' ? 'selected' : null;?>>Others</option>
       </select>
      </div>
    </div>

    <div class="form-group">
      <label for="ref_no" class="col-sm-3 control-label">
        <?php echo trans('label_ref_no'); ?>
      </label>
      <div class="col-sm-8">
        <input type="text" class="form-control" id="ref_no" value="<?php echo $loan['ref_no'];?>" name="ref_no" autocomplete="off">
      </div>      
    </div>

    <div class="form-group">
      <label for="title" class="col-sm-3 control-label">
        <?php echo trans('label_title'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-8">
        <input type="text" id="title" class="form-control" name="title" value="<?php echo $loan['title'];?>">
      </div>
    </div>

    <div class="form-group">
      <label for="details" class="col-sm-3 control-label">
        <?php echo trans('label_details'); ?>
      </label>
      <div class="col-sm-8">
        <textarea name="details" id="details" class="form-control"><?php echo $loan['details'];?></textarea>
      </div>
    </div>

    <div class="form-group hidden">
      <label for="status" class="col-sm-3 control-label">
        <?php echo trans('label_status'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-8">
        <select id="status" class="form-control" name="status" >
          <option <?php echo isset($loan['status']) && $loan['status'] == '1' ? 'selected' : null; ?> value="1">
            <?php echo trans('text_active'); ?>
           </option>
          <option <?php echo isset($loan['status']) && $loan['status'] == '0' ? 'selected' : null; ?> value="0">
            <?php echo trans('text_in_active'); ?>
          </option>
        </select>
      </div>
    </div>

    <div class="form-group hidden">
      <label for="sort_order" class="col-sm-3 control-label">
        <?php echo sprintf(trans('label_sort_order'), null); ?><i class="required">*</i>
      </label>
      <div class="col-sm-8">
        <input type="text" class="form-control" id="sort_order" value="<?php echo $loan['sort_order']; ?>" name="sort_order" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onKeyUp="if(this.value<0){this.value='1';}">
      </div>
    </div>

    <div class="form-group">
      <label for="image" class="col-sm-3 control-label">
        <?php echo trans('label_attachment'); ?>
      </label>
      <div class="col-sm-8">
        <div class="preview-thumbnail">
          <a ng-click="POSFilemanagerModal({target:'image',thumb:'image_thumb'})" onClick="return false;" href="#" data-toggle="image" id="image_thumb">
            <?php if (isset($loan['attachment']) && ((FILEMANAGERPATH && is_file(FILEMANAGERPATH.$loan['attachment']) && file_exists(FILEMANAGERPATH.$loan['attachment'])) || (is_file(DIR_STORAGE . 'products' . $loan['attachment']) && file_exists(DIR_STORAGE . 'products' . $loan['attachment'])))) : ?>
              <img  src="<?php echo FILEMANAGERURL ? FILEMANAGERURL : root_url().'/storage/products'; ?>/<?php echo $loan['attachment']; ?>" width="40" height="50">
            <?php else : ?>
              <img src="../assets/itsolution24/img/noimage.jpg" width="40" height="50">
            <?php endif; ?>
          </a>
          <input type="hidden" name="image" id="image" value="<?php echo $loan['attachment'];?>">
        </div>
      </div>
    </div>

    <div class="form-group">
      <label for="box_address" class="col-sm-3 control-label"></label>
      <div class="col-sm-8">            
        <button id="loan-update" class="btn btn-block btn-info"  data-form="#loan-edit-form" data-datatable="#loan-loan-list" name="btn_edit_loan" data-loading-text="Updating...">
          <i class="fa fa-fw fa-pencil"></i>
          <?php echo trans('button_update'); ?>
        </button>
      </div>
    </div>
    
  </div>
</form>