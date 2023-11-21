<?php
    $ev = 0;

    // permit score
    if (!isset($permit_score) OR empty($permit_score)) $permit_score = 0;
    if (!isset($trails_match) OR empty($trails_match)) $trails_match = 0;
    if (!isset($alt_carriers_here) OR empty($alt_carriers_here)) $alt_carriers_here = 0;
    if (!isset($equipment_matches_carrier) OR empty($equipment_matches_carrier)) $equipment_matches_carrier = 0;
    if (!isset($cellmapper_triangulation) OR empty($cellmapper_triangulation)) $cellmapper_triangulation = 0;
    if (!isset($image_evidence) OR empty($image_evidence)) $image_evidence = 0;
    if (!isset($verified_by_visit) OR empty($verified_by_visit)) $verified_by_visit = 0;
    if (!isset($sector_split_match) OR empty($sector_split_match)) $sector_split_match = 0;
    if (!isset($verified_by_visit) OR empty($verified_by_visit)) $verified_by_visit = 0;
    if (!isset($archival_antenna_addition) OR empty($archival_antenna_addition)) $archival_antenna_addition = 0;
    if (!isset($only_reasonable_location) OR empty($only_reasonable_location)) $only_reasonable_location = 0;
    if (!isset($other_user_map_primary) OR empty($other_user_map_primary)) $other_user_map_primary  = 0;

    // permit scores
    if ($permit_score >= 25 && ($permit_score < 50)) $ev += 2;
    if ($permit_score >= 50 && ($permit_score < 60)) $ev += 5;
    if ($permit_score >= 60 && ($permit_score < 75)) $ev += 7;
    if ($permit_score >= 75 && ($permit_score < 85)) $ev += 10;
    if ($permit_score >= 85 && ($permit_score < 100)) $ev += 17;
    if ($permit_score >= 100) $ev += 20;

    // trails match
    if ($trails_match >= 20 && ($trails_match < 45)) $ev += 1;
    if ($trails_match >= 45 && ($trails_match < 65)) $ev += 2;
    if ($trails_match >= 65 && ($trails_match < 85)) $ev += 3;
    if ($trails_match >= 85 && ($trails_match < 100)) $ev += 4;
    if ($trails_match >= 100) $ev += 5;

    // carriers ruled out
    // A) Because they have poor trails here
    // B) Because they also have antennas here
    // C) Because another eNB ID is stretching to provide coverage in this area
    // D) RootMetrics Data shows their coverage to shit/not as good.
    if (@$carriers_ruled_out == 1) $ev += 3;
    if (@$carriers_ruled_out == 2) $ev += 6;
    if (@$carriers_ruled_out == 3) $ev += 9;
    if (@$carriers_ruled_out == 4) $ev += 12;

    // antennas match carrier
    if ($equipment_matches_carrier >= 20 && ($equipment_matches_carrier < 50)) $ev += 2;
    if ($equipment_matches_carrier >= 50 && ($equipment_matches_carrier < 80)) $ev += 4;
    if ($equipment_matches_carrier >= 80 && ($equipment_matches_carrier < 90)) $ev += 7;
    if ($equipment_matches_carrier >= 90 && ($equipment_matches_carrier < 100)) $ev += 9;
    if ($equipment_matches_carrier >= 100) $ev += 13;

    // cellmapper triangulation
    if ($cellmapper_triangulation >= 20 && ($cellmapper_triangulation < 60)) $ev += 2;
    if ($cellmapper_triangulation >= 60 && ($cellmapper_triangulation < 80)) $ev += 4;
    if ($cellmapper_triangulation >= 80 && ($cellmapper_triangulation < 100)) $ev += 6;
    if ($cellmapper_triangulation >= 100) $ev += 7;

    // Combo Pack!
    if ($only_reasonable_location >= 80 && ($only_reasonable_location >= 100)) if ($sector_split_match >= 80 && ($sector_split_match >= 100)) if ($cellmapper_triangulation >= 100 ) $ev += 5;
    if ($only_reasonable_location >= 80 && ($only_reasonable_location >= 100)) if ($sector_split_match >= 0 && ($sector_split_match < 80)) if ($cellmapper_triangulation >= 100 ) $ev += 4;
    if ($only_reasonable_location < 80) if ($sector_split_match >= 80 && ($sector_split_match >= 100)) if ($cellmapper_triangulation >= 100 ) $ev += 3;
    if ($only_reasonable_location < 80) if ($sector_split_match >= 0 && ($sector_split_match < 80)) if ($cellmapper_triangulation >= 100 ) $ev += 2;

    // Combo Pack!
    if (@$carriers_ruled_out == 3) if ($sector_split_match >= 90 && ($sector_split_match >= 100)) $ev += 4;
    if (@$carriers_ruled_out == 2) if ($sector_split_match >= 90 && ($sector_split_match >= 100)) $ev += 2;

    // image evidence
    if ($image_evidence >= 25 && ($image_evidence < 45)) $ev += 2;
    if ($image_evidence >= 45 && ($image_evidence < 65)) $ev += 4;
    if ($image_evidence >= 65 && ($image_evidence < 75)) $ev += 7;
    if ($image_evidence >= 75 && ($image_evidence < 100)) $ev += 9;
    if ($image_evidence >= 100) $ev += 12;

    // verified by visit
    if ($equipment_matches_carrier >= 80 && ($equipment_matches_carrier <= 100)) {
      if ($verified_by_visit >= 10 && ($verified_by_visit < 30)) $ev += 2;
      if ($verified_by_visit >= 30 && ($verified_by_visit < 60)) $ev += 4;
      if ($verified_by_visit >= 60 && ($verified_by_visit < 80)) $ev += 10;
      if ($verified_by_visit >= 80 && ($verified_by_visit < 100)) $ev += 12;
      if ($verified_by_visit >= 100) $ev += 16;
    } else {
      if ($verified_by_visit >= 30 && ($verified_by_visit < 60)) $ev += 1;
      if ($verified_by_visit >= 60 && ($verified_by_visit < 80)) $ev += 5;
      if ($verified_by_visit >= 80 && ($verified_by_visit < 100)) $ev += 7;
      if ($verified_by_visit >= 100) $ev += 10;
    }

    // sector split match
    if ($sector_split_match >= 35 && ($sector_split_match < 50)) $ev += 2;
    if ($sector_split_match >= 50 && ($sector_split_match < 60)) $ev += 3;
    if ($sector_split_match >= 60 && ($sector_split_match < 75)) $ev += 4;
    if ($sector_split_match >= 75 && ($sector_split_match < 85)) $ev += 7;
    if ($sector_split_match >= 85 && ($sector_split_match < 90)) $ev += 8;
    if ($sector_split_match >= 90 && ($sector_split_match < 100)) $ev += 11;
    if ($sector_split_match >= 100) $ev += 14;


    // archival antenna addition
    if ($equipment_matches_carrier >= 80 && ($equipment_matches_carrier <= 100)) {
      if ($archival_antenna_addition == 1) $ev += 3;
      if ($archival_antenna_addition == 2) $ev += 5;
      if ($archival_antenna_addition == 3) $ev += 8;
      if ($archival_antenna_addition == 4) $ev += 10;
      if ($archival_antenna_addition == 5) $ev += 13;
    } else {
      if ($archival_antenna_addition == 1) $ev += 1;
      if ($archival_antenna_addition == 2) $ev += 2;
      if ($archival_antenna_addition == 3) $ev += 4;
      if ($archival_antenna_addition == 4) $ev += 6;
      if ($archival_antenna_addition == 5) $ev += 8;
    }

    // only reasonable location
    if ($permit_score < 40) {
      if ($only_reasonable_location >= 55 && ($only_reasonable_location < 75)) $ev += 1;  // (55-75)
      if ($only_reasonable_location >= 75 && ($only_reasonable_location < 100)) $ev += 2; // (75-99)
      if ($only_reasonable_location >= 100 ) $ev += 3;                                    // (100)
    } elseif ($permit_score >= 40 && ($permit_score < 60)) {
      if ($only_reasonable_location >= 45 && ($only_reasonable_location < 70)) $ev += 2;  // (45-70)
      if ($only_reasonable_location >= 70 && ($only_reasonable_location < 100)) $ev += 3; // (70-99)
      if ($only_reasonable_location >= 100 ) $ev += 4;                                    // (100)
    } elseif ($permit_score >= 60 && ($permit_score < 100)) {
      if ($only_reasonable_location >= 45 && ($only_reasonable_location < 60)) $ev += 2;  // (45-70)
      if ($only_reasonable_location >= 60 && ($only_reasonable_location < 75)) $ev += 3;  // (60-75)
      if ($only_reasonable_location >= 75 && ($only_reasonable_location < 100)) $ev += 4; // (75-99)
      if ($only_reasonable_location >= 100 ) $ev += 5;                                    // (100)
    } elseif ($permit_score >= 100) {
      if ($only_reasonable_location >= 45 && ($only_reasonable_location < 60)) $ev += 3;  // (45-60)
      if ($only_reasonable_location >= 60 && ($only_reasonable_location < 70)) $ev += 4;  // (60-70)
      if ($only_reasonable_location >= 70 && ($only_reasonable_location < 100)) $ev += 6; // (70-99)
      if ($only_reasonable_location >= 100 ) $ev += 8;                                    // (100)
    }

    // other user map primary
    if ($other_user_map_primary == "true") $ev += 5;
?>
