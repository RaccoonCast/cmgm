<?php
$sql = "SELECT city,state, COUNT(city) AS city_count FROM db WHERE $db_vars AND city <> '' GROUP BY city ORDER BY city_count DESC LIMIT $limit;";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
  $location = $row["city"] . ', ' . $row['state'];
  $location_for_url = $current_url . "&city=" . $row["city"] . "&state=" . $row['state'];

  $url = '<a href="'. $location_for_url.'">'.$location.'</a>';
  $plural_namething = ($row["city_count"] > 1) ? " records" : " record";

  if (!isset($_GET['percents_view'])) {
    echo $row["city_count"] . $plural_namething . "  created in " . $url . "<br>";
  } else {
    echo getPercent($row["city_count"]) . " of " . $plural_namething . "  created in " . $url . "<br>";
  }

}

echo "<br>";
?>
