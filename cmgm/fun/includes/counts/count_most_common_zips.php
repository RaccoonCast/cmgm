<?php
$sql = "SELECT zip, COUNT(zip) AS zip_count FROM db WHERE $db_vars AND zip <> '' GROUP BY zip ORDER BY zip_count DESC LIMIT $limit;";
$result = $conn->query($sql);

if (!isset($json_flag)) echo "<h3>Most common zip codes</h3>";

while ($row = $result->fetch_assoc()) {
  $location = $row["zip"];
  $location_for_url = $current_url . "&zip=" . $row["zip"];

  $url = '<a href="'. $location_for_url.'">'.$location.'</a>';
  $plural_namething = ($row["zip_count"] > 1) ? " records" : " record";

  if (isset($_GET['percents_view']))  { echo getPercent($row["zip_count"]) . "  in " . $url . "<br>";
  } elseif (isset($json_flag))        { $json_array[$location] = $row["zip_count"];
  } else                              { echo $row["zip_count"] . $plural_namething . "  in " . $url . "<br>";
  }

}

echo (isset($_GET['json_flag'])) ? "<br>" : "";
?>
