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
$conn->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, true);
$result = $conn->query($sql_query);
$carrierGroups = array();

while ($row = $result->fetch_assoc()) {
    // Convert numeric values to float where possible
    // foreach ($row as $key => $value) {
    //     if (is_numeric($value)) {
    //         $row[$key] = (float)$value;
    //     }
    // }

    $results[] = $row;
}

echo json_encode($results);
$result->close(); $conn->close();
?>