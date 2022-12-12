<?php
function error() {
  http_response_code(400);
  die();
}

//  cody and alps' purple iphones (CAAPI)
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');

if (is_numeric($_GET['plmn'])) {
  include '../includes/functions/sqlpw.php'; // doesn't call native

  if (isset($_GET['properties'])) {
    $database_get_list = preg_replace("[^_a-zA-Z0-9,-]", "", $_GET['properties']);
    $database_get_list = str_replace("edit_userid","id",$database_get_list);
    $database_get_list = str_replace("edit_lock","id",$database_get_list);
  } else {
    $database_get_list = "*";
  }

  // $search = substr($_GET['search'], 0, 100); // trim to 100 characters
  $search = ($_GET['search']);
  $id = explode (",", $search);
  $plmn = substr($_GET['plmn'], 0, 6); // trim to 6 characters
  if ($plmn == "310260") $carrier = "T-Mobile";
  if ($plmn == "311480") $carrier = "Verizon";
  if ($plmn == "310120") $carrier = "Sprint";
  if ($plmn == "310410") $carrier = "ATT";
  if (!isset($carrier)) die();
  $i = 0;
  foreach ($id as $value) {
  $i++;

    $sql = "SELECT $database_get_list from db WHERE (id = '$value' OR LTE_1 = '$value' OR LTE_2 = '$value' OR LTE_3 = '$value' OR LTE_4 = '$value' OR LTE_5 = '$value' OR LTE_6 = '$value' OR NR_1 = '$value' OR NR_2 = '$value') AND carrier = '$carrier' ";
     $arr = array();
     if ($result = $conn->query($sql) or error()) {
         while($row = $result->fetch_array(MYSQLI_ASSOC)) {
           unset($row["edit_userid"]);
           unset($row["edit_lock"]);
           $result_object[$value] = $row;
         }
     }
     }

}
foreach($result_object as $key => $value) $result_object[$key]['url'] = "$domain_with_http/database/Edit.php?q=".$value['id'];

echo json_encode($result_object);

?>
