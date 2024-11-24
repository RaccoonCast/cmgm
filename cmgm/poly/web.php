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
$prettyPost = json_encode($_POST);

// Verify at least the eNB and the carrier name are available in $_POST request.
if (!isset($_POST['eNB']) || !isset($_POST['plmn'])) {
    $missingField = !isset($_POST['eNB']) ? "Missing eNB" : "Missing Carrier";
    error($missingField);
}

// Call the master
include "master.php";

// debugging: 
// echo $json_response;

// Call the Polygon Maker
include "poly.php";

// Echo $link for Poly.
echo json_encode([
    'URL' => $url,
	'polygon' => $responses,
]);
?>