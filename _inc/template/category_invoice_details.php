<table class="table table-striped table-condensed table-condensed">
	<thead>
		<tr class="active">
			<th>Sl. No.</th>
			<th>Invoice ID</th>
			<th>Patient ID</th>
			<th>Received At</th>
			<th class="text-right">Item Total</th>
			<th class="text-right">Discount Amount</th>
			<th class="text-right">Net Amount</th>
		</tr>
	</thead>
	<tbody>
		<tbody>
			<?php 
			$total_price = 0;
			$total_item_discount = 0;
			$total_net_amount = 0;
			$inc = 1;
			foreach ($invoices as $invoice) : ?>

				<tr>
					<td><?php echo $inc; ?></td>
					<td>
						<?php 
							$invoice_id = $invoice['ref_invoice_id'] ? $invoice['ref_invoice_id'] : $invoice['invoice_id'];
							echo $invoice_id; ?>
					</td>
					<td>
						<?php 
							$patient_id = $invoice['patient_id'];
							echo patient_prefix().$patient_id; ?>
					</td>
					<td><?php echo format_date($invoice['created_at']); ?></td>
					<td class="text-right">
						<?php 
						$total_price += $invoice['item_total_price'];
						echo currency_format($invoice['item_total_price']); ?></td>
					<td class="text-right">
						<?php 
						$total_item_discount += $invoice['item_discount'];
						echo currency_format($invoice['item_discount']); ?></td>
					<td class="text-right">
						<?php 
						$total_net_amount += $invoice['item_total_price'] - $invoice['item_discount'];
						echo currency_format($invoice['item_total_price'] - $invoice['item_discount']); ?></td>
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
				<th class="text-right"><?php echo currency_format($total_price); ?></th>
				<th class="text-right"><?php echo currency_format($total_item_discount); ?></th>
				<th class="text-right"><?php echo currency_format($total_net_amount); ?></th>
			</tr>
		</tfoot>
		
	</tbody>
</table>