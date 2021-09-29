<?php
    $ev = 0;

    // permit score
    if (!isset($permit_score) OR empty($permit_score)) $permit_score = 0;
    if (!isset($trails_match) OR empty($trails_match)) $trails_match = 0;
    if (!isset($alt_carriers_here) OR empty($alt_carriers_here)) $alt_carriers_here = 0;
    if (!isset($antennas_match_carrier) OR empty($antennas_match_carrier)) $antennas_match_carrier = 0;
    if (!isset($cellmapper_triangulation) OR empty($cellmapper_triangulation)) $cellmapper_triangulation = 0;
    if (!isset($image_evidence) OR empty($image_evidence)) $image_evidence = 0;
    if (!isset($verified_by_visit) OR empty($verified_by_visit)) $verified_by_visit = 0;
    if (!isset($sector_split_match) OR empty($sector_split_match)) $sector_split_match = 0;
    if (!isset($verified_by_visit) OR empty($verified_by_visit)) $verified_by_visit = 0;
    if (!isset($archival_antenna_addition) OR empty($archival_antenna_addition)) $archival_antenna_addition = 0;
    if (!isset($only_reasonable_location) OR empty($only_reasonable_location)) $only_reasonable_location = 0;
    if (!isset($other_user_map_primary) OR empty($other_user_map_primary)) $other_user_map_primary  = 0;

    // permit scores
    if ($permit_score >= 10 && ($permit_score < 40)) $ev += 1;
    if ($permit_score >= 40 && ($permit_score < 60)) $ev += 3;
    if ($permit_score >= 60 && ($permit_score < 75)) $ev += 4;
    if ($permit_score >= 75 && ($permit_score < 85)) $ev += 7;
    if ($permit_score >= 85 && ($permit_score < 100)) $ev += 8;
    if ($permit_score >= 100) $ev += 9;

    // trails match
    if ($trails_match >= 45 && ($trails_match < 65)) $ev += 1;
    if ($trails_match >= 65 && ($trails_match < 85)) $ev += 2;
    if ($trails_match >= 85 && ($trails_match < 100)) $ev += 3;
    if ($trails_match >= 100) $ev += 4;

    // carriers ruled out
    // A) Because they have poor trails hebrev
    // B) Because they also have antennas here
    // C) Because another eNB ID is stretching to provide coverage in this area
    // D) RootMetrics Data shows their coverage to shit/not as good.
    if (@$carriers_ruled_out == 1) $ev += 1;
    if (@$carriers_ruled_out == 2) $ev += 3;
    if (@$carriers_ruled_out == 3) $ev += 5;

    // alt carriers here
    if (@$alt_carriers_here == "1") $ev += 1;
    if (@$alt_carriers_here == "2") $ev += 2;
    if (@$alt_carriers_here == "3") $ev += 3;

    // antennas match carrier
    if ($antennas_match_carrier >= 20 && ($antennas_match_carrier < 50)) $ev += 1;
    if ($antennas_match_carrier >= 50 && ($antennas_match_carrier < 80)) $ev += 2;
    if ($antennas_match_carrier >= 80 && ($antennas_match_carrier < 100)) $ev += 5;
    if ($antennas_match_carrier >= 100) $ev += 7;

    // cellmapper triangulation
    if ($cellmapper_triangulation >= 20 && ($cellmapper_triangulation < 60)) $ev += 1;
    if ($cellmapper_triangulation >= 60 && ($cellmapper_triangulation < 80)) $ev += 2;
    if ($cellmapper_triangulation >= 80 && ($cellmapper_triangulation < 100)) $ev += 3;
    if ($cellmapper_triangulation >= 100) $ev += 4;

    // Combo Pack!
    if ($only_reasonable_location >= 80 && ($only_reasonable_location >= 100)) if ($sector_split_match >= 80 && ($sector_split_match >= 100)) if ($cellmapper_triangulation >= 100 ) $ev += 4;
    if ($only_reasonable_location >= 80 && ($only_reasonable_location >= 100)) if ($sector_split_match >= 0 && ($sector_split_match < 80)) if ($cellmapper_triangulation >= 100 ) $ev += 3;
    if ($only_reasonable_location < 80) if ($sector_split_match >= 80 && ($sector_split_match >= 100)) if ($cellmapper_triangulation >= 100 ) $ev += 2;
    if ($only_reasonable_location < 80) if ($sector_split_match >= 0 && ($sector_split_match < 80)) if ($cellmapper_triangulation >= 100 ) $ev += 1;

    // image evidence
    if ($image_evidence >= 45 && ($image_evidence < 65)) $ev += 2;
    if ($image_evidence >= 65 && ($image_evidence < 75)) $ev += 4;
    if ($image_evidence >= 75 && ($image_evidence < 95)) $ev += 5;
    if ($image_evidence >= 95) $ev += 8;

    // verified by visit
    if ($verified_by_visit >= 10 && ($verified_by_visit < 30)) $ev += 1;
    if ($verified_by_visit >= 30 && ($verified_by_visit < 60)) $ev += 2;
    if ($verified_by_visit >= 60 && ($verified_by_visit < 80)) $ev += 5;
    if ($verified_by_visit >= 80 && ($verified_by_visit < 100)) $ev += 6;
    if ($verified_by_visit >= 100) $ev += 8;

    // sector split match
    if ($sector_split_match >= 25 && ($sector_split_match < 45)) $ev += 1;
    if ($sector_split_match >= 45 && ($sector_split_match < 75)) $ev += 2;
    if ($sector_split_match >= 75 && ($sector_split_match < 90)) $ev += 4;
    if ($sector_split_match >= 90 && ($sector_split_match < 100)) $ev += 6;
    if ($sector_split_match >= 100) $ev += 8;


    // archival antenna addition
    if ($archival_antenna_addition >= 1 && ($archival_antenna_addition < 2)) $ev += 2;
    if ($archival_antenna_addition >= 2 && ($archival_antenna_addition < 3)) $ev += 4;
    if ($archival_antenna_addition >= 3 ) $ev += 5;

    // only reasonable location
    if ($permit_score < 40 OR empty($permit_score)) {
      if ($only_reasonable_location >= 45 && ($only_reasonable_location < 100)) $ev += 1;
      if ($only_reasonable_location >= 100 ) $ev += 2;
    } elseif ($permit_score >= 40 && ($permit_score < 60)) {
      if ($only_reasonable_location >= 0 && ($only_reasonable_location < 70)) $ev += 1;
      if ($only_reasonable_location >= 70 && ($only_reasonable_location < 100)) $ev += 2;
      if ($only_reasonable_location >= 100 ) $ev += 3;
    } elseif ($permit_score >= 60 && ($permit_score < 100)) {
      if ($only_reasonable_location >= 45 && ($only_reasonable_location < 70)) $ev += 2;
      if ($only_reasonable_location >= 70 && ($only_reasonable_location < 85)) $ev += 3;
      if ($only_reasonable_location >= 100 ) $ev += 4;
    } elseif ($permit_score >= 100) {
      if ($only_reasonable_location >= 35 && ($only_reasonable_location < 65)) $ev += 1; // (55-65)
      if ($only_reasonable_location >= 45 && ($only_reasonable_location < 65)) $ev += 2; // (45-65)
      if ($only_reasonable_location >= 65 && ($only_reasonable_location < 85)) $ev += 3; // (65-85)
      if ($only_reasonable_location >= 85 && ($only_reasonable_location < 100)) $ev += 4; // (85-95)
      if ($only_reasonable_location >= 100 ) $ev += 6;                                    // (95+)
    }

    // other user map primary
    if ($other_user_map_primary == "true") $ev += 8;
?>
