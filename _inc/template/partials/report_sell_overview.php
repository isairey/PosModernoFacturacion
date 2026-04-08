<div class="table-responsive">
  <h4 class="text-center"><b><?php echo trans('text_title_sells_overview'); ?></b></h4>
  <table class="table table-striped table-condenced">
    <tbody>
      <tr>
        <td class="text-center bg-blue">
          <h4><?php echo trans('text_invoice_amount'); ?></h4>
          <h2 class="price">
              <?php 
              $invoice_amount = selling_price(from(), to());
              echo currency_format($invoice_amount); ?>
          </h2>
          <br>
          <a href="report_sell_itemwise.php" class="text-yellow">
            <?php echo trans('button_details'); ?> &rarr;
          </a>
        </td>
        <td class="text-center bg-green">
          <h4><?php echo trans('text_discount_amount'); ?></h4>
          <h2 class="price">
            <?php echo currency_format(discount_amount(from(), to())); ?>
          </h2>
        </td>
        <td class="text-center bg-red">
          <h4><?php echo trans('text_due_given'); ?></h4>
          <h2 class="price">
            <?php 
              $total_due_amount = due_amount(from(), to());
              echo currency_format($total_due_amount);?>
          </h2>
          <br>
          <a href="invoice.php?type=due" class="text-yellow">
            <?php echo trans('button_details'); ?> &rarr;
          </a>
        </td>
      </tr>
      <tr>
        <td class="text-center bg-green">
          <h4><?php echo trans('text_due_collection'); ?></h4>
          <h2 class="price">
            <?php $due_collection_amount = due_collection_amount(from(), to());
            echo currency_format($due_collection_amount); ?>
          </h2>
          <br>
          <a href="report_customer_due_collection.php" class="text-yellow">
            <?php echo trans('button_details'); ?> &rarr;
          </a>
        </td>
        <td class="text-center bg-purple">
          <h4><?php echo trans('text_shipping_charge'); ?></h4>
          <h2 class="price">
            <?php $shipping_charge = shipping_charge(from(), to());
            echo currency_format($shipping_charge); ?>
          </h2>
          <br>
          <a href="report_customer_due_collection.php" class="text-yellow">
            <?php echo trans('button_details'); ?> &rarr;
          </a>
        </td>
        <td class="text-center bg-green">
          <h4><?php echo trans('text_others_charge'); ?></h4>
          <h2 class="price">
            <?php $others_charge = others_charge(from(), to());
            echo currency_format($others_charge); ?>
          </h2>
          <br>
          <a href="report_customer_due_collection.php" class="text-yellow">
            <?php echo trans('button_details'); ?> &rarr;
          </a>
        </td>
      </tr>
      <?php 
      $sell_return_amount = selling_return_amount(from(), to());
      if ($sell_return_amount > 0):?>
      <tr>
        <td class="text-center danger">&nbsp;</td>
        <td class="text-center danger">
          <h4><?php echo trans('text_return_amount'); ?></h4>
          <h2 class="price">
            <?php echo currency_format($sell_return_amount); ?>
          </h2>
          <br>
          <a href="sell_return.php" class="text-yellow">
            <?php echo trans('button_details'); ?> &rarr;
          </a>
        </td>
        <td class="text-center danger">&nbsp;</td>
      </tr>
      <?php endif;?>
    </tbody>
  </table>

  <table class="table table-striped table-condenced">
    <tbody>  
      <tr>
        <td class="text-center bg-info">
          <h4><?php echo trans('text_order_tax'); ?></h4>
          <h2 class="price">
            <?php 
            $order_tax = get_tax('order_tax',from(), to());
            echo currency_format($order_tax); ?>
          </h2>
          <br>
          <a href="report_sell_tax.php">
            <?php echo trans('button_details'); ?> &rarr;
          </a>
        </td>
        <td class="text-center bg-success">
          <h4><?php echo trans('text_item_tax'); ?></h4>
          <h2 class="price">
            <?php 
            $item_tax = get_in_or_exclusive_tax('exclusive',from(), to());
            echo currency_format($item_tax); ?>
          </h2>
          <br>
          <a href="report_sell_tax.php">
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
          <a href="report_sell_tax.php">
            <?php echo trans('button_details'); ?> &rarr;
          </a>
        </td>
      </tr>
      <?php 
      $tax_return_amount = tax_return_amount(from(), to());
      if ($tax_return_amount > 0):?>
      <tr>
        <td class="text-center danger">&nbsp;</td>
        <td class="text-center danger">
          <h4><?php echo trans('text_substract_amount'); ?> <small>(for sell return)</small></h4>
          <h2 class="price">
            <?php echo currency_format($tax_return_amount); ?>
          </h2>
        </td>
        <td class="text-center danger">&nbsp;</td>
      </tr>
      <?php endif;?>
    </tbody>
  </table>

  <?php if (get_preference('invoice_view') == 'indian_gst') : ?>
  <h4 class="text-center"><b><?php echo trans('text_selling_tax'); ?> (GST)</b></h4>
  <table class="table table-striped table-condenced">
    <tbody>  
      <tr>
        <td class="text-center bg-success">
          <h4><?php echo trans('text_igst'); ?></h4>
          <h2 class="price">
            <?php $igst = get_tax('igst', from(), to());
            echo currency_format($igst); ?>
          </h2>
          <br>
          <a href="report_sell_tax.php">
            <?php echo trans('button_details'); ?> &rarr;
          </a>
        </td>
        <td class="text-center bg-info">
          <h4><?php echo trans('text_cgst'); ?></h4>
          <h2 class="price">
            <?php 
            $cgst = get_tax('cgst', from(), to());
            echo currency_format($cgst); ?>
          </h2>
          <br>
          <a href="report_sell_tax.php">
            <?php echo trans('button_details'); ?> &rarr;
          </a>
        </td>
        <td class="text-center bg-success">
          <h4><?php echo trans('text_sgst'); ?></h4>
          <h2 class="price">
            <?php 
              $sgst = get_tax('sgst', from(), to());
              echo currency_format($sgst); ?>
          </h2>
          <br>
          <a href="report_sell_tax.php">
            <?php echo trans('button_details'); ?> &rarr;
          </a>
        </td>
      </tr>
      <?php 
      $gst_return_amount = gst_return_amount(from(), to());
      if ($gst_return_amount > 0):?>
      <tr>
        <td class="text-center danger">&nbsp;</td>
        <td class="text-center danger">
          <h4><?php echo trans('text_substract_amount'); ?> <small>(for sell return)</small></h4>
          <h2 class="price">
            <?php echo currency_format($gst_return_amount); ?>
          </h2>
        </td>
        <td class="text-center danger">&nbsp;</td>
      </tr>
      <?php endif;?>
    </tbody>
  </table>
  <?php endif; ?>

</div>