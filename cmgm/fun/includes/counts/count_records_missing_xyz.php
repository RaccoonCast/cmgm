<?php
// copy and paste from db-filter, when making mods mod db-filter first.
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
AND (status = "verified")';


if (!isset($json_flag)) echo "<h3>Pins missing ______</h3><hr>";
echo "<b>Street view: </b><br>";
$sql = "SELECT SUM(CASE WHEN (sv_a != '' OR sv_b != '' OR sv_c != '' OR sv_d != '' OR sv_e != '' OR sv_f != '') THEN 1 ELSE 0 END) AS street_view_has_count,
       SUM(CASE WHEN (sv_a = '' AND sv_b = '' AND sv_c = '' AND sv_d = '' AND sv_e = '' AND sv_f = '') THEN 1 ELSE 0 END) AS street_view_does_not_has_count
		  FROM db WHERE $db_vars;";

$result = $conn->query($sql);

// Output data
while ($row = $result->fetch_assoc()) {

	if (isset($_GET['percents_view'])) {
    echo "Sites with: " .  '<a href="' . $current_url . '&has_street_view=true">' . getPercent($row["street_view_has_count"])  . '</a><br>';
	echo "Sites without: " .  '<a href="' . $current_url . '&has_street_view=false">' . getPercent($row["street_view_does_not_has_count"])  . '</a><br><br>';
	} elseif (isset($json_flag)) {
		$json_array["street_view_has_count"] = $row["street_view_has_count"];
		$json_array["street_view_does_not_has_count"] = $row["street_view_has_count"];
	} else {
		    echo "Sites with: " .  '<a href="' . $current_url . '&has_street_view=true">' . $row["street_view_has_count"]  . '</a><br>';
		    echo "Sites without: " .  '<a href="' . $current_url . '&has_street_view=false">' . $row["street_view_does_not_has_count"]  . '</a><br><br>';
	}
}

echo "<b>Evidence: </b><br>";
$sql = "SELECT SUM(CASE WHEN (evidence_a != '' OR evidence_b != '' OR evidence_c != '') THEN 1 ELSE 0 END) AS evidence_has_count,
       SUM(CASE WHEN (evidence_a = '' AND evidence_b = '' AND evidence_c = '') THEN 1 ELSE 0 END) AS evidence_does_not_has_count
		  FROM db WHERE $db_vars";

$result = $conn->query($sql);

// Output data
while ($row = $result->fetch_assoc()) {

	if (isset($_GET['percents_view'])) {
    echo "Sites with: " .  '<a href="' . $current_url . '&has_evidence=true">' . getPercent($row["evidence_has_count"])  . '</a><br>';
	echo "Sites without: " .  '<a href="' . $current_url . '&has_evidence=false">' . getPercent($row["evidence_does_not_has_count"])  . '</a><br><br>';
	} elseif (isset($json_flag)) {
		$json_array["evidence_has_count"] = $row["evidence_has_count"];
		$json_array["evidence_does_not_has_count"] = $row["evidence_does_not_has_count"];
	} else {
		    echo "Sites with: " .  '<a href="' . $current_url . '&has_evidence=true">' . $row["evidence_has_count"]  . '</a><br>';
		    echo "Sites without: " .  '<a href="' . $current_url . '&has_evidence=false">' . $row["evidence_does_not_has_count"]  . '</a><br><br>';
	}
}


$sql = "SELECT SUM(CASE WHEN ($incomplete_sql_query) THEN 1 ELSE 0 END) AS incomplete FROM db WHERE $db_vars";

$result = $conn->query($sql);

// Output data
while ($row = $result->fetch_assoc()) {

	if (isset($_GET['percents_view'])) {
    echo "<b>Overall incomplete records: </b>" .  '<a href="' . $current_url . '&incomplete=true">' . getPercent($row["incomplete"])  . '</a><br>';
	} elseif (isset($json_flag)) {
		$json_array["street_view_has_count"] = $row["incomplete"];
	} else {
		    echo "<b>Overall incomplete records: </b>" .  '<a href="' . $current_url . '&incomplete=true">' . $row["incomplete"]  . '</a><br>';
	}
}

echo (isset($_GET['json_flag'])) ? "<br>" : "";
?>
