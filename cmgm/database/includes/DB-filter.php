<?php

$incomplete_sql_query = '
((((NOT sv_a = "" AND sv_a IS NOT NULL) AND (sv_a_date = "" OR sv_a_date IS NULL)) -- pulls records where SV_A is set but SV_A_date is not 
OR ((region_lte = "" AND lte_1 != "")) -- pulls records where region_lte is not set but lte_1 is
OR ((pci_1 = "")) -- pulls record where pci_1 is not set
OR ((NOT NR_1 = "" AND NR_1 IS NOT NULL) AND (region_nr = "" OR region_nr IS NULL)) -- pulls records where NR_1 is set but NR_region is not
OR ((cellsite_type = "" OR cellsite_type IS NULL))) -- pulls records where cell site type is not set
AND (tags NOT LIKE "%future%" AND tags NOT LIKE "%unmapped%") -- dont count count records marked future or unmapped
AND (status = "verified")) -- only show records marked as verified
';

if ($value == "NULL") $value = null; // Support user specifiying a search for empty fields.
if ($value == "!NULL") $trimChar = null; // Support user specifiying a search for non-empty fields.

// List of keys to ignore.
if ($key == "latitude" OR $key == "longitude" OR $key == "zoom" OR $key == "limit" OR $key == "marker_latitude" OR $key == "marker_longitude" OR $key == "back" OR $key == "pin_style" or $key == "q" or $key == "pin_size" OR $key == "title" OR $key == "percents_view" OR $key == "next")  { ${$key} = $value; }

// Filtering by ID ranges, like &id=69-420, shows records with CMGM #s between 69 and 420. (and including 69&420)
elseif ($key == "id" AND (strpos($value, '-') !== false)) { $strings = explode('-',$value); $db_vars = " AND ID BETWEEN $strings[0] AND $strings[1]" . @$db_vars; }

// Filtering by date ranges, like &date=2022-01-01,2022-03-01 to filter between January-March of 2022.
elseif ($key == "date" AND (strpos($value, ',') !== false)) {
    $strings = explode(',',$value);
    $db_vars = " AND date_added" . ' >= "'.date("Y-m-d", strtotime($strings[0])).'"' . @$db_vars;
    $db_vars = " AND date_added" . ' <= "'.date("Y-m-d", strtotime($strings[1])).'"' . @$db_vars;
  }

// Filtering by date ranges, like &date=<2022-01-01, to filter all records before January 1st 2022
elseif ($key == "date" && $value[0] == ">") { $db_vars = " AND date_added" . ' >= "'.date("Y-m-d", strtotime($trimChar)).'"' . @$db_vars; }
elseif ($key == "date" && $value[0] == "<") { $db_vars = " AND date_added" . ' <= "'.date("Y-m-d", strtotime($trimChar)).'"' . @$db_vars; }

// Filtering by whether street view is set, &has_street_view=false to show records missing SV.
elseif ($key == "has_street_view" && $value == "true") $db_vars = " AND sv_a != '' " . @$db_vars;
elseif ($key == "has_street_view" && $value == "false") $db_vars = " AND sv_a = '' " . @$db_vars;

// Filtering by date/year IS NOT like x, like &date=!2022 to show records where year is not 2022. Or &date=!06-01 to hide records created June 1st.
elseif (($key == "date" OR $key == "year") && @$value[0] == "!") { $db_vars = " AND (date_added not like '%".$value."%')" . @$db_vars; }

// Filtering by time IS NOT like X, like &time=!14 to show records not created at 2:00 PM.
elseif ($key == "time" && @$value[0] == "!") { $db_vars = " AND (date_added not like '% ".$trimChar.":%')" . @$db_vars; }

// Filtering by month IS NOT like x, like &month=!12 to show records not created during December.
elseif ($key == "month" && @$value[0] == "!") { $db_vars = " AND (MONTH(date_added) != ".$trimChar.")" . @$db_vars; }

