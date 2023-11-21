<?php
$sql = "SELECT created_by, COUNT(created_by) AS created_by_count FROM db WHERE $db_vars_unamended GROUP BY created_by ORDER BY created_by DESC LIMIT $limit;";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
  $url = removeParameterFromURL($current_url,"created_by");
  $location_for_url = $url . "&created_by=" . $row["created_by"];

  $url = '<a href="'. $location_for_url.'">'.$created_by.'</a>';
  echo $row["created_by"] . " records created by " . $url . "<br>";
}

echo "<br>";
?>
