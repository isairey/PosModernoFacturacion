<form class="form-horizontal" id="payment-form" action="installment.php" method="post">
  <input type="hidden" id="action_type" name="action_type" value="PAY">
  <input type="hidden" id="id" name="id" value="<?php echo $payment['id']; ?>">
  <div class="box-body">
      <div class="form-group">
        <label for="amount" class="col-sm-3 control-label">
          <?php echo trans('label_amount'); ?>
        </label>
        <div class="col-sm-7">
          <input type="text" class="form-control no-resize" id="amount" name="amount" value="<?php echo $payment['due']; ?>" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onKeyUp="if(this.value<0){this.value='1';}" required>
        </div>
      </div>
      <div class="form-group">
        <label for="note" class="col-sm-3 control-label">
          <?php echo trans('label_note'); ?>
        </label>
        <div class="col-sm-7">
          <textarea class="form-control no-resize" id="note" name="note"><?php echo $payment['note']; ?></textarea>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label"></label>
        <div class="col-sm-7">
          <button id="payment-update" data-form="#payment-form" data-datatable="#payment-payment-list" class="btn btn-info" name="btn_edit_payment" data-loading-text="Updating...">
            <span class="fa fa-fw fa-money"></span>
            <?php echo trans('button_pay_now'); ?>
          </button>
        </div>
      </div>
  </div>
</form>