// Filtering by string is not equal to x, with special support for tags. Example: &tags=!n41 OR &carrier=!T-Mobile 
elseif (@$value[0] == "!") {
  if ($key == "tags") { $db_vars = " AND (tags NOT like '".$trimChar.",%' AND tags NOT like '%,".$trimChar."' AND tags NOT like '%,".$trimChar.",%' AND NOT tags = '".$trimChar."' OR tags is null)" . @$db_vars; }
  elseif ($key != "tags") { $db_vars = " AND NOT " . $key . ' = "'.$trimChar.'"' . @$db_vars; }
}

// Filtering by greater than or less than x for other strings, example &lte_3=<60000 to show records greater with Lte_3 set to something greater than or equal to 60000. 
elseif (@$value[0] == ">") { $db_vars = " AND ". $key . ' >= '.$trimChar . @$db_vars; }
elseif (@$value[0] == "<") { $db_vars = " AND ". $key . ' <= '.$trimChar . @$db_vars; }

// Filtering by lte OR cmgm#id is X, exmaple &id=82869.
elseif ($key == "id") { $db_vars = " AND (id = '$id' OR LTE_1 = '$id' OR LTE_2 = '$id' OR LTE_3 = '$id' OR LTE_4 = '$id' OR LTE_5 = '$id' OR LTE_6 = '$id' OR LTE_7 = '$id' OR LTE_8 = '$id' OR LTE_9 = '$id' OR NR_1 = '$id' OR NR_2 = '$id' OR NR_3 = '$id') " . @$db_vars; }

// Filtering by a comma seperated list of cmgm #ids, example: 1,2,3,5,10,11,12 to show records 1-12 but not 6,7,8,9.
elseif ($key == "idlist") { $db_vars = " AND FIND_IN_SET(`id`, '$value')" . @$db_vars; }

// Filtering by an attached filename mentioned on various records. Example: &fileSearch=image_34324324.jpg will show records where image_34324324.jpg is referenced.
elseif ($key == "fileSearch") { $db_vars = " AND (evidence_a like '%$fs%' OR evidence_b like '%$fs%' OR evidence_c like '%$fs%' OR photo_a like '%$fs%' OR photo_b like '%$fs%' OR photo_c like '%$fs%' OR photo_d like '%$fs%' OR photo_e like '%$fs%' OR photo_f like '%$fs%' OR extra_a like '%$fs%' OR extra_b like '%$fs%' OR extra_c like '%$fs%')" . @$db_vars; }

// Address search, example &address=Main will filter records on Main St/Main Ave/etc.
elseif ($key == "address" && !empty($value)) { $db_vars = " AND " . $key . ' like "% '.$value.' %"' . @$db_vars; }
elseif ($key == "tags") { $db_vars = " AND (tags like '".$value.",%' OR tags like '%,".$value."' OR tags like '%,".$value.",%' OR tags = '".$value."')" . @$db_vars; }
elseif ($key == "edit_date_like") { $db_vars = " AND (edit_date like '%".$value."%')" . @$db_vars; }
elseif ($key == "notes_like") { $db_vars =  " AND (notes like '%".$value."%')" . @$db_vars; }
elseif ($key == "cellsite_type" OR $key == "cellsite" OR $key == "type") { $db_vars =  " AND (cellsite_type like '%".$value."%')" . @$db_vars; }
elseif ($key == "site_id") { $db_vars = " AND (site_id like '%".$value."%')" . @$db_vars; }
elseif ($key == "date" OR $key == "year") { $db_vars = " AND (date_added like '%".$value."%')" . @$db_vars; }
elseif ($key == "time") { $db_vars = " AND (date_added like '% ".$value."%')" . @$db_vars; }
elseif ($key == "month") { $db_vars = " AND (MONTH(date_added) = ".$value.")" . @$db_vars; }
elseif ($key == "username") { $db_vars = " AND created_by = '".$value."'" . @$db_vars; }
elseif ($key == "incomplete" & $value == "true") { $db_vars = " AND $incomplete_sql_query" . @$db_vars; }
elseif ($key == "incomplete" & $value == "false") { $db_vars = " AND NOT $incomplete_sql_query" . @$db_vars; }
else { $db_vars = " AND ". $key . ' = "'.$value.'"' . @$db_vars; }
?>
