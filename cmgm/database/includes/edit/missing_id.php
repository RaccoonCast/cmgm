<?php
if (isset($_GET['new'])) {
     $id = mysqli_fetch_array(mysqli_query($conn, "SELECT t1.id + 1 FROM database_db t1 WHERE NOT EXISTS (SELECT * FROM database_db t2 WHERE t2.id = t1.id + 1) LIMIT 1"))['t1.id + 1'];
} else {
  if (@$back_num < 3 & isset($back_num)) redir("Edit.php?id=" . --$id . "&back=" . ++$back_num . "&nolocsearch","0"); // back
  if (@$next_num < 3 & isset($next_num)) redir("Edit.php?id=" . ++$id . "&next=" . ++$next_num . "&nolocsearch","0"); // next
  $id_trim =preg_replace('/\s+/', '', $id);
  if (!empty($id_trim) && is_numeric($id_trim)) $new_id = @mysqli_fetch_array(mysqli_query($conn, "SELECT id FROM database_db WHERE LTE_1='$id_trim' OR LTE_2='$id_trim' OR LTE_3='$id_trim' OR LTE_4='$id_trim' OR LTE_5='$id_trim' OR LTE_5='$id_trim' OR LTE_6='$id_trim' OR NR_1='$id_trim' OR NR_2='$id_trim'"))['id']; // id search
  if (!empty($new_id)) redir("Edit.php?id=$new_id","0"); // redir to searched ID
  ?> <title>CMGM - Edit</title> <?php
  // LAST DITCH EFFORT TO FIGURE OUT WHO WE EDITING
  include SITE_ROOT . "/includes/home-functions/convert.php";
  if (!empty($id) && isset($_GET['locsearch'])) {
    [$latitude,$longitude] = @convert($id,"HomeSmart",$default_latitude,$default_longitude,$maps_api_key,$userID,$default_carrier,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom);
    if (!empty($latitude) && !empty($longitude)) @redir("Edit.php?id=" . @mysqli_fetch_array(mysqli_query($conn,"SELECT DISTINCT id, (3959 * ACOS(COS(RADIANS($latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($longitude)) + SIN(RADIANS($latitude)) * SIN(RADIANS(latitude)))) AS DISTANCE FROM database_db ORDER BY distance LIMIT 1"))['id'],"0");
  }
  include "includes/edit/id_input_1.php";

  die();
}
?>
