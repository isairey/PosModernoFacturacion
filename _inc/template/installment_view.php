<div class="row">
	<div class="col-md-12">
		<table class="table table-bordered table-striped table-condensed">
			<tbody>
				<tr>
					<th class="w-30 text-right bg-gray">
						<?php echo trans('label_customer_name'); ?>
					</th>
					<td>
						<?php echo get_the_customer($invoice['customer_id'], 'customer_name');?>
					</td>
				</tr>
				<tr>
					<th class="w-30 text-right bg-gray">
						<?php echo trans('label_phone'); ?>
					</th>
					<td>
						<?php echo get_the_customer($invoice['customer_id'], 'customer_mobile');?>
					</td>
				</tr>
			</tbody>
		</table>

		<h4><?php echo trans('text_payments'); ?></h4>
	
		<div class="table-responsive">
			<table class="table table-bordered table-striped margin-b0">
				<thead>
					<tr class="bg-gray">
						<td class="text-center">
							<?php echo trans('label_payment_date'); ?>
						</td>
						<td class="text-right">
							<?php echo trans('label_interest'); ?>
						</td>
						<td class="text-right">
							<?php echo trans('label_payable'); ?>
						</td>
						<td class="text-right">
							<?php echo trans('label_paid'); ?>
						</td>
						<td class="text-right">
							<?php echo trans('label_due'); ?>
						</td>
						<td class="text-center">
							<?php echo trans('label_status'); ?>
						</td>
						<td class="text-center">
							<?php echo trans('label_action'); ?>
						</td>
						<td class="text-center">
							<?php echo trans('label_note'); ?>
						</td>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($payments)) : ?>
						<?php foreach ($payments as $payment): ?>
                          	<tr class="bt-1">
	                            <td class="w-30 text-center">
	                                <?php echo $payment['payment_date'];?>
	                            </td>
	                            <td class="w-10 text-right">
	                                <?php echo currency_format($payment['interest']);?>
	                            </td>
	                            <td class="w-10 text-right">
	                                <?php echo currency_format($payment['payable']);?>
	                            </td>
	                            <td class="w-10 text-right">
	                                <?php echo currency_format($payment['paid']);?>
	                            </td>
	                            <td class="w-10 text-right">
	                                <?php echo currency_format($payment['due']);?>
	                            </td>
	                            <td class="w-10 text-center">
	                                <?php echo ucfirst($payment['payment_status']);?>
	                            </td>
	                            <td class="w-10 text-center">
	                            	<?php if ($payment['payment_status'] == 'due') : ?>
	                                	<button ng-click="payForm(<?php echo $payment['id'];?>)" class="btn btn-xs btn-info btn-block"><span class="fa fa-money"></span></button>
	                                <?php else: ?>
	                                	<span class="label label-success">
	                                		<i class="fa fa-fw fa-check"></i> <?php echo trans('text_paid'); ?> &nbsp;
                                		</span>
	                                <?php endif; ?>
	                            </td>
	                            <td class="w-5 text-center text-green">
	                            	<?php if ($payment['note']): ?>
	                            		<span class="fa fa-comments" data-toggle="tooltip" data-original-title="<?php echo $payment['note'];?>"></span>
	                            	<?php endif; ?>
	                            </td>
                          	</tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
				</tbody>
			</table>
		</div>

		<h4 class="mt-20"><?php echo trans('text_installment_details'); ?></h4>

		<div class="table-responsive">
			<table class="table table-bordered table-striped mb-0">
				<tbody>
					<tr>
						<td class="w-30 text-right bg-gray">
							<?php echo trans('label_invoice_id'); ?>
						</td>
						<td class="w-70">
							<?php echo $invoice['invoice_id']; ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 text-right bg-gray">
							<?php echo trans('label_payable_amount'); ?>
						</td>
						<td class="w-70">
							<?php echo currency_format($invoice['payable_amount']); ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 text-right bg-gray">
							<?php echo trans('label_initial_payment'); ?>
						</td>
						<td class="w-70">
							<?php echo currency_format($invoice['initial_amount']); ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 text-right bg-gray">
							<?php echo trans('label_due'); ?>
						</td>
						<td class="w-70">
							<?php echo currency_format($invoice['due']); ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 text-right bg-gray">
							<?php echo trans('label_interest'); ?>
						</td>
						<td class="w-70">
							<?php echo currency_format($invoice['interest_percentage']); ?> %
						</td>
					</tr>
					<tr>
						<td class="w-30 text-right bg-gray">
							<?php echo trans('label_interest_amount'); ?>
						</td>
						<td class="w-70">
							<?php echo currency_format($invoice['interest_amount']); ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 text-right bg-gray">
							<?php echo trans('label_duration'); ?>
						</td>
						<td class="w-70">
							<?php echo $invoice['duration']; ?> days
						</td>
					</tr>
					<tr>
						<td class="w-30 text-right bg-gray">
							<?php echo trans('label_interval_count'); ?>
						</td>
						<td class="w-70">
							<?php echo $invoice['interval_count']; ?> days
						</td>
					</tr>
					<tr>
						<td class="w-30 text-right bg-gray">
							<?php echo trans('label_installment_count'); ?>
						</td>
						<td class="w-70">
							<?php echo $invoice['installment_count']; ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 text-right <?php echo $invoice['payment_status'] == 'due' ? 'bg-red' : 'bg-green';?>">
							<?php echo trans('label_payment_status'); ?>
						</td>
						<td class="w-70 <?php echo $invoice['payment_status'] == 'due' ? 'text-red' : 'text-green';?>">
							<?php echo ucfirst($invoice['payment_status']); ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 text-right bg-gray">
							<?php echo trans('label_last_installment_date'); ?>
						</td>
						<td class="w-70">
							<?php echo date("j M Y g:i A", strtotime($invoice['last_installment_date'])); ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 text-right bg-gray">
							<?php echo trans('label_installment_end_date'); ?>
						</td>
						<td class="w-70">
							<?php echo date("j M Y", strtotime($invoice['installment_end_date'])); ?>
						</td>
					</tr>
					<tr>
						<td class="w-30 text-right bg-gray">
							<?php echo trans('label_created_at'); ?>
						</td>
						<td class="w-70">
							<?php echo date("j M Y g:i A", strtotime($invoice['created_at'])); ?>
						</td>
					</tr>
				</tbody>
			</table>

		</div>
	</div>
</div>