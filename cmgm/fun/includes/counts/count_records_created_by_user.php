<?php
include "count_records_created_by_everyone.php";
$sql = "SELECT created_by, COUNT(created_by) AS count FROM db WHERE $db_vars GROUP BY created_by ORDER BY count DESC limit $limit;";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
  $url = '<a href="' . $current_url . '&username='.$row["created_by"].'">'. $row["created_by"].'</a>';

  $plural_namething = ($row["count"] > 1) ? " records" : " record";

  if (isset($_GET['percents_view'])) {
    echo getPercent($row["count"]) . " of " . $plural_namething . "  by " . $url . "<br>";
  } elseif (isset($json_flag)) {
    $json_array[$row["created_by"]] = $row['count'];
  } else {
    echo $row["count"] . $plural_namething . "  by " . $url . "<br>";
  }
}

echo (isset($_GET['json_flag'])) ? "<br>" : "";
?>
