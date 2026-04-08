<?php 
if (top_suppliers(from(), to(), 5)) {
  foreach (top_suppliers(from(), to(), 5) as $row) {
    $top_suppliers['name'][] = limit_char(get_the_supplier($row['sup_id'], 'sup_name'),15);
    $top_suppliers['quantity'][] = currency_format($row['quantity']);
  } 
} else {
  $top_suppliers['name'] = array();
  $top_suppliers['quantity'] = array();
}
?>

<div class="box box-info">
  <div class="box-header with-border">
    <h3 class="box-title">
      <?php echo trans('text_top_suppliers'); ?>
    </h3>
  </div>
  <div class="box-body">
    <canvas id="topSuppliers" height="250"></canvas>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
  var topSuppliers = <?php echo json_encode(array_values($top_suppliers['name'])); ?>;
  var topSuppliersQuantity = <?php echo json_encode(array_values($top_suppliers['quantity'])); ?>;
  var ctx = document.getElementById("topSuppliers");
  var myPieChart = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: topSuppliers,
        datasets: [
            {
              label: "Top",
              backgroundColor: ["#e6194B", "#f58231", "#ffe119", "#3cb44b", "#4363d8", "#f032e6", "#42d4f4", "#9A6324", "#469990", "#fabebe"],
              data: topSuppliersQuantity
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