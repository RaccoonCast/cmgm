<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', '1');

function error($error) { 
	echo json_encode([
	'error' => $error
	]);
	die();
}

if (!isset($_POST['eNB']) || !isset($_POST['carrier'])) {
    $missingField = !isset($_POST['eNB']) ? "Missing eNB" : "Missing Carrier";
    error($missingField);
}
 
$eNB = intval($_POST['eNB']);
$numbersArg = $_POST['cellList'];
$plmn = $_POST['carrier'];
$maps_api_key = file_get_contents("../secret_maps_api_key.hiddenpass");

include "master.php";

if (!count($latLngPairs) >= 3) {
	if (empty($foundCells)) error("Cells " . implode(",", $missingCells) . " for eNB " . $eNB . " could not be found");
    error("Not enough cells were found to draw a polygon, only cell(s) " . implode(",", $foundCells) . " could be found, cell(s) " . implode(",", $missingCells) . " could not be found");
}

echo json_encode([
    'URL' => $link
]);
?>