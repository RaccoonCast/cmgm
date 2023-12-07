<?php
$sql = "SELECT MONTH(date_added) AS month, COUNT(*) AS count FROM db WHERE $db_vars_unamended GROUP BY month ORDER BY month;";
$result = $conn->query($sql);

$monthNames = array_combine(range(1, 12), ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]);

while ($row = $result->fetch_assoc()) {
   echo $monthNames[$row["month"]] . ": {$row["count"]}<br>";
}

echo "<br>";
?>
