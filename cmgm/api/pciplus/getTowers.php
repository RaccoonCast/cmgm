<?php
//  cody and alps' purple iphones (CAAPI)
include "includes/functions.php";

if (is_numeric($_GET['plmn'])) {

  if (isset($_GET['properties'])) {
    $database_get_list = preg_replace("[^_a-zA-Z0-9,-]", "", $_GET['properties']);
    $database_get_list = str_replace("edit_userid","id",$database_get_list);
    $database_get_list = str_replace("edit_lock","id",$database_get_list);
  } else {
    $database_get_list = "*";
  }

  $id = explode(",", $_GET['id']);

  $plmn = substr($_GET['plmn'], 0, 6); // trim to 6 characters
  if ($plmn == "310260") $carrier = "T-Mobile";
  if ($plmn == "311480") $carrier = "Verizon";
  if ($plmn == "310120") $carrier = "Sprint";
  if ($plmn == "310410") $carrier = "ATT";
  if ($plmn == "313100") $carrier = "ATT";
  if ($plmn == "313340") $carrier = "Dish";
  if (!isset($carrier)) error("This carrier is not supported by CMGM, only the major US networks are supported.",$_GET['plmn']);

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

if(empty($result_object)) error("No results found for anything in query.",$id);
foreach($result_object as $key => $value) $result_object[$key]['url'] = "$domain_with_http/database/Edit.php?q=".$value['id'];

if (!empty($value['cellsite_type'])) {
  include "../../includes/functions/tower_types.php";
  $category = ucfirst(explode('_', $value['cellsite_type'])[0]);
  // if ($category != "Nature") $category_suffix = " ($category)";
  $cellsite_type = $options[$category][$value['cellsite_type']];
  foreach($result_object as $key => $value) $result_object[$key]['cellsite_type_normalized'] = $cellsite_type . @$category_suffix;
}

$validOptions = array("evidence_a", "evidence_b", "evidence_c", "photo_a", "photo_b", "photo_c", "photo_d", "photo_e", "photo_f", "extra_a", "extra_b", "extra_c","extra_d","extra_e","extra_f");
foreach($result_object as $key => $value) {
    foreach($validOptions as $validOption) {
      if (isset($value[$validOption]) && (strpos($value[$validOption], "image-") === 0 || strpos($value[$validOption], "misc-") === 0)) {
           $result_object[$key][$validOption] = "$domain_with_http/database/uploads/" .$value[$validOption];
      }
    }
}

$validSVOptions = array("sv_a", "sv_b", "sv_c", "sv_d", "sv_e", "sv_f");
foreach($result_object as $key => $value) {
    foreach($validSVOptions as $validSVOption) {
      if (!empty($value[$validSVOption])) {
        $result_object[$key][$validSVOption] = "https://" .$value[$validSVOption];
      }
    }
}

} else {
  error("This carrier is not supported by CMGM, only the major US networks are supported.",@$_GET['plmn']);
}

echo json_encode($result_object);

?>
