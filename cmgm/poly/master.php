<?php

// Handle PLMN-specific default cells
$defaultNumbers = match ($plmn) {
	'310410' => [15, 16, 17], // AT&T B12 Default
	'311480', '310260' => [1, 2, 3], // VZW B13 Default + T-Mo B66 default
	default => error("Please provide a cell list for " . $plmn)
};

// Convert comma separated cell list into array.
$numbers = empty($numbersArg)
	? $defaultNumbers
	: array_map('intval', explode(',', $numbersArg));

// Initiliaze some preset variables.
$base = $eNB * 256;
[$mobileCountryCode, $mobileNetworkCode] = [substr($plmn, 0, 3), substr($plmn, 3, 3)];
$apiUrl = "https://www.googleapis.com/geolocation/v1/geolocate?key=" . $maps_api_key;
$latLngPairs = array();
$foundCells = array();
$missingCells = array();

foreach ($numbers as $number) {
	$cellId = $base + $number;

	// Prepare the request payload for Google Geolocation API
	$requestPayload = [
		"cellTowers" => [[
			"cellId" => $cellId,
			"mobileCountryCode" => $mobileCountryCode,
			"mobileNetworkCode" => $mobileNetworkCode,
			"age" => 1,
			"signal" => -40,
			"timingAdvance" => 15,
		]],
		"considerIp" => false,
		"homeMobileCountryCode" => $mobileCountryCode,
		"homeMobileNetworkCode" => $mobileNetworkCode,
		"radioType" => "LTE",
	];

	// Make a POST request to the API
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $apiUrl);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestPayload));
	$response = curl_exec($ch);

	if (curl_errno($ch)) {
		$error = curl_error($ch);
		curl_close($ch);
		msg("CRITICAL_ERROR", $error);
		continue;
	}

	curl_close($ch);

	// Parse the API response
	$responseData = json_decode($response, true);

	if (isset($responseData['location']) && isset($responseData['accuracy'])) {
		$lat = $responseData['location']['lat'];
		$lng = $responseData['location']['lng'];
		$accuracyMeters = $responseData['accuracy'];
		$accuracyMiles = $accuracyMeters / 1609;

		$latLng = "$lat,$lng";
		$latLngPairs[] = "$lat,$lng";
		
		$foundCells[] = $number;
		
		$values[] = array(
        'cellNumber' => $number,
        'accuracyMiles' => $accuracyMiles,
        'latLng' => $latLng
		);
		
	} else {
		$missingCells[] = $number;
	}
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
	return $maxDist > 2 ? 14.75 : 14.75;

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

if (count($latLngPairs) >= 3) {
	
	$link = "https://cmgm.us/database/Map.php?latitude=" . get("lat",$latLngPairs) . "&longitude=" . get("lon",$latLngPairs) . "&zoom=" . getZoom($latLngPairs) . "&polygon=";
	foreach ($latLngPairs as $latLngPair) {
		$link .= $latLngPair . ',';
	}
	$link = rtrim($link, ',') . "&polygonlabels=" . $numbersArg;

}
