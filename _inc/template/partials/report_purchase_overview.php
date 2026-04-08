<div class="table-responsive">
  <h4 class="text-center"><b><?php echo trans('text_title_purchase_overview'); ?></b></h4>
  <table class="table table-striped table-condenced">
    <tbody>
      <tr>
        <td class="text-center bg-blue">
          <h4><?php echo trans('text_purchase_amount'); ?></h4>
          <h2 class="price">
            <?php $purchase_price = purchase_price(from(), to());
            echo currency_format($purchase_price); ?>
          </h2>
          <br>
          <a href="report_purchase_itemwise.php" class="text-yellow">
            <?php echo trans('button_details'); ?> &rarr;
          </a>
        </td>
        <td class="text-center bg-green">
          <h4><?php echo trans('text_discount_amount'); ?></h4>
          <h2 class="price">
            <?php echo currency_format(purchase_discount_amount(from(), to())); ?>
          </h2>
        </td>
        <td class="text-center bg-red">
          <h4><?php echo trans('text_due_taken'); ?></h4>
          <h2 class="price">
            <?php 
              $total_due_amount = purchase_due_amount(from(), to());
              echo currency_format($total_due_amount);?>
          </h2>
          <br>
          <a href="purchase.php?type=due" class="text-yellow">
            <?php echo trans('button_details'); ?> &rarr;
          </a>
        </td>
      </tr>
      <tr>
        <td class="text-center bg-green">
          <h4><?php echo trans('text_due_paid'); ?></h4>
          <h2 class="price">
            <?php $due_paid_amount = purchase_due_paid_amount(from(), to());
            echo currency_format($due_paid_amount); ?>
          </h2>
          <br>
          <a href="report_supplier_due_paid.php" class="text-yellow">
            <?php echo trans('button_details'); ?> &rarr;
          </a>
        </td>
        <td class="text-center bg-purple">
          <h4><?php echo trans('text_shipping_charge'); ?></h4>
          <h2 class="price">
            <?php 
              $shipping_charge = purchase_shipping_charge(from(), to());
              echo currency_format($shipping_charge); ?>
          </h2>
          <br>
          <a href="purchase.php?type=paid" class="text-yellow">
            <?php echo trans('button_details'); ?> &rarr;
          </a>
        </td>
        <td class="text-center bg-yellow">
          <h4><?php echo trans('text_others_charge'); ?></h4>
          <h2 class="price">
            <?php 
              $others_charge = purchase_others_charge(from(), to());
              echo currency_format($others_charge); ?>
          </h2>
          <br>
          <a href="purchase.php?type=paid" class="text-yellow">
            <?php echo trans('button_details'); ?> &rarr;
          </a>
        </td>
      </tr>
      <tr>
        <td class="text-center bg-purple">
          <h4><?php echo trans('text_total_paid'); ?></h4>
          <h2 class="price">
            <?php 
              $purchase_total_paid = purchase_total_paid(from(), to());
              echo currency_format($purchase_total_paid); ?>
          </h2>
          <br>
          <a href="purchase.php?type=paid" class="text-yellow">
            <?php echo trans('button_details'); ?> &rarr;
          </a>
        </td> 
        <td class="text-center danger">
          <h4><?php echo trans('text_return_amount'); ?></h4>
          <h2 class="price">
            <?php $purchase_return_amount = purchase_return_amount(from(), to());
            echo currency_format($purchase_return_amount); ?>
          </h2>
          <br>
          <a href="purchase_return.php" class="text-yellow">
            <?php echo trans('button_details'); ?> &rarr;
          </a>
        </td>
        <td class="text-center">&nbsp;</td>
      </tr>
    </tbody>
  </table>

  <table class="table table-striped table-condenced">
    <tbody>  
      <tr>
        <td class="text-center bg-info">
          <h4><?php echo trans('text_order_tax'); ?></h4>
          <h2 class="price">
            <?php 
            $order_tax = get_purchase_tax('order_tax',from(), to());
            echo currency_format($order_tax); ?>
          </h2>
          <br>
          <a href="report_purchase_tax.php">
            <?php echo trans('button_details'); ?> &rarr;
          </a>
        </td>
        <td class="text-center bg-warning">
          <h4><?php echo trans('text_item_tax'); ?></h4>
          <h2 class="price">
            <?php 
            $item_tax = get_in_or_exclusive_purchase_tax('exclusive',from(), to());
            echo currency_format($item_tax); ?>
          </h2>
          <br>
          <a href="report_purchase_tax.php">
            <?php echo trans('button_details'); ?> &rarr;
          </a>
        </td>
        <td class="text-center bg-info">
          <h4><?php echo trans('text_total_tax'); ?></h4>
          <h2 class="price">
            <?php 
              $total_tax = $order_tax + $item_tax;
              echo currency_format($total_tax); ?>
          </h2>
          <br>
          <a href="expense.php" class="text-warning">
            <?php echo trans('button_details'); ?> &rarr;
          </a>
        </td>
      </tr>
      <?php 
      $purchase_tax_return_amount = purchase_tax_return_amount(from(), to());
      if ($purchase_tax_return_amount > 0):?>
      <tr>
        <td class="text-center danger">&nbsp;</td>
        <td class="text-center danger">
          <h4><?php echo trans('text_substract_amount'); ?> <small>(for sell return)</small></h4>
          <h2 class="price">
            <?php echo currency_format($purchase_tax_return_amount); ?>
          </h2>
        </td>
        <td class="text-center danger">&nbsp;</td>
      </tr>
      <?php endif;?>
    </tbody>
  </table>

  <?php if (get_preference('invoice_view') == 'indian_gst') : ?>
  <h4 class="text-center"><b><?php echo trans('text_purchase_tax'); ?> (GST)</b></h4>
  <table class="table table-striped table-condenced">
    <tbody>  
      <tr>
        <td class="text-center bg-info">
          <h4><?php echo trans('text_igst'); ?></h4>
          <h2 class="price">
            <?php $igst = get_purchase_tax('igst', from(), to());
            echo currency_format($igst); ?>
          </h2>
          <br>
          <a href="report_purchase_tax.php">
            <?php echo trans('button_details'); ?> &rarr;
          </a>
        </td>
        <td class="text-center bg-warning">
          <h4><?php echo trans('text_cgst'); ?></h4>
          <h2 class="price">
            <?php 
            $cgst = get_purchase_tax('cgst', from(), to());
            echo currency_format($cgst); ?>
          </h2>
          <br>
          <a href="report_purchase_tax.php">
            <?php echo trans('button_details'); ?> &rarr;
          </a>
        </td>
        <td class="text-center bg-info">
          <h4><?php echo trans('text_sgst'); ?></h4>
          <h2 class="price">
            <?php 
              $sgst = get_purchase_tax('sgst', from(), to());
              echo currency_format($sgst); ?>
          </h2>
          <br>
          <a href="report_purchase_tax.php">
            <?php echo trans('button_details'); ?> &rarr;
          </a>
        </td>
      </tr>
      <?php 
      $purchase_gst_return_amount = purchase_gst_return_amount(from(), to());
      if ($purchase_gst_return_amount > 0):?>
      <tr>
        <td class="text-center danger">&nbsp;</td>
        <td class="text-center danger">
          <h4><?php echo trans('text_substract_amount'); ?> <small>(for sell return)</small></h4>
          <h2 class="price">
            <?php echo currency_format($purchase_gst_return_amount); ?>
          </h2>
        </td>
        <td class="text-center danger">&nbsp;</td>
      </tr>
      <?php endif;?>
    </tbody>
  </table>
  <?php endif; ?>

</div>