<?php
$remove_username_from_db_vars = str_replace('AND created_by = "'.$_GET['username'].'"', '', $db_vars_unamended);
$sql = "SELECT COUNT(*) AS count FROM db WHERE 1=1 $remove_username_from_db_vars";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
  $url = $domain_with_http . removeParameterFromURL($current_url, "username");
  $url = '<a href="' . $url . '">everyone</a>';

  echo $row["count"] . " records created by " . $url  . "<br>";
}
?>
