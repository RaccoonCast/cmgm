<?php
include __DIR__ . '/../includes/functions/sqlpw.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate');

$default_latitude = 41.87846175857693;
$default_longitude = -87.62886586726619;
$mobileRequest = isset($_GET['mobile']) || preg_match(
  '/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i',
  $_SERVER['HTTP_USER_AGENT'] ?? ''
);

function json_error(string $message, int $status = 400): never
{
  http_response_code($status);
  echo json_encode(['error' => $message]);
  exit;
}

function numeric_parameter(string $name, ?float $fallback = null): ?float
{
  if (!isset($_GET[$name]) || $_GET[$name] === '') return $fallback;
  if (!is_numeric($_GET[$name])) json_error("Invalid $name");
  return (float) $_GET[$name];
}

function normalize_longitude(float $longitude): float
{
  $longitude = fmod($longitude, 360.0);
  if ($longitude < -180.0) $longitude += 360.0;
  if ($longitude > 180.0) $longitude -= 360.0;
  return $longitude;
}

function spatial_bounds_filter(float $south, float $west, float $north, float $east): string
{
  return "MBRWithin(coords, ST_GeomFromText('POLYGON(("
    . "$south $west, $north $west, $north $east, $south $east, $south $west))', 4326))";
}

function capped_viewport_bounds(
  float $south,
  float $westRaw,
  float $north,
  float $eastRaw,
  int $limit
): array {
  // Work in an unwrapped longitude interval so viewports crossing the
  // antimeridian can be capped before being split into valid SQL polygons.
  while ($eastRaw < $westRaw) $eastRaw += 360.0;

  $latitudeSpan = max(0.0, $north - $south);
  $longitudeSpan = max(0.0, $eastRaw - $westRaw);
  $maxSpan = max($latitudeSpan, $longitudeSpan);
  $halfSpanCap = null;

  // Keep these thresholds aligned with api/poly/filterPoly.php. Large result
  // limits are allowed a larger search area; ordinary map batches cap broad
  // viewports to a small, centered spatial-index search.
  if ($limit > 50000) {
    $halfSpanCap = null;
  } elseif ($limit > 7499) {
    if ($maxSpan > 15.0) $halfSpanCap = 7.5;
  } elseif ($limit > 2999) {
    if ($maxSpan > 10.0) $halfSpanCap = 5.0;
  } elseif ($limit > 450) {
    if ($maxSpan > 7.0) $halfSpanCap = 3.5;
  } elseif ($maxSpan > 4.5) {
    $halfSpanCap = 2.25;
  }

  if ($halfSpanCap === null || $maxSpan <= 0.0) {
    return [$south, $westRaw, $north, $eastRaw, false];
  }

  $centerLat = ($south + $north) / 2.0;
  $centerLon = ($westRaw + $eastRaw) / 2.0;
  $latitudeRatio = $latitudeSpan / $maxSpan;
  $longitudeRatio = $longitudeSpan / $maxSpan;
  $latitudeHalfSpan = $halfSpanCap * $latitudeRatio;
  $longitudeHalfSpan = $halfSpanCap * $longitudeRatio;

  return [
    max($south, $centerLat - $latitudeHalfSpan),
    max($westRaw, $centerLon - $longitudeHalfSpan),
    min($north, $centerLat + $latitudeHalfSpan),
    min($eastRaw, $centerLon + $longitudeHalfSpan),
    true
  ];
}

function spatial_bounds_clause(float $south, float $westRaw, float $north, float $eastRaw): string
{
  while ($eastRaw < $westRaw) $eastRaw += 360.0;
  if (($eastRaw - $westRaw) >= 360.0) {
    return spatial_bounds_filter($south, -180.0, $north, 180.0);
  }

  $west = normalize_longitude($westRaw);
  $east = normalize_longitude($eastRaw);
  if ($west <= $east) return spatial_bounds_filter($south, $west, $north, $east);

  return '('
    . spatial_bounds_filter($south, $west, $north, 180.0)
    . ' OR '
    . spatial_bounds_filter($south, -180.0, $north, $east)
    . ')';
}

