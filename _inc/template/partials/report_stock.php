<?php
  $hide_colums = "1, ";
?> 
<div class="table-responsive" ng-controller="ReportStockController">
  <table id="report_stock" class="table table-striped" data-hide-colums="<?php echo $hide_colums; ?>">
    <thead>
      <tr class="bg-gray">
        <th class="w-5">#</th>
        <th class="w-25">
          <?php echo trans('supplier_name'); ?>
        </th>
        <th class="w-30">
          <?php echo sprintf(trans('label_name'), null); ?>
        </th>
        <th class="text-right w-10">
          <?php echo trans('label_available'); ?>
        </th>
        <th class="text-right w-15">
          <?php echo trans('label_sell_price'); ?>
        </th>
        <th class="text-right w-15">
          <?php echo trans('label_purchase_price'); ?>
        </th>
      </tr>
    </thead>
    <tfoot>
      <tr class="bg-gray">
        <th class="w-5">#</th>
        <th class="w-25">
          <?php echo trans('supplier_name'); ?>
        </th>
        <th class="w-30">
          <?php echo sprintf(trans('label_name'), null); ?>
        </th>
        <th class="text-right w-10">
          <?php echo trans('label_available'); ?>
        </th>
        <th class="text-right w-15">
          <?php echo trans('label_sell_price'); ?>
        </th>
        <th class="text-right w-15">
          <?php echo trans('label_purchase_price'); ?>
        </th>
      </tr>
    </tfoot>
  </table>
</div>