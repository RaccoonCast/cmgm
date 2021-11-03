<?php
foreach($_GET as $key => $value){
@$fs = @$id = str_replace(' ', '', $value);
@$trimChar = substr($value, 1);

if (!empty($value) OR $value == "NULL") {
  if ($key != "latitude" && $key != "longitude" && $key != "zoom" && $key != "mp-id") @$url_suffix = @$url_suffix . "&" . $key . "=" . $value;
  if ($value == "NULL") $value = null;
  if ($value == "!NULL") $trimChar = null;
  if ($key == "latitude" OR $key == "longitude" OR $key == "zoom" OR $key == "limit" OR $key == "marker_latitude" OR $key == "marker_longitude" OR $key == "back" OR $key == "basic") { ${$key} = $value; }
  elseif ($key == "id" AND (strpos($value, '-') !== false)) { $strings = explode('-',$value); $db_vars = " AND ID BETWEEN $strings[0] AND $strings[1]" . @$db_vars; }
  elseif ($key == "date" AND (strpos($value, ',') !== false)) {
      $strings = explode(',',$value);
      $db_vars = " AND date_added" . ' >= "'.date("Y-m-d", strtotime($strings[0])).'"' . @$db_vars;
      $db_vars = " AND date_added" . ' <= "'.date("Y-m-d", strtotime($strings[1])).'"' . @$db_vars;
    }
  elseif ($key == "id") { $db_vars = "AND (id = '$id' OR LTE_1 = '$id' OR LTE_2 = '$id' OR LTE_3 = '$id' OR LTE_4 = '$id' OR LTE_5 = '$id' OR LTE_6 = '$id' OR NR_1 = '$id' OR NR_2 = '$id') " . @$db_vars; }
  elseif ($key == "date" && $value[0] == ">") { $db_vars = " AND date_added" . ' >= "'.date("Y-m-d", strtotime($trimChar)).'"' . @$db_vars; }
  elseif ($key == "date" && $value[0] == "<") { $db_vars = " AND date_added" . ' <= "'.date("Y-m-d", strtotime($trimChar)).'"' . @$db_vars; }
  elseif ($key == "date") { $db_vars = " AND date_added" . ' = "'.date("Y-m-d", strtotime($value)).'"' . @$db_vars; }
  elseif ($key == "has_street_view" && $value == "true") $db_vars = " AND street_view_a != '' " . @$db_vars;
  elseif ($key == "has_street_view" && $value == "false") $db_vars = " AND street_view_a = '' " . @$db_vars;
  elseif ($value[0] == "!") {
    if ($key == "tags") { $db_vars = "AND (tags NOT like '".$trimChar.",%' && tags NOT like '%,".$trimChar."' && tags NOT like '%,".$trimChar.",%' && NOT tags = '".$trimChar."')" . @$db_vars; }
    elseif ($key != "tags") { $db_vars = " AND NOT " . $key . ' = "'.$trimChar.'"' . @$db_vars; }
  }
  elseif ($value[0] == ">") { $db_vars = " AND ". $key . ' > '.$trimChar . @$db_vars; }
  elseif ($value[0] == "<") { $db_vars = " AND ". $key . ' < '.$trimChar . @$db_vars; }
  elseif ($key == "idlist") { $db_vars = " AND FIND_IN_SET(`id`, '$value')" . @$db_vars; }
  elseif ($key == "fileSearch") { $db_vars = " AND (evidence_a like '%$fs%' OR evidence_b like '%$fs%' OR evidence_c like '%$fs%' OR photo_a like '%$fs%' OR photo_b like '%$fs%' OR photo_c like '%$fs%' OR photo_d like '%$fs%' OR photo_e like '%$fs%' OR photo_f like '%$fs%' OR extra_a like '%$fs%' OR extra_b like '%$fs%' OR extra_c like '%$fs%')" . @$db_vars; }
  elseif ($key == "address") { $db_vars = " AND " . $key . ' like "%'.$value.'%"' . @$db_vars; }
  elseif ($key == "tags") { $db_vars = "AND (tags like '".$value.",%' OR tags like '%,".$value."' OR tags like '%,".$value.",%' OR tags = '".$value."')" . @$db_vars; }
  elseif ($key == "carrier") { $db_vars = " AND ". $key . ' like "%'.$value.'%"' . @$db_vars; }
  else { $db_vars = " AND ". $key . ' = "'.$value.'"' . @$db_vars; }
 }
}
if (!empty($db_vars)) $db_vars = "WHERE " . substr(strstr($db_vars," "), 1);
@$db_vars = str_replace('WHERE AND', 'WHERE', @$db_vars);
 ?>
