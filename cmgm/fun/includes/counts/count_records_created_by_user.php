<?php
include "count_records_created_by_everyone.php";
$sql = "SELECT created_by, COUNT(created_by) AS count FROM db WHERE $db_vars GROUP BY created_by ORDER BY count DESC limit $limit;";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
  $url = '<a href="' . $current_url . '&username='.$row["created_by"].'">'. $row["created_by"].'</a>';

  $plural_namething = ($row["count"] > 1) ? " records" : " record";

  if (!isset($_GET['percents_view'])) {
    echo $row["count"] . $plural_namething . "  created by " . $url . "<br>";
  } else {
    echo getPercent($row["count"]) . " of " . $plural_namething . "  created by " . $url . "<br>";
  }
}

echo "<br>";
?>
