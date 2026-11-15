<?php
// api.php
include '../functions.php';

// 1. Set headers for JSON output and allow cross-origin requests (CORS) if needed
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// 2. Validate incoming coordinates
if (!isset($_GET['lat']) || !isset($_GET['lng'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing latitude (lat) or longitude (lng) parameters']);
    exit;
}

$lat = (float) $_GET['lat'];
$lng = (float) $_GET['lng'];
$rat = $_GET['rat'];
$radius = 'All';

try {
    // 3. Initialize the extension
    $client = new SurroClient();

    // 4. Query the coordinates
    $results = $client->getNearbyCells($lat, $lng, $rat, $radius);

    // 5. Extract the data
    // Because the PhpSurroundingTower class properties are public, 
    // json_encode will automatically serialize them perfectly.
    $surrounding_cells = $results->getSurrounding();
    $found_cells = $results->getFound();

    // 6. Return the combined JSON payload
    echo json_encode([
        'status' => 'success',
        'request' => [
            'lat' => $lat,
            'lng' => $lng,
            'rat' => $rat
        ],
        'data' => [
            'found' => $found_cells,
            'surrounding' => $surrounding_cells
        ]
    ]);

} catch (Exception $e) {
    // Catch any extension-level crashes or timeouts
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Backend failed to process the request.',
        'details' => $e->getMessage()
    ]);
}
?>