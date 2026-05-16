<?php
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
$tableName = $viewMode == "enbs" ? 'local_poly_enbs' : 'local_poly_beta';
if ($viewMode == "cells") $whereFilters = 'AND plmn <> 312190 ';

$keys = $viewMode == "enbs" ? "plmn,rat,enb,tac,cells,is_exact_location,oldest_date,newest_date" : "enb,cell AS cells,cell_id,plmn,rat,tac,latitude,longitude,date_of_info";
if ($viewMode !== "cells") {
    $keys .= $locationType == 2 ? ",latitude_advanced AS latitude,longitude_advanced AS longitude" : ",latitude AS latitude,longitude AS longitude";
}

// Filter 1: Location (latitude & longitude)
if (!is_null($boundsNELat) && !is_null($boundsNELon) && !is_null($boundsSWLat) && !is_null($boundsSWLon)) {
    // Calculate Distances & Center Point
    $latDiff = abs($boundsNELat - $boundsSWLat);
    $lonDiff = abs($boundsNELon - $boundsSWLon);

    $centerLat = ($boundsNELat + $boundsSWLat) / 2;
    $centerLon = ($boundsNELon + $boundsSWLon) / 2;
    $centerPoint = "ST_GeomFromText('POINT($centerLat $centerLon)', 4326)";

    // Reduce bounding box size if conditions for reducing are met.
    if ($limit !== 0) {
        $baseCap = null;
        $maxDiff = max($latDiff, $lonDiff); // Find the dominant axis
        // Determine the base cap distance using the dominant axis
            if ($viewMode == "enbs") {
                if ($maxDiff > 15.0 && $limit > 50000) {
                    $baseCap = null;
                } elseif ($maxDiff > 15.0 && $limit > 7499) {
                    $baseCap = 7.5;
                } elseif ($maxDiff > 10.0 && $limit > 2999) {
                    $baseCap = 5.0;
                } elseif ($maxDiff > 7.0 && $limit > 450) {
                    $baseCap = 3.5;
                } elseif ($maxDiff > 4.50) {
                    $baseCap = 2.25;
                }
            } elseif ($viewMode == "cells") {
                if ($maxDiff > 10.0 && $limit > 50000) {
                    $baseCap = null;
                } elseif ($maxDiff > 10.0 && $limit > 7499) {
                    $baseCap = 5.00;
                } elseif ($maxDiff > 7.0 && $limit > 2999) {
                    $baseCap = 3.50;
                } elseif ($maxDiff > 4.5 && $limit > 450) {
                    $baseCap = 2.25;
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

        if (!is_null($plmn)) $baseCap *= 1.75;
        if (!is_null($rat)) $baseCap *= 1.10;

        $searchPolygon = buildCappedPolygon(
            $boundsNELat, $boundsNELon, 
            $boundsSWLat, $boundsSWLon, 
            $centerLat, $centerLon, 
            $capLatDistance, $capLonDistance
        );
    }
    $orderBy .= "ORDER BY ST_Distance_Sphere(coords, ST_SRID(POINT($centerLon, $centerLat), 4326)) ASC ";
    }

    // Bounding box not limited by previous if blocks, set bounding box to be equal to the user's bounding box.
    if (!isset($searchPolygon)) $searchPolygon = "POLYGON(($boundsSWLat $boundsSWLon, $boundsNELat $boundsSWLon, $boundsNELat $boundsNELon, $boundsSWLat $boundsNELon, $boundsSWLat $boundsSWLon))";

    $whereFiltersLocation .= "AND MBRWithin(coords, ST_GeomFromText('$searchPolygon', 4326)) ";
} elseif (!is_null($latitude) && !is_null($longitude)) {
    // OPTION B: Haversine Formula)
    $distanceExpr = "(3959 * 2 * ASIN(SQRT(
        POWER(SIN(RADIANS(latitude - $latitude) / 2), 2) +
        COS(RADIANS($latitude)) * COS(RADIANS(latitude)) *
        POWER(SIN(RADIANS(longitude - $longitude) / 2), 2)
    )))";

    $orderBy .= "ORDER BY ST_Distance_Sphere(coords, ST_SRID(POINT($longitude, $latitude), 4326)) ASC ";
}

// Filter 2: Date Filtering
$date_of_info = $oldest_date; // Rename oldest_date to date_of_info for Cells mode.
$dateKeys = $viewMode == "enbs" ? ['oldest_date', 'newest_date'] : ['date_of_info'];

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

// Filter 3: PLMN
if (!is_null($plmn)) {
    $include = $exclude = [];

    foreach (explode(',', $plmn) as $plmnValue) {
        $plmnValue = trim($plmnValue);
        if ($plmnValue === '') continue;

        $num = intval(preg_replace('/\D/', '', $plmnValue));
        if (!$num) continue;

        if (str_starts_with($plmnValue, '!')) {
            $exclude[] = $num;
        } else {
            $include[] = $num;
        }
    }

    if ($include) $whereFilters .= "AND plmn IN (" . implode(',', $include) . ") ";
    if ($exclude) $whereFilters .= "AND plmn NOT IN (" . implode(',', $exclude) . ") ";
}

// Filter 4: Within distance.
if (!is_null($locationFilter) && !is_null($radius)) {
    $whereFilters .= "AND $distanceExpr <= $radius ";
}

// Filter 5: Rat
if (!is_null($rat)) {
    $whereFilters .= "AND RAT = '$rat' ";
}

