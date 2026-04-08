<table class="table table-striped table-bordered table-condensed">
	<thead>
		<tr class="bg-gray">
			<th class="w-one"><?php echo trans('label_serial_no'); ?></th>
			<th class="w-three"><?php echo trans('label_created_at'); ?></th>
			<th class="w-one"><?php echo trans('label_invoice_id'); ?></th>
			<th class="w-two"><?php echo trans('label_customer_name'); ?></th>
			<th class="text-right w-one"><?php echo trans('label_invoice_amount'); ?></th>
			<th class="text-right w-one"><?php echo trans('label_discount_amount'); ?></th>
			<th class="text-right w-one"><?php echo trans('label_received_amount'); ?></th>
		</tr>
	</thead>
	<tbody>

		<?php
			$invoice_amount = 0;
			$discount_amount = 0;
			$discount_on_due_paid = 0;
			$paid_amount = 0;
			$due_amount = 0;
		?>

		<?php 
		$report_model = registry()->get('loader')->model('report');
		$inc = 1;
		foreach ($invoices as $invoice) : 
			$invoice_id = $invoice['ref_invoice_id'] ? $invoice['ref_invoice_id'] : $invoice['invoice_id'];
			?>

			<tr>
				<td><?php echo $inc; ?></td>

				<td><?php echo format_date($invoice['created_at']); ?></td>

				<td><a href="view_invoice.php?invoice_id=<?php echo $invoice_id; ?>" target="_blink"><?php echo $invoice_id; ?></a></td>

				<td><?php echo get_the_customer($invoice['customer_id'],'customer_name'); ?></td>

				<td class="text-right"><?php 
					$invoice_amount += $invoice['subtotal'];
					echo currency_format($invoice['subtotal']); ?>	
				</td>

				<td class="text-right"><?php 
					$discount_amount += $invoice['discount_amount'];
					echo currency_format($invoice['discount_amount']); ?>
				</td>
					
				<td class="text-right"><?php 
					$paid_amount += $invoice['paid_amount'];
					echo currency_format($invoice['paid_amount']); ?></td>
				</td>
			</tr>

		<?php 
		$inc++;
		endforeach; ?>
		
	</tbody>
	<tfoot>
		<tr class="bg-gray">
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th class="text-right"><?php echo currency_format($invoice_amount); ?></th>
			<th class="text-right"><?php echo currency_format($discount_amount); ?></th>
			<th class="text-right"><?php echo currency_format($paid_amount); ?></th>
		</tr>
	</tfoot>
</table>