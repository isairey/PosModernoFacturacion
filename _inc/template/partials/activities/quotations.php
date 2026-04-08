<table class="table table-striped no-margin">
  <thead>
    <tr class="bg-gray">
      <th class="text-center"><?php echo trans('label_date'); ?></th>
      <th class="text-center"><?php echo trans('label_reference_no'); ?></th>
      <th class="text-center"><?php echo trans('label_customer'); ?></th>
      <th class="text-center"><?php echo trans('label_status'); ?></th>
      <th class="text-right"><?php echo trans('label_amount'); ?></th>
    </tr>
  </thead>
  <tbody>
    <?php if ($invoices = get_quotations(store_id(), 5)) : ?>
      <?php foreach ($invoices as $row) : ?>
        <tr>
          <td class="text-center"><?php echo $row['created_at'];?></td>
          <td class="text-center"><a ng-click="QuotationViewModal({'reference_no':'<?php echo $row['reference_no'];?>'});" onClick="return false;" href="<?php echo root_url();?>/admin/quotation.php?reference_no=<?php echo $row['reference_no'];?>"><?php echo $row['reference_no'];?></a></td>
          <td class="text-center"><?php echo get_the_customer($row['customer_id'], 'customer_name');?></td>
          <td class="text-center"><?php echo $row['status'];?></td>
          <td class="text-right"><?php echo currency_format($row['payable_amount']);?></td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>