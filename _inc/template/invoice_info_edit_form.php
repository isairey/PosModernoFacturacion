<h4 class="sub-title">
  <?php echo trans('text_update_title'); ?>
</h4>

<div class="table-responsive">
  <table class="table table-striped table-bordered table-condensed mb-0">
    <tbody>
      <tr>
        <td class="w-40 bg-gray text-right">
          <?php echo trans('label_customer'); ?>
        </td>
        <td class="w-60">
          <?php echo get_the_customer($invoice['customer_id'],'customer_name');?>
        </td>
      </tr>
      <tr>
        <td class="w-40 bg-gray text-right">
          <?php echo trans('label_subtotal'); ?>
        </td>
        <td class="w-60">
          <?php echo currency_format($invoice['subtotal']);?>
        </td>
      </tr>
      <tr>
        <td class="w-40 bg-gray text-right">
          <?php echo trans('label_discount'); ?>
        </td>
        <td class="w-60">
          <?php echo currency_format($invoice['discount_amount']);?>
        </td>
      </tr>
      <tr>
        <td class="w-40 bg-gray text-right">
          <?php echo trans('label_invoice_amount'); ?>
        </td>
        <td class="w-60">
          <?php echo currency_format($invoice['payable_amount']);?>
        </td>
      </tr>
      <tr>
        <td class="w-40 bg-gray text-right">
          <?php echo trans('label_paid_amount'); ?>
        </td>
        <td class="w-60">
          <?php echo currency_format($invoice['paid_amount']);?>
        </td>
      </tr>
      <tr>
        <td class="w-40 bg-gray text-right">
          <?php echo trans('label_due'); ?>
        </td>
        <td class="w-60">
          <?php echo currency_format($invoice['due']);?>
        </td>
      </tr>      
    </tbody>
  </table>
</div>

<form class="form-horizontal" id="invoice-form" action="invoice.php" method="post">
  <input type="hidden" id="action_type" name="action_type" value="UPDATEINVOICEINFO">
  <input type="hidden" id="invoice_id" name="invoice_id" value="<?php echo $invoice['invoice_id']; ?>">
  <div class="box-body">

      <div class="form-group">
        <label for="customer_mobile" class="col-sm-3 control-label">
          <?php echo trans('label_customer_mobile'); ?>
        </label>
        <div class="col-sm-7">
          <input type="text" class="form-control" id="customer_mobile" value="<?php echo $invoice['customer_mobile']; ?>" name="customer_mobile" required>
        </div>
      </div>

      <div class="form-group">
        <label for="invoice_note" class="col-sm-3 control-label">
          <?php echo trans('label_invoice_note'); ?>
        </label>
        <div class="col-sm-7">
          <textarea class="form-control no-resize" id="invoice_note" name="invoice_note"><?php echo $invoice['invoice_note']; ?></textarea>
        </div>
      </div>

      <div class="form-group">
        <label for="status" class="col-sm-3 control-label">
          <?php echo trans('label_status'); ?>
        </label>
        <div class="col-sm-7">
          <select id="status" class="form-control" name="status" >
            <option <?php echo isset($invoice['status']) && $invoice['status'] == '1' ? 'selected' : null; ?> value="1">
              <?php echo trans('text_active'); ?>
            </option>
            <option <?php echo isset($invoice['status']) && $invoice['status'] == '0' ? 'selected' : null; ?> value="0">
              <?php echo trans('text_inactive'); ?>
            </option>
          </select>
        </div>
      </div>

      <!-- <div class="form-group">
        <label for="discount_amount" class="col-sm-3 control-label">
          <?php echo trans('label_discount_amount'); ?>
        </label>
        <div class="col-sm-7">
          <input type="text" class="form-control" id="discount_amount" value="<?php echo currency_format($invoice['discount_amount']); ?>" name="discount_amount" onClick="this.select();" onclick="this.select();" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onKeyUp="if(this.value<0){this.value='1';}" required>
        </div>
      </div> -->

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