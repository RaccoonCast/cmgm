<?php
$sql = "SELECT date_added, COUNT(date_added) AS value_occurrence FROM db WHERE 1=1 $db_vars_unamended GROUP BY date_added ORDER BY value_occurrence DESC LIMIT $limit OFFSET 1";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
  echo $row["value_occurrence"] . " records created on " . $row["date_added"] . "<br>";
}

echo "<br>";
?>
