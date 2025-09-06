<?php

$incomplete_sql_query = '
((((NOT sv_a = "" AND sv_a IS NOT NULL) AND (sv_a_date = "" OR sv_a_date IS NULL))
OR ((region_lte = "" AND lte_1 != ""))
OR ((pci_1 = ""))
OR ((region_nr = "" AND NR_1 != ""))
OR ((cellsite_type = "" OR cellsite_type IS NULL)))
OR ((permit_score IS NULL OR permit_score = "" OR permit_score = 0)
AND (trails_match IS NULL OR trails_match = "" OR trails_match = 0)
AND (equipment_matches_carrier IS NULL OR equipment_matches_carrier = "" OR equipment_matches_carrier = 0)
AND (cellmapper_triangulation IS NULL OR cellmapper_triangulation = "" OR cellmapper_triangulation = 0)
AND (image_evidence IS NULL OR image_evidence = "" OR image_evidence = 0)
AND (verified_by_visit IS NULL OR verified_by_visit = "" OR verified_by_visit = 0)
AND (sector_split_match IS NULL OR sector_split_match = "" OR sector_split_match = 0)
AND (only_reasonable_location IS NULL OR only_reasonable_location = "" OR only_reasonable_location = 0)
AND (archival_antenna_addition IS NULL OR archival_antenna_addition = "" OR archival_antenna_addition = 0)
AND (carriers_ruled_out IS NULL OR carriers_ruled_out = "" OR carriers_ruled_out = 0)
AND (alt_carriers_here IS NULL OR alt_carriers_here = "" OR alt_carriers_here = 0)))
AND (tags NOT LIKE "%future%" AND tags NOT LIKE "%unmapped%") 
AND (status = "verified")
';


$key = preg_replace('/[^A-Za-z0-9_]+/', '', $key);

if ($value === "NULL") { $value = null; } // Support user specifiying a search for empty fields.
else if ( $value === "!NULL") { $trimChar = null; } // Support user specifiying a search for non-empty fields.
elseif ($value !== null) { $value = mysqli_real_escape_string($conn, $value); }

// List of keys to ignore.
if ($key == "latitude" OR $key == "longitude" OR $key == "zoom" OR $key == "limit" OR $key == "marker_latitude" OR $key == "marker_longitude" OR $key == "back" OR $key == "pin_style" or $key == "q" or $key == "hideui" or $key == "pin_size" OR $key == "title" OR $key == "percents_view" OR $key == "next" OR $key == "cachebuster" or str_contains($key, "polygon"))  { ${$key} = $value; }

// Filter records by LTE or CMGM #id, e.g., &id=82869.
// Filter records by a comma-separated list of CMGM #ids, e.g., &idlist=1,2,3,5,10,11,12 to show records 1-12 but not 6,7,8,9.
// Filter records by ID ranges, e.g., &id=69-420, to include CMGM #s between 69 and 420 (including 69 and 420).
elseif (is_string($value) && preg_match('/^!?(?<start>\d+)-(?<end>\d+)$/', $value, $matches) && $key !== 'id') {
  $start = (int)$matches['start'];
  $end = (int)$matches['end'];

  if ($value[0] === "!") {
      // Exclusion range (!start-end)
      $db_vars = " AND $key NOT BETWEEN $start AND $end" . @$db_vars;
  } else {
      // Inclusion range (start-end)
      $db_vars .= " AND $key BETWEEN $start AND $end" . @$db_vars;
  }
}
elseif ($key == "idlist") { $db_vars = " AND FIND_IN_SET(`id`, '$value')" . @$db_vars; }

elseif ($key == "id" && is_string($id)) {
    $cols = ['id','LTE_1','LTE_2','LTE_3','LTE_4','LTE_5','LTE_6','LTE_7','LTE_8','LTE_9','NR_1','NR_2','NR_3','NR_4'];
    if (strpos($id, '-') !== false) {
        list($start, $end) = explode('-', $id, 2);
        $start = (int)trim($start);
        $end = (int)trim($end);
        $conds = array_map(fn($c) => "$c BETWEEN $start AND $end", $cols);
        $db_vars = " AND (" . implode(" OR ", $conds) . ") " . @$db_vars;
    } elseif (is_numeric($id)) {
        $id = (int)$id;
        $conds = array_map(fn($c) => "$c = $id", $cols);
        $db_vars = " AND (" . implode(" OR ", $conds) . ") " . @$db_vars;
    }
}

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
elseif (($key == "date" OR $key == "year") && @$value[0] == "!") { $db_vars = " AND (date_added not like '%".$trimChar."%')" . @$db_vars; }
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
elseif ($key == "filesearch") { $db_vars = " AND (evidence_a like '%$fs%' OR evidence_b like '%$fs%' OR evidence_c like '%$fs%' OR photo_a like '%$fs%' OR photo_b like '%$fs%' OR photo_c like '%$fs%' OR photo_d like '%$fs%' OR photo_e like '%$fs%' OR photo_f like '%$fs%' OR extra_a like '%$fs%' OR extra_b like '%$fs%' OR extra_c like '%$fs%' OR extra_d like '%$fs%' OR extra_e like '%$fs%' OR extra_f like '%$fs%')" . @$db_vars; }
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

// if value is empty, do default
elseif ($value == null) { $db_vars = " AND ". $key . ' = "'.$value.'"' . @$db_vars; }

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
