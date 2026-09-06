<?php
// Location Types:
// 0: Average'd location from poly cells, could be adv_lat/adv_lon or simple lat/lon. The only important distinction is this is never a true verified location.
// 1: Apple's perfect surro location, quite reliably the exact location.
// 2: eNB has a Apple PS location, however, it is more than ~5 miles away from the average location of non-PS cells. The perfect surro is likely stale.
// 3: Pinned location from CellMapper
// 4: Pinned location from CMGM

function calculateMiles($lat1, $lon1, $lat2, $lon2) {
    $earthRadius = 3959; // Radius of Earth in Miles
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
    $c = 2 * asin(sqrt($a));
    return $earthRadius * $c;
}
function buildCappedPolygon($boundsNELat, $boundsNELon, $boundsSWLat, $boundsSWLon, $centerLat, $centerLon, $capLatDistance, $capLonDistance) {
    // Apply independent lat/lon caps
    $microNELat = min($boundsNELat, $centerLat + $capLatDistance);
    $microNELon = min($boundsNELon, $centerLon + $capLonDistance);
    $microSWLat = max($boundsSWLat, $centerLat - $capLatDistance);
    $microSWLon = max($boundsSWLon, $centerLon - $capLonDistance);

    return "POLYGON(($microSWLat $microSWLon, $microNELat $microSWLon, $microNELat $microNELon, $microSWLat $microNELon, $microSWLat $microSWLon))";
}

// Get variables needed
include "get_param.php";

// Start query build, identify whether working with LPB/LPE/LPBE.
$tableName = $view_mode == "cells" ? 'local_poly_beta' : 'poly_enbs';

$keys = $view_mode == "enbs" ? "plmn,rat,enb,tac,cells,location_type,oldest_date,newest_date" : "enb,cell AS cells,cell_id,plmn,rat,tac,latitude,longitude,date_of_info";
if ($view_mode !== "cells") {
    $keys .= ",poly_latitude AS latitude,poly_longitude AS longitude";
}


// Filter 1: PLMN
$blacklist = [311580, 311588, 311589];
$include = $exclude = [];

if ($plmn !== null) {
    foreach (explode(',', $plmn) as $val) {
        $val = trim($val);
        // Extract numbers; if valid/non-zero, route to include or exclude
        if ($num = (int) preg_replace('/\D/', '', $val)) {
            $val[0] === '!' ? $exclude[] = $num : $include[] = $num;
        }
    }

    if ($include) $whereFilters .= "plmn IN (" . implode(',', $include) . ") ";
    if ($exclude) $whereFilters .= "plmn NOT IN (" . implode(',', $exclude) . ") ";
}

// Check if any blacklisted PLMNs are still possible after filtering
$possible = $include ? array_intersect($include, $blacklist) : $blacklist;

if (array_diff($possible, $exclude)) {
    $whereFilters .= "NOT (plmn IN (" . implode(',', $blacklist) . ") AND tac > 10000)";
}


