<?php
$filename_for_css = "DB";
include "../includes/functions/css.php";
include "../includes/link-conversion-and-handling/function_goto.php";

@$q = @$_GET['q'];
include "includes/DB-filter-get.php";

if (!empty($q)) {
  if (@$back_num < 3 & isset($back_num)) redir("Edit.php?id=" . --$id . "&back=" . ++$back_num . "&nolocsearch","0"); // back
  if (@$next_num < 3 & isset($next_num)) redir("Edit.php?id=" . ++$id . "&next=" . ++$next_num . "&nolocsearch","0"); // next
  $id_trim =preg_replace('/\s+/', '', $id);
  if (!empty($id_trim) && is_numeric($id_trim)) {
    $new_id = @mysqli_fetch_array(mysqli_query($conn, "SELECT id FROM db WHERE LTE_1='$id_trim' OR LTE_2='$id_trim' OR LTE_3='$id_trim' OR LTE_4='$id_trim' OR LTE_5='$id_trim' OR LTE_5='$id_trim' OR LTE_6='$id_trim' OR NR_1='$id_trim' OR NR_2='$id_trim'"))['id']; // id search
    if (!empty($new_id)) redir("Edit.php?id=$new_id","0"); // redir to searched ID
  }
  include SITE_ROOT . "/includes/link-conversion-and-handling/convert.php";
  [$latitude,$longitude] = convert($q,"HomeSmart",$default_latitude,$default_longitude,$maps_api_key,$userID,$default_carrier,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom);
}
include "$SITE_ROOT/database/includes/DB.php";
?>
