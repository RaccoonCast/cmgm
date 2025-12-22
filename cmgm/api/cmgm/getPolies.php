<?php
//  cody and alps' purple iphones (CAAPI)
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');

include '../../includes/functions/sqlpw.php'; // doesn't call native

//  cody and alps' purple iphones (CAAPI)
$db_vars = " AND provider_source IS NOT NULL";
$max_distance = "5";
$num = 0;
$db_vars_uno = "";
$db_vars_dos = "";
if (isset($_GET['plmn']) && is_numeric($_GET['plmn'])) $plmn = $_GET['plmn'];
if (isset($_GET['rat']) && strlen($_GET['rat']) <= 3) $rat = $_GET['rat'];

foreach($_GET as $key => $value) {
  if ($key === "latitude" || $key === "longitude" || $key === "limit" || $key === "properties" || $key === "showsql" || $key == "average") {
    ${$key} = $value;
  }

  elseif ($key === 'filterCells' && $value !== "false") {
    $cells = array_map('intval', explode(',', $value));
    if (!empty($cells)) {
        $conditions = array_map(function($cell) {
            return "cell = $cell";
        }, $cells);
        $db_vars = 'AND (' . implode(' OR ', $conditions) . ') ' . $db_vars;
    }
  }

  elseif ($key === 'filterEnbs' && preg_match('/^!?(?<start>\d+)-(?<end>\d+)$/', $value, $matches)) {
    $start = (int)$matches['start'];
    $end = (int)$matches['end'];

    if ($value[0] === "!") {
        $db_vars_uno = "AND enb NOT BETWEEN $start AND $end " . @$db_vars_uno;
        $db_vars_dos = "AND lp.enb NOT BETWEEN $start AND $end " . @$db_vars_dos;
    } else {
        $db_vars_uno = "AND enb BETWEEN $start AND $end " . @$db_vars_uno;
        $db_vars_dos = "AND lp.enb BETWEEN $start AND $end " . @$db_vars_dos;
    }
  }

  elseif ($key === 'filterTac' && preg_match('/^!?(?<tac>\d+)$/', $value, $matches)) {
    $tac = (int)$matches['tac'];

    $db_vars = " AND tac = $tac" . $db_vars;
  }

  elseif ($value != "false" && $value != "0") {
    $sanitizedKey = preg_replace('/[^a-zA-Z0-9_]/', '', $key);
    $sanitizedValue = preg_replace('/[^a-zA-Z0-9_]/', '', $value);
    $db_vars = " AND $sanitizedKey = '$sanitizedValue'" . $db_vars;
  }
}




