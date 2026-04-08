<div class="progress-group-container">
  <div class="progress-group">
    <span class="progress-text"><?php echo trans('text_sales_amount'); ?></span>
    <span class="progress-number">
      <?php 
      $invoice_amount = selling_price(from(), to());
      $discount_amount = discount_amount(from(), to());
      $due_amount = due_amount(from(), to());
      $received_amount = sell_received_amount(from(), to());
      echo currency_format($invoice_amount);?></span>
    <div class="progress sm">
      <div class="progress-bar progress-bar-aqua" style="width: <?php echo get_progress_percentage($invoice_amount, $discount_amount+$due_amount);?>%"></div>
    </div>
  </div>
  <div class="progress-group">
    <span class="progress-text"><?php echo trans('text_discount_given'); ?></span>
    <span class="progress-number"><?php echo currency_format($discount_amount); ?></span>
    <div class="progress sm">
      <div class="progress-bar progress-bar-warning" style="width:<?php echo ($discount_amount/$invoice_amount)*100;?>%"></div>
    </div>
  </div>
  <div class="progress-group">
    <span class="progress-text"><?php echo trans('text_due_given'); ?></span>
    <span class="progress-number"><?php echo currency_format($due_amount); ?></span>
    <div class="progress sm">
      <div class="progress-bar progress-bar-red" style="width: <?php echo ($due_amount/$invoice_amount)*100;?>%"></div>
    </div>
  </div>
  <div class="progress-group">
    <span class="progress-text"><?php echo trans('text_received_amount'); ?></span>
    <span class="progress-number"><?php echo currency_format($received_amount); ?></span>
    <div class="progress sm">
      <div class="progress-bar progress-bar-success" style="width: <?php echo ($received_amount/$invoice_amount)*100;?>%"></div>
    </div>
  </div>
  <a href="<?php root_url();?>/admin/report_overview.php" class="btn btn-sm btn-block btn-warning btn-flat"><?php echo trans('button_overview_report'); ?> &rarr;</a>
</div>