<?php $hide_colums = "";?>
<div class="table-responsive">                     
  <table id="expense-expense-list" class="table table-bordered table-striped table-hovered" data-hide-colums="<?php echo $hide_colums; ?>">
    <thead>
      <tr class="bg-gray">
        <th class="w-5">
          <?php echo trans('label_serial_no'); ?>
        </th>
        <th class="w-35">
          <?php echo trans('label_title'); ?>
        </th>
        <th class="w-20">
          <?php echo trans('label_this_month'); ?>
        </th>
        <th class="w-20">
          <?php echo trans('label_this_year'); ?>
        </th>
        <th class="w-20">
          <?php echo trans('label_till_now'); ?>
        </th>
      </tr>
    </thead>
    <tfoot>
      <tr class="bg-gray">
        <th class="text-right" colspan="2">
          <?php echo trans('label_total'); ?>
        </th>
        <th class="w-20">
          <?php echo trans('label_this_month'); ?>
        </th>
        <th class="w-20">
          <?php echo trans('label_this_year'); ?>
        </th>
        <th class="w-25">
          <?php echo trans('label_till_now'); ?>
        </th>
      </tr>
    </tfoot>
  </table>    
</div>