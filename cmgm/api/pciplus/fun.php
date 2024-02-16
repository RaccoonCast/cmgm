<?php
//  cody and alps' purple iphones (CAAPI)
include "includes/functions.php"; // error msg / report function
include "../../database/includes/DB-filter-get.php";
$limit = isset($_GET['limit']) ? $_GET['limit'] : 100000;
$json_flag = "true";
$db_vars = "1=1 " . $db_vars;

include "../../fun/includes/counts/" . $_GET['q'];
if (isset($_GET['showTotals'])) include "../../fun/includes/counts/count_records_created_by_everyone.php";
echo json_encode($json_array);
?>