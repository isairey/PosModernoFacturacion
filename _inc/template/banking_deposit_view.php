<div class="row">
	<div class="col-md-12">
		<div class="table-responsive">
			<table class="table table-bordered table-striped table-condensed mb-0">
				<tbody>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_source'); ?>
						</td>
						<td class="w-70">
							<?php echo get_the_income_source($invoice['source_id'], 'source_name'); ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_account'); ?>
						</td>
						<td class="w-70">
							<?php echo get_the_bank_account($invoice['account_id'], 'account_name'); ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_title'); ?>
						</td>
						<td class="w-70">
							<?php echo $invoice['title']; ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_note'); ?>
						</td>
						<td class="w-70">
							<?php echo $invoice['details']; ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_slip_no'); ?>
						</td>
						<td class="w-70">
							<?php echo $invoice['ref_no']; ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_datetime'); ?>
						</td>
						<td class="w-70">
							<?php echo format_date($invoice['created_at']); ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_by'); ?>
						</td>
						<td class="w-70">
							<?php echo get_the_user($invoice['created_by'], 'username'); ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_attachment'); ?>
						</td>
						<td class="w-70">
							<?php if (isset($invoice['image']) && ((FILEMANAGERPATH && is_file(FILEMANAGERPATH.$invoice['image']) && file_exists(FILEMANAGERPATH.$invoice['image'])) || (is_file(DIR_STORAGE . 'products' . $invoice['image']) && file_exists(DIR_STORAGE . 'products' . $invoice['image'])))) : ?>
				              <a target="_blink" href="<?php echo FILEMANAGERURL ? FILEMANAGERURL : root_url().'/storage/products'; ?>/<?php echo $invoice['image']; ?>"><img  src="<?php echo FILEMANAGERURL ? FILEMANAGERURL : root_url().'/storage/products'; ?>/<?php echo $invoice['image']; ?>" width="40" height="50"></a>
				            <?php endif; ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_total'); ?>
						</td>
						<td class="w-70">
							<?php echo currency_format($invoice['amount']); ?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>