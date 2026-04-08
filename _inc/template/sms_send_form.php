<form id="sms-send-form" class="form-horizontal" action="sms/index.php" method="post">
  <input type="hidden" id="action_type" name="action_type" value="SENDINDIVIDUAL">
  <div class="box-body">
    <div class="form-group">
      <label for="phone_number" class="col-sm-2 control-label">
        <?php echo trans('label_phone_number'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-6">
        <input type="text" class="form-control" id="phone_number" name="phone_number" required>
      </div>
    </div>
    <div class="form-group">
      <label for="message" class="col-sm-2 control-label">
        <?php echo trans('label_message'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-6">
        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
        <i>Max character: 160</i>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label"></label>
      <div class="col-sm-6">
        <button class="btn btn-block btn-info" id="sms-send-btn" type="submit" data-form="#sms-send-form" data-loading-text="Sending...">
          <span class="fa fa-fw fa-paper-plane"></span>
          <?php echo trans('button_send'); ?>
        </button>  
      </div>
    </div>
  </div>
</form>