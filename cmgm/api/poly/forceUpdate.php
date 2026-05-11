<?php
// Purge Permanently? (Override present locs with 0.0 and 0.0 for LAT/LNG)
// Delete specific cells? (Add conditional cell filter)

//  cody and alps' purple iphones (CAAPI)
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');

include "get_param.php";
include '../../functions.php';

// Die if eNB or RAT is not specified
if (!isset($enb) || !isset($rat) || !isset($plmn)) {
    die();
}


mysqli_query($conn, "DELETE FROM local_poly_enbs WHERE plmn = $plmn AND enb = $enb AND rat = '$rat'");
mysqli_query($conn, "CALL update_poly_enbs($plmn, '$rat', $enb)");