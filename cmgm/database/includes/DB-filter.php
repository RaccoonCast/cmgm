<?php

$incomplete_sql_query = '
((((NOT sv_a = "" AND sv_a IS NOT NULL) AND (sv_a_date = "" OR sv_a_date IS NULL)) -- pulls records where SV_A is set but SV_A_date is not 
OR ((region_lte = "" AND lte_1 != "")) -- pulls records where region_lte is not set but lte_1 is
OR ((pci_1 = "")) -- pulls record where pci_1 is not set
OR ((region_nr = "" AND NR_1 != "")) -- pulls records where NR_1 is set but NR_region is not
OR ((cellsite_type = "" OR cellsite_type IS NULL))) -- pulls records where cell site type is not set
AND (tags NOT LIKE "%future%" AND tags NOT LIKE "%unmapped%") -- dont count records marked future or unmapped
AND (status = "verified")) -- only show records marked as verified
';

if ($value == "NULL") $value = null; // Support user specifiying a search for empty fields.
if ($value == "!NULL") $trimChar = null; // Support user specifiying a search for non-empty fields.

// List of keys to ignore.
if ($key == "latitude" OR $key == "longitude" OR $key == "zoom" OR $key == "limit" OR $key == "marker_latitude" OR $key == "marker_longitude" OR $key == "back" OR $key == "pin_style" or $key == "q" or $key == "hideui" or $key == "pin_size" OR $key == "title" OR $key == "percents_view" OR $key == "next" or str_contains($key, "polygon"))  { ${$key} = $value; }

// Filter records by LTE or CMGM #id, e.g., &id=82869.
// Filter records by a comma-separated list of CMGM #ids, e.g., &idlist=1,2,3,5,10,11,12 to show records 1-12 but not 6,7,8,9.
// Filter records by ID ranges, e.g., &id=69-420, to include CMGM #s between 69 and 420 (including 69 and 420).
elseif ($key == "lte_1" AND (strpos($value, '-') !== false)) { $strings = explode('-',$value); $db_vars = " AND lte_1 BETWEEN $strings[0] AND $strings[1]" . @$db_vars; }
elseif ($key == "lte_2" AND (strpos($value, '-') !== false)) { $strings = explode('-',$value); $db_vars = " AND lte_2 BETWEEN $strings[0] AND $strings[1]" . @$db_vars; }
elseif ($key == "lte_3" AND (strpos($value, '-') !== false)) { $strings = explode('-',$value); $db_vars = " AND lte_3 BETWEEN $strings[0] AND $strings[1]" . @$db_vars; }
elseif ($key == "nr_1" AND (strpos($value, '-') !== false)) { $strings = explode('-',$value); $db_vars = " AND nr_1 BETWEEN $strings[0] AND $strings[1]" . @$db_vars; }
elseif ($key == "nr_2" AND (strpos($value, '-') !== false)) { $strings = explode('-',$value); $db_vars = " AND nr_2 BETWEEN $strings[0] AND $strings[1]" . @$db_vars; }
elseif ($key == "nr_3" AND (strpos($value, '-') !== false)) { $strings = explode('-',$value); $db_vars = " AND nr_3 BETWEEN $strings[0] AND $strings[1]" . @$db_vars; }
elseif ($key == "id" AND (strpos($value, '-') !== false)) { $strings = explode('-',$value); $db_vars = " AND ID BETWEEN $strings[0] AND $strings[1]" . @$db_vars; }
elseif ($key == "idlist") { $db_vars = " AND FIND_IN_SET(`id`, '$value')" . @$db_vars; }
elseif ($key == "id") { $db_vars = " AND (id = '$id' OR LTE_1 = '$id' OR LTE_2 = '$id' OR LTE_3 = '$id' OR LTE_4 = '$id' OR LTE_5 = '$id' OR LTE_6 = '$id' OR LTE_7 = '$id' OR LTE_8 = '$id' OR LTE_9 = '$id' OR NR_1 = '$id' OR NR_2 = '$id' OR NR_3 = '$id') " . @$db_vars; }

// Filtering by date ranges, like &date=2022-01-01,2022-03-01 to filter between January-March of 2022.
elseif ($key == "date" AND (strpos($value, ',') !== false)) {
    $strings = explode(',',$value);
    $db_vars = " AND date_added" . ' >= "'.date("Y-m-d", strtotime($strings[0])).'"' . @$db_vars;
    $db_vars = " AND date_added" . ' <= "'.date("Y-m-d", strtotime($strings[1])).'"' . @$db_vars;
  }

// Filtering by date ranges, like &date=<2022-01-01, to filter all records before January 1st 2022
elseif ($key == "date" && $value[0] == ">") { $db_vars = " AND date_added" . ' >= "'.date("Y-m-d", strtotime($trimChar)).'"' . @$db_vars; }
elseif ($key == "date" && $value[0] == "<") { $db_vars = " AND date_added" . ' <= "'.date("Y-m-d", strtotime($trimChar)).'"' . @$db_vars; }

