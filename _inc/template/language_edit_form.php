<form id="form-language-edit" class="form-horizontal" action="language.php" method="post" enctype="multipart/form-data">
  <input type="hidden" id="action_type" name="action_type" value="UPDATE">
  <input type="hidden" id="lang_id" name="lang_id" value="<?php echo $lang_id; ?>">
  <div class="box-body">
    <div class="form-group">
      <label for="lang_name" class="col-sm-3 control-label">
        <?php echo sprintf(trans('label_name'), null); ?><i class="required">*</i>
     </label>
      <div class="col-sm-8">
        <input type="text" class="form-control" id="lang_name" value="<?php echo $lang['name']; ?>" name="lang_name" ng-init="brandName='<?php echo $lang['name']; ?>'" value="<?php echo $lang['name']; ?>" ng-model="brandName">
      </div>
    </div>
    <div class="form-group">
      <label for="slug" class="col-sm-3 control-label">
        <?php echo trans('label_slug'); ?><i class="required">*</i>
     </label>
      <div class="col-sm-8">
        <input type="text" class="form-control" id="slug" value="<?php echo $lang['slug'] ? $lang['slug'] : "{{ brandName | strReplace:' ':'_' | lowercase }}"; ?>" name="slug" required>
      </div>
    </div>
    <div class="form-group">
      <label for="code" class="col-sm-3 control-label">
        <?php echo sprintf(trans('code'), null); ?><i class="required">*</i>
     </label>
      <div class="col-sm-8">
        <input type="text" class="form-control" id="code" name="code" value="<?php echo $lang['code']; ?>">
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-3 control-label"></label>
      <div class="col-sm-8">            
        <button id="language-update" class="btn btn-info" data-form="#form-language-edit" name="submit" data-loading-text="Updating...">
          <i class="fa fa-fw fa-pencil"></i>
          <?php echo trans('button_update'); ?>
        </button>
      </div>
    </div>
  </div>
</form>