<?php
$sql = "SELECT COUNT(*) as total_records FROM db WHERE $db_vars_unamended";
$row = $conn->query($sql)->fetch_assoc();
echo "<title>" . $row['total_records'] . " fun records - CMGM</title>";
?>