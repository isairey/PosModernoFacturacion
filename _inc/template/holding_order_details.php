<div class="bootbox-body">
	<div class="table-selection">
		<div class="col-lg-4 col-md-4 col-sm-5 col-xs-6 bootboox-container table-holding-order">
			<div class="input-group input-group-sm pmethod-field-wrapper mtb-10">
				<span class="input-group-addon hidden-sm hidden-xs">
					<i class="fa fa-search"></i>
				</span>
				<input class="form-control" ng-model="search" placeholder="Search Here">
			</div>
			<ul class="list-group">
				<li id="holding-order-item-{{ order.ref_no }}" class="list-group-item holding-order-item" ng-repeat="order in orders | filter:search">
					<div style="display:table;width:100%;">
						<div class="table-row">
							<div class="table-cell" style="display:table-cell;vertical-align:middle;">
								<div ng-click="loadHoldingOrderDetails(order.ref_no);" class="pointer">
									<i class="fa fa-fw fa-angle-double-right"></i> {{ order.order_title }}	{{ order.ref_no ? order.ref_no : null }}</div>
							</div>
							<div class="table-cell text-right" style="display:table-cell;vertical-align:middle;">
								<span ng-click="deleteHoldingOrder(order.ref_no);" class="fa fa-trash text-red pointer" title="Delete" class="pointer" ></span>
							</div>
						</div>
					</div>
				</li>
			</ul>
		</div>
		<div class="col-lg-8 col-md-8 col-sm-7 col-xs-6 bootboox-tab-menu bootboox-container">
			<div ng-show="!showOrderDetails">
				<br><br><p class="text-center"><i>Select an order to view details</i></p>
			</div>
			<div ng-show="showOrderDetails" class="table-resposive p10">
				<h4 class="text-center"><b><?php echo trans('text_order_details');?></b></h4>
				<table class="table table-bordered table-stripted table-condensed">
					<tbody>
						<tr class="active">
							<th class="w-20 text-right"><?php echo trans('label_customer_name');?> :</th>
							<td class="w-30">{{ orderDetails.customer }}</td>

							<th class="w-20 text-right"><?php echo trans('label_date');?> :</th>
							<td class="w-30">{{ orderDetails.created_at }}</td>
						</tr>
					</tbody>
				</table>
				<table class="table table-bordered table-stripted table-condensed">
					<thead>
						<tr class="bg-gray">
							<th class="w-5 text-center"><?php echo trans('label_serial_no');?></th>
							<th class="w-40 text-center"><?php echo trans('label_product_name');?></th>
							<th class="w-20 text-right"><?php echo trans('label_price');?></th>
							<th class="w-20 text-center"><?php echo trans('label_quantity');?></th>
							<th class="w-20 text-right"><?php echo trans('label_amount');?></th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="item in orderDetails.items">
							<td class="text-center">{{ $index+1 }}</td>
							<td class="text-center">{{ item.item_name }}</td>
							<td class="text-right">{{ item.item_price | formatDecimal:2 }}</td>
							<td class="text-center">{{ item.item_quantity | formatDecimal:2 }} {{ item.unit_name }}</td>
							<td class="text-right">{{ item.item_total | formatDecimal:2 }}</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td class="w-80 text-right" colspan="4"><?php echo trans('label_subtotal');?></td>
							<td class="w-20 text-right">{{ orderDetails.subtotal | formatDecimal:2 }}</td>
						</tr>
						<tr>
							<td class="w-80 text-right" colspan="4"><?php echo trans('label_discount_amount');?></td>
							<td class="w-20 text-right">{{ orderDetails.discount_amount | formatDecimal:2 }}</td>
						</tr>
						<tr>
							<td class="w-80 text-right" colspan="4"><?php echo trans('label_tax_amount');?>(%)</td>
							<td class="w-20 text-right">{{ (orderDetails.item_tax + orderDetails.order_tax) | formatDecimal:2 }}</td>
						</tr>
						<tr>
							<td class="w-80 text-right" colspan="4"><?php echo trans('label_shipping_charge');?></td>
							<td class="w-20 text-right">{{ orderDetails.shipping_amount | formatDecimal:2 }}</td>
						</tr>
						<tr>
							<td class="w-80 text-right" colspan="4"><?php echo trans('label_others_charge');?></td>
							<td class="w-20 text-right">{{ orderDetails.others_charge | formatDecimal:2 }}</td>
						</tr>
						<tr>
							<td class="w-80 text-right" colspan="4"><?php echo trans('label_payable_amount');?></td>
							<td class="w-20 text-right">{{ orderDetails.payable_amount | formatDecimal:2 }}</td>
						</tr>
						<tr ng-show="invoiceNote">
							<td colspan="5">{{ invoiceNote }}</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>