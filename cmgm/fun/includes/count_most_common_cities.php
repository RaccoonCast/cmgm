<?php
$sql = "SELECT city,state, COUNT(city) AS city_count FROM db WHERE 1=1 $db_vars_unamended GROUP BY city ORDER BY city_count DESC LIMIT $limit;";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
  echo $row["city_count"] . " records created in " . $row["city"] . ', ' . $row['state'] . "<br>";
}

echo "<br>";
?>
