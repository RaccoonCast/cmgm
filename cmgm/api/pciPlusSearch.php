<?php
//  cody and alps' purple iphones (CAAPI)
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');

if (is_numeric($_GET['search']) && is_numeric($_GET['plmn'])) {
  include '../includes/functions/sqlpw.php'; // doesn't call native

  if (isset($_GET['properties'])) {
    $database_get_list = preg_replace("[^_a-zA-Z0-9,-]", "", $_GET['properties']);
    $database_get_list = str_replace("edit_userid","id",$database_get_list);
  } else {
    $database_get_list = "*, NULL AS edit_userid";
  }

  $id = substr($_GET['search'], 0, 10); // trim to 10 characters
  $plmn = substr($_GET['plmn'], 0, 6); // trim to 6 characters

  if ($plmn == "310260") $carrier = "T-Mobile";
  if ($plmn == "311480") $carrier = "Verizon";
  if ($plmn == "310120") $carrier = "Sprint";
  if ($plmn == "310410") $carrier = "ATT";
  if (!isset($carrier)) die();

  $sql = "SELECT $database_get_list from db WHERE (id = '$id' OR LTE_1 = '$id' OR LTE_2 = '$id' OR LTE_3 = '$id' OR LTE_4 = '$id' OR LTE_5 = '$id' OR LTE_6 = '$id' OR NR_1 = '$id' OR NR_2 = '$id') AND carrier = '$carrier' ";

  $arr = array();
  if ($result = $conn->query($sql)) {
      while($row = $result->fetch_array(MYSQLI_ASSOC)) { $arr[] = $row; }
         echo json_encode($arr);
  }

  $result->close(); $conn->close();
} else {
  die();
}

?>
