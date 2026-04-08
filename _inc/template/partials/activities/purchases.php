<table class="table table-striped no-margin">
  <thead>
    <tr class="bg-gray">
      <th class="text-center"><?php echo trans('label_invoice_id'); ?></th>
      <th class="text-center"><?php echo trans('label_created_at'); ?></th>
      <th class="text-center"><?php echo trans('label_supplier_name'); ?></th>
      <th class="text-right"><?php echo trans('label_amount'); ?></th>
      <th class="text-center"><?php echo trans('label_payment_status'); ?></th>
    </tr>
  </thead>
  <tbody>
    <?php if ($invoices = get_purchases('purchase', store_id(), 5)) : ?>
      <?php foreach ($invoices as $row) : ?>
        <tr>
          <td class="text-center"><a href="<?php echo root_url();?>/admin/purchase.php?invoice_id=<?php echo $row['invoice_id'];?>"><?php echo $row['invoice_id'];?></a></td>
          <td class="text-center"><?php echo $row['created_at'];?></td>
          <td class="text-center"><?php echo get_the_supplier($row['sup_id'], 'sup_name');?></td>
          <td class="text-right"><?php echo currency_format($row['payable_amount']);?></td>
          <td class="text-center"><?php echo $row['payment_status'] == 'paid' ? '<span class="label label-success">'.trans('text_paid').'</span>' : '<span class="label label-danger">'.trans('text_due').'</span>';?></span></td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>