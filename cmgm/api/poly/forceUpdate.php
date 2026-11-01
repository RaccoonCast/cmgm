<?php
//  cody and alps' purple iphones (CAAPI)
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');

include "get_param.php";
include '../../functions.php';

isset($enb, $rat, $plmn) ? mysqli_query($conn, "CALL update_poly_enbs($plmn, '$rat', $enb, NULL)") : die();