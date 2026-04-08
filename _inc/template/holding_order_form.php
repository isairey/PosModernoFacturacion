<?php include ("../../_init.php");?>
<style type="text/css">
.order-table td {
	padding: 3px!important;
}
.order-table tfoot {
	border: 1px solid #ccc;
	margin-top: 5px;
	background: #fff;
}
</style>
<form class="form-horizontal" id="order-place-form" action="holding_order.php?action_type=HOLD">
<input type="hidden" name="customer-id" value="{{ customerId }}">
<input type="hidden" name="customer-mobile-number" value="{{ customerMobileNumber }}">
<div class="bootbox-body">
	<div class="row">
		<div class="col-lg-12">
			<div class="input-group input-group-lg group-content">
				<span class="input-group-addon"><?php echo trans('text_order_title'); ?></span>
				<input id="order-title" class="form-control" ng-model="orderName" name="order-title" ng-keypress="holdOrderWhilePressEnter($event)" autofocus>
			</div>
		</div>
	</div>

	<div class="table-selection">
		<div class="text-center">
			<h4><?php echo trans('text_order_details'); ?></h4>
		</div>
		<div class="well" style="margin-bottom: 0;">
			<div class="table-responsive">
			<table class="table table-bordered order-table">
				<tbody>
					<tr ng-repeat="items in itemArray" class="bg-gray">
						<td class="text-center w-10">
							<input type="hidden" name="product-item['{{ items.id }}'][item_id]" value="{{ items.id }}">
							<input type="hidden" name="product-item['{{ items.id }}'][category_id]" value="{{ items.categoryId }}">
							<input type="hidden" name="product-item['{{ items.id }}'][sup_id]" value="{{ items.supId }}">
							<input type="hidden" name="product-item['{{ items.id }}'][item_name]" value="{{ items.name }}">
							<input type="hidden" name="product-item['{{ items.id }}'][item_price]" value="{{ items.price  | formatDecimal:2 }}">
							<input type="hidden" name="product-item['{{ items.id }}'][item_quantity]" value="{{ items.quantity }}">
							<input type="hidden" name="product-item['{{ items.id }}'][item_total]" value="{{ items.subTotal  | formatDecimal:2 }}">
							{{ $index+1 }}
						</td>
						<td class="w-70">{{ items.name }} (x{{ items.quantity }} {{ items.unitName }})</td>
						<td class="text-right w-30">{{ items.subTotal  | formatDecimal:2 }}</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td class="text-right w-70" colspan="2">
							<?php echo trans('label_subtotal'); ?>
						</td>
						<td class="text-right w-30">
							<input type="hidden" name="sub-total" value="{{ totalAmount }}">
							{{ totalAmount  | formatDecimal:2 }}
						</td>
					</tr>
					<tr>
						<td class="text-right w-70"  colspan="2">
							<?php echo trans('label_discount'); ?> {{ discountType  == 'percentage' ? '('+discountAmount+'%)' : '' }}
						</td>
						<td class="text-right w-30" >
							<input type="hidden" name="discount-amount" value="{{ discountType  == 'percentage' ? _percentage(totalAmount, discountAmount) : discountAmount }}">
							<input type="hidden" name="discount-type" value="{{ discountType }}">
							{{ discountType  == 'percentage' ? (_percentage(totalAmount, discountAmount) | formatDecimal:2) : (discountAmount | formatDecimal:2) }}
						</td>
					</tr>
					<tr>
						<td class="text-right w-70" colspan="2">
							<?php echo trans('label_tax_amount'); ?>(%)
						</td>
						<td class="text-right w-30">
							<input type="hidden" name="tax-amount" value="{{ taxAmount }}">
							{{ taxAmount | formatDecimal:2 }}
						</td>
					</tr>
					<tr>
						<td class="text-right w-70"  colspan="2">
							<?php echo trans('label_shipping_charge'); ?> {{ shippingType  == 'percentage' ? '('+shippingAmount+'%)' : '' }}
						</td>
						<td class="text-right w-30" >
							<input type="hidden" name="shipping-amount" value="{{ shippingType  == 'percentage' ? _percentage(totalAmount, shippingAmount) : shippingAmount }}">
							<input type="hidden" name="shipping-type" value="{{ shippingType }}">
							{{ shippingType  == 'percentage' ? (_percentage(totalAmount, shippingAmount) | formatDecimal:2) : (shippingAmount | formatDecimal:2) }}
						</td>
					</tr>
					<tr>
						<th class="text-right w-70" colspan="2">
							<?php echo trans('label_others_charge'); ?>
						</th>
						<td class="text-right w-30">
							<input type="hidden" name="others-charge" value="{{ othersCharge }}">
							{{ othersCharge | formatDecimal:2 }}
						</td>
					</tr>
					<tr>
						<td class="text-right w-70" colspan="2">
							<?php echo trans('label_payable_amount'); ?>
							<small>({{ totalItem }} items)</small>
						</td>
						
						<td class="text-right w-30">
							<input type="hidden" name="payable-amount" value="{{ totalPayable }}">
							{{ totalPayable  | formatDecimal:2 }}
						</td>
					</tr>
					<tr><td colspan="3">&nbsp;</td></tr>
					<tr ng-show="invoiceNote">
						<td colspan="3">
							<b><?php echo trans('label_note'); ?>:</b> <i>{{ invoiceNote }}</i>
							<input class="hidden" name="invoice-note" value="{{ invoiceNote }}">
						</td>
					</tr>
				</tfoot>
			</table>
			</div>
		</div>
	</div>
</div>
</form>