// Filter 6: Tac
if (!is_null($tacsAllowList)) {
    $tacsAllowListArray = explode(',', $tacsAllowList);
    $tacConditions = [];

    foreach ($tacsAllowListArray as $range) {
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
if (!is_null($tacsBlockList)) {
    $tacsBlockListArray = explode(',', $tacsBlockList);
    $tacConditions = [];

    foreach ($tacsBlockListArray as $range) {
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
if (!is_null($enbAllowList)) {
    $enbAllowArray = explode(',', $enbAllowList);
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
if (!is_null($enbBlockList)) {
    $enbBlockArray = explode(',', $enbBlockList);
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

// Allowlist (must match at least one)
// Allowlist (must match at least one)
// Allowlist (must match at least one)
if (!is_null($cellsAllowList) && $viewMode == "enbs") {
    // Adding ?? '' ensures str_replace never receives null
    $list = str_replace(' ', ',', $cellsAllowList ?? ''); 
    
    if (!empty($list)) {
        $whereFilters .= "AND (" . implode(' OR ', array_map(fn($c) => "FIND_IN_SET('$c', REPLACE(cells, ' ', ','))", explode(',', $list))) . ") ";
    }
} elseif (!is_null($cellsAllowList)) {
    $cells = array_map('intval', explode(',', $cellsAllowList));
    if (!empty($cells)) {
        $conditions = array_map(fn($cell) => "cell = $cell", $cells);
        $whereFilters .= 'AND (' . implode(' OR ', $conditions) . ') ';
    }
}

// Blocklist (must match none)
if (!is_null($cellsBlockList) && $viewMode == "enbs") {
    // Adding ?? '' ensures str_replace never receives null
    $list = str_replace(' ', ',', $cellsBlockList ?? '');
    
    if (!empty($list)) {
        $whereFilters .= "AND NOT (" . implode(' OR ', array_map(fn($c) => "FIND_IN_SET('$c', REPLACE(cells, ' ', ','))", explode(',', $list))) . ") ";
    }
} elseif (!is_null($cellsBlockList)) {
    $cells = array_map('intval', explode(',', $cellsBlockList));
    if (!empty($cells)) {
        $conditions = array_map(fn($cell) => "cell = $cell", $cells);
        $whereFilters .= ' AND NOT (' . implode(' OR ', $conditions) . ') ';
    }
}

// Filter 9: Perfect Surro Only)
if (!is_null($perfectSurroOnly)) {
    $whereFilters .= "AND is_exact_location = 1 ";
}

// Filter 10: Cell quantity
if (!is_null($cellQuantity)) {
    $expr = "(LENGTH(TRIM(cells)) - LENGTH(REPLACE(TRIM(cells), ' ', '')) + 1)";
    $val = (int)$cellQuantity;

    if ($cellQuantity[0] === '>') {
        $val = (int)substr($cellQuantity, 1);
        $whereFilters .= " AND ($expr) > $val ";

    } elseif ($cellQuantity[0] === '<') {
        $val = (int)substr($cellQuantity, 1);
        $whereFilters .= " AND ($expr) < $val ";

    } else {
        $whereFilters .= " AND ($expr) = $val ";
    }
}

// Filter 11: Score
if (!is_null($score)) {

    $score = trim($score);

    // RANGE: 1-30
    if (preg_match('/^(\d+)\s*-\s*(\d+)$/', $score, $m)) {
        $min = (int)$m[1];
        $max = (int)$m[2];

        $whereFilters .= "AND score BETWEEN $min AND $max ";

    } else {

        // DEFAULT: <, >, or =
        if (substr($score, 0, 1) !== '<' &&
            substr($score, 0, 1) !== '>') {
            $score = "= $score";
        }

        $whereFilters .= "AND score $score ";
    }
}

// Filter 98: Set limit
if (!is_null($limit) && $limit > 0) {
    $limitClause = "LIMIT $limit";
}

// Filter 99: Build it
$sql_query = "SELECT $keys$locationFilter FROM $tableName WHERE 1=1 $whereFiltersLocation$whereFilters$orderBy$limitClause";

if ($viewMode == "cells") {
    // 1. Prefix the keys to avoid the "ambiguous" error in SELECT
    $prefixedKeys = implode(', ', array_map(fn($k) => "main." . trim($k), explode(',', $keys)));

    // 2. Create a prefixed version of filters for the main query SELECT block
    // This adds 'main.' to the common columns to prevent the ambiguity error
    $mainWhereFilters = str_replace(
        ['plmn', 'enb', 'cell', 'RAT', 'tac', 'pci'], 
        ['main.plmn', 'main.enb', 'main.cell', 'main.RAT', 'main.tac', 'main.pci'], 
        $whereFilters
    );

    $roughLimit = 1.25; 
    $boundingBox = " AND latitude BETWEEN " . ($centerLat - $roughLimit) . " AND " . ($centerLat + $roughLimit) . " 
                     AND longitude BETWEEN " . ($centerLon - $roughLimit) . " AND " . ($centerLon + $roughLimit);
  
    // 4. Build the query
    $sql_query = "
    WITH selected_enbs AS (
        SELECT plmn, enb, coords, latitude, longitude
        FROM $tableName 
        WHERE 1=1 $whereFilters$whereFiltersLocation
        $orderBy
        $limitClause
    )
    SELECT $prefixedKeys $locationFilter 
    FROM $tableName main
    JOIN selected_enbs se ON main.enb = se.enb AND main.plmn = se.plmn
    WHERE main.latitude <> 0.0 AND main.longitude <> 0.0 
    $mainWhereFilters
    AND main.latitude BETWEEN (se.latitude - 1.5) AND (se.latitude + 1.5)
    AND main.longitude BETWEEN (se.longitude - 1.5) AND (se.longitude + 1.5)
    ";

    // 5. Performance: Rough Bounding Box

}


// The return
if ($showsql) {
    echo $sql_query;
    die();
};
?>