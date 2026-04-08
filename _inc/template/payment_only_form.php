<?php 
include ("../../_init.php");
$customer_id = isset($request->get['customer_id']) ? $request->get['customer_id'] : 0;
?>

<style type="text/css">
.modal-lg {
	width: 97%;
	margin-top: 20px;
	margin-bottom: 20px;
}
</style>

<form class="form-horizontal" id="checkout-form" action="payment.php">

<input type="hidden" name="invoice-id" value="{{ order.invoice_id }}">
<input type="hidden" name="customer-id" value="{{ order.customer_id }}">
<input type="hidden" name="pmethod-id" value="{{ pmethodId }}">

<div class="bootbox-body">
	<div class="table-selection">
		<div class="col-lg-3 col-md-3 col-sm-3 bootboox-tab-menu bootboox-container p-0">
			<div class="list-group">
				<?php $inc = 0;foreach(get_pmethods() as $pmethod) : ?>
					<a class="text-left list-group-item pmethod_item" id="pmethod_<?php echo $pmethod['pmethod_id']; ?>" href="javascript:void(0)" <?php echo $inc == 0 ? 'ng-init="selectPaymentMethod('.$pmethod['pmethod_id'].',\''.$pmethod['code_name'].'\')"' : null;?> ng-click="selectPaymentMethod('<?php echo $pmethod['pmethod_id']; ?>', '<?php echo $pmethod['code_name']; ?>')" onClick="return false;" ><span class="fa fa-fw fa-angle-double-right"></span> <b><?php echo $pmethod['name']; ?>
						<?php if (strtolower($pmethod['code_name']) == 'credit'):
							$customer_balance = get_customer_balance($customer_id);
							?>
							<span ng-init="customerBalance='<?php echo $customer_balance;?>'">
							(<?php echo get_currency_symbol();?><?php echo currency_format($customer_balance);?>)
							</span>
						<?php endif;?>
						</b></a>
				<?php $inc++;endforeach; ?>
			</div>
		</div>
		<div class="col-lg-5 col-md-5 col-sm-5 bootboox-container pmethod-option checkout-payment-option">
			<div class="tab-wrapper tab-cheque bootboox-container tab-cheque-payment">
				<h4 ng-show="pmethodId" class="text-center"><?php echo trans('text_pmethod'); ?> <b>{{ pmethodName }}</b></h4>
				<button ng-click="payNowWithFullPaid()" onClick="return false;" class="btn btn-success full-paid" style="width:100%">
					<span class="fa fa-fw fa-money"></span> <?php echo trans('button_full_payment'); ?>
				</button>
				<div class="input-group input-group-lg">
					<span class="input-group-addon hidden-sm"><?php echo trans('text_pay_amount'); ?></span>
					<input class="form-control" name="paid-amount" ng-model="paidAmount" placeholder="<?php echo trans('placeholder_input_an_amount'); ?>" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onKeyUp="if(this.value<0){this.value='1';}">
				</div>
				<!-- <div class="input-group input-group-sm mt-5">
					<span class="input-group-addon hidden-sm">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo trans('text_discount_amount'); ?></span>
					<input class="form-control" type="text" name="discount-amount" ng-model="discountAmount" placeholder="<?php echo trans('placeholder_input_discount_amount'); ?>" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onKeyUp="if(this.value<0){this.value='1';}">
				</div> -->
				<div class="mt-5">
					<input type="text" name="note" class="form-control note" placeholder="<?php echo trans('placeholder_note_here');?>">
				</div>
				<div bind-html-compile="rawPaymentMethodHtml"></div>
			</div>
		</div>

		<div class="col-lg-4 col-md-4 col-sm-4 cart-details bootboox-container">
			<div class="table-responsive mt-30">
				<table class="table table-bordered table-striped table-condensed">
					<tbody>
						<tr>
							<td class="w-40 text-right"><?php echo trans('label_invoice_id'); ?></td>
							<td class="w-60 bg-gray">{{ order.invoice_id }}</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="text-center">
				<h4><?php echo trans('text_billing_details'); ?></h4>
			</div>
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-condensed">
					<tbody>
						<tr ng-repeat="items in order.items">
							<td class="text-center w-10">
								<input type="hidden" name="item['{{ items.item_id }}'][item_id]" value="{{ items.item_id }}">
								<input type="hidden" name="item['{{ items.item_id }}'][category_id]" value="{{ items.categoryId }}">
								<input type="hidden" name="item['{{ items.item_id }}'][sup_id]" value="{{ items.supId }}">
								<input type="hidden" name="item['{{ items.item_id }}'][item_name]" value="{{ items.item_name }}">
								<input type="hidden" name="item['{{ items.item_id }}'][item_price]" value="{{ items.item_price  | formatDecimal:2 }}">
								<input type="hidden" name="item['{{ items.item_id }}'][item_quantity]" value="{{ items.item_quantity }}">
								<input type="hidden" name="item['{{ items.item_id }}'][item_total]" value="{{ items.item_total  | formatDecimal:2 }}">
								{{ $index+1 }}
							</td>
							<td class="w-70">{{ items.item_name }} (x{{ items.item_quantity | formatDecimal:2 }} {{ items.unitName }})</td>
							<td class="text-right w-20">{{ items.item_total  | formatDecimal:2 }}</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<th class="text-right w-60" colspan="2">
								<?php echo trans('label_subtotal'); ?>
							</th>
							<input type="hidden" name="sub-total" value="{{ order.subtotal }}">
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
							<input type="hidden" name="tax-amount" value="{{ order.order_tax }}">
							<td class="text-right w-40">{{ order.order_tax  | formatDecimal:2 }}</td>
						</tr>
						<tr>
							<th class="text-right w-60"  colspan="2">
								<?php echo trans('label_shipping_charge'); ?> {{ shippingType  == 'percentage' ? '('+shippingAmount+'%)' : '' }}
							</th>
							<input type="hidden" name="shipping-type" value="{{ shippingType }}">
							<input type="hidden" name="shipping-amount" value="{{ shippingType  == 'percentage' ? _percentage(totalAmount, shippingAmount) : shippingAmount }}">
							<td class="text-right w-40" >{{ shippingType  == 'percentage' ? (_percentage(totalAmount, shippingAmount) | formatDecimal:2) : (shippingAmount | formatDecimal:2) }}</td>
						</tr>
						<tr>
							<th class="text-right w-60"  colspan="2">
								<?php echo trans('label_others_charge'); ?>
							</th>
							<input type="hidden" name="others-charge" value="{{ othersCharge }}">
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
							<input type="hidden" name="payable-amount" value="{{ order.payable_amount }}">
							<td class="text-right w-40 bg-gray">{{ order.payable_amount | formatDecimal:2 }}</td>
						</tr>
						<tr class="success">
							<th class="text-right w-60" colspan="2">
								<?php echo trans('label_previous_due_paid'); ?>
							</th>
							<input type="hidden" name="previous-due" value="{{ order.prev_due_paid }}">
							<td class="text-right w-40">{{ order.prev_due_paid | formatDecimal:2 }}</td>
						</tr>

						<!-- Discount on duepaid end -->
						<tr ng-repeat="payments in order.payments" class="{{ payments.type=='discount' ? 'danger' : 'success' }}">
							<th ng-show="payments.type=='discount'" class="text-right w-60" colspan="2"><small><i>Discount on</i></small> {{ payments.created_at }} <small><i>by {{ payments.by }}</i></small></th>
							<td ng-show="payments.type=='discount'" class="text-right w-40">{{ payments.amount | formatDecimal:2 }}</td>
						</tr>
						<!-- Discount on duepaid start -->

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
							<input type="hidden" name="due-amount" value="{{ order.due }}">
							<td class="text-right w-40">{{ order.due | formatDecimal:2 }}</td>
						</tr>

						<!-- Payments start -->
						<tr ng-repeat="payments in order.payments" class="{{ 'success' }}">
							<th ng-show="payments.type=='change'" class="text-right w-60" colspan="2"><small><i>Change on</i></small> {{ payments.created_at }} <small><i>by {{ payments.by }}</i></small></th>
							<td ng-show="payments.type=='change'" class="text-right w-40">{{ payments.pos_balance | formatDecimal:2 }}</td>
						</tr>
						<!-- Payments end -->

						<tr ng-show="order.invoice_not" class="active">
							<td colspan="3">
								<b><?php echo trans('label_note'); ?>:</b> {{ order.invoice_note }}
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
</form>