// Filter 2: Location (latitude & longitude)
if ($boundsNELat !== null && $boundsNELon !== null && $boundsSWLat !== null && $boundsSWLon !== null) {
    // Calculate Distances & Center Point
    $latDiff = abs($boundsNELat - $boundsSWLat);
    $lonDiff = abs($boundsNELon - $boundsSWLon);

    $centerLat = ($boundsNELat + $boundsSWLat) / 2;
    $centerLon = ($boundsNELon + $boundsSWLon) / 2;
    $centerPoint = "ST_GeomFromText('POINT($centerLat $centerLon)', 4326)";

    // Increase bounding box for mini
    if (isset($_GET['mini'])) {
        // ~10 mile bounding box (~20 mile wide bounding box)
        $miniRadius = 0.15;

        // Expand coordinates while clamping to valid lat/lon Earth boundaries
        $boundsNELat = min(90, $centerLat + $miniRadius);
        $boundsSWLat = max(-90, $centerLat - $miniRadius);
        $boundsNELon = min(180, $centerLon + $miniRadius);
        $boundsSWLon = max(-180, $centerLon - $miniRadius);

        // Recalculate diffs for any downstream logic
        $latDiff = abs($boundsNELat - $boundsSWLat);
        $lonDiff = abs($boundsNELon - $boundsSWLon);
    }

    // Reduce bounding box size if conditions for reducing are met.
    if ($limit !== 0) {
        $baseCap = null;
        $maxDiff = max($latDiff, $lonDiff); // Find the dominant axis
        // Determine the base cap distance using the dominant axis
        if ($view_mode == "enbs" || $view_mode == "cm"){
            if ($limit > 50000) {
                if ($maxDiff > 15.0) $baseCap = null;
            } elseif ($limit > 7499) {
                if ($maxDiff > 15.0) $baseCap = 7.5;
            } elseif ($limit > 2999) {
                if ($maxDiff > 10.0) $baseCap = 5.0;
            } elseif ($limit > 450) {
                if ($maxDiff > 7.0) $baseCap = 3.5;
            } elseif ($maxDiff > 4.50) {
                $baseCap = 2.25;
            }
        } elseif ($view_mode == "cells") {
            if ($limit > 50000) {
                if ($maxDiff > 10.0) $baseCap = null;
            } elseif ($limit > 7499) {
                if ($maxDiff > 10.0) $baseCap = 5.00;
            } elseif ($limit > 2999) {
                if ($maxDiff > 7.0) $baseCap = 3.50;
            } elseif ($limit > 450) {
                if ($maxDiff > 4.5) $baseCap = 2.25;
            } elseif ($maxDiff > 3.0) {
                $baseCap = 1.50;
            }
        }

        // Modify cap to consider aspect ratio of device.
        if ($baseCap !== null) {
        // Calculate multipliers (the dominant axis will always have a ratio of 1)
        $ratioLat = $latDiff / $maxDiff;
        $ratioLon = $lonDiff / $maxDiff;

        // Apply ratios to the base cap to get independent lat/lon caps
        $capLatDistance = $baseCap * $ratioLat;
        $capLonDistance = $baseCap * $ratioLon;

        if ($plmn !== null) $baseCap *= 1.75;
        if ($rat !== null) $baseCap *= 1.10;

        $searchPolygon = buildCappedPolygon(
            $boundsNELat, $boundsNELon, 
            $boundsSWLat, $boundsSWLon, 
            $centerLat, $centerLon, 
            $capLatDistance, $capLonDistance
        );
    }
    }

    // Bounding box not limited by previous if blocks, set bounding box to be equal to the user's bounding box.
    if (!isset($searchPolygon)) $searchPolygon = "POLYGON(($boundsSWLat $boundsSWLon, $boundsNELat $boundsSWLon, $boundsNELat $boundsNELon, $boundsSWLat $boundsNELon, $boundsSWLat $boundsSWLon))";

    $whereFiltersLocation .= "AND MBRWithin(coords, ST_GeomFromText('$searchPolygon', 4326)) ";
    $whereFiltersPolyLocation = "AND MBRWithin(poly_coords, ST_GeomFromText('$searchPolygon', 4326)) ";
    $whereFiltersCmLocation = "AND MBRWithin(cm_coords, ST_GeomFromText('$searchPolygon', 4326)) ";
} elseif ($latitude !== null && $longitude !== null) {
    // OPTION B: Haversine Formula)
    $distanceExpr = "(3959 * 2 * ASIN(SQRT(
        POWER(SIN(RADIANS(latitude - $latitude) / 2), 2) +
        COS(RADIANS($latitude)) * COS(RADIANS(latitude)) *
        POWER(SIN(RADIANS(longitude - $longitude) / 2), 2)
    )))";

    $orderByCell .= "ORDER BY ST_Distance_Sphere(coords, ST_SRID(POINT($longitude, $latitude), 4326)) ASC ";
}

// Filter 3: Date Filtering
$date_of_info = $oldest_date; // Rename oldest_date to date_of_info for Cells mode.
$dateKeys = ($view_mode === "enbs" || $view_mode === "cmgm"  || $view_mode === "cm") ? ['oldest_date', 'newest_date'] : ['date_of_info'];

