<form id="send-form" class="form form-horizontal" action="sms/index.php" method="post">
  <input type="hidden" id="invoice_id" name="invoice_id" value="<?php echo $invoice_id; ?>">
  <input type="hidden" id="action_type" name="action_type" value="SEND">
  <div class="box-body">
    <div class="form-group">
      <label for="phone_number" class="col-sm-3 control-label">
        <?php echo trans('label_phone_number'); ?>
      </label>
      <div class="col-sm-8">
        <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo $invoice['customer_mobile'] ? $invoice['customer_mobile'] : $invoice['mobile_number'];?>" required>
      </div>
    </div>
    <div class="form-group">
      <label for="message" class="col-sm-3 control-label">
        <?php echo trans('label_message'); ?>
      </label>
      <div class="col-sm-8">
        <textarea class="form-control" id="message" name="message" rows="5" required><?php echo trans('invoice_sms_text'); ?></textarea>
        <p class="mt-5"><?php echo $tags; ?></p>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-3 control-label"></label>
      <div class="col-sm-8">
        <button id="send" data-form="#send-form" class="btn btn-block btn-info" data-loading-text="Sending...">
          <span class="fa fa-fw fa-paper-plane"></span>
          <?php echo trans('button_send'); ?>
        </button>
      </div>
    </div>
  </div>
</form>