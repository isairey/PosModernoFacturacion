<form id="form-purchase" class="form-horizontal" action="purchase.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="action_type" value="CREATE">
  <div class="box-body">
    <div class="form-group">
      <label for="date" class="col-sm-3 control-label">
        <?php echo trans('label_date'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-6">
        <input type="text" class="form-control datepicker" name="date" autocomplete="off">
      </div>
    </div>

    <div class="form-group">
      <label for="reference_no" class="col-sm-3 control-label">
        <?php echo trans('label_reference_no'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-6">
        <input type="text" class="form-control" id="reference_no" name="reference_no" autocomplete="off">
      </div>
    </div>

    <div class="form-group">
      <label for="purchase-note" class="col-sm-3 control-label">
        <?php echo trans('label_note'); ?>
      </label>
      <div class="col-sm-6">
        <textarea id="purchase-note" class="form-control" name="purchase-note"></textarea>
      </div>
    </div>

    <div class="form-group hide">
      <label for="status" class="col-sm-3 control-label">
        <?php echo trans('label_status'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-6">
        <select id="status" class="form-control" name="status" >
          <option value="received"><?php echo trans('text_received'); ?></option>
          <option value="pending"><?php echo trans('text_pending'); ?></option>
          <option value="ordered"><?php echo trans('text_ordered'); ?></option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="add_attachment" class="col-sm-3 control-label">
        <?php echo trans('label_attachment'); ?>
      </label>
      <div class="col-sm-7">
        <div class="preview-thumbnail">
          <a ng-click="POSFilemanagerModal({target:'image',thumb:'image_thumb'})" onClick="return false;" href="#" data-toggle="image" id="image_thumb">
            <img src="../assets/itsolution24/img/noimage.jpg">
          </a>
          <input type="hidden" name="image" id="image" value="">
        </div>
      </div>
    </div>

    <div class="well well-sm">

      <div class="form-group sup-id-selector">
        <label for="sup_id" class="col-sm-3 control-label">
          <?php echo trans('label_supplier'); ?><i class="required">*</i>
        </label>
        <div class="col-sm-6">
          <select id="sup_id" class="form-control select2" name="sup_id">
            <option value=""><?php echo trans('text_select'); ?></option>
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
            <table id="product-table" class="table table-striped table-bordered mb-0">
              <thead>
                <tr class="bg-info">
                  <th class="w-35 text-center">
                    <?php echo trans('label_product'); ?>
                  </th>
                  <th class="w-10 text-center">
                    <?php echo trans('label_available'); ?>
                  </th>
                  <th class="w-10 text-center">
                    <?php echo trans('label_quantity'); ?>
                  </th>
                  <th class="w-10 text-center">
                    <?php echo trans('label_cost'); ?>
                  </th>
                  <th class="w-10 text-center">
                    <?php echo trans('label_sell_price'); ?>
                  </th>
                  <th class="w-10 text-center">
                    <?php echo trans('label_item_tax'); ?>
                  </th>
                  <th class="w-10 text-center">
                    <?php echo trans('label_item_total'); ?>
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
                  <th class="text-right" colspan="6">
                    <?php echo trans('label_subtotal'); ?>
                  </th>
                  <th class="col-sm-2 text-right">
                    <input id="total-tax" type="hidden" name="total-tax" value="">
                    <input id="total-amount" type="hidden" name="total-amount" value="">
                    <span id="total-amount-view">0.00</span>
                  </th>
                  <th class="w-25p">&nbsp;</th>
                </tr>
                <tr class="bg-gray">
                  <th class="text-right" colspan="6">
                    <?php echo trans('label_order_tax');?> (%)
                  </th>
                  <th class="col-sm-2 text-right">
                    <input ng-change="addOrderTax();" id="order-tax" class="text-right p-5" type="taxt" name="order-tax" ng-model="orderTax" onclick="this.select();" ondrop="return false;" onkeypress="return IsNumeric(event);" onpaste="return false;" autocomplete="off">
                  </th>
                  <th class="w-25p">&nbsp;</th>
                </tr>
                <tr class="bg-gray">
                  <th class="text-right" colspan="6">
                    <?php echo trans('label_shipping_charge'); ?>
                  </th>
                  <th class="col-sm-2 text-right">
                    <input ng-change="addShippingAmount();" id="shipping-amount" class="text-right p-5" type="taxt" name="shipping-amount" ng-model="shippingAmount" onclick="this.select();" ondrop="return false;" onkeypress="return IsNumeric(event);" onpaste="return false;" autocomplete="off">
                  </th>
                  <th class="w-25p">&nbsp;</th>
                </tr>
                <tr class="bg-gray">
                  <th class="text-right" colspan="6">
                    <?php echo trans('label_others_charge'); ?>
                  </th>
                  <th class="col-sm-2 text-right">
                    <input ng-change="addOthersCharge();" id="others-charge" class="text-right p-5" type="taxt" name="others-charge" ng-model="othersCharge" onclick="this.select();" ondrop="return false;" onkeypress="return IsNumeric(event);" onpaste="return false;" autocomplete="off">
                  </th>
                  <th class="w-25p">&nbsp;</th>
                </tr>
                <tr class="bg-gray">
                  <th class="text-right" colspan="6">
                    <?php echo trans('label_discount_amount'); ?>
                  </th>
                  <th class="col-sm-2 text-right">
                    <input ng-change="addDiscountAmount();" id="discount-amount" class="text-right p-5" type="taxt" name="discount-amount" ng-model="discountAmount" onclick="this.select();" ondrop="return false;" onkeypress="return IsNumeric(event);" onpaste="return false;" autocomplete="off">
                  </th>
                  <th class="w-25p">&nbsp;</th>
                </tr>
                <tr class="bg-yellow">
                  <th class="text-right" colspan="6">
                    <?php echo trans('label_payable_amount'); ?>
                  </th>
                  <th class="col-sm-2 text-right">
                    <input type="hidden" name="payable-amount" value="{{ payableAmount }}">
                    <h4 class="text-center"><b>{{ payableAmount | formatDecimal:2 }}</b></h4>
                  </th>
                  <th class="w-25p">&nbsp;</th>
                </tr>
                <tr class="bg-blue">
                  <th class="text-right" colspan="6">
                    <?php echo trans('label_payment_method'); ?>
                  </th>
                  <th class="col-sm-2 text-center">
                    <select id="pmethod-id" class="form-control select2" name="pmethod-id">
                      <?php foreach (get_pmethods() as $pmethod):?>
                        <option value="<?php echo $pmethod['pmethod_id'];?>"><?php echo $pmethod['name'];?></option>
                      <?php endforeach;?>
                    </select>
                  </th>
                  <th class="w-25p">&nbsp;</th>
                </tr>
                <tr class="bg-blue">
                  <th class="text-right" colspan="6">
                    <?php echo trans('label_paid_amount'); ?>
                  </th>
                  <th class="col-sm-2 text-right">
                    <input ng-change="addPaidAmount();" id="paid-amount" class="text-center paidAmount" type="taxt" name="paid-amount" ng-model="paidAmount" onclick="this.select();" ondrop="return false;" onkeypress="return IsNumeric(event);" onpaste="return false;" autocomplete="off">
                  </th>
                  <th class="w-25p">&nbsp;</th>
                </tr>
                <tr class="bg-gray">
                  <th colspan="2" class="w-10 text-right">
                    <?php echo trans('label_due_amount'); ?>
                  </th>
                  <th colspan="4" class="w-70 bg-red text-center">
                    <input type="hidden" name="due-amount" value="{{ dueAmount }}">
                    <span>{{ dueAmount | formatDecimal:2 }}</span>
                  </th>
                  <th colspan="2">&nbsp;</th>
                </tr>
                <tr class="bg-gray">
                  <th colspan="2" class="w-10 text-right">
                    <?php echo trans('label_change_amount'); ?>
                  </th>
                  <th colspan="4" class="w-70 bg-green text-center">
                    <input type="hidden" name="change-amount" value="{{ changeAmount }}">
                    <span>{{ changeAmount | formatDecimal:2 }}</span>
                  </th>
                  <th colspan="2">&nbsp;</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="col-sm-4 col-sm-offset-3 text-center">            
        <button id="create-purchase-submit" class="btn btn-block btn-lg btn-info" data-form="#form-purchase" data-datatable="#purchase-purchase-list" name="submit" data-loading-text="Processing...">
          <i class="fa fa-fw fa-save"></i>
          <?php echo trans('button_submit'); ?>
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