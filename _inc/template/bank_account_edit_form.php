<h4 class="sub-title">
  <?php echo trans('text_update_title'); ?>
</h4>

<form class="form-horizontal" id="account-form" action="bank_account.php" method="post">
  
  <input type="hidden" id="action_type" name="action_type" value="UPDATE">
  <input type="hidden" id="id" name="id" value="<?php echo $account['id']; ?>">

  <div class="box-body">
    
    <div class="form-group">
      <label for="account_name" class="col-sm-4 control-label">
        <?php echo trans('label_account_name'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-7">
        <input type="text" class="form-control" id="account_name" value="<?php echo $account['account_name']; ?>" name="account_name">
      </div>
    </div>

    <div class="form-group">
      <label for="account_details" class="col-sm-4 control-label">
        <?php echo trans('label_account_details'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-7">
        <input type="text" class="form-control" id="account_details" value="<?php echo $account['account_details']; ?>" name="account_details">
      </div>
    </div>

    <div class="form-group">
      <label for="account_no" class="col-sm-4 control-label">
        <?php echo trans('label_account_no'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-7">
        <input type="text" class="form-control" id="account_no" value="<?php echo $account['account_no']; ?>" name="account_no">
      </div>
    </div>

    <div class="form-group">
      <label for="contact_person" class="col-sm-4 control-label">
        <?php echo trans('label_contact_person'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-7">
        <input type="text" class="form-control" id="contact_person" value="<?php echo $account['contact_person']; ?>" name="contact_person">
      </div>
    </div>

    <div class="form-group">
      <label for="phone_number" class="col-sm-4 control-label">
        <?php echo trans('label_phone_number'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-7">
        <input type="text" class="form-control" id="phone_number" value="<?php echo $account['phone_number']; ?>" name="phone_number">
      </div>
    </div>

    <div class="form-group">
      <label for="url" class="col-sm-4 control-label">
        <?php echo trans('label_internal_banking_url'); ?>
      </label>
      <div class="col-sm-7">
        <input type="text" class="form-control" id="url" value="<?php echo $account['url']; ?>" name="url">
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-4 control-label">
        <?php echo trans('label_store'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-7 store-selector">
        <div class="checkbox selector">
          <label>
            <input type="checkbox" onclick="$('input[name*=\'account_store\']').prop('checked', this.checked);"> Select / Deselect
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
                  <input type="checkbox" name="account_store[]" value="<?php echo $the_store['store_id']; ?>" <?php echo in_array($the_store['store_id'], $account['stores']) ? 'checked' : null; ?>>
                  <?php echo $the_store['name']; ?>
                </label>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label for="status" class="col-sm-4 control-label">
        <?php echo trans('label_status'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-7">
        <select id="status" class="form-control" name="status" >
          <option <?php echo isset($account['status']) && $account['status'] == '1' ? 'selected' : null; ?> value="1">
            <?php echo trans('text_active'); ?>
           </option>
          <option <?php echo isset($account['status']) && $account['status'] == '0' ? 'selected' : null; ?> value="0">
            <?php echo trans('text_in_active'); ?>
          </option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="sort_order" class="col-sm-4 control-label">
        <?php echo sprintf(trans('label_sort_order'), null); ?><i class="required">*</i>
      </label>
      <div class="col-sm-7">
        <input type="number" class="form-control" id="sort_order" value="<?php echo $account['sort_order']; ?>" name="sort_order">
      </div>
    </div>

    <div class="form-group">
      <label for="account_address" class="col-sm-4 control-label"></label>
      <div class="col-sm-7">            
        <button id="account-update" class="btn btn-info"  data-form="#account-form" data-datatable="#account-list" name="btn_edit_customer" data-loading-text="Updating...">
          <i class="fa fa-fw fa-pencil"></i>
          <?php echo trans('button_update'); ?>
        </button>
      </div>
    </div>

  </div>
</form>