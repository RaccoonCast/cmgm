<?php
// Set-up JSON headers.
header('Content-Type: application/json');

// Setup error function
$prettyPost = json_encode($_POST);

// Verify at least the eNB and the carrier name are available in $_POST request.
if (!array_filter(array_keys($_POST), fn($k) => str_starts_with($k, 'eNB')) || 
    !array_filter(array_keys($_POST), fn($k) => str_starts_with($k, 'plmn'))) {
    error(!array_filter(array_keys($_POST), fn($k) => str_starts_with($k, 'eNB')) ? "Missing eNB" : "Missing PLMN");
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