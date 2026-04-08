<table class="table table-striped no-margin">
  <thead>
    <tr class="bg-gray">
      <th class="text-center"><?php echo trans('label_date'); ?></th>
      <th class="text-center"><?php echo trans('label_invoice_id'); ?></th>
      <th class="text-center"><?php echo trans('label_from'); ?></th>
      <th class="text-center"><?php echo trans('label_to'); ?></th>
      <th class="text-center"><?php echo trans('label_status'); ?></th>
      <th class="text-center"><?php echo trans('label_quantity'); ?></th>
    </tr>
  </thead>
  <tbody>
    <?php if ($transfers = get_transfers(store_id(), 5)) : ?>
      <?php foreach ($transfers as $row) : ?>
        <tr>
          <td class="text-center"><?php echo $row['created_at'];?></td>
          <td class="text-center"><a href="<?php echo root_url();?>/admin/transfer.php?invoice_id=<?php echo $row['ref_no'];?>"><?php echo $row['ref_no'];?></a></td>
          <td class="text-center"><?php echo store_field('name',$row['from_store_id']);?></td>
          <td class="text-center"><?php echo store_field('name',$row['to_store_id']);?></td>
          <td class="text-center"><?php echo ucfirst($row['status']);?></span></td>
          <td class="text-center"><?php echo $row['total_quantity'];?></span></td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>