<?php
// Purge Permanently? (Override present locs with 0.0 and 0.0 for LAT/LNG)
// Delete specific cells? (Add conditional cell filter)

//  cody and alps' purple iphones (CAAPI)
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');

include "../../functions.php";
include "get_param.php";

// Die if eNB or RAT is not specified
if (!isset($enb) || !isset($rat) || !isset($cells) || !isset($plmn)) {
    die();
}

// Delete perm?
if (!$permanentlyDelete) {
    $sql_query = "DELETE FROM local_poly_beta";
} else {
    $sql_query = "UPDATE local_poly_beta SET latitude = 0.0, longitude = 0.0, coords = ST_SRID(POINT(0.0, 0.0), 4326)";
}

// Filter 0 specify the eNB & RAT
$enbFilters = "WHERE eNB = $enb AND rat = '$rat'";


// Filter 1: Specify cells
if (!is_null($cells)) {
    $include = array_filter(array_map(
        fn($v) => intval(preg_replace('/\D/', '', trim($v))),
        explode(',', $cells)
    ));

    if ($include) {
        $whereFilters .= "AND cell IN (" . implode(',', $include) . ")";
    }
}

$main_query = "
    $sql_query $enbFilters $whereFilters;
    DELETE FROM local_poly_enbs $enbFilters;
    CALL update_poly_enbs($plmn, '$rat', $enb);
";

$conn->multi_query($main_query);

// Flush all results
do {
    if ($result = $conn->store_result()) {
        $result->free();
    }
} while ($conn->more_results() && $conn->next_result());