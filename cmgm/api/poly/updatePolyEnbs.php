<?php
//  cody and alps' purple iphones (CAAPI)
header('Pragma: no-cache');
include '../../functions.php'; 

$dateOfData = mysqli_fetch_assoc(mysqli_query($conn, "SELECT last_run FROM local_poly_enbs_date"))['last_run'];

$lastUpdateTime = new DateTime($dateOfData, new DateTimeZone('UTC'));
$currentTime = new DateTime("now", new DateTimeZone('UTC'));

$diffSeconds = $currentTime->getTimestamp() - $lastUpdateTime->getTimestamp();

if ($diffSeconds < 300) { // if data is less than 5 minutes recent, refuse.
    die("Data is up to date. (reasonably)");
}
mysqli_query($conn, "CALL update_poly_enbs();");

echo "Table updated.";
?>