// Filter records by date, e.g., &date=2022 for records created in 2022, or &date=!2022 to exclude records from 2022 or &date=!06-01 to hide records from June 1st.
elseif (($key == "date" OR $key == "year") && @$value[0] == "!") { $db_vars = " AND (date_added not like '%".$value."%')" . @$db_vars; }
elseif ($key == "date" OR $key == "year") { $db_vars = " AND (date_added like '%".$value."%')" . @$db_vars; }

// Filter records by time, e.g., &month=5 for records created in May, or &month=!12 to exclude records from December.
elseif ($key == "month" && @$value[0] == "!") { $db_vars = " AND (MONTH(date_added) != ".$trimChar.")" . @$db_vars; }
elseif ($key == "month") { $db_vars = " AND (MONTH(date_added) = ".$value.")" . @$db_vars; }

// Filter records by time, e.g., &time=11 for records created at 11 AM, or &time=!14 to exclude records created at 2:00 PM.
elseif ($key == "time" && @$value[0] == "!") { $db_vars = " AND (date_added not like '% ".$trimChar.":%')" . @$db_vars; }
elseif ($key == "time") { $db_vars = " AND (date_added like '% ".$value."%')" . @$db_vars; }

// Filtering by whether street view is set, &has_street_view=false to show records missing SV.
elseif ($key == "has_street_view" && $value == "true") $db_vars = " AND sv_a != '' " . @$db_vars;
elseif ($key == "has_street_view" && $value == "false") $db_vars = " AND sv_a = '' " . @$db_vars;

// Filtering by string is not equal to x, with special support for tags. Example: &tags=!n41 OR &carrier=!T-Mobile 
elseif (@$value[0] == "!") {
  if ($key == "tags") { $db_vars = " AND (tags NOT like '".$trimChar.",%' AND tags NOT like '%,".$trimChar."' AND tags NOT like '%,".$trimChar.",%' AND NOT tags = '".$trimChar."' OR tags is null)" . @$db_vars; }
  elseif ($key != "tags") { $db_vars = " AND NOT " . $key . ' = "'.$trimChar.'"' . @$db_vars; }
}

// Filtering by an attached filename mentioned on various records. Example: &fileSearch=image_34324324.jpg will show records where image_34324324.jpg is referenced.
elseif ($key == "filesearch") { $db_vars = " AND (evidence_a like '%$fs%' OR evidence_b like '%$fs%' OR evidence_c like '%$fs%' OR photo_a like '%$fs%' OR photo_b like '%$fs%' OR photo_c like '%$fs%' OR photo_d like '%$fs%' OR photo_e like '%$fs%' OR photo_f like '%$fs%' OR extra_a like '%$fs%' OR extra_b like '%$fs%' OR extra_c like '%$fs%')" . @$db_vars; }
elseif ($key == "filesearchexclude") { $db_vars = " AND (evidence_a NOT LIKE '%$fs%' AND evidence_b NOT LIKE '%$fs%' AND evidence_c NOT LIKE '%$fs%' AND photo_a NOT LIKE '%$fs%' AND photo_b NOT LIKE '%$fs%' AND photo_c NOT LIKE '%$fs%' AND photo_d NOT LIKE '%$fs%' AND photo_e NOT LIKE '%$fs%' AND photo_f NOT LIKE '%$fs%' AND extra_a NOT LIKE '%$fs%' AND extra_b NOT LIKE '%$fs%' AND extra_c NOT LIKE '%$fs%')" . @$db_vars; }
elseif ($key == "filesearchprefix") { $db_vars = " AND (evidence_a LIKE '$fs%' OR evidence_b LIKE '$fs%' OR evidence_c LIKE '$fs%' OR photo_a LIKE '$fs%' OR photo_b LIKE '$fs%' OR photo_c LIKE '$fs%' OR photo_d LIKE '$fs%' OR photo_e LIKE '$fs%' OR photo_f LIKE '$fs%' OR extra_a LIKE '$fs%' OR extra_b LIKE '$fs%' OR extra_c LIKE '$fs%')" . @$db_vars; }
elseif ($key == "filesearchprefixexclude") { $db_vars = " AND (evidence_a NOT LIKE '$fs%' AND evidence_b NOT LIKE '$fs%' AND evidence_c NOT LIKE '$fs%' AND photo_a NOT LIKE '$fs%' AND photo_b NOT LIKE '$fs%' AND photo_c NOT LIKE '$fs%' AND photo_d NOT LIKE '$fs%' AND photo_e NOT LIKE '$fs%' AND photo_f NOT LIKE '$fs%' AND extra_a NOT LIKE '$fs%' AND extra_b NOT LIKE '$fs%' AND extra_c NOT LIKE '$fs%')" . @$db_vars; }
elseif ($key == "filesearchsuffix") { $db_vars = " AND (evidence_a LIKE '%$fs' OR evidence_b LIKE '%$fs' OR evidence_c LIKE '%$fs' OR photo_a LIKE '%$fs' OR photo_b LIKE '%$fs' OR photo_c LIKE '%$fs' OR photo_d LIKE '%$fs' OR photo_e LIKE '%$fs' OR photo_f LIKE '%$fs' OR extra_a LIKE '%$fs' OR extra_b LIKE '%$fs' OR extra_c LIKE '%$fs')" . @$db_vars; }
elseif ($key == "filesearchsuffixexclude") { $db_vars = " AND (evidence_a NOT LIKE '%$fs' AND evidence_b NOT LIKE '%$fs' AND evidence_c NOT LIKE '%$fs' AND photo_a NOT LIKE '%$fs' AND photo_b NOT LIKE '%$fs' AND photo_c NOT LIKE '%$fs' AND photo_d NOT LIKE '%$fs' AND photo_e NOT LIKE '%$fs' AND photo_f NOT LIKE '%$fs' AND extra_a NOT LIKE '%$fs' AND extra_b NOT LIKE '%$fs' AND extra_c NOT LIKE '%$fs')" . @$db_vars; }

