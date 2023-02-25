<?php
//  cody and alps' purple iphones (CAAPI)
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');

function error($error,$id) {
  $a = array("error","search_items");
  $b = array($error,$id);
  $c = array_combine($a, $b);
  echo json_encode($c);

  http_response_code(400);
  die();
}
function error_handler($errno, $errstr, $errfile, $errline) {
    // your custom error handling logic here
    error($errstr." on line ".$errline,$_GET['search']);
}
set_error_handler("error_handler");

if (is_numeric($_GET['plmn'])) {
  include '../../includes/functions/sqlpw.php'; // doesn't call native

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
  if ($plmn == "313340") $carrier = "Dish";
  if (!isset($carrier)) error("Carrier not set correctly, accepted values: 310260, 311480, 310120, 310410, 313340",$_GET['search']);

  foreach ($id as $value) {

    $sql = "SELECT $database_get_list from db WHERE (LTE_1 = '$value' OR LTE_2 = '$value' OR LTE_3 = '$value' OR LTE_4 = '$value' OR LTE_5 = '$value' OR LTE_6 = '$value' OR LTE_7 = '$value' OR LTE_8 = '$value' OR LTE_9 = '$value' OR NR_1 = '$value' OR NR_2 = '$value' OR NR_3 = '$value') AND carrier = '$carrier' ";
     if ($result = $conn->query($sql) or error("$conn->error",$_GET['search'])) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)) {
              unset($row["edit_userid"]);
              unset($row["edit_lock"]);
              $result_object[$value] = $row;
            }

         }
     }
     }

if(empty($result_object)) error("No results found for anything in query.",$id);
foreach($result_object as $key => $value) $result_object[$key]['url'] = "$domain_with_http/database/Edit.php?q=".$value['id'];

$validOptions = array("evidence_a", "evidence_b", "evidence_c", "photo_a", "photo_b", "photo_c", "photo_d", "photo_e", "photo_f", "extra_a", "extra_b", "extra_c","extra_d","extra_e","extra_f", "sv_a", "sv_b", "sv_c", "sv_d", "sv_e", "sv_f");
foreach($result_object as $key => $value) {
    foreach($validOptions as $validOption) {
      if (isset($value[$validOption]) && (strpos($value[$validOption], "image-") === 0 || strpos($value[$validOption], "misc-") === 0)) {
           $result_object[$key][$validOption] = "$domain_with_http/database/uploads/" .$value[$validOption];
      } elseif (isset($value[$validOption]) && (strpos($value[$validOption], "goo.gl") === 0)) {
        $result_object[$key][$validOption] = "https://" .$value[$validOption];
      }
    }
}


echo json_encode($result_object);

?>
