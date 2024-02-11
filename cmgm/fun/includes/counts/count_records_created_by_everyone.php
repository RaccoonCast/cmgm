<?php
$sql = "SELECT COUNT(*) AS count FROM db WHERE $db_vars";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
  $plural_namething = ($row["count"] > 1) ? " records" : " record";
  echo "<b>Total: " . $row["count"] . $plural_namething  . "</b><hr>";
}
?>
