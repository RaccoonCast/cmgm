<?php

$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total_records FROM db WHERE $db_vars"))['total_records'];
define("TOTAL", $total);
$total_out_of_everything = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total_records FROM db"))['total_records'];

echo "<title>" . $total . " Pins - CMGM</title>";
echo !($total == $total_out_of_everything) ? "<h3>With your current filters applied, you're looking at $total out of $total_out_of_everything CMGM pins.</h3>" : "<br>";

?>