// Address search, example &address=Main will filter records on Main St/Main Ave/etc.
elseif ($key == "address" && !empty($value)) {
  $db_vars = " AND (
                         address LIKE '% $value %' 
                      OR address LIKE '$value %'
                      OR address LIKE '% $value'
                      OR address = '$value')" . @$db_vars;
}

// Search for tags, &tags=n41 to search for records tagged n41.
elseif ($key == "tags" && !empty($value)) {
  $db_vars = " AND (
                         tags LIKE '%,$value,%' 
                      OR tags LIKE '$value,%'
                      OR tags LIKE '%,$value'
                      OR tags = '$value')" . @$db_vars;
}

// Search for a edit date like, &edit_date_like=2022-01 will filter records created any time during January 2022.
elseif ($key == "edit_date_like") { $db_vars = " AND (edit_date like '%".$value."%')" . @$db_vars; }

// Search for notes containing a specific string, "AT&T" to look for records mentioning AT&T in notes.
elseif ($key == "notes_like") { $db_vars =  " AND (notes like '%".$value."%')" . @$db_vars; }

// Search for cellsite_type, supports &cellsite_tower=tower to look for tower_monopole/tower_lattice, or &cellsite_type=tower_monopole to filtering specifically tower monopoles.
elseif ($key == "cellsite_type" OR $key == "cellsite" OR $key == "type") { $db_vars =  " AND (cellsite_type like '%".$value."%')" . @$db_vars; }

// Search for site_id, example &site_id=CVL to filter sites with a site ID prefix of CVL.
elseif ($key == "site_id") { $db_vars = " AND (site_id like '%".$value."%')" . @$db_vars; }

// Search for records created by a specific user, example &username=Bob to show records made by Bob.
elseif ($key == "username") { $db_vars = " AND created_by = '".$value."'" . @$db_vars; }

// Search for records with missing info or records without missing info, example &incomplete=true will yield records that are missing info.
elseif ($key == "incomplete" & $value == "true") { $db_vars = " AND $incomplete_sql_query" . @$db_vars; }
elseif ($key == "incomplete" & $value == "false") { $db_vars = " AND NOT $incomplete_sql_query" . @$db_vars; }

// Filtering by lat/lon greater or less than.
elseif ($key == "lat" && $value[0] == ">") { $db_vars = " AND latitude" . ' >= "'.$trimChar.'"' . @$db_vars; }
elseif ($key == "lat" && $value[0] == "<") { $db_vars = " AND latitude" . ' <= "'.$trimChar.'"' . @$db_vars; }
elseif ($key == "lon" && $value[0] == "<") { $db_vars = " AND longitude" . ' <= "'.$trimChar.'"' . @$db_vars; }
elseif ($key == "lon" && $value[0] == "<") { $db_vars = " AND longitude" . ' <= "'.$trimChar.'"' . @$db_vars; }

// Filtering by greater than or less than x for other strings, example &lte_3=<60000 to show records greater with Lte_3 set to something greater than or equal to 60000. 
elseif (@$value[0] == ">") { $db_vars = " AND ". $key . ' >= '.$trimChar . @$db_vars; }
elseif (@$value[0] == "<") { $db_vars = " AND ". $key . ' <= '.$trimChar . @$db_vars; }

// If none of the past conditionals caught X, just query key_name=key_value, example &equipment_matches_carrier=60 will filter records where EMC is 60. 
else { $db_vars = " AND ". $key . ' = "'.$value.'"' . @$db_vars; }
?>