$mode = ($_GET['mode'] ?? 'viewport') === 'initial' ? 'initial' : 'viewport';
$requestedLimit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int) $_GET['limit'] : null;
$limit = max(1, min(25000, $requestedLimit ?: ($mobileRequest ? 300 : 550)));
$centerLat = max(-90.0, min(90.0, numeric_parameter('centerLat', (float) $default_latitude)));
$centerLon = normalize_longitude(numeric_parameter('centerLon', (float) $default_longitude));
$pinStyle = strtolower(trim((string) ($_GET['pin_style'] ?? 'carrier')));
if (!in_array($pinStyle, ['carrier', 'concealed', 'status'], true)) $pinStyle = 'status';

// These parameters control the map itself and must never become database filters.
$mapParameters = [
  'mode', 'limit', 'batch_size', 'mobile', 'latitude', 'longitude', 'zoom', 'centerLat', 'centerLon',
  'boundsNELat', 'boundsNELon', 'boundsSWLat', 'boundsSWLon',
  'useCoordCenter', 'polygon', 'polygonlabels', 'skipPolyZoom', 'hideui',
  'marker_latitude', 'marker_longitude', 'showPolyLink', 'hidePolyForm',
  'pin_style', 'pin_size', 'title', 'back', 'percents_view', 'next',
  'cachebuster', 'showsql', 'refresh', 'q'
];

$north = $eastRaw = $south = $westRaw = null;
if ($mode === 'viewport') {
  $north = numeric_parameter('boundsNELat');
  $eastRaw = numeric_parameter('boundsNELon');
  $south = numeric_parameter('boundsSWLat');
  $westRaw = numeric_parameter('boundsSWLon');
}
foreach ($mapParameters as $parameter) unset($_GET[$parameter]);

include __DIR__ . '/includes/DB-filter-get.php';
$filters = !empty($db_vars) ? ' ' . $db_vars : '';
$boundsFilter = '';
$boundsWereCapped = false;

if ($mode === 'viewport') {
  if ($north === null || $eastRaw === null || $south === null || $westRaw === null) {
    json_error('Viewport bounds are required');
  }

  $north = max(-90.0, min(90.0, $north));
  $south = max(-90.0, min(90.0, $south));
  if ($south > $north) [$south, $north] = [$north, $south];

  if ($limit > 1000) {
    [$searchSouth, $searchWest, $searchNorth, $searchEast] = [$south, $westRaw, $north, $eastRaw];
  } else {
    [$searchSouth, $searchWest, $searchNorth, $searchEast, $boundsWereCapped] = capped_viewport_bounds(
      $south,
      $westRaw,
      $north,
      $eastRaw,
      $limit
    );
  }
  $boundsFilter = ' AND ' . spatial_bounds_clause($searchSouth, $searchWest, $searchNorth, $searchEast);
}

$distance = '(3959 * ACOS(LEAST(1, GREATEST(-1, COS(RADIANS(' . $centerLat . '))'
  . ' * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS(' . $centerLon . '))'
  . ' + SIN(RADIANS(' . $centerLat . ')) * SIN(RADIANS(latitude))))))';
$pinColumns = ['id', 'latitude', 'longitude'];
if ($pinStyle === 'carrier') {
  array_push($pinColumns, 'carrier', 'tags');
} elseif ($pinStyle === 'concealed') {
  array_push($pinColumns, 'concealed', 'tags');
} else {
  $pinColumns[] = 'status';
}
$sql = 'SELECT ' . implode(', ', $pinColumns) . ', ' . $distance . ' AS distance'
  . ' FROM db WHERE 1=1' . $filters . $boundsFilter . ' ORDER BY distance LIMIT ' . $limit;
$queryStarted = microtime(true);
$result = mysqli_query($conn, $sql);
if ($result === false) json_error('Unable to load pins', 500);
header('Server-Timing: db;dur=' . round((microtime(true) - $queryStarted) * 1000, 1));

$records = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);
foreach ($records as &$record) unset($record['distance']);
unset($record);

echo json_encode([
  'records' => $records,
  'count' => count($records),
  'limit' => $limit,
  'capped' => $boundsWereCapped
], JSON_UNESCAPED_SLASHES);
