<table class="table table-striped no-margin">
  <thead>
    <tr class="bg-gray">
      <th class="text-center"><?php echo trans('label_invoice_id'); ?></th>
      <th class="text-center"><?php echo trans('label_created_at'); ?></th>
      <th class="text-left"><?php echo trans('label_customer_name'); ?></th>
      <th class="text-right"><?php echo trans('label_amount'); ?></th>
      <th class="text-center"><?php echo trans('label_payment_status'); ?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach  (get_invoices('sell', store_id(), 5) as $row) : ?>
      <tr>
        <td class="text-center"><a ng-click="InvoiceViewModal({'invoice_id':'<?php echo $row['invoice_id'];?>'})" onClick="return false;" href="<?php echo root_url();?>/admin/view_invoice.php?invoice_id=<?php echo $row['invoice_id'];?>"><?php echo $row['invoice_id'];?></a></td>
        <td class="text-center"><?php echo $row['created_at'];?></td>
        <td class="text-left"><?php echo get_the_customer($row['customer_id'], 'customer_name');?></td>
        <td class="text-right"><?php echo currency_format($row['payable_amount']);?></td>
        <td class="text-center"><?php echo $row['payment_status'] == 'paid' ? '<span class="label label-success">'.trans('text_paid').'</span>' : '<span class="label label-danger">'.trans('text_due').'</span>';?></span></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>