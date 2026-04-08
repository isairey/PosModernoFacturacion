<table class="table table-striped table-bordered table-condensed">
	<thead>
		<tr class="bg-gray">
			<th class="w-one"><?php echo trans('label_serial_no'); ?></th>
			<th><?php echo trans('label_customer_name'); ?></th>
			<th><?php echo trans('label_received_by'); ?></th>
			<th><?php echo trans('label_received_at'); ?></th>
			<th class="text-right"><?php echo trans('label_collection_amount'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$inc = 1;
		foreach ($invoices as $invoice) : ?>
			<tr>
				<td><?php echo $inc; ?></td>
				<td><?php echo get_the_customer($invoice['customer_id'],'customer_name'); ?></td>
				<td><?php echo get_the_user($invoice['created_by'], 'username');?></td>
				<td><?php echo format_date($invoice['created_at']); ?></td>
				<td class="text-right"><?php echo currency_format($invoice['paid_amount']); ?></td>
			</tr>
		<?php 
		$inc++;
		endforeach; ?>
		
	</tbody>
</table>