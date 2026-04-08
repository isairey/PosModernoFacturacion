<style type="text/css">

@media only print {
	.print-modal-content {
	     position: absolute;
	     overflow: auto;
	     width: 100%;
	     height: 100%;
	     z-index: 100000; /* CSS doesn't support infinity */
	}
	.modal-dialog {
		display: none;
	}
}

</style>

<?php $customer_name = get_the_customer($quotation['customer_id'],'customer_name');

// Qrcode
$qrcode_text = 'Reference No.: ' . $quotation['reference_no'] . ', Customer: ' . $customer_name;
include(DIR_VENDOR.'/phpqrcode/qrlib.php');
QRcode::png($qrcode_text, ROOT.'/storage/qrcode.png', 'L', 3, 1);
?>

<div id="quotataion-view">

	<!-- <h2 style="text-align:center;margin:0 0 10px 0;padding:0;"><?php echo store('name');?></h2> -->
	<div class="logo" style="text-align:center;margin-bottom:10px;">
	<?php if ($store->get('logo')): ?>
	  <img src="<?php echo root_url(); ?>/assets/itsolution24/img/logo-favicons/<?php echo $store->get('logo'); ?>" width="auto" height="60">
	<?php else: ?>
	  <img src="<?php echo root_url(); ?>/assets/itsolution24/img/logo-favicons/nologo.png" width="auto" height="60">
	<?php endif; ?>
	</div>


	<table class="table table-bordered">
		<tbody>
			<tr>
				<td style="width:50%;vertical-align:top;">
					<h6 style="font-style:italic;font-weight:bold;"><?php echo trans('label_from');?>:</h6>
					<address>
						<h4 style="font-weight:bold;"><?php echo store('name');?></h4>
						<p><?php echo nl2br(store('address'));?></p>
						<?php if ($vat = store('vat_reg_no')):?>
						<span><?php echo trans('label_vat_number');?>: <?php echo $vat;?></span><br>
						<?php endif; ?>
						<?php if (get_preference('invoice_view') == 'indian_gst'):?>
				            <?php if (get_preference('gst_reg_no')):?>
				              <span><?php echo trans('label_gst_reg_no'); ?>: <?php echo get_preference('gst_reg_no'); ?></span>
				            <?php endif;?>
				        <?php endif;?>
						<span><?php echo trans('label_mobile');?>: <?php echo store('mobile');?></span><br>
						<span><?php echo trans('label_email');?>: <?php echo get_preference('smtp_username');?></span><br>
						<span><?php echo trans('label_date');?>: <?php echo format_date($quotation['created_at']);?></span><br>
						<span><?php echo trans('label_reference_no');?>: <?php echo $quotation['reference_no'];?></span><br>
					</address>
				</td>
				<td style="width:50%;vertical-align:top;">
					<h6 style="font-style:italic;font-weight:bold;"><?php echo trans('label_to');?>:</h6>
					<address>
						<h4 style="font-weight:bold;"><?php echo $customer_name;?></h4>
						<p><?php echo nl2br(get_the_customer($quotation['customer_id'],'customer_address'));?></p>
						<span><?php echo trans('label_mobile');?>: <?php echo get_the_customer($quotation['customer_id'],'customer_mobile');?></span><br>
						<span><?php echo trans('label_email');?>: <?php echo get_the_customer($quotation['customer_id'],'customer_email');?></span><br>
						<div class="qrcode">
						  <img src="<?php echo root_url();?>/storage/qrcode.png">
						</div>
					</address>
				</td>
			</tr>
		</tbody>
	</table>

	<div class="row">
		<div class="col-md-12">
			<div class="table-responsive">
				<table class="table table-bordered margin-b0">
					<thead>
					<tr class="bg-gray">
						<td class="w-5" style="background:#f4f4f4;">
							<?php echo trans('label_serial_no'); ?>	
						</td>
						<td class="w-35" style="background:#f4f4f4;">
							<?php echo trans('label_product'); ?>	
						</td>
						<td class="w-20 text-right" style="background:#f4f4f4;">
							<?php echo trans('label_unit_price'); ?>
						</td>
						<td class="w-15 text-right" style="background:#f4f4f4;">
							<?php echo trans('label_item_tax'); ?>
						</td>
						<td class="w-25 text-right" style="background:#f4f4f4;">
							<?php echo trans('label_subtotal'); ?>
						</td>
					</tr>
					</thead>
					<tbody>
						<?php $inc=1;foreach ($quotation_items as $product) : ?>
							<tr>
								<td class="text-center"><?php echo $inc;?></td>
								<td>
									<?php echo $product['item_name']; ?> (x<?php echo currency_format($product['item_quantity']); ?> <?php echo $product['unitName']; ?>)
								</td>
								<td class="text-right">
									<?php echo currency_format($product['item_price']); ?>
								</td>
								<td class="text-right">
									<?php echo currency_format($product['item_tax']); ?>
								</td>
								<td class="text-right">
									<?php echo currency_format($product['item_total']); ?>
								</td>
							</tr>
						<?php $inc++;endforeach; ?>
					</tbody>
					<tfoot>
						<tr class="bg-gray">
							<td class="text-right" colspan="4">
								<?php echo trans('label_subtotal'); ?>
							</td>
							<td class="w-20 text-right">
								<?php echo currency_format($quotation['subtotal']+$quotation['item_tax']); ?>
							</td>
						</tr>
						<tr class="bg-gray">
							<td class="text-right" colspan="4">
								<?php echo trans('label_discount'); ?> (-)
							</td>
							<td class="w-20 text-right">
								<?php echo currency_format($quotation['discount_amount']); ?>
							</td>
						</tr>
						<tr class="bg-gray">
							<td class="text-right" colspan="4">
								<?php echo trans('label_order_tax'); ?> (+)
							</td>
							<td class="w-20 text-right">
								<?php echo currency_format($quotation['order_tax']); ?>
							</td>
						</tr>
						<tr class="bg-gray">
							<td class="text-right" colspan="4">
								<?php echo trans('label_shipping'); ?> (+)
							</td>
							<td class="w-20 text-right">
								<?php echo currency_format($quotation['shipping_amount']); ?>
							</td>
						</tr>
						<tr class="bg-gray">
							<td class="text-right" colspan="4" style="font-weight:bold">
								<?php echo trans('label_total_amount'); ?>
							</td>
							<td class="w-20 text-right" style="font-weight:bold">
								<?php echo currency_format($quotation['payable_amount']); ?>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>

			<table class="table margin-b0" style="margin-top:100px;">
				<tr>
					<td class="w-25" style="text-align:center;border:0;"></td>
					<td class="w-25" style="text-align:center;border:0;"></td>
					<td style="text-align:center;border:0;">
						<hr>
						<?php echo trans('label_stamp_and_signature'); ?>
					</td>
				</tr>
			</table>
				
		</div>
	</div>
</div>