<h4 class="sub-title">
  <?php echo trans('text_update_title'); ?>
</h4>

<form class="form-horizontal" id="user-form" action="user.php" method="post">
  
  <input type="hidden" id="action_type" name="action_type" value="UPDATE">
  <input type="hidden" id="id" name="id" value="<?php echo $the_user['id']; ?>">
  
  <div class="box-body">
    <div class="form-group">
      <label class="col-sm-3 control-label">
        <?php echo sprintf(trans('label_image'),null); ?>
      </label>
      <div class="col-sm-2">
        <div class="preview-thumbnail">
          <a ng-click="POSFilemanagerModal({target:'the_user_image',thumb:'the_user_thumb'})" onClick="return false;" href="" data-toggle="image" id="the_user_thumb">
            <?php if (isset($the_user['user_image']) && ((FILEMANAGERPATH && is_file(FILEMANAGERPATH.$the_user['user_image']) && file_exists(FILEMANAGERPATH.$the_user['user_image'])) || (is_file(DIR_STORAGE . 'users' . $the_user['user_image']) && file_exists(DIR_STORAGE . 'users' . $the_user['user_image'])))) : ?>
              <img  src="<?php echo FILEMANAGERURL ? FILEMANAGERURL : root_url().'/storage/users'; ?>/<?php echo $the_user['user_image']; ?>">
            <?php else : ?>
              <img src="../assets/itsolution24/img/noimage.jpg">
            <?php endif; ?>
          </a>
          <input type="hidden" name="user_image" id="the_user_image" value="<?php echo isset($the_user['user_image']) ? $the_user['user_image'] : null; ?>">
        </div>
      </div>
    </div>

    <div class="form-group">
      <label for="username" class="col-sm-3 control-label">
        <?php echo sprintf(trans('label_name'), null); ?><i class="required">*</i>
      </label>
      <div class="col-sm-7">
        <input type="text" class="form-control" id="username" value="<?php echo $the_user['username']; ?>" name="username" required>
      </div>
    </div>

    <div class="form-group">
      <label for="email" class="col-sm-3 control-label">
        <?php echo sprintf(trans('label_email'), null); ?><i class="required">*</i>
      </label>
      <div class="col-sm-7">
        <input type="email" class="form-control" id="email" value="<?php echo $the_user['email']; ?>" name="email">
      </div>
    </div>

    <div class="form-group">
      <label for="mobile" class="col-sm-3 control-label">
        <?php echo trans('label_mobile'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-7">
        <input type="text" class="form-control" id="mobile" value="<?php echo $the_user['mobile']; ?>" name="mobile">
      </div>
    </div>

    <div class="form-group">
      <label for="group_id" class="col-sm-3 control-label">
        <?php echo trans('label_group'); ?><i class="required">*</i>
        <span data-toggle="tooltip" title="" data-original-title="<?php echo trans('hint_group'); ?>"></span>    
      </label>
      <div class="col-sm-7">
        <select class="form-control" name="group_id" required>
          <option value="">
            <?php echo trans('text_select'); ?>
          </option>
          <?php foreach (get_usergroups() as $group) { ?>
              <?php if($group['group_id'] == $the_user['group_id']) : ?>
                <option value="<?php echo $group['group_id']; ?>" selected><?php echo $group['name'] ; ?></option>
              <?php else: ?>
                <option value="<?php echo $group['group_id']; ?>"><?php echo $group['name'] ; ?></option>
              <?php endif; ?>
          <?php } ?>
       </select>
      </div>
    </div>

    <div class="form-group">
      <label for="dob" class="col-sm-3 control-label">
        <?php echo sprintf(trans('label_date_of_birth'), null); ?>
      </label>
      <div class="col-sm-7">
        <input type="date" class="form-control" id="dob" value="<?php echo $the_user['dob']; ?>" name="dob" autocomplete="off">
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-3 control-label">
        <?php echo trans('label_store'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-7 store-selector">
        <div class="checkbox selector">
          <label>
            <input type="checkbox" onclick="$('input[name*=\'user_store\']').prop('checked', this.checked);"> Select / Deselect
          </label>
        </div>
        <div class="filter-searchbox">
          <input ng-model="search_store" class="form-control" type="text" id="search_store" placeholder="<?php echo trans('search'); ?>">
        </div>
        <div class="well well-sm store-well">
          <div filter-list="search_store">
            <?php foreach(get_stores() as $the_store) : ?>                    
              <div class="checkbox">
                <label>                         
                  <input type="checkbox" name="user_store[]" value="<?php echo $the_store['store_id']; ?>" <?php echo in_array($the_store['store_id'], $the_user['stores']) ? 'checked' : null; ?>>
                  <?php echo $the_store['name']; ?>
                </label>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label for="status" class="col-sm-3 control-label">
        <?php echo trans('label_status'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-7">
        <select id="status" class="form-control" name="status" >
          <option <?php echo isset($the_user['status']) && $the_user['status'] == '1' ? 'selected' : null; ?> value="1">
            <?php echo trans('text_active'); ?>
          </option>
          <option <?php echo isset($the_user['status']) && $the_user['status'] == '0' ? 'selected' : null; ?> value="0">
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
        <input type="number" class="form-control" id="sort_order" value="<?php echo $the_user['sort_order']; ?>" name="sort_order">
      </div>
    </div>

    <div class="form-group">
      <label for="user_address" class="col-sm-3 control-label"></label>
      <div class="col-sm-7">
        <button id="user-update" data-form="#user-form" data-datatable="#user-user-list" class="btn btn-info" name="btn_edit_user" data-loading-text="Updating...">
          <span class="fa fa-fw fa-pencil"></span> 
          <?php echo trans('button_update'); ?>
        </button>
      </div>
    </div>
    
  </div>
</form>