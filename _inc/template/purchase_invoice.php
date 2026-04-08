<div class="row">
	<div class="col-md-12">
		<div class="table-responsive">
			<table class="table table-bordered table-striped table-condensed">
				<tbody>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_date'); ?>
						</td>
						<td class="w-70">
							<?php echo format_only_date($invoice['purchase_date']); ?>
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
							<?php echo trans('label_supplier'); ?>
						</td>
						<td class="w-70">
							<?php echo get_the_supplier($invoice['sup_id'],'sup_name'); ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_gtin'); ?>
						</td>
						<td class="w-70">
							<?php echo get_the_supplier($invoice['sup_id'],'gtin'); ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_payment_status'); ?>
						</td>
						<td class="w-70">
							<?php echo ucfirst($invoice['payment_status']); ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_note'); ?>
						</td>
						<td class="w-70">
							<?php echo $invoice['purchase_note']; ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_created_by'); ?>
						</td>
						<td class="w-70">
							<?php echo get_the_user($invoice['created_by'],'username'); ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 bg-gray text-right">
							<?php echo trans('label_attachment'); ?>
						</td>
						<td class="w-70">
							<?php if (isset($invoice['attachment']) && ((FILEMANAGERPATH && is_file(FILEMANAGERPATH.$invoice['attachment']) && file_exists(FILEMANAGERPATH.$invoice['attachment'])) || (is_file(DIR_STORAGE . 'purchase-invoices' . $invoice['attachment']) && file_exists(DIR_STORAGE . 'purchase-invoices' . $invoice['attachment'])))) : ?>
								<a href="<?php echo FILEMANAGERURL; ?><?php echo $invoice['attachment']; ?>" target="_blink" class="pointer">
				              		<img  src="<?php echo FILEMANAGERURL ? FILEMANAGERURL : root_url().'/storage/purchase-invoices'; ?>/<?php echo $invoice['attachment']; ?>" width="40" height="40">
				              	</a>
				            <?php endif;?>
						</td>
					</tr>
				</tbody>
			</table>

			<h4><?php echo trans('text_product_list'); ?></h4>
			
			<div class="table-responsive">
				<table class="table table-bordered margin-b0 table-condensed">
					<thead>
					<tr class="bg-gray">
						<th class="w-60"><?php echo trans('label_product'); ?></th>
						<th class="w-20 text-right">
							<?php echo trans('label_cost'); ?>
						</th>
						<th class="w-20 text-right"">
							<?php echo trans('label_sub_total'); ?>
						</th>
					</tr>
					</thead>
					<tbody>
						<?php 
						$subtotal = 0;
						foreach ($invoice_items as $product) : ?>
							<tr>
								<td>
									<?php echo $product['item_name']; ?> ( x<?php echo currency_format($product['item_quantity']); ?> <?php echo $product['unitName']; ?> )
								</td>
								<td class="text-right">
									<?php echo currency_format($product['item_purchase_price']); ?>
								</td>
								<td class="text-right">
									<?php 
									$subtotal += $product['item_total'];
									echo currency_format($product['item_total']); ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
						<tr class="bg-gray">
							<td class="text-right" colspan="2">
								<?php echo trans('label_subtotal'); ?>
							</td>
							<td class="w-20 text-right">
								<?php echo currency_format($subtotal); ?>
							</td>
						</tr>
						<tr class="bg-gray">
							<td class="text-right" colspan="2">
								<?php echo trans('label_order_tax'); ?>
							</td>
							<td class="w-20 text-right">
								<?php echo currency_format($invoice['order_tax']); ?>
							</td>
						</tr>
						<tr class="bg-gray">
							<td class="text-right" colspan="2">
								<?php echo trans('label_shipping_charge'); ?>
							</td>
							<td class="w-20 text-right">
								<?php echo currency_format($invoice['shipping_amount']); ?>
							</td>
						</tr>
						<tr class="bg-gray">
							<td class="text-right" colspan="2">
								<?php echo trans('label_others_charge'); ?>
							</td>
							<td class="w-20 text-right">
								<?php echo currency_format($invoice['others_charge']); ?>
							</td>
						</tr>
						<tr class="bg-gray">
							<td class="text-right" colspan="2">
								<?php echo trans('label_discount'); ?>
							</td>
							<td class="w-20 text-right">
								<?php echo currency_format($invoice['discount_amount']); ?>
							</td>
						</tr>
						<tr class="bg-gray">
							<td class="text-right" colspan="2">
								<?php echo trans('label_paid_amount'); ?>
							</td>
							<td class="w-20 text-right">
								<?php echo currency_format($invoice['paid_amount']); ?>
							</td>
						</tr>
						<tr class="danger">
							<td class="text-right" colspan="2">
								<?php echo trans('label_due_amount'); ?>
							</td>
							<td class="w-20 text-right">
								<?php echo currency_format($invoice['due']); ?>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>

			<?php if (!empty($returns)) : ?>
			<h4><?php echo trans('text_returns'); ?></h4>
			<div class="table-responsive">
				<table class="table table-bordered margin-b0 table-condensed">
					<thead>
						<tr class="bg-gray">
							<th class="w-5 text-center"><?php echo trans('label_sl');?></th>
							<th class="w-30 text-center"><?php echo trans('label_returned_at');?></th>
							<th class="w-40 text-center"><?php echo trans('label_item_name');?></th>
							<th class="w-25 text-center"><?php echo trans('label_quantity');?></th>
						</tr>
					</thead>
					<tbody>
                        <?php $inc=1;foreach ($returns as $row):?>
                          <tr>
                            <td class="w-5 text-center"><?php echo $inc;?></td>
                            <td class="w-30 text-center"><?php echo format_date($row['created_at']);?></td>
                            <td class="w-40 text-center"><?php echo $row['item_name'];?></td>
                            <td class="w-25 text-center"><?php echo currency_format($row['item_quantity']);?> <?php echo get_the_unit(get_the_product($row['item_id'],'unit_id'), 'unit_name');?></td>
                          <t/r>
                        <?php $inc++;endforeach; ?>
					</tbody>
				</table>
			</div>
			<?php endif; ?>

			<?php if (!empty($payments)) : ?>
			<h4><?php echo trans('text_payments'); ?></h4>
			<div class="table-responsive">
				<table class="table table-bordered margin-b0 table-condensed">
					<thead>
						<tr class="bg-gray">
							<th class="w-50 text-center"><?php echo trans('label_description');?></th>
							<th class="w-25">&nbsp;</th>
							<th class="w-25">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
                        <?php foreach ($payments as $row) : 
                          if ($row['type'] == 'return') {
                            $color = 'danger';
                          } elseif ($row['type'] == 'change') {
                            $color = 'info';
                          } elseif ($row['type'] == 'discount') {
                            $color = 'warning';
                          } else {
                            $color = 'success';
                          }
                          ?>
                          <tr class="bt-1 <?php echo $color;?>">
                            <td class="w-50 text-right">
                              <?php if ($row['type'] == 'return') : ?>
                                <small><i>Return on</i></small> <?php echo $row['created_at'];?> <small><i>by</i></small> <?php echo get_the_user($row['created_by'], 'username');?>
                              <?php elseif ($row['type'] == 'change') : ?>
                                <small><i>Change on</i></small> <?php echo $row['created_at'];?> <small><i>by</i></small> <?php echo get_the_user($row['created_by'], 'username');?>
                              <?php elseif ($row['type'] == 'discount') : ?>
                                <small><i>Discount on</i></small> <?php echo $row['created_at'];?> <small><i>by</i></small> <?php echo get_the_user($row['created_by'], 'username');?>
                              <?php elseif ($row['type'] == 'due_paid') : ?>
                                <small><i>Duepaid on</i></small> <?php echo $row['created_at'];?> 
                                <?php if ($row['pmethod_id']) : ?>
                                (via <?php echo get_the_pmethod($row['pmethod_id'], 'name');?>)
                                <?php endif; ?>
                                by <?php echo get_the_user($row['created_by'], 'username');?>
                              <?php else : ?>
                                <small><i>Paid on</i></small> <?php echo $row['created_at'];?> 
                                <?php if ($row['pmethod_id']) : ?>
                                (via <?php echo get_the_pmethod($row['pmethod_id'], 'name');?>)
                                <?php endif; ?>
                                by <?php echo get_the_user($row['created_by'], 'username');?>
                              <?php endif; ?>
                            </td>
                            <td class="w-25 text-right">
                              <?php if ($row['type'] == 'return') : ?>
                                <?php echo trans('label_amount'); ?>:&nbsp; <?php echo currency_format($row['amount']); ?>
                              <?php elseif ($row['type'] == 'change') : ?>
                                &nbsp;
                              <?php else : ?>
                                <?php echo trans('label_amount'); ?>:&nbsp; <?php echo currency_format($row['total_paid']); ?>
                              <?php endif; ?>
                            </td>
                            <td class="w-25 text-right">
                              <?php if ($row['type'] != 'return' && $row['balance'] > 0) : ?>
                                <?php echo trans('label_change'); ?>:&nbsp; <?php echo currency_format($row['balance']); ?>
                              <?php else: ?>
                                &nbsp;
                              <?php endif; ?>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      
					</tbody>
				</table>
			</div>
			<?php endif; ?>

		</div>
	</div>
</div>