<?php 
if (top_products(from(), to(), 5)) {
  foreach (top_products(from(), to(), 5) as $product) {
    $top_products['name'][] = limit_char($product['item_name'],15);
    $top_products['quantity'][] = currency_format($product['quantity']);
  } 
} else {
  $top_products['name'] = array();
  $top_products['quantity'] = array();
}
?>

<div class="box box-info">
  <div class="box-header with-border">
    <h3 class="box-title">
      <?php echo trans('text_top_products'); ?>
    </h3>
  </div>
  <div class="box-body">
    <canvas id="topProducts" height="250"></canvas>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
  var topProducts = <?php echo json_encode(array_values($top_products['name'])); ?>;
  var topProductsQuantity = <?php echo json_encode(array_values($top_products['quantity'])); ?>;
  var ctx = document.getElementById("topProducts");
  var myPieChart = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: topProducts,
        datasets: [
            {
              label: "Top",
              backgroundColor: ["#e6194B", "#f58231", "#ffe119", "#3cb44b", "#4363d8", "#f032e6", "#42d4f4", "#9A6324", "#469990", "#fabebe"],
              data: topProductsQuantity
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