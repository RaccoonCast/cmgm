<?php
$sql = "SELECT YEAR(date_added) AS year, MONTH(date_added) AS month, COUNT(*) AS record_count FROM db WHERE $db_vars_unamended AND YEAR(date_added) = YEAR(CURRENT_DATE)  GROUP BY year, month ORDER BY year, month";

$result = $conn->query($sql);
  while ($row = $result->fetch_assoc()) {
    $monthNames = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    echo $monthNames[$row["month"]] . ": " . $row["record_count"] . "<br>";
  }

  echo "<br>";
  ?>
