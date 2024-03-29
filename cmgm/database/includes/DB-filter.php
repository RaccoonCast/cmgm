<?php
if ($value == "NULL") $value = null;
if ($value == "!NULL") $trimChar = null;
if ($key == "latitude" OR $key == "longitude" OR $key == "zoom" OR $key == "limit" OR $key == "marker_latitude" OR $key == "marker_longitude" OR $key == "back" OR $key == "pin_style" or $key == "q" or $key == "pin_size" OR $key == "title" OR $key == "percents_view")  { ${$key} = $value; }
elseif ($key == "id" AND (strpos($value, '-') !== false)) { $strings = explode('-',$value); $db_vars = " AND ID BETWEEN $strings[0] AND $strings[1]" . @$db_vars; }
elseif ($key == "date" AND (strpos($value, ',') !== false)) {
    $strings = explode(',',$value);
    $db_vars = " AND date_added" . ' >= "'.date("Y-m-d", strtotime($strings[0])).'"' . @$db_vars;
    $db_vars = " AND date_added" . ' <= "'.date("Y-m-d", strtotime($strings[1])).'"' . @$db_vars;
  }
elseif ($key == "date" && $value[0] == ">") { $db_vars = " AND date_added" . ' >= "'.date("Y-m-d", strtotime($trimChar)).'"' . @$db_vars; }
elseif ($key == "date" && $value[0] == "<") { $db_vars = " AND date_added" . ' <= "'.date("Y-m-d", strtotime($trimChar)).'"' . @$db_vars; }
elseif ($key == "has_street_view" && $value == "true") $db_vars = " AND sv_a != '' " . @$db_vars;
elseif ($key == "has_street_view" && $value == "false") $db_vars = " AND sv_a = '' " . @$db_vars;
elseif (($key == "date" OR $key == "year") && @$value[0] == "!") { $db_vars = " AND (date_added not like '%".$value."%')" . @$db_vars; }
elseif ($key == "time" && @$value[0] == "!") { $db_vars = " AND (date_added not like '% ".$trimChar.":%')" . @$db_vars; }
elseif ($key == "month" && @$value[0] == "!") { $db_vars = " AND (MONTH(date_added) != ".$trimChar.")" . @$db_vars; }
elseif (@$value[0] == "!") {
  if ($key == "tags") { $db_vars = " AND (tags NOT like '".$trimChar.",%' AND tags NOT like '%,".$trimChar."' AND tags NOT like '%,".$trimChar.",%' AND NOT tags = '".$trimChar."' OR tags is null)" . @$db_vars; }
  elseif ($key != "tags") { $db_vars = " AND NOT " . $key . ' = "'.$trimChar.'"' . @$db_vars; }
}
elseif (@$value[0] == ">") { $db_vars = " AND ". $key . ' > '.$trimChar . @$db_vars; }
elseif (@$value[0] == "<") { $db_vars = " AND ". $key . ' < '.$trimChar . @$db_vars; }
elseif ($key == "id" OR isset($magickey)) { $db_vars = " AND (id = '$id' OR LTE_1 = '$id' OR LTE_2 = '$id' OR LTE_3 = '$id' OR LTE_4 = '$id' OR LTE_5 = '$id' OR LTE_6 = '$id' OR LTE_7 = '$id' OR LTE_8 = '$id' OR LTE_9 = '$id' OR NR_1 = '$id' OR NR_2 = '$id' OR NR_3 = '$id') " . @$db_vars; }
elseif ($key == "idlist") { $db_vars = " AND FIND_IN_SET(`id`, '$value')" . @$db_vars; }
elseif ($key == "fileSearch") { $db_vars = " AND (evidence_a like '%$fs%' OR evidence_b like '%$fs%' OR evidence_c like '%$fs%' OR photo_a like '%$fs%' OR photo_b like '%$fs%' OR photo_c like '%$fs%' OR photo_d like '%$fs%' OR photo_e like '%$fs%' OR photo_f like '%$fs%' OR extra_a like '%$fs%' OR extra_b like '%$fs%' OR extra_c like '%$fs%')" . @$db_vars; }
elseif ($key == "address") { $db_vars = " AND " . $key . ' like "%'.$value.'%"' . @$db_vars; }
elseif ($key == "tags") { $db_vars = " AND (tags like '".$value.",%' OR tags like '%,".$value."' OR tags like '%,".$value.",%' OR tags = '".$value."')" . @$db_vars; }
elseif ($key == "edit_date_like") { $db_vars = " AND (edit_date like '%".$value."%')" . @$db_vars; }
elseif ($key == "notes_like") { $db_vars =  " AND (notes like '%".$value."%')" . @$db_vars; }
elseif ($key == "cellsite_type" OR $key == "cellsite" OR $key == "type") { $db_vars =  " AND (cellsite_type like '%".$value."%')" . @$db_vars; }
elseif ($key == "site_id") { $db_vars = " AND (site_id like '%".$value."%')" . @$db_vars; }
elseif ($key == "date" OR $key == "year") { $db_vars = " AND (date_added like '%".$value."%')" . @$db_vars; }
elseif ($key == "time") { $db_vars = " AND (date_added like '% ".$value."%')" . @$db_vars; }
elseif ($key == "month") { $db_vars = " AND (MONTH(date_added) = ".$value.")" . @$db_vars; }
elseif ($key == "username") { $db_vars = " AND created_by = '".$value."'" . @$db_vars; }
elseif ($key == "incomplete" & $value == "true") { $db_vars = " AND (region_lte = '"."' OR pci_1 = '"."')" . @$db_vars; }
elseif ($key == "incomplete" & $value == "false") { $db_vars = " AND NOT (region_lte = '"."' OR pci_1 = '"."')" . @$db_vars; }
else { $db_vars = " AND ". $key . ' = "'.$value.'"' . @$db_vars; }
?>
