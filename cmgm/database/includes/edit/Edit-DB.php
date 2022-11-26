<?php
$filename_for_css = "DB";
include "../includes/functions/css.php";
@$q = @$_GET['q'];
include "includes/DB-filter-get.php";

if (!empty($q)) {
  if (@$back_num < 3 & isset($back_num)) redir("Edit.php?id=" . --$id . "&back=" . ++$back_num . "&nolocsearch","0"); // back
  if (@$next_num < 3 & isset($next_num)) redir("Edit.php?id=" . ++$id . "&next=" . ++$next_num . "&nolocsearch","0"); // next
  $id_trim =preg_replace('/\s+/', '', $id);
  unset($sql);
  if (!empty($id_trim) && is_numeric($id_trim)) {
    $magickey = "true";
    $new_id = @mysqli_fetch_array(mysqli_query($conn, "SELECT id FROM db WHERE LTE_1='$id_trim' OR LTE_2='$id_trim' OR LTE_3='$id_trim' OR LTE_4='$id_trim' OR LTE_5='$id_trim' OR LTE_5='$id_trim' OR LTE_6='$id_trim' OR NR_1='$id_trim' OR NR_2='$id_trim'"))['id']; // id search
    if (!empty($new_id)) {
      $sql = "SELECT id,LTE_1,carrier,latitude,longitude,address,city,state,zip,notes,evidence_a, (3959 * ACOS(COS(RADIANS($default_latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($default_longitude)) + SIN(RADIANS($default_latitude)) * SIN(RADIANS(latitude)))) AS DISTANCE FROM db WHERE LTE_1='$id_trim' OR LTE_2='$id_trim' OR LTE_3='$id_trim' OR LTE_4='$id_trim' OR LTE_5='$id_trim' OR LTE_5='$id_trim' OR LTE_6='$id_trim' OR NR_1='$id_trim' OR NR_2='$id_trim' ORDER BY distance LIMIT 75";
    }
  }
  include SITE_ROOT . "/includes/link-conversion-and-handling/convert.php";
  [$latitude,$longitude] = convert($q,"HomeSmarter",$default_latitude,$default_longitude,@$maps_api_key,$userID,$default_carrier,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom);
} else {
  redir("Search.php","0");
}
$locsearch = "HAVING distance < 0.333";
include "$SITE_ROOT/database/includes/DB.php";
?>
