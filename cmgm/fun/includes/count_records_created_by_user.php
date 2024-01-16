<?php
include "includes/count_records_created_by_everyone.php";
$sql = "SELECT created_by, COUNT(created_by) AS count FROM db WHERE $db_vars_unamended GROUP BY created_by ORDER BY count DESC;";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
  $url = '<a href="' . $current_url . '&username='.$row["created_by"].'">'. $row["created_by"].'</a>';

  $plural_namething = ($row["count"] > 1) ? " records" : " record";
  echo $row["count"] . $plural_namething . " created by " . $url  . "<br>";
}

echo "<br>";
?>