foreach ($dateKeys as $key) {
    $val = $$key;

    if ($val === null) {
        continue;
    }

    if (strpos($val, ',') !== false) {
        $strings = explode(',', $val);

        $start = date("Y-m-d", strtotime($strings[0]));
        $end   = date("Y-m-d", strtotime($strings[1]));

        $whereFilters .= "AND ($key >= '$start' AND $key <= '$end') ";
    }
    elseif ($val[0] === '>') {
        $whereFilters .= "AND $key >= '" . date("Y-m-d", strtotime(substr($val, 1))) . "' ";
    }
    elseif ($val[0] === '<') {
        $whereFilters .= "AND $key <= '" . date("Y-m-d", strtotime(substr($val, 1))) . "' ";
    }
    elseif ($val[0] === '!') {
        $trimChar = substr($val, 1);
        $whereFilters .= "AND ($key NOT LIKE '%$trimChar%')";
    }
    else {
        $whereFilters .= "AND ($key LIKE '%$val%') ";
    }
}

// Filter 4: Within distance.
if ($radius !== null) {
    $whereFilters .= "AND $distanceExpr <= $radius ";
}

// Filter 5: rat
if ($rat !== null) {
    $whereFilters .= "AND RAT = '$rat' ";
    $ratFiltered = true;
}

// Filter 6: Tac
if ($tacs_allow_list !== null) {
    $tacs_allow_listArray = explode(',', $tacs_allow_list);
    $tacConditions = [];

    foreach ($tacs_allow_listArray as $range) {
        if (strpos($range, '-') !== false) {
            $bounds = explode('-', $range);
            if (count($bounds) == 2) {
            $start = (int)$bounds[0];
            $end   = (int)$bounds[1];
            $tacConditions[] = "tac BETWEEN $start AND $end";
            }
        } else {
            $tacConditions[] = "tac = $range";
        }
        }
        $bounds = explode('-', $range);
    if (!empty($tacConditions)) {
        $whereFilters .= "AND (" . implode(' OR ', $tacConditions) . ") ";
    }
}
if ($tacs_block_list !== null) {
    $tacs_block_listArray = explode(',', $tacs_block_list);
    $tacConditions = [];

    foreach ($tacs_block_listArray as $range) {
        if (strpos($range, '-') !== false) {
            $bounds = explode('-', $range);
            if (count($bounds) == 2) {
            $start = (int)$bounds[0];
            $end   = (int)$bounds[1];
            $tacConditions[] = "tac NOT BETWEEN $start AND $end";
            }
        } else {
            $tacConditions[] = "tac != $range";
        }
        }
        $bounds = explode('-', $range);
    if (!empty($tacConditions)) {
        $whereFilters .= "AND (" . implode(' AND ', $tacConditions) . ") ";
    }
}

// Filter 7: By eNB Range
// eNB allowlist ranges
if ($enb_allow_list !== null) {
    $enbAllowArray = explode(',', $enb_allow_list);
    $enbConditions = [];

    foreach ($enbAllowArray as $range) {
        if (strpos($range, '-') !== false) {
            $bounds = explode('-', $range);
            if (count($bounds) == 2) {
            $start = (int)$bounds[0];
            $end   = (int)$bounds[1];
            $enbConditions[] = "enb BETWEEN $start AND $end";
            }
        } else {
            $enbConditions[] = "enb = $range";
        }
        }
        $bounds = explode('-', $range);
    if (!empty($enbConditions)) {
        $whereFilters .= "AND (" . implode(' OR ', $enbConditions) . ") ";
    }
}

// eNB blocklist ranges
if ($enb_block_list !== null) {
    $enbBlockArray = explode(',', $enb_block_list);
    $enbConditions = [];

    foreach ($enbBlockArray as $range) {
        if (strpos($range, '-') !== false) {
            $bounds = explode('-', $range);
            if (count($bounds) == 2) {
            $start = (int)$bounds[0];
            $end   = (int)$bounds[1];
            $enbConditions[] = "enb NOT BETWEEN $start AND $end";
            }
        } else {
            $enbConditions[] = "enb != $range";
        }
        }
        $bounds = explode('-', $range);
    if (!empty($enbConditions)) {
        $whereFilters .= "AND (" . implode(' AND ', $enbConditions) . ") ";
    }
}


// Filter 8: Cell filtering (space-separated string column)
if ($cells_allow_list !== null && ($view_mode == "enbs" || $view_mode == "cm")) {
    $list = str_replace(' ', ',', $cells_allow_list ?? ''); 
    
    if (!empty($list)) {
        $whereFilters .= "AND (" . implode(' OR ', array_map(fn($c) => "FIND_IN_SET('$c', REPLACE(cells, ' ', ','))", explode(',', $list))) . ") ";
    }
} elseif ($cells_allow_list !== null) {
    $cells = array_map('intval', explode(',', $cells_allow_list));
    if (!empty($cells)) {
        $conditions = array_map(fn($cell) => "cell = $cell", $cells);
        $whereFilters .= 'AND (' . implode(' OR ', $conditions) . ') ';
    }
}