if (empty($limit)) $limit = "150";
$sql = "
-- First: get the XX closest rows by distance
WITH base_results AS (
  SELECT *, 
    (3959 * ACOS(
      COS(RADIANS($latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($longitude)) +
      SIN(RADIANS($latitude)) * SIN(RADIANS(latitude))
    )) AS distance
  FROM local_poly_beta
  WHERE 1=1 $db_vars $db_vars_uno AND provider_source IS NOT NULL 
    AND latitude IS NOT NULL 
    AND longitude IS NOT NULL
  ORDER BY distance ASC
  LIMIT $limit
),
selected_enbs AS (
  SELECT DISTINCT enb FROM base_results
)

-- Finally: return all rows matching those enbs AND within xyz miles
SELECT lp.*,
  (3959 * ACOS(
    COS(RADIANS($latitude)) * COS(RADIANS(lp.latitude)) * COS(RADIANS(lp.longitude) - RADIANS($longitude)) +
    SIN(RADIANS($latitude)) * SIN(RADIANS(lp.latitude))
  )) AS distance
FROM local_poly_beta lp
JOIN selected_enbs se ON lp.enb = se.enb
WHERE 1=1 $db_vars $db_vars_dos
  AND (3959 * ACOS(
    COS(RADIANS($latitude)) * COS(RADIANS(lp.latitude)) * COS(RADIANS(lp.longitude) - RADIANS($longitude)) +
    SIN(RADIANS($latitude)) * SIN(RADIANS(lp.latitude))
  )) <= 300
ORDER BY lp.enb, lp.cell;
";
// $sql = "
// WITH
// params AS (
//     SELECT
//         $longitude AS my_lon,
//         $latitude AS my_lat,
//         0.2 AS buffer_size
// ),
// my_location AS (
//     SELECT ST_SRID(POINT(my_lon, my_lat), 4326) AS loc
//     FROM params
// ),
// search_envelope AS (
//     SELECT ST_SRID(
//         ST_MakeEnvelope(
//             POINT(my_lon - buffer_size, my_lat - buffer_size),
//             POINT(my_lon + buffer_size, my_lat + buffer_size)
//         ),
//         4326
//     ) AS env
//     FROM params
// ),
// ClosestENBs AS (
//      SELECT
//         t1.cell_id AS cell_id,
//         t1.enb AS enb,
//         t1.cell AS cell,
//         t1.tac AS tac,
//         t1.rat AS rat,
//         t1.provider_source AS provider_source,
//         t1.plmn AS plmn
//     FROM local_poly_beta t1, search_envelope se, my_location ml
//     WHERE 1=1 $db_vars $db_vars_uno AND ST_Intersects(t1.coords, se.env)
//     -- ORDER BY ST_DISTANCE_SPHERE(t1.coords, ml.loc) ASC
//     LIMIT 150
// )
// SELECT DISTINCT
//     t2.enb,
//     t2.cell,
//     t2.cell_id,
//     t2.plmn,
//     t2.latitude,
//     t2.longitude,
//     ST_DISTANCE_SPHERE(t2.coords, (SELECT loc FROM my_location)) AS distance_in_meters
// FROM local_poly_beta t2
// INNER JOIN ClosestENBs c ON t2.enb = c.enb
// ORDER BY t2.enb, t2.cell, distance_in_meters ASC;
// ";
if ($_GET['showsql'] == "true") {
  echo $sql;
  die();
}


$result = $conn->query($sql);

if ($result === FALSE) {
    // An error occurred during query execution
    echo "Error: " . $conn->error;
}


// print_r($result->fetch_assoc());
// die();

$sortTemp = [];

while ($row = $result->fetch_assoc()) {
    if (empty($row['latitude']) || empty($row['longitude'])) continue;
    if (($row['enb'] > 950000 && $row['enb'] < 959999 && $row['plmn'] == 310410)) continue;
    if (($row['enb'] > 990000 && $row['enb'] < 999999 && $row['plmn'] == 310410)) continue;

    $plmn = (int)$row['plmn'];
    $enb = (int)$row['enb'];
    $cell = (int)$row['cell'];
    $lat = (float)$row['latitude'];
    $lon = (float)$row['longitude'];

    if ($lat == 0.0 || $lon == 0.0) {
      $lat = null;
      $lon = null;
    }

    $sortKey = "{$plmn}.{$enb}";

    if (!isset($sortTemp[$sortKey])) {
        $sortTemp[$sortKey] = [
            'plmn' => $plmn,
            'enb' => $enb,
            'tac' => (int)$row['tac'],
            'cells' => [],
            'latitudes' => [],
            'longitudes' => []
        ];
    }

    $sortTemp[$sortKey]['cells'][] = $cell;
    $sortTemp[$sortKey]['latitudes'][] = $lat;
    $sortTemp[$sortKey]['longitudes'][] = $lon;
}

ksort($sortTemp);

$structured = [];
$useAverage = isset($_GET['average']) && $_GET['average'] === 'true';

foreach ($sortTemp as $data) {
    sort($data['cells']);
    $structuredItem = [
        'plmn' => $data['plmn'],
        'enb' => $data['enb'],
        'tac' => $data['tac'],
    ];

    if ($useAverage) {
        $structuredItem['cells'] = $data['cells'];
        $structuredItem['latitude'] = array_sum($data['latitudes']) / count($data['latitudes']);
        $structuredItem['longitude'] = array_sum($data['longitudes']) / count($data['longitudes']);
    } else {
        $structuredItem['cells'] = array_combine($data['cells'], array_map(null, $data['latitudes'], $data['longitudes']));
    }

    $structured[$data['enb']] = $structuredItem;
}

header('Content-Type: application/json');
echo json_encode($structured, JSON_PRETTY_PRINT);

$result->close();
$conn->close();
