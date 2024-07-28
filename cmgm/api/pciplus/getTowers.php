<?php
//  cody and alps' purple iphones (CAAPI)
include "includes/functions.php";
include "../../includes/functions/convert-url.php";

if (is_numeric(@$_GET['plmn']) || isset($_GET['cmgm_id'])) { // Verify PLMN is numeric, if not error()

  // If specific fields are requested, filter a-z0-9 to cheat sql injection prevention.
  $database_get_list = isset($_GET['properties']) ? preg_replace("/[^_a-zA-Z0-9,-]/", "", $_GET['properties']) : "*";

  // Explode comma seperated list of multiple towers being requested.
  if (isset($_GET['id'])) $id = explode(",", $_GET['id']);
  if (isset($_GET['cmgm_id'])) $id = explode(",", $_GET['cmgm_id']);

  // Set carrier name based on $_GET['plmn']
  $plmnToCarrier = array(
      "310260" => "T-Mobile",
      "311480" => "Verizon",
      "310120" => "Sprint",
      "310410" => "ATT",
      "313100" => "ATT",
      "313340" => "Dish"
  );
  if (!isset($_GET['cmgm_id'])) $carrier = isset($plmnToCarrier[$_GET['plmn']]) ? $plmnToCarrier[$_GET['plmn']] : error("This carrier is not supported by CMGM, only the major US networks are supported.", $_GET['plmn']);

  // Check all LTE/NR fields for provided eNBs that match PLMN provided, remove edit_userid & edit_lock from fields returned by each tower.
  // Put each tower into $result_object PHP array.
  foreach ($id as $value) {
    if (!isset($_GET['cmgm_id'])) $sql = "SELECT $database_get_list,id from db WHERE (LTE_1 = '$value' OR LTE_2 = '$value' OR LTE_3 = '$value' OR LTE_4 = '$value' OR LTE_5 = '$value' OR LTE_6 = '$value' OR LTE_7 = '$value' OR LTE_8 = '$value' OR LTE_9 = '$value' OR NR_1 = '$value' OR NR_2 = '$value' OR NR_3 = '$value') AND carrier = '$carrier' ";
    if (isset($_GET['cmgm_id'])) $sql = "SELECT $database_get_list,id from db WHERE id = $value";
    if ($result = $conn->query($sql) or error("$conn->error",$_GET['search'])) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)) {
              unset($row["edit_userid"]);
              unset($row["edit_lock"]);
              $result_object[$value] = $row;
            }

         }
     }

     // Check if PHP array with towers is empty, if so error() out. (No towers found)
     if(empty($result_object)) error("No results found for anything in query.",$id);

     // Add database edit link to $result_object array for each tower.
     foreach($result_object as $key => $value) $result_object[$key]['url'] = "$domain_with_http/database/Edit.php?q=".$value['id'];

     // Creates a field named 'cellsite_type_normalized' with a value that contains the normalized cellsite type name, for example: rooftop_framed_box -> Framed Box (FRP)
      if (!empty($value['cellsite_type'])) {
       include "../../includes/functions/tower_types.php";
       $category = ucfirst(explode('_', $value['cellsite_type'])[0]);
       $cellsite_type = $options[$category][$value['cellsite_type']];
       foreach($result_object as $key => $value) $result_object[$key]['cellsite_type_normalized'] = $cellsite_type . @$category_suffix;
     }

     // Add CMGM Uploads URL prefix for values like 'image-3249823428394.jpg' + Convert @s to canon.cmgm.us links
     $validOptions = array("evidence_a", "evidence_b", "evidence_c", "photo_a", "photo_b", "photo_c", "photo_d", "photo_e", "photo_f", "extra_a", "extra_b", "extra_c","extra_d","extra_e","extra_f","bingmaps_a","bingmaps_b","bingmaps_c");
     foreach($result_object as $key => $value) {
       foreach($validOptions as $validOption) {
        if (isset($result_object[$key][$validOption])) $result_object[$key][$validOption] = convert_url($value[$validOption]);
      }
    }

      // Add HTTPS:// to Street View URLs. (DB has https trimmed off)
      $validSVOptions = array("sv_a", "sv_b", "sv_c", "sv_d", "sv_e", "sv_f");
      foreach($result_object as $key => $value) {
          foreach($validSVOptions as $validSVOption) {
            if (!empty($value[$validSVOption])) {
              $result_object[$key][$validSVOption] = "https://" .$value[$validSVOption];
            }
          }
      }



} else {
  error("Invalid PLMN.",@$_GET['plmn']);
}

echo json_encode($result_object);

?>
