<?php
$year = from() ? date('Y', strtotime(from())) : year();
$month = from() ? date('m', strtotime(from())) : month();
?>
<div class="box box-info mb-0"> 
  <div class="box-header with-border">
    <h4 class="box-title">
      <?php echo trans('title_income_vs_expense'); ?>
      &rarr;<?php echo date("F", mktime(0, 0, 0, $month, 10)) . ', ' .$year; ?>
    </h4>
    <div class="box-tools pull-right">
      <div class="btn-group">
        <a class="btn btn-xs btn-info" href="income-vs-expense.js" id="income-expense-graph"><span class="fa fa-fw fa-download"></span><?php echo trans('text_download_as_jpg');?></a>
      </div>
    </div>
  </div>
  <div class="box-body">
      <canvas id="income-vs-expense" class="report-chart"></canvas>
  </div>
  <div class="box-footer text-center">
    <a href="report_income_and_expense.php">
      <?php echo trans('text_details'); ?> <i class="fa fa-arrow-circle-right"></i>
    </a>
  </div>
</div>

<?php 
$days_array = array();
$incomes = array();
$expenses = array();
$total_days = get_total_day_in_month() + 1;
for ($i=1; $i < $total_days; $i++) { 
  $from = date('Y-m-d',strtotime($year.'-'.$month.'-'.$i));
  $days_array[] = trans('label_day').': ' . $i;
  // $total = selling_price_daywise($year, $month, $i);
  $incomes[] = number_format(get_total_substract_income($from, $from), 2, '.', '');
  // $incomes[] = $total ? number_format((float)$total, 2, '.', '') : 0;
  // $total = profit_amount_daywise($year, $month, $i);
  $expenses[] = number_format(get_total_expense($from, $from), 2, '.', '');
  // $expenses[] = $total ? number_format((float)$total, 2, '.', '') : 0;
}
// dd($expenses);
?>

<script type="text/javascript"> 
$(function() {
  var labels = <?php echo json_encode($days_array); ?>;
  var incomes = <?php echo json_encode($incomes); ?>;
  var expenses = <?php echo json_encode($expenses); ?>;
  // var profitData = <?php echo json_encode($expenses); ?>;
  var ctx = document.getElementById("income-vs-expense");
  ctx.height = 80;
  var myChart = new Chart(ctx, {
      type: 'bar',
      data: {
          labels: labels,
          datasets: [
              {
                  label: "<?php echo trans('text_income');?>",
                  borderColor: "#27CDF7",
                  borderWidth: "1",
                  backgroundColor: "#27CDF7",
                  pointHighlightStroke: "rgba(26,179,148,1)",
                  data: incomes
              },
              {
                  label: "<?php echo trans('text_expense');?>",
                  borderColor: "#27CDF7",
                  borderWidth: "1",
                  backgroundColor: "#00A65A",
                  pointHighlightStroke: "rgba(26,179,148,1)",
                  data: expenses
              },
          ]
      },
      options: {
          responsive: true,
          tooltips: {
              mode: 'index',
              intersect: false
          },
          hover: {
              mode: 'nearest',
              intersect: true
          },
          barPercentage: 0.5
      }
  });
  $("#income-expense-graph").on("click",function(e) {
    var link = $(this);
    var canvas = document.getElementById("income-vs-expense");
    var img    = canvas.toDataURL("image/png");
    link.attr("href",img);
    link.attr("download", "<?php echo trans('text_income_vs_expense');?>-"+window.formatDate(new Date())+".png");
  });
  // $("#income-vs-expense").css({height:"500px", paddingRight:"40px",paddingLeft:"40px"});
});
</script>