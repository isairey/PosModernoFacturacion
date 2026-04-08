<?php 
if (top_brands(from(), to(), 5)) {
  foreach (top_brands(from(), to(), 5) as $row) {
    $top_brands['name'][] = limit_char(get_the_brand($row['brand_id'], 'brand_name'),15);
    $top_brands['quantity'][] = currency_format($row['quantity']);
  } 
} else {
  $top_brands['name'] = array();
  $top_brands['quantity'] = array();
}
?>

<div class="box box-info">
  <div class="box-header with-border">
    <h3 class="box-title">
      <?php echo trans('text_top_brands'); ?>
    </h3>
  </div>
  <div class="box-body">
    <canvas id="topBrands" height="250"></canvas>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
  var topBrands = <?php echo json_encode(array_values($top_brands['name'])); ?>;
  var topBrandsQuantity = <?php echo json_encode(array_values($top_brands['quantity'])); ?>;
  var ctx = document.getElementById("topBrands");
  var myPieChart = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: topBrands,
        datasets: [
            {
              label: "Top",
              backgroundColor: ["#e6194B", "#f58231", "#ffe119", "#3cb44b", "#4363d8", "#f032e6", "#42d4f4", "#9A6324", "#469990", "#fabebe"],
              data: topBrandsQuantity
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