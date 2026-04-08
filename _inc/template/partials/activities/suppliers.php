<table class="table table-striped no-margin">
  <thead>
    <tr class="bg-gray">
      <th><?php echo trans('label_supplier_name'); ?></th>
      <th><?php echo trans('label_phone'); ?></th>
      <th><?php echo trans('label_email'); ?></th>
      <th><?php echo trans('label_address'); ?></th>
      <th><?php echo trans('label_created_at'); ?></th>
    </tr>
  </thead>
  <tbody>
    <?php if ($suppliers = get_suppliers(array('start'=>0,'limit'=>5,'order'=>'DESC'))) : ?>
      <?php foreach ($suppliers as $row) : ?>
        <tr>
          <td><a href="<?php echo root_url();?>/admin/supplier_profile.php?sup_id=<?php echo $row['sup_id'];?>"><?php echo $row['sup_name'];?></a></td>
          <td><?php echo $row['sup_mobile'];?></td>
          <td><?php echo $row['sup_email'];?></td>
          <td><?php echo $row['sup_address'];?></td>
          <td><?php echo $row['created_at'];?></td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>