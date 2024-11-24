<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$silent = true;
include "../functions.php";
include "functions.php";

$formData = [];

// Group incoming POST data dynamically based on indices
foreach ($_POST as $key => $value) {
    if (preg_match('/^(eNB|cellList|plmn|rat)(_(\d+))?$/', $key, $matches)) {
        $field = $matches[1]; // eNB, cellList, plmn, or rat
        $index = $matches[3] ?? 0; // Use 0 if no index is specified

        // Ensure the array for the specific index exists
        if (!isset($formData[$index])) {
            $formData[$index] = [];
        }

        // Store the value in the corresponding field for that index
        $formData[$index][$field] = $value;
    }
}

$multiCurl = curl_multi_init();
$curlHandles = [];
$responses = [];

// Process each group in $formData
foreach ($formData as $index => $data) {
    $rat = $data['rat'];
    $eNB = $data['eNB'];
    $cellList = explode(',', $data['cellList']);
    $plmn = $data['plmn'];

    // Set base calculation for cellId
    $base = 0;
    if ($rat == 'NR') {
        $cellIdLabel = 'newRadioCellId';
        $signalLabel = 'signalStrength';
        $base = match ($plmn) {
            "310260" => $eNB * 4096,
            "310410" => $eNB * 1024,
            "311480" => $eNB * 16384,
            default => $eNB * 4096,
        };
    } elseif ($rat == 'LTE') {
        $cellIdLabel = 'cellId';
        $signalLabel = 'signal';
        $base = $eNB * 256;
    }
    foreach ($cellList as $cellNumber) {
		
        $cellId = $base + (int)$cellNumber;
		
		$tmpDb = getFromDb($conn, $cellId, $plmn);
		if (!empty($tmpDb)) { // Check if data was returned
			// Adjust your response structure
			$responses[$eNB][$cellNumber] = [
				'cellId' => $tmpDb['cell_id'], // Calculate the correct cellId
				'lat' => $tmpDb['latitude'],          // Using the latitude from the DB result
				'lng' => $tmpDb['longitude'],         // Using the longitude from the DB result
				'accuracyMiles' => $tmpDb['accuracyMiles'], // Convert accuracy from meters to miles
			];
			continue;
		}
		
        $mobileCountryCode = substr($plmn, 0, 3);
        $mobileNetworkCode = substr($plmn, 3);

        $requestPayload = [
            "cellTowers" => [[
                $cellIdLabel => $cellId,
                "mobileCountryCode" => $mobileCountryCode,
                "mobileNetworkCode" => $mobileNetworkCode,
                "age" => 1,
                $signalLabel => -40,
            ]],
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

        $curlHandles["$index-$cellNumber"] = $ch;
        curl_multi_add_handle($multiCurl, $ch);
    }
}

// Execute all requests
do {
    $status = curl_multi_exec($multiCurl, $active);
    curl_multi_select($multiCurl);
} while ($active && $status == CURLM_OK);

foreach ($curlHandles as $key => $ch) {
    $response = curl_multi_getcontent($ch);
    [$index, $cellNumber] = explode('-', $key);
    $data = json_decode($response, true);

    $eNB = $formData[$index]['eNB']; // Retrieve the eNB for the current index

    if (isset($data['location'])) {
		$cell_identifier = get_cell($cellNumber, $eNB, $plmn, $rat);
        $responses[$eNB][$cellNumber] = [
            'cellId' => $cell_identifier, // Ensure the correct cellId calculation
            'lat' => $data['location']['lat'],
            'lng' => $data['location']['lng'],
            'accuracyMiles' => $data['accuracy'] / 1609,
        ];
		$accuracyMiles = $data['accuracy'] / 1609;
		$lat = $data['location']['lat'];
		$lng = $data['location']['lng'];
		$cell_identifier = $cell_identifier;
		

	      // Insert or update database with the new data (batch insert approach)
        $sqlInsert = "INSERT INTO local_poly (plmn, cell, cell_id, enb, rat, latitude, longitude, accuracyMiles)
                      VALUES ('$plmn', '$cellNumber', '$cell_identifier', '$eNB', '$rat', '$lat', '$lng', '$accuracyMiles')
                      ON DUPLICATE KEY UPDATE latitude = '$lat', longitude = '$lng', accuracyMiles = '$accuracyMiles'";

        $conn->query($sqlInsert);
    }

    curl_multi_remove_handle($multiCurl, $ch);
    curl_close($ch);
}

if (empty($responses)) {
	error("Nothing could be found");
	}

curl_multi_close($multiCurl);

$json_response = json_encode($responses);