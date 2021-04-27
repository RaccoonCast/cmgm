<?php
    $ev = 0;

    // permit score
    if (!isset($permit_score)) $permit_score = null;
    if (!isset($trails_match)) $trails_match = null;
    if (!isset($alt_carriers_here)) $alt_carriers_here = null;
    if (!isset($antennas_match_carrier)) $antennas_match_carrier = null;
    if (!isset($cellmapper_triangulation)) $cellmapper_triangulation = null;
    if (!isset($image_evidence)) $image_evidence = null;
    if (!isset($verified_by_visit)) $verified_by_visit = null;
    if (!isset($sector_split_match)) $sector_split_match = null;
    if (!isset($verified_by_visit)) $verified_by_visit = null;
    if (!isset($verified_by_visit)) $verified_by_visit = null;
    if (!isset($archival_antenna_addition)) $archival_antenna_addition = null;
    if (!isset($only_reasonable_location )) $only_reasonable_location = null;
    if (!isset($permit_score)) $permit_score = null;
    if (!isset($other_user_map_primary)) $other_user_map_primary  = null;

    // permit scores
    if ($permit_score >= 10 && ($permit_score < 40)) $ev += 1;
    if ($permit_score >= 40 && ($permit_score < 60)) $ev += 2;
    if ($permit_score >= 60 && ($permit_score < 75)) $ev += 5;
    if ($permit_score >= 75 && ($permit_score < 85)) $ev += 11;
    if ($permit_score >= 85 && ($permit_score < 100)) $ev += 13;
    if ($permit_score >= 100) $ev += 15;

    // trails match
    if ($trails_match >= 1 && ($trails_match < 15)) $ev += 1;
    if ($trails_match >= 15 && ($trails_match < 35)) $ev += 2;
    if ($trails_match >= 35 && ($trails_match < 45)) $ev += 3;
    if ($trails_match >= 45 && ($trails_match < 65)) $ev += 4;
    if ($trails_match >= 65 && ($trails_match < 85)) $ev += 5;
    if ($trails_match >= 85 ) $ev += 6;

    // carrier's don't trail match
    if ($alt_carriers_here == "0") {
    if ($carriers_dont_trail_match == 1) $ev += 1;
    if ($carriers_dont_trail_match == 2) $ev += 3;
    if ($carriers_dont_trail_match == 3) $ev += 8;
    }

    // alt carriers here
    if ($alt_carriers_here == "1") $ev += 1;
    if ($alt_carriers_here == "2") $ev += 2;
    if ($alt_carriers_here == "3") $ev += 3;

    // antennas match carrier
    if ($antennas_match_carrier >= 1 && ($antennas_match_carrier < 20)) $ev += 1;
    if ($antennas_match_carrier >= 20 && ($antennas_match_carrier < 60)) $ev += 2;
    if ($antennas_match_carrier >= 60 && ($antennas_match_carrier < 80)) $ev += 3;
    if ($antennas_match_carrier >= 80 && ($antennas_match_carrier < 100)) $ev += 5;
    if ($antennas_match_carrier >= 100) $ev += 7;

    // cellmapper triangulation
    if ($cellmapper_triangulation >= 20 && ($cellmapper_triangulation < 60)) $ev += 2;
    if ($cellmapper_triangulation >= 60 && ($cellmapper_triangulation < 80)) $ev += 3;
    if ($cellmapper_triangulation >= 80 && ($cellmapper_triangulation < 100)) $ev += 4;
    if ($cellmapper_triangulation >= 100 ) $ev += 6;

    // image evidence
    if ($image_evidence >= 45 && ($image_evidence < 65)) $ev += 5;
    if ($image_evidence >= 65 && ($image_evidence < 75)) $ev += 6;
    if ($image_evidence >= 75 && ($image_evidence < 95)) $ev += 7;
    if ($image_evidence >= 95) $ev += 11;

    // verified by visit
    if ($verified_by_visit >= 10 && ($verified_by_visit < 30)) $ev += 1;
    if ($verified_by_visit >= 30 && ($verified_by_visit < 60)) $ev += 3;
    if ($verified_by_visit >= 60 && ($verified_by_visit < 90)) $ev += 6;
    if ($verified_by_visit >= 100 ) $ev += 10;

    // sector split match
    if ($sector_split_match >= 25 && ($sector_split_match < 45)) $ev += 3;
    if ($sector_split_match >= 45 && ($sector_split_match < 75)) $ev += 4;
    if ($sector_split_match >= 75 && ($sector_split_match < 90)) $ev += 5;
    if ($sector_split_match >= 90 && ($sector_split_match < 100)) $ev += 8;
    if ($sector_split_match >= 100) $ev += 10;


    // archival antenna addition
    if ($archival_antenna_addition >= 1 && ($archival_antenna_addition < 2)) $ev += 4;
    if ($archival_antenna_addition >= 2 && ($archival_antenna_addition < 3)) $ev += 7;
    if ($archival_antenna_addition >= 3 ) $ev += 11;

    // only reasonable location
    if ($permit_score < 40) {
      if ($only_reasonable_location >= 15 && ($only_reasonable_location < 45)) $ev += 2;
      if ($only_reasonable_location >= 85 ) $ev += 3;
    } elseif ($permit_score >= 40 && ($permit_score < 60)) {
      if ($only_reasonable_location >= 15 && ($only_reasonable_location < 45)) $ev += 1;
      if ($only_reasonable_location >= 45 && ($only_reasonable_location < 65)) $ev += 3;
      if ($only_reasonable_location >= 65 && ($only_reasonable_location < 85)) $ev += 4;
      if ($only_reasonable_location >= 85 ) $ev += 5;
    } elseif ($permit_score >= 60 && ($permit_score < 100)) {
      if ($only_reasonable_location >= 15 && ($only_reasonable_location < 45)) $ev += 3;
      if ($only_reasonable_location >= 45 && ($only_reasonable_location < 65)) $ev += 4;
      if ($only_reasonable_location >= 65 && ($only_reasonable_location < 85)) $ev += 5;
      if ($only_reasonable_location >= 85 ) $ev += 6;
    } elseif ($permit_score >= 100) {
      if ($only_reasonable_location >= 15 && ($only_reasonable_location < 55)) $ev += 3; // (15-55)
      if ($only_reasonable_location >= 55 && ($only_reasonable_location < 65)) $ev += 4; // (55-65)
      if ($only_reasonable_location >= 45 && ($only_reasonable_location < 65)) $ev += 5; // (45-65)
      if ($only_reasonable_location >= 65 && ($only_reasonable_location < 85)) $ev += 6; // (65-85)
      if ($only_reasonable_location >= 85 && ($only_reasonable_location < 95)) $ev += 7; // (85-95)
      if ($only_reasonable_location >= 95 ) $ev += 8;                                    // (95+)
    }

    // other user map primary
    if ($other_user_map_primary == "true") $ev += 10;
?>
