<div class="row">
	<div class="col-md-12">
		<div class="table-responsive">
			<table class="table table-bordered table-striped table-condensed">
				<tbody>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_datetime'); ?>
						</td>
						<td class="w-70">
							<?php echo $invoice['created_at']; ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_invoice_id'); ?>
						</td>
						<td class="w-70">
							<?php echo $invoice['invoice_id']; ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_reference_no'); ?>
						</td>
						<td class="w-70">
							<?php echo $invoice['reference_no']; ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_customer_id'); ?>
						</td>
						<td class="w-70">
							<?php echo get_the_customer($invoice['customer_id'],'customer_name'); ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_returened_by'); ?>
						</td>
						<td class="w-70">
							<?php echo get_the_user($invoice['created_by'],'username'); ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_note'); ?>
						</td>
						<td class="w-70">
							<?php echo $invoice['note']; ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_total_item'); ?>
						</td>
						<td class="w-70">
							<?php echo currency_format($invoice['total_item']); ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_total_quantity'); ?>
						</td>
						<td class="w-70">
							<?php echo currency_format($invoice['total_quantity']); ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_total_amount'); ?>
						</td>
						<td class="w-70">
							<?php echo currency_format($invoice['total_amount']); ?>
						</td>
					</tr>
				</tbody>
			</table>

			<h4><?php echo trans('text_return_products'); ?></h4>
			
			<div class="table-responsive">
				<table class="table table-bordered table-condensed margin-b0">
					<thead>
					<tr class="bg-gray">
						<th class="w-60 text-center"><?php echo trans('label_product_name'); ?></th>
						<th class="w-20 text-right"">
							<?php echo trans('label_quantity'); ?>
						</th>
						<th class="w-20 text-right"">
							<?php echo trans('label_item_total'); ?>
						</th>
					</tr>
					</thead>
					<tbody>
						<?php foreach ($invoice_items as $product) : ?>
							<tr>
								<td class="text-center">
									<?php echo $product['item_name']; ?>
								</td>
								<td class="text-right">
									<?php echo currency_format($product['item_quantity']); ?> <?php echo get_the_unit($product['item_id'],'unit_name'); ?>
								</td>
								<td class="text-right">
									<?php echo currency_format($product['item_total']); ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>