<?php
$sql = "SELECT COUNT(*) AS count FROM db WHERE $db_vars_unamended";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
  $url = $domain_with_http . $current_url;
  $url = '<a href="' . $url . '">everyone</a>';
  $plural_namething = ($row["count"] > 1) ? " records" : " record";
  echo $row["count"] . $plural_namething . "  created by " . $url  . "<br>";
}
?>
