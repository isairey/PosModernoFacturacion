<div class="row">
	<div class="col-md-12">
		<div class="table-responsive">
			<table class="table table-bordered table-condensed">
				<tbody>
					<tr>
						<td class="w-30">
							<?php echo trans('label_reference_no'); ?>
						</td>
						<td class="w-70">
							<?php echo $expense['reference_no']; ?>
						</td>
					</tr>
					<tr>
						<td class="w-30">
							<?php echo trans('label_created_at'); ?>
						</td>
						<td class="w-70">
							<?php echo format_date($expense['created_at']); ?>
						</td>
					</tr>
					<tr>
						<td class="w-30">
							<?php echo trans('label_what_for'); ?>
						</td>
						<td class="w-70">
							<?php echo $expense['title']; ?>
						</td>
					</tr>
					<tr>
						<td class="w-30">
							<?php echo trans('label_note'); ?>
						</td>
						<td class="w-70">
							<?php echo $expense['note']; ?>
						</td>
					</tr>
					<tr>
						<td class="w-30">
							<?php echo trans('label_amount'); ?>
						</td>
						<td class="w-70">
							<?php echo currency_format($expense['amount']); ?>
						</td>
					</tr>
					<tr>
						<td class="w-30">
							<?php echo trans('label_attachment'); ?>
						</td>
						<td class="w-70">
				            <?php if (isset($expense['attachment']) && ((FILEMANAGERPATH && is_file(FILEMANAGERPATH.$expense['attachment']) && file_exists(FILEMANAGERPATH.$expense['attachment'])) || (is_file(DIR_STORAGE . 'products' . $expense['attachment']) && file_exists(DIR_STORAGE . 'products' . $expense['attachment'])))) : ?>
				            	<a href="<?php echo FILEMANAGERURL ? FILEMANAGERURL : root_url().'/storage'; ?>/<?php echo $expense['attachment']; ?>" target="_blink">
				              		<img  src="<?php echo FILEMANAGERURL ? FILEMANAGERURL : root_url().'/storage'; ?>/<?php echo $expense['attachment']; ?>" style="width: 80px;height:auto;">
			          			</a>
				            <?php endif;?>
				        </td>
					</tr>
				</tbody>
			</table>
			</div>
		</div>
	</div>
</div>