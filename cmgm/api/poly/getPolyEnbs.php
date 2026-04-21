<?php
ini_set('memory_limit','2048M');
//  cody and alps' purple iphones (CAAPI)
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');

include '../../includes/functions/sqlpw.php'; 
// Apply the filters.
include "filterPoly.php";

$tableName = ($locationType == 1) ? "local_poly_enbs" : "local_poly_beta_enbs";

$result = $conn->query($sql_query);
$carrierGroups = array();

while ($row = $result->fetch_assoc()) {
    $carrierType = $row['plmn'];

    if (!isset($carrierGroups[$carrierType])) $carrierGroups[$carrierType] = array();
    $carrierGroups[$carrierType][] = $row;
}

echo json_encode($carrierGroups);
$result->close(); $conn->close();
?>