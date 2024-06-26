<?php
$sql = "SELECT county,state, COUNT(county) AS county_count FROM db WHERE $db_vars AND county <> '' GROUP BY county ORDER BY county_count DESC LIMIT $limit;";
$result = $conn->query($sql);

if (!isset($json_flag)) echo "<h3>Most Common Counties</h3>";

while ($row = $result->fetch_assoc()) {
  $location = $row["county"] . ', ' . $row['state'];
  $location_for_url = $current_url . "&county=" . $row["county"] . "&state=" . $row['state'];

  $url = '<a href="'. $location_for_url.'">'.$location.'</a>';
  $plural_namething = ($row["county_count"] > 1) ? " records" : " record";

  if (isset($_GET['percents_view'])) {
    echo getPercent($row["county_count"]) . "  in " . $url . "<br>";
  } elseif (isset($json_flag)) {
    $json_array[$location] = $row["county_count"];
  } else {
    echo $row["county_count"] . $plural_namething . "  in " . $url . "<br>";
  }

}

echo (isset($_GET['json_flag'])) ? "<br>" : "";
?>