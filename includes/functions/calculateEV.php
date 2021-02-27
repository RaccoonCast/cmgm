<?php
function calculateEV($id,$carrier) {
  include "sqlpw.php";

  $ev = 0;
  $db_variables = "LTE_1='$id' OR LTE_2='$id' OR LTE_3='$id' OR LTE_4='$id' OR LTE_5='$id' OR LTE_6='$id'";
  $database_get_list = "permit_score,trails_match,carriers_dont_trail_match,antennas_match_carrier,cellmapper_triangulation,image_evidence,
  verified_by_visit,sector_split_match,archival_antenna_addition,only_reasonable_location,alt_carriers_here";
  $sql = "SELECT $database_get_list FROM database_db WHERE $db_variables";
  $result = mysqli_query($conn, $sql);
  while ($row = mysqli_fetch_assoc($result)) {

    $colCount = 1;
      foreach ($row as $field => $value) {
        $sepCount = ($colCount++);

  switch ($sepCount) {
    case 1:  $permit_score = $value; break;
    case 2:  $trails_match = $value; break;
    case 3:  $carriers_dont_trail_match = $value; break;
    case 4:  $antennas_match_carrier = $value; break;
    case 5:  $cellmapper_triangulation = $value; break;
    case 6:  $image_evidence = $value; break;
    case 7:  $verified_by_visit = $value; break;
    case 8:  $sector_split_match = $value; break;
    case 9:  $archival_antenna_addition = $value; break;
    case 10:  $only_reasonable_location = $value; break;
    case 11:  $alt_carriers_here = $value;

    // permit score
    if ($permit_score >= 10 && ($permit_score < 40)) $ev += 1; // have a permit but no carrier name
    if ($permit_score >= 40 && ($permit_score < 60)) $ev += 2; // have ev with a nearby address match
    if ($permit_score >= 60 && ($permit_score < 75)) $ev += 5; // have ev with address match - ish
    if ($permit_score >= 75 && ($permit_score < 100)) $ev += 11; // have ev with almost perfect address match
    if ($permit_score >= 100) $ev += 15; // have precisely perfect ev match

    // trails match
    if ($trails_match >= 15 && ($trails_match < 35)) $ev += 1;
    if ($trails_match >= 35 && ($trails_match < 45)) $ev += 2;
    if ($trails_match >= 45 && ($trails_match < 65)) $ev += 3;
    if ($trails_match >= 65 && ($trails_match < 85)) $ev += 4;
    if ($trails_match >= 85 ) $ev += 5;

    // carrier's don't trail match
    if ($alt_carriers_here == "0") {
    if ($carriers_dont_trail_match == 1) $ev += 1;
    if ($carriers_dont_trail_match == 2) $ev += 3;
    if ($carriers_dont_trail_match == 3) $ev += 8;
    }
    if ($alt_carriers_here == "1") $ev += 1;
    if ($alt_carriers_here == "2") $ev += 3;
    if ($alt_carriers_here == "3") $ev += 5;

    // antennas match carrier
    if ($antennas_match_carrier >= 20 && ($antennas_match_carrier < 60)) $ev += 1;
    if ($antennas_match_carrier >= 60 && ($antennas_match_carrier < 80)) $ev += 2;
    if ($antennas_match_carrier >= 80 && ($antennas_match_carrier < 100)) $ev += 4;
    if ($antennas_match_carrier >= 100) $ev += 6;

    // cellmapper triangulation
    if ($cellmapper_triangulation >= 20 && ($cellmapper_triangulation < 60)) $ev += 1;
    if ($cellmapper_triangulation >= 60 && ($cellmapper_triangulation < 80)) $ev += 2;
    if ($cellmapper_triangulation >= 80 && ($cellmapper_triangulation < 100)) $ev += 3;
    if ($cellmapper_triangulation >= 100 ) $ev += 5;

    // image evidence
    if ($image_evidence >= 45 && ($image_evidence < 65)) $ev += 4;
    if ($image_evidence >= 65 && ($image_evidence < 95)) $ev += 7;
    if ($image_evidence >= 95) $ev += 11;

    // verified by visit
    if ($verified_by_visit >= 10 && ($verified_by_visit < 30)) $ev += 1;
    if ($verified_by_visit >= 30 && ($verified_by_visit < 60)) $ev += 2;
    if ($verified_by_visit >= 60 && ($verified_by_visit < 90)) $ev += 5;
    if ($verified_by_visit >= 100 ) $ev += 8;

    // sector split match
    if ($sector_split_match >= 25 && ($sector_split_match < 45)) $ev += 2;
    if ($sector_split_match >= 45 && ($sector_split_match < 75)) $ev += 3;
    if ($sector_split_match >= 75 && ($sector_split_match < 90)) $ev += 5;
    if ($sector_split_match >= 90 && ($sector_split_match < 100)) $ev += 6;
    if ($sector_split_match >= 100) $ev += 8;


    // archival antenna addition
    if ($archival_antenna_addition >= 1 && ($archival_antenna_addition < 2)) $ev += 3;
    if ($archival_antenna_addition >= 2 && ($archival_antenna_addition < 3)) $ev += 7;
    if ($archival_antenna_addition >= 3 ) $ev += 10;

    // only reasonable location
    if ($permit_score < 40) {
      if ($only_reasonable_location >= 15 && ($only_reasonable_location < 45)) $ev += 1;
      if ($only_reasonable_location >= 85 ) $ev += 2;
    } elseif ($permit_score >= 40 && ($permit_score < 60)) {
      if ($only_reasonable_location >= 15 && ($only_reasonable_location < 45)) $ev += 1;
      if ($only_reasonable_location >= 45 && ($only_reasonable_location < 65)) $ev += 2;
      if ($only_reasonable_location >= 65 && ($only_reasonable_location < 85)) $ev += 3;
      if ($only_reasonable_location >= 85 ) $ev += 4;
    } elseif ($permit_score >= 60 && ($permit_score < 100)) {
      if ($only_reasonable_location >= 15 && ($only_reasonable_location < 45)) $ev += 3;
      if ($only_reasonable_location >= 45 && ($only_reasonable_location < 65)) $ev += 4;
      if ($only_reasonable_location >= 65 && ($only_reasonable_location < 85)) $ev += 4;
      if ($only_reasonable_location >= 85 ) $ev += 5;
    } elseif ($permit_score >= 100) {
      if ($only_reasonable_location >= 15 && ($only_reasonable_location < 45)) $ev += 3;
      if ($only_reasonable_location >= 45 && ($only_reasonable_location < 65)) $ev += 5;
      if ($only_reasonable_location >= 65 && ($only_reasonable_location < 85)) $ev += 5;
      if ($only_reasonable_location >= 85 ) $ev += 7;
    }

    break;
              }
      }
  }
return $ev;
}
 ?>
