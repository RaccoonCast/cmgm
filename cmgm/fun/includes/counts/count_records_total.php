<?php

$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total_records FROM db WHERE $db_vars"))['total_records'];
define("TOTAL", $total);
$total_out_of_everything = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total_records FROM db"))['total_records'];

if ($total == 1) {
    $get_id_for_edit_link = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM db WHERE $db_vars"))['id'];
    $total_text = '<a href ="/database/Edit.php?id='. $get_id_for_edit_link .'">1</a>';
} else {
    $total_text = $total;
}

function getPercent_2($number, $total_out_of_everything) {
    return " (" . sprintf('%.2f', round(($number / $total_out_of_everything) * 100, 2)) . "%)";
}

echo "<title>" . number_format($total) . " Pins - CMGM</title>";
$percent_stat = isset($_GET['percents_view']) ? getPercent_2($total, $total_out_of_everything) : "";
echo !($total == $total_out_of_everything) ? "<h3>With your current filters applied, you're looking at $total_text out of $total_out_of_everything CMGM pins$percent_stat. </h3>" : "<br>";

?>