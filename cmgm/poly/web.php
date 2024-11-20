<?php
// Set-up JSON headers.
header('Content-Type: application/json');

// Setup error function
function error($error) { 
	echo json_encode([
	'error' => $error
	]);
	die();
}

// Verify at least the eNB and the carrier name are available in $_POST request.
if (!isset($_POST['eNB']) || !isset($_POST['carrier'])) {
    $missingField = !isset($_POST['eNB']) ? "Missing eNB" : "Missing Carrier";
    error($missingField);
}
 
// Get the required variables from $_POST request.
$eNB = intval($_POST['eNB']);
$numbersArg = $_POST['cellList'];
$plmn = $_POST['carrier'];

// Call the master
include "master.php";

// Check whether there are enough points to draw a polygon, if not die(), if so continue.
if (!count($latLngPairs) >= 3) {
	if (empty($foundCells)) error("Cells " . implode(",", $missingCells) . " for eNB " . $eNB . " could not be found");
    error("Not enough cells were found to draw a polygon, only cell(s) " . implode(",", $foundCells) . " could be found, cell(s) " . implode(",", $missingCells) . " could not be found");
}

// Echo $link for Poly.
echo json_encode([
    'URL' => $link
]);
?>