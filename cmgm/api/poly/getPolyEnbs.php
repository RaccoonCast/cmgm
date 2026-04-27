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

$conn->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
$result = $conn->query($sql_query);

echo "{";

$currentGroup = null;
$firstGroup = true;
$firstRow = true;

while ($row = $result->fetch_assoc()) {

    $row['latitude']  = (float)$row['latitude'];
    $row['longitude'] = (float)$row['longitude'];

    $group = $row['plmn'];

    // new group
    if ($group !== $currentGroup) {

        if (!$firstGroup) {
            echo "]";
        }

        echo ($firstGroup ? "" : ",") . "\"$group\":[";

        $currentGroup = $group;
        $firstGroup = false;
        $firstRow = true;
    }

    if (!$firstRow) {
        echo ",";
    }

    echo json_encode($row);

    $firstRow = false;
}

if (!$firstGroup) {
    echo "]";
}

echo "}";
?>