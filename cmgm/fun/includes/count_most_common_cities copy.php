<?php
$sql = "SELECT city,state, COUNT(city) AS city_count FROM db WHERE $db_vars_unamended GROUP BY city ORDER BY city_count DESC LIMIT $limit;";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
  $location = $row["city"] . ', ' . $row['state'];
  $url = removeParameterFromURL($current_url,"city");
  $url = removeParameterFromURL($url,"state");
  $location_for_url = $url . "&city=" . $row["city"] . "&state=" . $row['state'];

  $url = '<a href="'. $location_for_url.'">'.$location.'</a>';
  echo $row["city_count"] . " records created in " . $url . "<br>";
}

echo "<br>";
?>
