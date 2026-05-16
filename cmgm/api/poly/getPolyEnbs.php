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

$result = $conn->query($sql_query, MYSQLI_USE_RESULT);

echo '[';

$first = true;

while ($row = $result->fetch_assoc()) {
    if (!$first) echo ',';

    echo json_encode($row);

    $first = false;
}

echo ']';

$result->close();
$conn->close();
?>