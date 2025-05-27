<?php
function error($error) { 
	echo json_encode([
	'error' => $error
	]);
	die();
}
function haversineGreatCircleDistance($lat1, $lon1, $lat2, $lon2) {
	$earthRadius = 3958.8; // Earth radius in miles

	$latFrom = deg2rad($lat1);
	$lonFrom = deg2rad($lon1);
	$latTo = deg2rad($lat2);
	$lonTo = deg2rad($lon2);

	$latDelta = $latTo - $latFrom;
	$lonDelta = $lonTo - $lonFrom;

	$a = sin($latDelta / 2) * sin($latDelta / 2) +
		 cos($latFrom) * cos($latTo) *
		 sin($lonDelta / 2) * sin($lonDelta / 2);

	$c = 2 * atan2(sqrt($a), sqrt(1 - $a));

	return $earthRadius * $c;
}

function getZoom($latLngPairs) {
	$maxDist = 0;

	// Calculate the maximum distance between any two points
	$count = count($latLngPairs);

	for ($i = 0; $i < $count - 1; $i++) {
	// Extract latitude and longitude for the current point
	list($lat1, $lon1) = explode(',', $latLngPairs[$i]);
	for ($j = $i + 1; $j < $count; $j++) {
		// Extract latitude and longitude for the comparison point
		list($lat2, $lon2) = explode(',', $latLngPairs[$j]);
		
		// Calculate the distance using the Haversine formula
		$distance = haversineGreatCircleDistance($lat1, $lon1, $lat2, $lon2);
		
		// Update the maximum distance if the current one is greater
		if ($distance > $maxDist) {
			$maxDist = $distance;
		}
	}
}
	
	// Determine the return value based on maxDist
	if ($maxDist > 7) return 12.75;
	if ($maxDist > 6) return 13.25;
	if ($maxDist > 4) return 13.75;
	if ($maxDist > 3) return 14.25;
	if ($maxDist > 2.5) return 14.75;
	if ($maxDist > 1.5) return 15.25;
	return $maxDist > 1 ? 15.50 : 15.50;

}

function get($type, $latLngPairs) {
	if ($type !== 'lat' && $type !== 'lon') {
		throw new Exception("Type must be 'latitude' or 'longitude'.");
	}

	$sum = 0.0;
	foreach ($latLngPairs as $pair) {
		// Split the lat,lng string into two parts
		[$lat, $lng] = explode(',', $pair);
		$value = ($type === 'lat') ? (float)$lat : (float)$lng;
		$sum += $value;
	}

	// Calculate the average
	return $sum / count($latLngPairs);
}

/**
 * No longer used - replaced by getMultipleFromDb
 * Left here for legacy reasons, can be removed at a later date
 */
function getFromDb($conn, $cellId, $plmn) {
    // Query to retrieve the data
    $query = "SELECT cell_id, latitude, longitude, accuracyMiles, date_of_info
              FROM local_poly
              WHERE cell_id = $cellId AND plmn = $plmn";

    // Execute the query
    $result = $conn->query($query);
	
    if ($result && $result->num_rows > 0) {
        // Fetch and return the data as an associative array
        return $result->fetch_assoc();
    } else {
        return null; // No data found
    }
}

function getMultipleFromDb($conn, $cellIdList, $plmn) {

	$cellIdListStr = implode(', ', $cellIdList);

	// Query to retrieve the data
	$query = "SELECT cell_id, latitude, longitude, accuracyMiles, date_of_info, provider_source
              FROM cmgm.local_poly
              WHERE cell_id IN ($cellIdListStr)
              AND plmn = $plmn";

	// Execute the query
	$result = $conn->query($query);

	if ($result && $result->num_rows > 0) {
			// Fetch and return the data as an associative array
			return $result->fetch_all(MYSQLI_ASSOC);
	} else {
			return null; // No data found
	}
}

function get_cell($cellNumber, $eNB, $plmn, $rat) {
    if ($rat == 'NR') {
        // For NR (New Radio) set the base calculation
        $base = match ($plmn) {
            "310260" => $eNB * 4096,
            "310410" => $eNB * 1024,
            "311480" => $eNB * 16384,
            default => $eNB * 4096,
        };
    } elseif ($rat == 'LTE') {
        // For LTE set the base calculation
        $base = $eNB * 256;
    }

    // Return the cellId by adding the cellNumber to the base
    return $base + (int)$cellNumber;
}

/**
 * Generate a cURL handle for Apple Surro API
 * @param mixed $carrier
 * @param mixed $cellId
 * @param mixed $tac
 * @return CurlHandle
 */
function genAppleHandle($carrier, $cellId, $tac): CurlHandle {
	
	// Generate payload for Apple
	$requestPayload = [
		"cellTowers" => [
			[
				"locationAreaCode" => $tac,
				"cellId" => $cellId,
				"mobileCountryCode" => substr($carrier, 0, 3),
				"mobileNetworkCode" => substr($carrier, 3, 3),
			]
		],
	];
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://localhost:1234/geolocate');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestPayload));
	curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

	return $ch;
}

/**
 * Generate cURL handle for Google Geolocation API
 * @param mixed $carrier six-digit PLMN
 * @param mixed $cellId 
 * @param mixed $rat
 * @param mixed $cellIdLabel Cell ID unit, dependent on RAT
 * @param mixed $signalLabel Signal strength unit, dependent on RAT
 * @param mixed $maps_api_key Google Maps API key
 * @return CurlHandle
 */
function genGoogleHandle($carrier, $cellId, $rat, $cellIdLabel, $signalLabel, $maps_api_key): CurlHandle {
	// Build curl handle for google
	$mobileCountryCode = substr($carrier, 0, 3);
	$mobileNetworkCode = substr($carrier, 3);

	$requestPayload = [
			"cellTowers" => [
					[
							$cellIdLabel => $cellId,
							"mobileCountryCode" => $mobileCountryCode,
							"mobileNetworkCode" => $mobileNetworkCode,
							"age" => 1,
							$signalLabel => -40,
					]
			],
			"considerIp" => false,
			"homeMobileCountryCode" => $mobileCountryCode,
			"homeMobileNetworkCode" => $mobileNetworkCode,
			"radioType" => $rat,
	];

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/geolocation/v1/geolocate?key=' . $maps_api_key);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestPayload));
	curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

	return $ch;
}

?>