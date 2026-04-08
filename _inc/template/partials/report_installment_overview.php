<div class="table-responsive">
  <table class="table table-striped table-condenced">
    <tbody>
      <tr>
        <td class="text-center bg-blue">
          <h4><?php echo trans('text_invoice_count'); ?></h4>
          <h2 class="price">
            <?php echo get_installment_invoice_count(); ?>
          </h2>
          <br>
          <a href="installment.php" class="text-yellow">
            <?php echo trans('button_details'); ?> &rarr;
          </a>
        </td>
        <td class="text-center bg-green">
          <h4><?php echo trans('text_sell_amount'); ?></h4>
          <h2 class="price">
            <?php $sell_amount = get_installment_sell_amount();
            echo currency_format($sell_amount); ?>
          </h2>
          <br>
          <!-- <a href="report_purchase_itemwise.php" class="text-yellow">
            <?php echo trans('button_details'); ?> &rarr;
          </a> -->
        </td>
        <td class="text-center bg-purple">
          <h4><?php echo trans('text_interest_amount'); ?></h4>
          <h2 class="price">
            <?php 
              $interest_amount = get_installment_intereset_amount();
              echo currency_format($interest_amount);?>
          </h2>
          <br>
          <!-- <a href="purchase.php?type=due" target="_blink">
            <?php echo trans('button_details'); ?> &rarr;
          </a> -->
        </td>
      </tr>
      <tr>
        <td class="text-center bg-yellow">
          <h4><?php echo trans('text_amount_received'); ?></h4>
          <h2 class="price">
            <?php 
            $total_received_amount = get_installment_received_amount();
            echo currency_format($total_received_amount); ?>
          </h2>
          <br>
          <!-- <a href="report_supplier_due_paid.php" class="text-red">
            <?php echo trans('button_details'); ?> &rarr;
          </a> -->
        </td>
        <td class="text-center bg-red">
          <h4><?php echo trans('text_amount_due'); ?></h4>
          <h2 class="price">
            <?php 
              $total_due_amount = get_installment_due_amount();
              echo currency_format($total_due_amount); ?>
          </h2>
          <br>
          <a href="installment_payment.php?type=all_due_payment" class="text-yellow">
            <?php echo trans('button_details'); ?> &rarr;
          </a>
        </td>
        <td class="text-center">&nbsp;</td>
        <td class="text-center">&nbsp;</td>
      </tr>
    </tbody>
  </table>
</div>