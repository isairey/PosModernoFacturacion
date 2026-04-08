<form id="form-quotation" class="form-horizontal" action="quotation.php" method="post" enctype="multipart/form-data">
  <div class="box-body">

    <div class="form-group">
      <label for="date" class="col-sm-3 control-label">
        <?php echo trans('label_date'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-6">
        <input type="date" id="date" class="form-control" name="date" value="<?php echo date('Y-m-d',strtotime($quotation['created_at']));?>" autocomplete="off">
      </div>
    </div>

    <div class="form-group">
      <label for="reference_no" class="col-sm-3 control-label">
        <?php echo trans('label_reference_no'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-6">
        <input type="text" class="form-control" id="reference_no" name="reference_no" value="<?php echo $quotation['reference_no'];?>" autocomplete="off">
      </div>
    </div>

    <div class="form-group">
      <label for="order-tax" class="col-sm-3 control-label">
        <?php echo trans('label_order_tax'); ?>
      </label>
      <div class="col-sm-6">
        <input type="text" class="form-control" id="order-tax" name="order-tax" value="<?php echo $quotation['order_tax'];?>" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onKeyUp="if(this.value<0){this.value='1';}">
      </div>
    </div>

    <div class="form-group">
      <label for="shipping-amount" class="col-sm-3 control-label">
        <?php echo trans('label_shipping_amount'); ?>
      </label>
      <div class="col-sm-6">
        <input type="text" id="shipping-amount" class="form-control" name="shipping-amount" value="<?php echo $quotation['shipping_amount'];?>" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onKeyUp="if(this.value<0){this.value='1';}">
      </div>
    </div>

    <div class="form-group">
      <label for="discount-amount" class="col-sm-3 control-label">
        <?php echo trans('label_discount_amount'); ?>
      </label>
      <div class="col-sm-6">
        <input type="text" id="discount-amount" class="form-control" name="discount-amount" value="<?php echo $quotation['discount_amount'];?>" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onKeyUp="if(this.value<0){this.value='1';}">
      </div>
    </div>

    <div class="form-group">
      <label for="quotation-note" class="col-sm-3 control-label">
        <?php echo trans('label_note'); ?>
      </label>
      <div class="col-sm-6">
        <textarea id="quotation-note" class="form-control" name="quotation-note"><?php echo $quotation['quotation_note'];?></textarea>
      </div>
    </div>

    <div class="form-group">
      <label for="status" class="col-sm-3 control-label">
        <?php echo trans('label_status'); ?><i class="required">*</i>
      </label>
      <div class="col-sm-6">
        <select id="status" class="form-control" name="status" >
          <option value="sent" <?php echo $quotation['status'] == 'sent' ? 'selected' : null;?>><?php echo trans('text_sent'); ?></option>
          <option value="pending" <?php echo $quotation['status'] == 'pending' ? 'selected' : null;?>><?php echo trans('text_pending'); ?></option>
          <option value="complete" <?php echo $quotation['status'] == 'complete' ? 'selected' : null;?>><?php echo trans('text_complete'); ?></option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="image" class="col-sm-3 control-label">
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

      <div class="form-group">
        <label for="sup_id" class="col-sm-3 control-label">
          <?php echo trans('label_supplier'); ?>
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
        <label for="customer_id" class="col-sm-3 control-label">
          <?php echo trans('label_customer'); ?><i class="required">*</i>
        </label>
        <div class="col-sm-6">
          <div class="input-group">
            <select id="customer_id" class="form-control" name="customer_id" >
              <option value=""><?php echo trans('text_select'); ?></option>
              <?php foreach (get_customers(array('exclude' => 1,'filter_has_giftcard'=>0)) as $the_customer) : ?>
                <option value="<?php echo $the_customer['customer_id'];?>" <?php echo $quotation['customer_id'] == $the_customer['customer_id'] ? 'selected' : null;?>>
                <?php echo $the_customer['customer_name'];?>
              </option>
            <?php endforeach;?>
            </select>
            <div class="input-group-addon no-print" style="padding: 2px 8px; border-left: 0;">
              <a id="edit_customer" href="customer.php">
              <i class="fa fa-pencil" id="addIcon" style="font-size: 1.2em;"></i>
              </a>
            </div>
            <div class="input-group-addon no-print" style="padding: 2px 7px; border-left: 0;">
              <button id="view_customer" class="btn btn-xs" style="background:#F5F5F5;color:#276B92">
              <i class="fa fa-eye" id="addIcon" style="font-size: 1.2em;"></i>
              </button>
            </div>
            <div class="input-group-addon no-print" style="padding: 2px 8px;">
              <a id="add_customer" href="customer.php">
              <i class="fa fa-plus-circle" id="addIcon" style="font-size: 1.2em;"></i>
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label for="image" class="col-sm-3 control-label">
          <?php echo trans('label_add_product'); ?>
        </label>
        <div class="col-sm-6">
          <div class="input-group wide-tip">
            <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
              <i class="fa fa-barcode addIcon fa-2x"></i>
            </div>
            <input type="text" name="add_item" value="" class="form-control input-lg autocomplete-product" id="add_item" data-type="p_name" onkeypress="return event.keyCode != 13;" onclick="this.select();" placeholder="Please add products to list" autocomplete="off" tabindex="1">
            <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
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
                    <?php echo trans('label_unit_price'); ?>
                  </th>
                  <th class="w-10 text-center">
                    <?php echo trans('label_product_tax'); ?>
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
                <?php foreach ($quotation_items as $item):?>
                  <tr id="<?php echo $item['item_id'];?>" class="58" data-item-id="<?php echo $item['item_id'];?>">
                    <td class="text-center" style="min-width:100px;" data-title="Product Name">
                      <input name="products[<?php echo $item['item_id'];?>][item_id]" type="hidden" class="item-id" value="<?php echo $item['item_id'];?>">
                      <input name="products[<?php echo $item['item_id'];?>][item_name]" type="hidden" class="item-name" value="<?php echo $item['item_name'];?>">
                      <input name="products[<?php echo $item['item_id'];?>][category_id]" type="hidden" class="categoryid" value="9">
                      <span class="name" id="name-<?php echo $item['item_id'];?>">
                        <?php echo $item['item_name'];?>
                      </span>
                    </td>
                    <td class="text-center"><?php echo currency_format(get_the_product($item['item_id'],'quantity_in_stock'));?></td>
                    <td style="padding:2px;" data-title="Product Name">
                      <input class="form-control input-sm text-center quantity" name="products[<?php echo $item['item_id'];?>][quantity]" type="number" value="<?php echo $item['item_quantity'];?>" data-id="<?php echo $item['item_id'];?>" id="quantity-<?php echo $item['item_id'];?>" onclick="this.select();" onkeyup="if(this.value
                      <=0){this.value=1;}">
                    </td>
                      <td style="padding:2px;min-width:80px;" data-title="Unit Price">
                        <input id="unit-price-<?php echo $item['item_id'];?>" class="form-control input-sm text-center unit-price" type="number" name="products[<?php echo $item['item_id'];?>][unit_price]" value="<?php echo $item['item_price'];?>" data-id="<?php echo $item['item_id'];?>" data-item="<?php echo $item['item_id'];?>" onclick="this.select();" onkeyup="if(this.value
                      <0){this.value=1;}">
                    </td>
                    <td class="text-center" data-title="Tax Amount">
                      <input id="tax-method-<?php echo $item['item_id'];?>" name="products[<?php echo $item['item_id'];?>][tax_method]" type="hidden" value="<?php echo $item['tax_method'];?>">
                      <input id="taxrate-<?php echo $item['item_id'];?>" name="products[<?php echo $item['item_id'];?>][taxrate]" type="hidden" value="<?php echo get_the_taxrate($item['taxrate_id'], 'taxrate');?>">
                      <input id="tax-amount-<?php echo $item['item_id'];?>" name="products[<?php echo $item['item_id'];?>][tax_amount]" type="hidden" value="<?php echo $item['item_tax']*$item['item_quantity'];?>">
                      <span class="tax-amount-view"><?php echo currency_format($item['item_tax']*$item['item_quantity']);?></span>
                    </td>
                    <td class="text-right" data-title="Total">
                      <input id="subtotal-<?php echo $item['item_id'];?>" name="products[<?php echo $item['item_id'];?>][subtotal]" type="hidden" value="<?php echo $item['item_total'];?>">
                      <span class="subtotal-" id="subtotal-<?php echo $item['item_id'];?>"><?php echo currency_format($item['item_total']);?>
                      </span>
                    </td>
                    <td class="text-center">
                        <i class="fa fa-close text-red pointer remove" data-id="<?php echo $item['item_id'];?>" title="Remove">
                      </i>
                    </td>
                  </tr>
                <?php endforeach;?>
              </tbody>
              <tfoot>
                <tr class="bg-gray rm-in-action">
                  <th class="text-right" colspan="5">
                    <?php echo trans('label_subtotal'); ?>
                  </th>
                  <th class="col-sm-2 text-right">
                    <span><?php echo currency_format($quotation['subtotal']+$quotation['item_tax']);?></span>
                  </th>
                  <th class="w-25p">&nbsp;</th>
                </tr>
                <tr class="bg-gray rm-in-action">
                  <th class="text-right" colspan="5">
                    <?php echo trans('label_discount'); ?> (-)
                  </th>
                  <th class="col-sm-2 text-right">
                    <span><?php echo currency_format($quotation['discount_amount']);?></span>
                  </th>
                  <th class="w-25p">&nbsp;</th>
                </tr>
                <tr class="bg-gray rm-in-action">
                  <th class="text-right" colspan="5">
                    <?php echo trans('label_order_tax'); ?> (+)
                  </th>
                  <th class="col-sm-2 text-right">
                    <span><?php echo currency_format($quotation['order_tax']);?></span>
                  </th>
                  <th class="w-25p">&nbsp;</th>
                </tr>
                <tr class="bg-gray rm-in-action">
                  <th class="text-right" colspan="5">
                    <?php echo trans('label_shipping'); ?> (+)
                  </th>
                  <th class="col-sm-2 text-right">
                    <span><?php echo currency_format($quotation['shipping_amount']);?></span>
                  </th>
                  <th class="w-25p">&nbsp;</th>
                </tr>
                <tr class="bg-success">
                  <th class="text-right" colspan="5">
                    <?php echo trans('label_payable_amount'); ?>
                  </th>
                  <th class="col-sm-2 text-right">
                    <input id="total-tax" type="hidden" name="total-tax" value="<?php echo $quotation['item_tax']+$quotation['order_tax'];?>">
                    <input id="total-amount" type="hidden" name="total-amount" value="<?php echo $quotation['payable_amount'];?>">
                    <span id="total-amount-view"><?php echo currency_format($quotation['payable_amount']);?></span>
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
      <div class="col-sm-6 col-sm-offset-3 text-center">            
        <button id="update-quotation-submit" class="btn btn-block btn-info" data-form="#form-quotation" data-datatable="#quotation-quotation-list" name="submit" data-loading-text="Updating...">
          <i class="fa fa-fw fa-pencil"></i>
          <?php echo trans('button_update'); ?>
        </button>
      </div>
    </div>
    
  </div>
</form>