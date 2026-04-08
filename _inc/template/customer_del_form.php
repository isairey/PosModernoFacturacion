<h4 class="sub-title">
  <?php echo trans('text_delete_title'); ?>
</h4>
<form class="form" id="customer-del-form" action="customer.php" method="post">
  <input type="hidden" id="action_type" name="action_type" value="DELETE">
  <input type="hidden" id="customer_id" name="customer_id" value="<?php echo $customer['customer_id']; ?>">
  <h4 class="box-title text-center">
    <?php echo trans('text_delete_instruction'); ?>
  </h4>
  <div class="box-body">
    <div class="row">
      <div class="col-sm-6 col-sm-offset-3">
        <label for="insert_to" class="control-label">
          <?php echo trans('label_insert_content_into'); ?>
        </label>
        <div class="radio">
          <input type="radio" id="insert_to" value="insert_to" name="delete_action" checked="checked">
          <select name="new_customer_id" class="form-control">
            <option value="">
              <?php echo trans('text_select'); ?>
            </option>
            <?php foreach (get_customers() as $the_customer) : ?>
              <?php if($the_customer['customer_id'] == $customer['customer_id']) continue ?>
              <option value="<?php echo $the_customer['customer_id']; ?>">
                <?php echo $the_customer['customer_name']; ?>
              </option>
            <?php endforeach; ?>
          </select> 
        </div>
        <br>
        <button id="customer-delete" data-form="#customer-del-form" data-datatable="#customer-customer-list" class="btn btn-block btn-danger" name="btn_edit_customer" data-loading-text="Deleting...">
          <span class="fa fa-fw fa-trash"></span>
          <?php echo trans('button_delete'); ?>
        </button>
        <br>
      </div>
    </div>
  </div>
</form>