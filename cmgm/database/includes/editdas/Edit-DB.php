<?php
// THIS CODE IS MIRRORED from Edit-DB as of 11/26/2022 12:52 AM PST, this code will likely be heavily changed when CMGM DAS code is finaled.
// This file is included by Edit.php when the $id variable wasn't supplied/clear, this code checks & tries a few things to understand where the user wants to be redirected to on Edit.php.
$filename_for_css = "DB";
include "../includes/functions/css.php";
@$q = @$_GET['q'];
include "includes/DB-filter-get.php";

if (!empty($q)) {
  // Prev / Next buttons, easy redirects.
  if (@$back_num < 3 & isset($back_num)) redir("Edit.php?id=" . --$id . "&back=" . ++$back_num . "&nolocsearch","0"); // back
  if (@$next_num < 3 & isset($next_num)) redir("Edit.php?id=" . ++$id . "&next=" . ++$next_num . "&nolocsearch","0"); // next

  // Search database for a LTE or NR nodeB that matches the search query, if found, redirect to that ID. (this is neccesary for CMGM Home -> Search box 81000 -> #318)
  $q_trim = preg_replace('/\s+/', '', $q); // remove spaces
  if (!empty($q_trim) && is_numeric($q_trim)) {
  $sql = "SELECT id FROM db WHERE LTE_1='$q_trim' OR LTE_2='$q_trim' OR LTE_3='$q_trim' OR LTE_4='$q_trim' OR LTE_5='$q_trim' OR LTE_5='$q_trim' OR LTE_6='$q_trim' OR NR_1='$q_trim' OR NR_2='$q_trim'";
  $new_id = @mysqli_fetch_array(mysqli_query($conn, "$sql"))['id']; // id search
  redir("$domain_with_http" . "/database/Edit.php?q=$new_id","0");
  }

  // Last resort, search query w/ gmaps api, ie "200 E Colfax", convert result location to lat,long, search for eNBs near there. (this is neccesary for PCI+ -> CMGM Edit)
  // Note: It's possible convert() will work without needing the google maps API key. (lat,long to $latitude | $longitude code does not call Google)
  include SITE_ROOT . "/includes/link-conversion-and-handling/convert.php";
  [$latitude,$longitude] = convert($q,"HomeWOAddr",$default_latitude,$default_longitude,@$maps_api_key,$userID,$default_carrier,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom);
  $sql = "SELECT id,LTE_1,carrier,latitude,longitude,address,city,state,zip,notes,evidence_a, (3959 * ACOS(COS(RADIANS($default_latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($default_longitude)) + SIN(RADIANS($default_latitude)) * SIN(RADIANS(latitude)))) AS DISTANCE FROM db WHERE LTE_1='$q_trim' OR LTE_2='$q_trim' OR LTE_3='$q_trim' OR LTE_4='$q_trim' OR LTE_5='$q_trim' OR LTE_5='$q_trim' OR LTE_6='$q_trim' OR NR_1='$q_trim' OR NR_2='$q_trim' ORDER BY distance LIMIT 75";
}

$locsearch = "HAVING distance < 0.333";
include "$SITE_ROOT/database/includes/DB.php";
?>
