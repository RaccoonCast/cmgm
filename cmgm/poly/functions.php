<?php
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

?>