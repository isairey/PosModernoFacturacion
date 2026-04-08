<form id="create-lang-form" class="form-horizontal" action="language.php" method="post" enctype="multipart/form-data">
  <input type="hidden" id="action_type" name="action_type" value="CREATE">
  <div class="box-body">
    
    <div class="form-group">
      <label for="lang_name" class="col-sm-3 control-label">
        <?php echo sprintf(trans('label_name'), null); ?><i class="required">*</i>
      </label>
      <div class="col-sm-7">
        <input type="text" class="form-control" id="lang_name" name="lang_name" value="" ng-model="langName" required>
      </div>
    </div>

    <div class="form-group">
      <label for="slug" class="col-sm-3 control-label">
        <?php echo trans('label_slug'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-7">
        <input type="text" class="form-control" id="slug" value="{{ langName | strReplace:' ':'_' | lowercase }}" name="slug" required>
      </div>
    </div>

    <div class="form-group">
      <label for="code" class="col-sm-3 control-label">
        <?php echo sprintf(trans('label_code'), null); ?><i class="required">*</i>
      </label>
      <div class="col-sm-7">
        <input type="text" class="form-control" id="code" name="code" value="" required>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-3 control-label"></label>
      <div class="col-sm-7">
        <button class="btn btn-info" id="create-lang-submit" type="submit" name="create-lang-submit" data-form="#create-lang-form" data-loading-text="Saving...">
          <span class="fa fa-fw fa-save"></span>
          <?php echo trans('button_save'); ?>
        </button>
        <button type="reset" class="btn btn-danger" id="reset" name="reset"><span class="fa fa-fw fa-circle-o"></span>
          <?php echo trans('button_reset'); ?>
        </button>
      </div>
    </div>
    
  </div>
</form>