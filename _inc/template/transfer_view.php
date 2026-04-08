<div class="row">
	<div class="col-md-12">
		<div class="table-responsive">
			<table class="table table-bordered table-striped table-condensed">
				<tbody>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_created_at'); ?>
						</td>
						<td class="w-70">
							<?php echo $transfer['created_at']; ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_ref_no'); ?>
						</td>
						<td class="w-70">
							<?php echo $transfer['ref_no']; ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_transferred_from'); ?>
						</td>
						<td class="w-70">
							<?php echo store_field('name', $transfer['from_store_id']); ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_transferred_to'); ?>
						</td>
						<td class="w-70">
							<?php echo store_field('name', $transfer['to_store_id']); ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_status'); ?>
						</td>
						<td class="w-70">
							<?php echo $transfer['status']; ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_total_item'); ?>
						</td>
						<td class="w-70">
							<?php echo currency_format($transfer['total_item']); ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_total_quantity'); ?>
						</td>
						<td class="w-70">
							<?php echo currency_format($transfer['total_quantity']); ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_created_by'); ?>
						</td>
						<td class="w-70">
							<?php echo $transfer['created_by']; ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_note'); ?>
						</td>
						<td class="w-70">
							<?php echo $transfer['note']; ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_attachment'); ?>
						</td>
						<td class="w-70">
							<?php if (isset($transfer['attachment']) && ((FILEMANAGERPATH && is_file(FILEMANAGERPATH.$transfer['attachment']) && file_exists(FILEMANAGERPATH.$transfer['attachment'])) || (is_file(DIR_STORAGE . 'purchase-transfers' . $transfer['attachment']) && file_exists(DIR_STORAGE . 'purchase-transfers' . $transfer['attachment'])))) : ?>
								<a href="<?php echo FILEMANAGERURL; ?><?php echo $transfer['attachment']; ?>" target="_blink" class="pointer">
				              		<img  src="<?php echo FILEMANAGERURL ? FILEMANAGERURL : root_url().'/storage/purchase-transfers'; ?>/<?php echo $transfer['attachment']; ?>" width="40" height="40">
				              	</a>
				            <?php endif;?>
						</td>
					</tr>
				</tbody>
			</table>

			<h4><?php echo trans('text_product_list'); ?></h4>
			
			<div class="table-responsive">
				<table class="table table-bordered margin-b0">
					<thead>
					<tr class="bg-gray">
						<th class="w-60 text-center"><?php echo trans('label_product_name'); ?></th>
						<th class="w-40 text-center">
							<?php echo trans('label_quantity'); ?>
						</th>
					</tr>
					</thead>
					<tbody>
						<?php foreach ($transfer_items as $product) : ?>
							<tr>
								<td class="text-center">
									<?php echo $product['product_name']; ?>
								</td>
								<td class="text-center">
									<?php echo currency_format($product['quantity']); ?> <?php echo $product['unit_name']; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>