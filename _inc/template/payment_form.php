<?php 
include ("../../_init.php"); 
$customer_id = isset($request->get['customer_id']) ? $request->get['customer_id'] : 0;
?>
<form class="form-horizontal" id="checkout-form" action="place_order.php">
<input type="hidden" name="invoice-id" value="{{ invoiceId }}">
<input type="hidden" name="customer-id" value="{{ customerId }}">
<input type="hidden" name="salesman-id" value="{{ salesmanId }}">
<input type="hidden" name="customer-mobile-number" value="{{ customerMobileNumber }}">
<input type="hidden" name="pmethod-id" value="{{ pmethodId }}">
<input type="hidden" name="is_installment_order" value="{{ isInstallmentOrder }}">
<input type="hidden" name="qref" value="{{ qRef }}">


<div class="bootbox-body">
	<div class="table-selection">
		<div class="col-lg-3 col-md-3 col-sm-3 bootboox-tab-menu bootboox-container p-0">
			<div class="list-group">
				<?php $inc = 0;foreach(get_pmethods() as $pmethod) :?>
					<a class="text-left list-group-item pmethod_item" id="pmethod_<?php echo $pmethod['pmethod_id']; ?>" href="javascript:void(0)" <?php echo $inc == 0 ? 'ng-init="selectPaymentMethod('.$pmethod['pmethod_id'].',\''.$pmethod['code_name'].'\')"' : null;?> ng-click="selectPaymentMethod('<?php echo $pmethod['pmethod_id']; ?>', '<?php echo $pmethod['code_name']; ?>')" onClick="return false;"><span class="fa fa-fw fa-angle-double-right"></span> <b><?php echo $pmethod['name']; ?>
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
		<div class="col-lg-5 col-md-5 col-sm-5 checkout-payment-option bootboox-container">
			<div class="tab-wrapper tab-cheque bootboox-container tab-cheque-payment">

				<h4 ng-show="pmethodId" class="text-center title"><?php echo trans('text_pmethod'); ?>: <b>{{ pmethodName }}</b></h4>

				<div class="btn-toolbar" role="toolbar" aria-label="...">
					 <div class="btn-group btn-group-justified" role="group" aria-label="...">
					 	<div class="btn-group" role="group">
							<a ng-hide="isInstallmentOrder" ng-click="checkoutWithFullPaid()" onClick="return false;" class="btn btn-success btn-md full-paid">
								<span class="fa fa-fw fa-money"></span> <?php echo trans('button_full_payment'); ?>
							</a>
						</div>
						<div class="btn-group" role="group">
							<a ng-hide="isInstallmentOrder" ng-click="checkoutWithFullDue()" onClick="return false;" class="btn btn-danger btn-md full-due">
								<span class="fa fa-fw fa-minus"></span> <?php echo trans('button_full_due'); ?>
							</a>
						</div>
					</div>
				</div>

				<div class="btn-toolbar mb-10" role="toolbar" aria-label="...">
					 <div class="btn-group btn-group-justified" role="group" aria-label="...">
					 	<div class="btn-group" role="group">
							<a ng-show="isInstallment" id="activeSellWithInstallmentBtn" ng-click="sellWithInstallment()" onClick="return false;" class="btn btn-default btn-md" style="border-radius:50px;">
							<span class="fa fa-fw fa-refresh"></span> <?php echo trans('button_sell_with_installment'); ?>
							</a>
						</div>
					</div>
				</div>

				<div class="input-group input-group-lg pmethod-field-wrapper">
					<span class="input-group-addon"><?php echo trans('text_pay_amount'); ?></span>
					<input id="paid-amount" class="form-control" type="text" name="paid-amount" ng-model="paidAmount" placeholder="<?php echo trans('placeholder_input_an_amount'); ?>" ng-keypress="checkoutWhilePressEnter($event)" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onKeyUp="if(this.value<0){this.value='1';}">
				</div>
				<div class="mt-5">
					<div class="input-group input-group-xs pmethod-field-wrapper">
						<span class="input-group-addon hidden-sm"><span class="fa fa-pencil"></span></span>
						<input type="text" name="invoice-note" class="form-control invoice-note" value="{{ invoiceNote }}" placeholder="<?php echo trans('placeholder_note_here');?>">
					</div>
				</div>
				<div bind-html-compile="rawPaymentMethodHtml"></div>
				<?php if(INSTALLMENT && (user_group_id() == 1 || has_permission('access', 'create_installment'))):?>
					<h4 ng-show="isInstallmentOrder" class="text-center"><?php echo trans('title_installment_details'); ?></h4>
					<div ng-show="isInstallmentOrder" class="panel panel-default mt-5">
						<div class="panel-body">
							<div class="form-group">
						      <label for="installment_duration" class="col-sm-4 control-label">
						        <?php echo trans('label_duration'); ?>
						      </label>
						      <div class="col-sm-6">
						        <input type="text" id="installment_duration" name="installment_duration"  ng-model="installmentDuration" class="form-control text-right" onclick="this.select();" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onKeyUp="if(this.value<0){this.value='1';}">
						      </div>
						      <div class="col-sm-2">
						        <i><?php echo trans('text_days'); ?></i>
						      </div>
						    </div>
						    <div class="form-group">
						      <label for="installment_interval_count" class="col-sm-4 control-label">
						        <?php echo trans('label_interval'); ?>
						      </label>
						      <div class="col-sm-6">
						        <input type="text" id="installment_interval_count" name="installment_interval_count" ng-model="installmentIntervalCount" class="form-control text-right" onclick="this.select();" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onKeyUp="if(this.value<0){this.value='1';}">
						      </div>
						      <div class="col-sm-2 text-left">
						        <i><?php echo trans('text_days'); ?></i>
						      </div>
						    </div>
						    <div class="form-group">
						      <label for="installment_count" class="col-sm-4 control-label">
						        <?php echo trans('label_total_installment'); ?>
						      </label>
						      <div class="col-sm-6">
						        <input type="text" id="installment_count" name="installment_count" value="{{ installmentDuration/installmentIntervalCount | formatDecimal:2 }}" class="form-control text-right" readonly>
						      </div>
						    </div>
						    <div class="form-group">
						      <label for="installment_interest_percentage" class="col-sm-4 control-label">
						        <?php echo trans('label_interest_percentage'); ?>
						      </label>
						      <div class="col-sm-6">
						        <input type="text" id="installment_interest_percentage" name="installment_interest_percentage" ng-model="installmentInterestPercentage" class="form-control text-right" onclick="this.select();" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onKeyUp="if(this.value<0){this.value='1';}">
						      </div>
						      <div class="col-sm-2">%</div>
						    </div>
						    <div class="form-group">
						      <label for="installment_interest_amount" class="col-sm-4 control-label">
						        <?php echo trans('label_interest_amount'); ?>
						      </label>
						      <div class="col-sm-6">
						        <input type="text" id="installment_interest_amount" name="installment_interest_amount" value="{{ installmentInterestAmount | formatDecimal:2}}" class="form-control text-right" readonly>
						      </div>
						    </div>
						</div>
					</div>
				<?php endif;?>
			</div>
		</div>

		<div class="col-lg-4 col-md-4 col-sm-4 order-details bootboox-container">
			<div class="text-center">
				<h4><?php echo trans('text_order_details'); ?></h4>
			</div>
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-condensed">
					<tbody>
						<tr ng-repeat="items in itemArray">
							<td class="text-center w-10">
								<input type="hidden" name="product-item['{{ items.id }}'][p_type]" value="{{ items.pType }}">
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
							<td class="text-right w-20">{{ items.subTotal  | formatDecimal:2 }}</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<th class="text-right w-60" colspan="2">
								<?php echo trans('label_subtotal'); ?>
							</th>
							<input type="hidden" name="sub-total" value="{{ totalAmount }}">
							<td class="text-right w-40">{{ totalAmount  | formatDecimal:2 }}</td>
						</tr>
						<tr>
							<th class="text-right w-60"  colspan="2">
								<?php echo trans('label_discount'); ?> {{ discountType  == 'percentage' ? '('+discountAmount+'%)' : '' }}
							</th>
							<input type="hidden" name="discount-type" value="{{ discountType }}">
							<input type="hidden" name="discount-amount" value="{{ discountType  == 'percentage' ? _percentage(totalAmount, discountAmount) : discountAmount }}">
							<td class="text-right w-40" >{{ discountType  == 'percentage' ? (_percentage(totalAmount, discountAmount) | formatDecimal:2) : (discountAmount | formatDecimal:2) }}</td>
						</tr>
						<tr>
							<th class="text-right w-60" colspan="2">
								<?php echo trans('label_order_tax'); ?>
							</th>
							<input type="hidden" name="tax-amount" value="{{ taxAmount }}">
							<td class="text-right w-40">{{ taxAmount  | formatDecimal:2 }}</td>
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
						<?php if(INSTALLMENT):?>
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
							<input type="hidden" name="previous-due" value="{{ dueAmount }}">
							<td class="text-right w-40">{{ dueAmount  | formatDecimal:2 }}</td>
						</tr>
						<tr>
							<th class="text-right w-60" colspan="2">
								<?php echo trans('label_payable_amount'); ?>
								<small>({{ totalItem }} items)</small>
							</th>
							<input type="hidden" name="payable-amount" value="{{ totalPayable }}">
							<td class="text-right w-40">{{ totalPayable  | formatDecimal:2 }}</td>
						</tr>
						<tr>
							<th class="text-right w-60" colspan="2">
								<?php echo trans('label_paid_amount'); ?>
							</th>
							<td class="text-right w-40">{{ paidAmount | formatDecimal:2 }}</td>
						</tr>
						<tr>
							<th class="text-right w-60" colspan="2">
								<?php echo trans('label_due_amount'); ?>
							</th>
							<td class="text-right w-40">{{ totalPayable < paidAmount ? totalPayable : totalPayable - paidAmount | formatDecimal:2 }}</td>
						</tr>
						<tr>
							<th class="text-right w-60" colspan="2">
								<?php echo trans('label_balance'); ?>
							</th>
							<td class="text-right w-40">{{ totalPayable < paidAmount ? paidAmount - totalPayable  : 0 | formatDecimal:2 }}</td>
						</tr>
						<tr ng-show="invoiceNote"><td colspan="3">&nbsp;</td></tr>
						<tr ng-show="invoiceNote" class="active">
							<td colspan="3">
								<b><?php echo trans('label_note'); ?>:</b> {{ invoiceNote }}
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
</form>