<table class="table table-striped no-margin">
  <thead>
    <tr class="bg-gray">
      <th><?php echo trans('label_customer_name'); ?></th>
      <th><?php echo trans('label_phone'); ?></th>
      <th><?php echo trans('label_email'); ?></th>
      <th><?php echo trans('label_address'); ?></th>
      <th><?php echo trans('label_created_at'); ?></th>
    </tr>
  </thead>
  <tbody>
    <?php if ($customers = get_customers(array('start'=>0,'limit'=>5,'order' => 'DESC'))) : ?>
      <?php foreach ($customers as $row) : ?>
        <tr>
          <td><a href="<?php echo root_url();?>/admin/customer_profile.php?customer_id=<?php echo $row['customer_id'];?>"><?php echo $row['customer_name'];?></a></td>
          <td><?php echo $row['customer_mobile'];?></td>
          <td><?php echo $row['customer_email'];?></td>
          <td><?php echo $row['customer_address'];?></td>
          <td><?php echo $row['created_at'];?></td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>