// Blocklist (must match none)
if ($cells_block_list !== null && ($view_mode == "enbs" ||  $view_mode == "cm")) {
    $list = str_replace(' ', ',', $cells_block_list ?? '');
    
    if (!empty($list)) {
        $whereFilters .= "AND NOT (" . implode(' OR ', array_map(fn($c) => "FIND_IN_SET('$c', REPLACE(cells, ' ', ','))", explode(',', $list))) . ") ";
    }
} elseif ($cells_block_list !== null) {
    $cells = array_map('intval', explode(',', $cells_block_list));
    if (!empty($cells)) {
        $conditions = array_map(fn($cell) => "cell = $cell", $cells);
        $whereFilters .= ' AND NOT (' . implode(' OR ', $conditions) . ') ';
    }
}

// Filter 9: Cell quantity
if ($cells_quantity !== null) {
    $expr = "(LENGTH(TRIM(cells)) - LENGTH(REPLACE(TRIM(cells), ' ', '')) + 1)";
    $val = (int)$cells_quantity;

    if ($cells_quantity[0] === '>') {
        $val = (int)substr($cells_quantity, 1);
        $whereFilters .= " AND ($expr) > $val ";

    } elseif ($cells_quantity[0] === '<') {
        $val = (int)substr($cells_quantity, 1);
        $whereFilters .= " AND ($expr) < $val ";

    } else {
        $whereFilters .= " AND ($expr) = $val ";
    }
}

// Filter 10: Score
if ($score !== null) {
    $score = trim($score);

    if (preg_match('/^(\d+)\s*-\s*(\d+)$/', $score, $m)) {
        $min = (int)$m[1];
        $max = (int)$m[2];

        $whereFilters .= "AND score BETWEEN $min AND $max ";
    } else {
        if (substr($score, 0, 1) !== '<' &&
            substr($score, 0, 1) !== '>') {
            $score = "= $score";
        }

        $whereFilters .= "AND (score $score OR score IS NULL)";
    }
}


// Filter 11: CM Filters
if ($view_mode == "cm") {
    $cmIncludes = isset($_GET['cm_includes']) ? explode(',', $_GET['cm_includes']) : [];
    $cmexcludes = isset($_GET['cm_excludes']) ? explode(',', $_GET['cm_excludes']) : [];

    $validTowerTypes = ['DAS', 'PICO', 'MACRO'];

    // Includes: Chained with AND (must match all selected types)
    $activeIncludes = array_intersect($cmIncludes, $validTowerTypes);
    if (!empty($activeIncludes)) {
        $includeClauses = [];
        foreach ($activeIncludes as $type) {
            $includeClauses[] = "cm_tower_type = '$type'";
        }
        $whereFilters .= " AND (" . implode(" OR ", $includeClauses) . ")";
    }

    // excludes: Must not match these types (preserving the legacy style allowing NULLs)
    $activeexcludes = array_intersect($cmexcludes, $validTowerTypes);
    if (!empty($activeexcludes)) {
        $excludeClauses = [];
        foreach ($activeexcludes as $type) {
            $excludeClauses[] = "(cm_tower_type != '$type' OR cm_tower_type IS NULL)";
        }
        $whereFilters .= " AND " . implode(" AND ", $excludeClauses);
    }

    // Handle PERFECTSURRO overrides over CMGMPINNED and PINNED conflicts
    if (in_array('PERFECTSURRO', $cmIncludes)) {
        $cmIncludes = array_diff($cmIncludes, ['CMGMPINNED', 'PINNED']);
    }
    if (in_array('PERFECTSURRO', $cmexcludes)) {
        $cmexcludes = array_diff($cmexcludes, ['CMGMPINNED', 'PINNED']);
    }

    // Location Type Definitions & Mapping
    $locationTypesMap = [
        'PERFECTSURRO' => ['31', '41', '1'],
        'CMGMPINNED'   => ['40', '41', '42'],
        'CMPINNED'       => ['30', '31', '32']
    ];

    // Location Type Includes
    $includeLocTypes = [];
    foreach ($locationTypesMap as $key => $types) {
        if (in_array($key, $cmIncludes)) {
            $includeLocTypes = array_merge($includeLocTypes, $types);
        }
    }
    $includeLocTypes = array_unique($includeLocTypes);
    if (!empty($includeLocTypes)) {
        $escapedTypes = implode("', '", $includeLocTypes);
        $whereFilters .= " AND location_type IN ('$escapedTypes')";
    }

    // Location Type Excludes
    $excludeLocTypes = [];
    foreach ($locationTypesMap as $key => $types) {
        if (in_array($key, $cmexcludes)) {
            $excludeLocTypes = array_merge($excludeLocTypes, $types);
        }
    }
    $excludeLocTypes = array_unique($excludeLocTypes);
    if (!empty($excludeLocTypes)) {
        $escapedTypes = implode("', '", $excludeLocTypes);
        $whereFilters .= " AND location_type NOT IN ('$escapedTypes')";
    }

    // Mapped Status Filters
    if (in_array('MAPPED', $cmIncludes)) {
        $whereFilters .= " AND cm_status <> 'UNMAPPED'";
    }
    if (in_array('MAPPED', $cmexcludes)) {
        $whereFilters .= " AND cm_status = 'UNMAPPED'";
    }
}

