<?php
$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total_records FROM db WHERE $db_vars_unamended"))['total_records'];
$total_out_of_everything = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total_records FROM db"))['total_records'];

echo "<title>" . $total . " Pins - CMGM</title>";
?>