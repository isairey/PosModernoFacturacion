<form id="form-quotation" class="form-horizontal" action="quotation.php" method="post" enctype="multipart/form-data">
  <div class="box-body">

    <div class="form-group">
      <label for="date" class="col-sm-3 control-label">
        <?php echo trans('label_date'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-6">
        <input type="text" class="form-control datepicker" name="date" ng-model="date" autocomplete="off">
      </div>
    </div>

    <div class="form-group">
      <label for="reference_no" class="col-sm-3 control-label">
        <?php echo trans('label_reference_no'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-6">
        <input type="text" class="form-control" id="reference_no" name="reference_no" ng-model="refNo" autocomplete="off">
      </div>
    </div>

    <div class="form-group">
      <label for="quotation-note" class="col-sm-3 control-label">
        <?php echo trans('label_note'); ?>
      </label>
      <div class="col-sm-6">
        <textarea id="quotation-note" class="form-control" name="quotation-note" ng-model="quotationNote"></textarea>
      </div>
    </div>

    <div class="form-group">
      <label for="status" class="col-sm-3 control-label">
        <?php echo trans('label_status'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-6">
        <select id="status" class="form-control" name="status" >
          <option value="sent"><?php echo trans('text_sent'); ?></option>
          <option value="pending"><?php echo trans('text_pending'); ?></option>
          <option value="complete"><?php echo trans('text_complete'); ?></option>
        </select>
      </div>
    </div>

    <div class="well well-sm">
      <div class="form-group">
        <label for="customer_id" class="col-sm-3 control-label">
          <?php echo trans('label_customer'); ?><i class="required">*</i>
        </label>
        <div class="col-sm-6">
          <div class="input-group">
            <select id="customer_id" class="form-control" name="customer_id" >
              <option value=""><?php echo trans('text_select'); ?></option>
              <?php foreach (get_customers(array('exclude' => 1)) as $the_customer) : ?>
                <option value="<?php echo $the_customer['customer_id'];?>">
                <?php echo $the_customer['customer_name'];?>
              </option>
            <?php endforeach;?>
            </select>
            <div class="input-group-addon no-print edit-customer">
              <a id="edit_customer" href="customer.php">
              <i class="fa fa-pencil font12em" id="addIcon"></i>
              </a>
            </div>
            <div class="input-group-addon no-print view-customer-button">
              <button id="view_customer " class="btn btn-xs">
              <i class="fa fa-eye font12em" id="addIcon"></i>
              </button>
            </div>
            <div class="input-group-addon no-print add-customer">
              <a id="add_customer" href="customer.php">
              <i class="fa fa-plus-circle font12em" id="addIcon"></i>
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label for="sup_id" class="col-sm-3 control-label">
          <?php echo trans('label_supplier'); ?>
        </label>
        <div class="col-sm-6">
          <select id="sup_id" class="form-control select2" name="sup_id">
            <option value=""><?php echo trans('text_all_suppliers'); ?></option>
            <?php foreach (get_suppliers() as $sup) : ?>
              <option value="<?php echo $sup['sup_id'];?>">
                <?php echo $sup['sup_name'];?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label for="image" class="col-sm-3 control-label">
          <?php echo trans('label_add_product'); ?>
        </label>
        <div class="col-sm-6">
          <div class="input-group wide-tip">
            <div class="input-group-addon paddinglr-10">
              <i class="fa fa-barcode addIcon fa-2x"></i>
            </div>
            <input type="text" name="add_item" value="" class="form-control input-lg autocomplete-product" id="add_item" data-type="p_name" onkeypress="return event.keyCode != 13;" onclick="this.select();" placeholder="<?php echo trans('placeholder_search_product'); ?>" autocomplete="off" tabindex="1">
            <div class="input-group-addon paddinglr-10">
              <a id="add_new_product" href="product.php">
                <i class="fa fa-plus-circle addIcon fa-2x" id="addIcon"></i>
              </a>
            </div>
          </div>
        </div>  
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="table-responsive">
            <table id="product-table" class="table table-striped table-bordered">
              <thead>
                <tr class="bg-info">
                  <th class="w-30 text-center">
                    <?php echo trans('label_product'); ?>
                  </th>
                  <th class="w-15 text-center">
                    <?php echo trans('label_available'); ?>
                  </th>
                  <th class="w-10 text-center">
                    <?php echo trans('label_quantity'); ?>
                  </th>
                  <th class="w-15 text-center">
                    <?php echo trans('label_sell_price'); ?>
                  </th>
                  <th class="w-10 text-center">
                    <?php echo trans('label_item_tax'); ?>
                  </th>
                  <th class="w-15 text-center">
                    <?php echo trans('label_subtotal'); ?>
                  </th>
                  <th class="w-5 text-center">
                    <i class="fa fa-trash-o"></i>
                  </th>
                </tr>
              </thead>
              <tbody>   
              </tbody>
              <tfoot>
                <tr class="bg-gray">
                  <th class="text-right" colspan="5">
                    <?php echo trans('label_subtotal'); ?>
                  </th>
                  <th class="col-sm-2 text-right">
                    <input id="total-tax" type="hidden" name="total-tax" ng-model="totalTax">
                    <input id="total-amount" type="hidden" name="total-amount" ng-model="totalAmount">
                    <span id="total-amount-view">{{ totalAmountView }}</span>
                  </th>
                  <th class="w-25p">&nbsp;</th>
                </tr>
                <tr class="bg-gray">
                  <th class="text-right" colspan="5">
                    <?php echo trans('label_order_tax'); ?> (%)
                  </th>
                  <th class="col-sm-2 text-right">
                    <input ng-change="addOrderTax();" id="order-tax" class="text-right p-5" type="taxt" name="order-tax" ng-model="orderTax" onclick="this.select();" ondrop="return false;" onkeypress="return IsNumeric(event);" onpaste="return false;" autocomplete="off">
                  </th>
                  <th class="w-25p">&nbsp;</th>
                </tr>
                <tr class="bg-gray">
                  <th class="text-right" colspan="5">
                    <?php echo trans('label_shipping_charge'); ?>
                  </th>
                  <th class="col-sm-2 text-right">
                    <input ng-change="addShippingAmount();" id="shipping-amount" class="text-right p-5" type="taxt" name="shipping-amount" ng-model="shippingAmount" onclick="this.select();" ondrop="return false;" onkeypress="return IsNumeric(event);" onpaste="return false;" autocomplete="off">
                  </th>
                  <th class="w-25p">&nbsp;</th>
                </tr>
                <tr class="bg-gray">
                  <th class="text-right" colspan="5">
                    <?php echo trans('label_others_charge'); ?>
                  </th>
                  <th class="col-sm-2 text-right">
                    <input ng-change="addOthersCharge();" id="others-charge" class="text-right p-5" type="taxt" name="others-charge" ng-model="othersCharge" onclick="this.select();" ondrop="return false;" onkeypress="return IsNumeric(event);" onpaste="return false;" autocomplete="off">
                  </th>
                  <th class="w-25p">&nbsp;</th>
                </tr>
                <tr class="bg-gray">
                  <th class="text-right" colspan="5">
                    <?php echo trans('label_discount_amount'); ?>
                  </th>
                  <th class="col-sm-2 text-right">
                    <input ng-change="addDiscountAmount();" id="discount-amount" class="text-right p-5" type="taxt" name="discount-amount" ng-model="discountAmount" onclick="this.select();" ondrop="return false;" onkeypress="return IsNumeric(event);" onpaste="return false;" autocomplete="off">
                  </th>
                  <th class="w-25p">&nbsp;</th>
                </tr>
                <tr class="bg-yellow">
                  <th class="text-right" colspan="5">
                    <?php echo trans('label_payable_amount'); ?>
                  </th>
                  <th class="col-sm-2 text-right">
                    <input type="hidden" name="payable-amount" ng-model="payableAmount" value="{{ payableAmount }}">
                    <h4 class="text-center"><b>{{ payableAmount | formatDecimal:2 }}</b></h4>
                  </th>
                  <th class="w-25p">&nbsp;</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-4 col-sm-offset-3 text-center">            
        <button id="create-quotation-submit" class="btn btn-block btn-lg btn-info" data-form="#form-quotation" data-datatable="#quotation-quotation-list" name="submit" data-loading-text="Saving...">
          <i class="fa fa-fw fa-save"></i>
          <?php echo trans('button_save'); ?>
        </button>
      </div>
      <div class="col-sm-2 text-center">
        <button type="reset" class="btn btn-block btn-lg btn-danger" id="reset" name="reset">
          <span class="fa fa-fw fa-circle-o"></span>
          <?php echo trans('button_reset'); ?>
        </button>
      </div>
    </div>
  </div>
</form>

<script type="text/javascript">
$(document).ready(function() {
  $('.datepicker').datepicker({
    language: langCode,
    format: "yyyy-mm-dd",
    autoclose:true,
    todayHighlight: true
  }).datepicker("setDate",'now');
});
</script>