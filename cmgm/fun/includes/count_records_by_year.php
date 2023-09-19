<?php
  $sql = "SELECT YEAR(date_added) AS creation_year, COUNT(*) AS record_count FROM db WHERE 1=1 $db_vars_unamended GROUP BY YEAR(date_added) ORDER BY creation_year";

  $result = $conn->query($sql);

  while ($row = $result->fetch_assoc()) {
    echo "" . $row["creation_year"] . ": " . $row["record_count"] . '<br>' . PHP_EOL;
  }

  echo "<br>";
?>
