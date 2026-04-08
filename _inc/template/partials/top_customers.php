<?php 
if (top_customers(from(), to(), 5)) {
  foreach (top_customers(from(), to(), 5) as $customer) {
    $top_customers['name'][] = limit_char(get_the_customer($customer['customer_id'], 'customer_name'),15);
    $top_customers['quantity'][] = currency_format($customer['total']);
  } 
} else {
  $top_customers['name'] = array();
  $top_customers['quantity'] = array();
}
?>

<div class="box box-info">
  <div class="box-header with-border">
    <h3 class="box-title">
      <?php echo trans('text_top_customers'); ?>
    </h3>
  </div>
  <div class="box-body">
    <canvas id="topCustomers" height="250"></canvas>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
  var topCustomers = <?php echo json_encode(array_values($top_customers['name'])); ?>;
  var topCustomersQuantity = <?php echo json_encode(array_values($top_customers['quantity'])); ?>;
  var ctx = document.getElementById("topCustomers");
  var myPieChart = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: topCustomers,
        datasets: [
            {
              label: "Top",
              backgroundColor: ["#e6194B", "#f58231", "#ffe119", "#3cb44b", "#4363d8", "#f032e6", "#42d4f4", "#9A6324", "#469990", "#fabebe"],
              data: topCustomersQuantity
            },
        ],
      },
      options: {
          responsive: true,
          tooltips: {
              mode: 'index',
              intersect: true
          },
          hover: {
              mode: 'nearest',
              intersect: true
          }
      }
  });
});
</script>