// Filter 97: Set limit variables
if ($limit !== null && $limit > 0) {
    $limitClause = "LIMIT $limit";
    $limitClauseQuadruple = "LIMIT " . $limit * 4;
}
// Filter 98: Build the query
if ($view_mode == "cells") {
    if (!isset($centerLon)) $centerLon = $longitude;
    if (!isset($centerLat)) $centerLat = $latitude;

    $prefixedKeys = implode(', ', array_map(fn($k) => "main." . trim($k), explode(',', $keys)));

    $mainWhereFilters = str_replace(
        ['plmn', 'enb', 'cell', 'RAT', 'tac'], 
        ['main.plmn', 'main.enb', 'main.cell', 'main.RAT', 'main.tac'], 
        $whereFilters
    );
    // This query is two-parter, part 1 grabs all the cells in area A, part 2 grabs all the cells for the matching eNBs that may be just outside the view box.
    $sql_query = "
    WITH selected_enbs AS (
        SELECT plmn, enb, AVG(latitude) AS latitude, AVG(longitude) AS longitude
        FROM $tableName FORCE INDEX (idx_coords)
        WHERE $whereFilters$whereFiltersLocation 
        GROUP by plmn, enb
        ORDER BY ST_Distance_Sphere(ST_SRID(POINT(AVG(longitude), AVG(latitude)), 4326), ST_SRID(POINT($centerLon, $centerLat), 4326)) ASC
        $limitClause
    )
    SELECT $prefixedKeys $locationFilter 
    FROM $tableName main
    JOIN selected_enbs se ON main.enb = se.enb AND main.plmn = se.plmn
    WHERE $mainWhereFilters 
    AND main.latitude <> 0.0 AND main.longitude <> 0.0
    AND main.latitude BETWEEN (se.latitude - 1.5) AND (se.latitude + 1.5)
    AND main.longitude BETWEEN (se.longitude - 1.5) AND (se.longitude + 1.5)
    ";
} else {
    $sql_query = "SELECT $keys$locationFilter FROM $tableName WHERE $whereFilters$whereFiltersPolyLocation
                  ORDER BY ST_Distance_Sphere(poly_coords, ST_SRID(POINT($centerLon, $centerLat), 4326)) ASC ";
}

// Filter 99: Compare to CM / CMGM data.
if ($view_mode == "cm") {
    $sql_query = "
    SELECT plmn,rat,enb,tac,cells,oldest_date,newest_date,location_type,cm_tower_type,cm_status,latitude,longitude FROM $tableName
    WHERE $whereFilters $whereFiltersCmLocation
    ORDER BY ST_Distance_Sphere(cm_coords, ST_SRID(POINT($centerLon, $centerLat), 4326)) ASC 
    ";
}

// Filter 100: Set final limit
$sql_query .= ($view_mode == "cells" && $limit !== 0) ? $limitClauseQuadruple : $limitClause;

// The return
if ($showsql) {
    echo $sql_query;
    die();
};