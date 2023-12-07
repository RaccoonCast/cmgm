<?php
$sql = "SELECT county,state, COUNT(county) AS county_count FROM db WHERE $db_vars_unamended AND county <> '' GROUP BY county ORDER BY county_count DESC LIMIT $limit;";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
  $location = $row["county"] . ', ' . $row['state'];
  $location_for_url = $current_url . "&county=" . $row["county"] . "&state=" . $row['state'];

  $url = '<a href="'. $location_for_url.'">'.$location.'</a>';
  echo $row["county_count"] . " records created in " . $url . "<br>";
}

echo "<br>";
?>
