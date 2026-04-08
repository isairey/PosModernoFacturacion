<?php 
include ("../../_init.php");
?>
<style type="text/css">
.modal-lg {
	width: 97%;
	margin-top: 20px;
    margin-bottom: 20px;
}
</style>
<form class="form-horizontal" id="sell-return-form" action="sell_return.php">
<input type="hidden" name="invoice-id" value="{{ order.invoice_id }}">
<input type="hidden" name="customer-id" value="{{ order.customer_id }}">
<div class="bootbox-body">
	<div class="table-selection">
		<div class="col-lg-6 col-md-6 col-sm-6 cart-details bootboox-container">
			<div class="table-responsive mt-30">
				<table class="table table-bordered table-striped table-condensed">
					<tbody>
						<tr>
							<td class="w-50 text-right"><?php echo trans('text_invoice_id'); ?></td>
							<td class="w-50 bg-gray">{{ order.invoice_id }}</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="text-center">
				<h4><?php echo trans('text_order_summary'); ?></h4>
			</div>
			<div class="table-responsive">
				<table class="table table-bordered table-condensed">
					<tbody>
						<tr ng-repeat="items in order.items">
							<td class="text-center w-10">
								{{ $index+1 }}
							</td>
							<td class="w-70">{{ items.item_name }} (x{{ items.item_quantity | formatDecimal:2 }} {{ items.unitName }})</td>
							<td class="text-right w-20">{{ items.item_total | formatDecimal:2 }}</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<th class="text-right w-60" colspan="2">
								<?php echo trans('label_subtotal'); ?>
							</th>
							<td class="text-right w-40">{{ order.subtotal  | formatDecimal:2 }}</td>
						</tr>
						<tr>
							<th class="text-right w-60"  colspan="2">
								<?php echo trans('label_discount'); ?> {{ discountType  == 'percentage' ? '('+discountAmount+'%)' : '' }}
							</th>
							<td class="text-right w-40" >{{ discountType  == 'percentage' ? (_percentage(totalAmount, discountAmount) | formatDecimal:2) : (discountAmount | formatDecimal:2) }}</td>
						</tr>
						<tr>
							<th class="text-right w-60" colspan="2">
								<?php echo trans('label_order_tax'); ?>
							</th>
							<td class="text-right w-40">{{ order.order_tax  | formatDecimal:2 }}</td>
						</tr>
						<tr>
							<th class="text-right w-60"  colspan="2">
								<?php echo trans('label_shipping_charge'); ?> {{ shippingType  == 'percentage' ? '('+shippingAmount+'%)' : '' }}
							</th>
							<td class="text-right w-40" >{{ shippingType  == 'percentage' ? (_percentage(totalAmount, shippingAmount) | formatDecimal:2) : (shippingAmount | formatDecimal:2) }}</td>
						</tr>
						<tr>
							<th class="text-right w-60"  colspan="2">
								<?php echo trans('label_others_charge'); ?>
							</th>
							<td class="text-right w-40" >{{ othersCharge | formatDecimal:2 }}</td>
						</tr>
						<?php if(INSTALLMENT && (user_group_id() == 1 || has_permission('access', 'create_installment'))):?>
							<tr>
								<th class="text-right w-60" colspan="2">
									<?php echo trans('label_interest_amount'); ?>
								</th>
								<td class="text-right w-40">{{ installmentInterestAmount  | formatDecimal:2 }}</td>
							</tr>
						<?php endif;?>

						<!-- Payments start -->
						<tr ng-repeat="payments in order.payments" class="{{ payments.type=='discount' ? 'info' : 'success' }}">
							<th ng-show="payments.type=='discount'" class="text-right w-60" colspan="2"><small><i>Discount on</i></small> {{ payments.created_at }} <small><i>by {{ payments.by }}</i></small></th>
							<td ng-show="payments.type=='discount'" class="text-right w-40">{{ payments.amount | formatDecimal:2 }}</td>
						</tr>
						<!-- Payments end -->

						<tr>
							<th class="text-right w-60" colspan="2">
								<?php echo trans('label_previous_due'); ?>
							</th>
							<input type="hidden" name="previous-due" value="{{ order.previous_due }}">
							<td class="text-right w-40">{{ order.previous_due | formatDecimal:2 }}</td>
						</tr>

						<tr>
							<th class="text-right w-60 bg-gray" colspan="2">
								<?php echo trans('label_payable_amount'); ?>
								<small>({{ order.total_items }} items)</small>
							</th>
							<td class="text-right w-40 bg-gray">{{ order.payable_amount | formatDecimal:2 }}</td>
						</tr>
						<tr class="success">
							<th class="text-right w-60" colspan="2">
								<?php echo trans('label_previous_due_paid'); ?>
							</th>
							<input type="hidden" name="previous-due" value="{{ order.prev_due_paid }}">
							<td class="text-right w-40">{{ order.prev_due_paid | formatDecimal:2 }}</td>
						</tr>

						<!-- Payments start -->
						<tr ng-repeat="payments in order.payments" class="{{ payments.type=='return' ? 'danger' : 'success' }}">
							<th ng-show="payments.type=='due_paid'" class="text-right w-60" colspan="2"><small><i>Duepaid on</i></small> {{ payments.created_at }} <small><i>by {{ payments.by }}</i></small></th>
							<td ng-show="payments.type=='due_paid'" class="text-right w-40">{{ payments.amount | formatDecimal:2 }}</td>

							<th ng-show="payments.type=='sell'" class="text-right w-60" colspan="2"><small><i>Paid by</i></small> {{ payments.name }} <i>on</i> {{ payments.created_at }} <small><i>by {{ payments.by }}</i></small></th>
							<td ng-show="payments.type=='sell'" class="text-right w-40">{{ payments.amount | formatDecimal:2 }}</td>

							<th ng-show="payments.type=='return'" class="text-right w-60" colspan="2"><small><i>Return on</i></small> {{ payments.created_at }} <small><i>by {{ payments.by }}</i></small></th>
							<td ng-show="payments.type=='return'" class="text-right w-40">{{ payments.amount | formatDecimal:2 }}</td>
						</tr>
						<!-- Payments end -->

						<tr class="danger">
							<th class="text-right w-60" colspan="2">
								<?php echo trans('label_due'); ?>
							</th>
							<td class="text-right w-40">{{ order.due | formatDecimal:2 }}</td>
						</tr>

						<!-- Payments start -->
						<tr ng-repeat="payments in order.payments" class="{{ 'success' }}">
							<th ng-show="payments.type=='change'" class="text-right w-60" colspan="2"><small><i>Change on</i></small> {{ payments.created_at }} <small><i>by {{ payments.by }}</i></small></th>
							<td ng-show="payments.type=='change'" class="text-right w-40">{{ payments.pos_balance | formatDecimal:2 }}</td>
						</tr>
						<!-- Payments end -->

						<!-- <tr class="warning">
							<th class="text-right w-60" colspan="2">Balance</th>
							<td class="text-right w-40">{{ order.balance | formatDecimal:2 }}</td>
						</tr> -->

						<tr ng-show="order.invoice_not" class="active">
							<td colspan="3">
								<b><?php echo trans('label_note'); ?>:</b> {{ order.invoice_note }}
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 bootboox-container pmethod-option checkout-payment-option">
			<div class="pl-10">
				<div class="text-center">
					<h4><?php echo trans('text_return_item'); ?></h4>
				</div>
				<div class="table-responsive">
					<table class="table table-bordered table-condensed">
						<thead>
							<tr class="bg-gray">
								<th class="text-center w-10">Yes/No</th>
								<th class="w-20"><?php echo trans('label_product_name'); ?></th>
								<th class="text-center w-70"><?php echo trans('label_return_quantity'); ?></th>
							</tr>
						</thead>
						<tbody>
							<tr ng-repeat="items in order.items">
								<td class="text-center w-10 bg-gray">
									<input type="hidden" name="items[{{ items.item_id }}][item_id]" value="{{ items.item_id }}">
									<input type="checkbox" name="items[{{ items.item_id }}][check]" value="1" style="width:20px;height:20px;">
								</td>
								<td class="w-70">{{ items.item_name }} (x{{ items.item_quantity-items.return_quantity | formatDecimal:2 }})</td>
								<td class="text-center w-20">
									<input class="text-center" type="text" name="items[{{ items.item_id }}][item_quantity]" value="{{ items.item_quantity-items.return_quantity | formatDecimal:2 }}" onclick="this.select();" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onKeyUp="if(this.value<0){this.value='1';}">
								</td>
							</tr>
							<tr>
								<td colspan="3">
									<textarea class="form-control no-resize" name="note" placeholder="<?php echo trans('placeholder_type_any_note'); ?>"></textarea>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
</form>