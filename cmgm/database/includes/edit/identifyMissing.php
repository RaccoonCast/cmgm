<?php

$missingItems = [];
$thrownMissing = "";

// 1. Check the base mandatory conditions first 
// Using stripos() for case-insensitive matching, mimicking SQL's LIKE "%...%"
$isVerified = (isset($status) && $status === 'verified');
$isNotFuture = (!isset($tags) || stripos($tags, 'future') === false);
$isNotUnmapped = (!isset($tags) || stripos($tags, 'unmapped') === false);

// Only evaluate for missing data if it passes the base criteria
if ($isVerified && $isNotFuture && $isNotUnmapped) {

    // sv_a is present but sv_a_date is missing
    if ((isset($sv_a) && $sv_a !== "") && (!isset($sv_a_date) || $sv_a_date === "")) {
        $missingItems[] = "sv_a_date (required when sv_a is present)";
    }

    // lte_1 is present but region_lte is missing
    if ((!isset($region_lte) || $region_lte === "") && (isset($lte_1) && $lte_1 !== "")) {
        $missingItems[] = "region_lte (required when lte_1 is present)";
    }
    
    // pci_1 is missing
    if (!isset($PCI_1) || $PCI_1 === "") {
        $missingItems[] = "PCIs (perhaps it's unmapped?)";
    }

    // NR_1 is present but region_nr is missing
    if ((!isset($region_nr) || $region_nr === "") && (isset($NR_1) && $NR_1 !== "")) {
        $missingItems[] = "region_nr";
    }

    // No NR/LTE
    if (empty($LTE_1) && empty($NR_1)) {
        $missingItems[] = "eNB/gNB is not set (perhaps it's a future site?)";
    }


    // The large evidence block: flags as missing if ALL of these are null, empty string, or 0.
    // PHP's empty() is perfect here because it evaluates `null`, `""`, `0`, and `"0"` as true.
    if (
        empty($permit_score) &&
        empty($trails_match) &&
        empty($equipment_matches_carrier) &&
        empty($cellmapper_triangulation) &&
        empty($image_evidence) &&
        empty($verified_by_visit) &&
        empty($sector_split_match) &&
        empty($only_reasonable_location) &&
        empty($archival_antenna_addition) &&
        empty($carriers_ruled_out) &&
        empty($alt_carriers_here)
    ) {
        $missingItems[] = "location ratings";
    }

    if (!empty($missingItems)) {
        $count = count($missingItems);

        if ($count === 1) {
            $thrownMissing = $missingItems[0];
        } elseif ($count === 2) {
            $thrownMissing = implode(' and ', $missingItems);
        } else {
            $last = array_pop($missingItems);
            $thrownMissing = implode(', ', $missingItems) . ', and ' . $last;
        }
    }
}

?>