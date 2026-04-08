<div class="table-responsive">
  <table class="table table-striped table-condenced mb-0">
    <tbody>
      <tr>
        <td class="text-center bg-blue">
          <h4><?php echo trans('text_total_loan'); ?></h4>
          <h2 class="price">
              <?php 
              $total_loan = get_total_loan(from(), to());
              echo currency_format($total_loan); ?>
          </h2>
        </td>
        <td class="text-center bg-green">
          <h4><?php echo trans('text_total_paid'); ?></h4>
          <h2 class="price">
            <?php echo currency_format(get_total_loan_paid(from(), to())); ?>
          </h2>
        </td>
        <td class="text-center bg-red">
          <h4><?php echo trans('text_total_due'); ?></h4>
          <h2 class="price">
            <?php 
              $total_due = get_total_laon_due(from(), to());
              echo currency_format($total_due);?>
          </h2>
          <br>
        </td>
      </tr>
    </tbody>
  </table>
</div>