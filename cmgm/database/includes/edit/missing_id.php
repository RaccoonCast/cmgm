<?php

if (isset($_GET['new'])) {
     $id = mysqli_fetch_array(mysqli_query($conn, "SELECT t1.id + 1 FROM db t1 WHERE NOT EXISTS (SELECT * FROM db t2 WHERE t2.id = t1.id + 1) LIMIT 1"))['t1.id + 1'];
} else {
  if (@$back_num < 3 & isset($back_num)) redir("Edit.php?id=" . --$id . "&back=" . ++$back_num . "&nolocsearch","0"); // back
  if (@$next_num < 3 & isset($next_num)) redir("Edit.php?id=" . ++$id . "&next=" . ++$next_num . "&nolocsearch","0"); // next
  $id_trim =preg_replace('/\s+/', '', $id);
  if (!empty($id_trim) && is_numeric($id_trim)) $new_id = @mysqli_fetch_array(mysqli_query($conn, "SELECT id FROM db WHERE LTE_1='$id_trim' OR LTE_2='$id_trim' OR LTE_3='$id_trim' OR LTE_4='$id_trim' OR LTE_5='$id_trim' OR LTE_5='$id_trim' OR LTE_6='$id_trim' OR NR_1='$id_trim' OR NR_2='$id_trim'"))['id']; // id search
  if (!empty($new_id)) redir("Edit.php?id=$new_id","0"); // redir to searched ID
  ?> <title>CMGM - Edit</title> <?php
  // LAST DITCH EFFORT TO FIGURE OUT WHO WE EDITING
  include SITE_ROOT . "/includes/link-conversion-and-handling/convert.php";
  if (!empty($id) && isset($_GET['locsearch'])) {
    $carrier_addin = null;
    if (@$_GET['MCC'] . @$_GET['MNC'] == "310260") {$carrier_addin = 'WHERE carrier = "'."T-Mobile".'"';}
    if (@$_GET['MCC'] . @$_GET['MNC'] == "310120") {$carrier_addin = 'WHERE carrier = "'."Sprint".'"';}
    if (@$_GET['MCC'] . @$_GET['MNC'] == "310410") {$carrier_addin = 'WHERE carrier = "'."ATT".'"';}
    if (@$_GET['MCC'] . @$_GET['MNC'] == "311480") {$carrier_addin = 'WHERE carrier = "'."Verizon".'"';}

    [$latitude,$longitude] = @convert($id,"HomeSmart",$default_latitude,$default_longitude,$maps_api_key,$userID,$default_carrier,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom);
    $query = "SELECT DISTINCT id,carrier, (3959 * ACOS(COS(RADIANS($latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($longitude)) + SIN(RADIANS($latitude)) * SIN(RADIANS(latitude)))) AS DISTANCE FROM db $carrier_addin HAVING distance < 0.045 ORDER BY DISTANCE LIMIT 1";
    if (!empty($latitude) && !empty($longitude)) @redir("Edit.php?id=" . @mysqli_fetch_array(mysqli_query($conn,$query))['id'],"0");
  }
  include "includes/edit/id_input/search_page.php";

  die();
}
?>
