<form id="form-pay" class="form-horizontal" action="loan.php" method="post">
  <input type="hidden" id="action_type" name="action_type" value="PAID">  
  <input type="hidden" id="loan_id" name="loan_id" value="<?php echo $loan['loan_id'];?>">  
  <div class="box-body">
    
    <div class="table-responsive">
      <table class="table table-bordered table-condenced">
        <tbody>
          <tr class="info">
            <th class="text-right w-25"><?php echo trans('label_payable_amount'); ?></th>
            <td><?php echo currency_format($loan['payable']);?></td>
          </tr>
          <tr class="success">
            <th class="text-right w-25"><?php echo trans('label_paid_amount'); ?></th>
            <td><?php echo currency_format($loan['paid']);?></td>
          </tr>
          <tr class="danger">
            <th class="text-right w-25"><?php echo trans('label_due_amount'); ?></th>
            <td><?php echo currency_format($loan['due']);?></td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="form-group">
      <label for="ref_no" class="col-sm-3 control-label">
          <?php echo trans('label_ref_no'); ?>
      </label>
      <div class="col-sm-7">
        <input type="text" class="form-control" id="ref_no" name="ref_no" autocomplete="off">
      </div>
    </div>
    <div class="form-group">
      <label for="paid" class="col-sm-3 control-label">
        <?php echo trans('label_paid'); ?>
       </label>
      <div class="col-sm-7">
        <input type="text" id="paid" class="form-control" name="paid" onclick="this.select();" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onKeyUp="if(this.value<0){this.value='1';}">
      </div>
    </div>
    <div class="form-group">
      <label for="note" class="col-sm-3 control-label">
        <?php echo trans('label_note'); ?>
      </label>
      <div class="col-sm-7">
        <textarea name="note" id="note" class="form-control"></textarea>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-3 control-label"></label>
      <div class="col-sm-7">            
        <button id="pay-confirm-btn" class="btn btn-info" data-form="#form-pay" data-datatable="#loan-loan-list" name="submit" data-loading-text="Paying...">
          <i class="fa fa-fw fa-paper-plane"></i>
          <?php echo trans('button_pay_now'); ?>
        </button>
      </div>
    </div>
  </div>
</form>