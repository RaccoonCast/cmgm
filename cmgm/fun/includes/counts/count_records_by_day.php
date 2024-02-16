<?php
$sql = "SELECT DATE(date_added) AS date_only, COUNT(date_added) AS value_occurrence FROM db WHERE $db_vars GROUP BY date_only ORDER BY value_occurrence DESC LIMIT $limit";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
  $plural_namething = ($row["value_occurrence"] > 1) ? " records" : " record";

  if (isset($_GET['percents_view'])) {
    echo getPercent($row["value_occurrence"]) . " of " . $plural_namething . ' created on ' . '<a href="' . $current_url . '&date=' . $row['date_only'] . '">' . $row['date_only']  . '</a><br>';
  } elseif (isset($json_flag)) {
    $json_array[$row['date_only']] = $row["value_occurrence"];
  } else {
    echo $row["value_occurrence"] . $plural_namething . ' created on ' . '<a href="' . $current_url . '&date=' . $row['date_only'] . '">' . $row['date_only']  . '</a><br>';
  }
}

echo (isset($_GET['json_flag'])) ? "<br>" : "";
?>
