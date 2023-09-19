<?php
$sql = "SELECT created_by, COUNT(created_by) AS count FROM db WHERE 1=1 $db_vars_unamended GROUP BY created_by ORDER BY count DESC;";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
  $url = $domain_with_http . removeParameterFromURL($current_url, "username");
  $url = '<a href="' . $url . '&created_by='.$row["created_by"].'">'. $row["created_by"].'</a>';

  echo $row["count"] . " records created by " . $url  . "<br>";
}

echo "<br>";
?>
