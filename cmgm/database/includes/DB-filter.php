 <?php
 foreach($_GET as $key => $value){
  @$id = str_replace(' ', '', $id);
  @$fs = str_replace(' ', '', $fileSearch);
  @$trimChar = substr($value, 1);

  if (!empty($value)) {
    if ($key != "latitude" && $key != "longitude" && $key != "zoom" && $key != "mp-id") @$url_suffix = @$url_suffix . "&" . $key . "=" . $value;
    if ($key == "latitude" OR $key == "longitude" OR $key == "zoom" OR $key == "limit" OR $key == "marker_latitude" OR $key == "marker_longitude" OR $key == "back") { ${$key} = $value; }
    elseif ($value[0] == "!") { $db_vars = " AND NOT " . $key . ' = "'.$trimChar.'"' . @$db_vars; }
    elseif ($value[0] == ">") { $db_vars = " AND ". $key . ' > '.$trimChar . @$db_vars; }
    elseif ($value[0] == "<") { $db_vars = " AND ". $key . ' < '.$trimChar . @$db_vars; }
    elseif ($key == "fileSearch") { $db_vars = " AND evidence_a='$fs' OR evidence_b='$fs' OR evidence_c='$fs' OR photo_a='$fs' OR photo_b='$fs' OR photo_c='$fs' OR photo_d='$fs' OR photo_e='$fs' OR photo_f='$fs' OR extra_a='$fs' OR extra_b='$fs' OR extra_c='$fs'" . @db_variables; }
    elseif ($key == "has_street_view") { if ($value == "true") $db_vars =  "AND street_view_url_a != '' " . @$db_vars; }
    elseif ($key == "has_street_view") { if ($value == "false") $db_vars = " AND street_view_url_a = '' " . @$db_vars; }
    elseif ($key == "address") { $db_vars = " AND " . $key . ' like "%'.$value.'%"' . @$db_vars; }
    elseif ($key == "tags") { $db_vars = " AND ". $key . ' like "%'.$value.'%"' . @$db_vars; }
    elseif ($key == "date") { if ($value[0] == ">") $db_vars = "AND date_added" . ' >= "'.$trimChar.'"' . @$db_vars; }
    elseif ($key == "date") { if ($value[0] == "<") $db_vars = "AND date_added" . ' <= "'.$trimChar.'"' . @$db_vars; }
    elseif ($key == "id") { $db_vars = "AND (id = '$id' OR LTE_1 = '$id' OR LTE_2 = '$id' OR LTE_3 = '$id' OR LTE_4 = '$id' OR LTE_5 = '$id' OR LTE_6 = '$id' OR NR_1 = '$id' OR NR_2 = '$id') " . @$db_vars; }
    else { $db_vars = " AND ". $key . ' = "'.$value.'"' . @$db_vars; }
  }
}
if (!empty($db_vars)) $db_vars = "WHERE " . substr(strstr($db_vars," "), 1);
@$db_vars = str_replace('WHERE AND', 'WHERE', @$db_vars);
 ?>
