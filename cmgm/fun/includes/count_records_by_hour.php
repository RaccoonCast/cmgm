<?php
$sql = "SELECT EXTRACT(HOUR FROM date_added) AS hour_of_day, COUNT(*) AS value_occurrence FROM db 
WHERE $db_vars_unamended AND date_added NOT LIKE '% 00:00:00' GROUP BY hour_of_day ORDER BY value_occurrence DESC LIMIT $limit; ";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
  $hour = $row['hour_of_day'];
  $formatted_hour = date("g A", strtotime("$hour:00:00"));

  $plural_namething = ($row["value_occurrence"] > 1) ? " records" : " record";
  echo $row["value_occurrence"] . $plural_namething . ' created during ' . '<a href="' . $current_url . '&time=' . $hour . '">' . $formatted_hour  . '</a><br>';
}

echo "<br>";
?>
