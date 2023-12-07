<?php
$sql = "SELECT city,state, COUNT(city) AS city_count FROM db WHERE $db_vars_unamended AND city <> '' GROUP BY city ORDER BY city_count DESC LIMIT $limit;";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
  $location = $row["city"] . ', ' . $row['state'];
  $location_for_url = $current_url . "&city=" . $row["city"] . "&state=" . $row['state'];

  $url = '<a href="'. $location_for_url.'">'.$location.'</a>';
  echo $row["city_count"] . " records created in " . $url . "<br>";
}

echo "<br>";
?>
