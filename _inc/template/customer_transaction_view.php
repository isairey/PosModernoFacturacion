<div class="row">
	<div class="col-md-12">
		<div class="table-responsive">
			<table class="table table-bordered table-striped table-condensed">
				<tbody>
					<tr>
						<td class="w-30">
							<?php echo trans('label_reference_no'); ?>
						</td>
						<td class="w-70">
							<?php echo $transaction['reference_no']; ?>
						</td>
					</tr>
					<tr>
						<td class="w-30">
							<?php echo trans('label_ref_invoice_Id'); ?>
						</td>
						<td class="w-70">
							<?php echo $transaction['ref_invoice_id']; ?>
						</td>
					</tr>
					<tr>
						<td class="w-30">
							<?php echo trans('label_customer'); ?>
						</td>
						<td class="w-70">
							<?php echo get_the_customer($transaction['customer_id'],'customer_name'); ?>
						</td>
					</tr>
					<tr>
						<td class="w-30">
							<?php echo trans('label_type'); ?>
						</td>
						<td class="w-70">
							<?php echo $transaction['type']; ?>
						</td>
					</tr>
					<tr>
						<td class="w-30">
							<?php echo trans('label_pmethod_name'); ?>
						</td>
						<td class="w-70">
							<?php echo get_the_pmethod($transaction['pmethod_id'],'name'); ?>
						</td>
					</tr>
					<tr>
						<td class="w-30">
							<?php echo trans('label_notes'); ?>
						</td>
						<td class="w-70">
							<?php echo $transaction['notes']; ?>
						</td>
					</tr>
					<tr>
						<td class="w-30">
							<?php echo trans('label_amount'); ?>
						</td>
						<td class="w-70">
							<?php echo currency_format($transaction['amount']); ?>
						</td>
					</tr>
					<tr>
						<td class="w-30">
							<?php echo trans('label_created_by'); ?>
						</td>
						<td class="w-70">
							<?php echo get_the_user($transaction['created_by'],'username'); ?>
						</td>
					</tr>
					<tr>
						<td class="w-30">
							<?php echo trans('label_created_at'); ?>
						</td>
						<td class="w-70">
							<?php echo format_date($transaction['created_at']); ?>
						</td>
					</tr>
				</tbody>
			</table>
			</div>
		</div>
	</div>
</div>