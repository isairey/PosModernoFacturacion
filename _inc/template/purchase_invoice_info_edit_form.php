<h4 class="sub-title">
  <?php echo trans('text_update_title'); ?>
</h4>

<form class="form-horizontal" id="invoice-form" action="purchase.php" method="post">
  <input type="hidden" id="action_type" name="action_type" value="UPDATEINVOICEINFO">
  <input type="hidden" id="invoice_id" name="invoice_id" value="<?php echo $invoice['invoice_id']; ?>">
  <div class="box-body">
      <div class="form-group">
        <label for="purchase_note" class="col-sm-3 control-label">
          <?php echo trans('label_purchase_note'); ?>
        </label>
        <div class="col-sm-7">
          <textarea class="form-control no-resize" id="purchase_note" name="purchase_note"><?php echo $invoice['purchase_note']; ?></textarea>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label"></label>
        <div class="col-sm-7">
          <button id="invoice-update" data-form="#invoice-form" data-datatable="#invoice-invoice-list" class="btn btn-info" name="btn_edit_invoice" data-loading-text="Updating...">
            <span class="fa fa-fw fa-pencil"></span>
            <?php echo trans('button_update'); ?>
          </button>
        </div>
      </div>
  </div>
</form>