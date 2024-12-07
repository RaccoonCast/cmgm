<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$silent = true;
if (!isset($polyFormData)) include "../functions.php";
include "functions.php";

$formData = [];

// Group incoming POST data dynamically based on indices
$dataSource = isset($polyFormData) ? $polyFormData : $_POST;

foreach ($dataSource as $key => $value) {
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
$json_formData = json_encode($formData);

$multiCurl = curl_multi_init();
$curlHandles = [];
$responses = [];

// Process each group in $formData
foreach ($formData as $index => $data) {
    $rat = in_array($data['rat'], ['LTE', 'NR']) ? $data['rat'] : error("Invalid RAT.");
    $eNB = preg_match('/^\d{1,10}$/', $data['eNB']) ? $data['eNB'] : error("Invalid eNB/gNB.");
    $cellList = preg_match('/^\d+(,\d+)*$/', $data['cellList']) ? explode(',', $data['cellList']) : error("Invalid cellList.");
    $plmn = preg_match('/^\d{6}$/', $data['plmn']) ? $data['plmn'] : error("Invalid PLMN.");

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

    // Get from DB
    $cellIdList = array_map(fn($cellNumber) => $base + (int)$cellNumber, $cellList); // Get list of ids
    $arrayResults = getMultipleFromDb($conn, $cellIdList, $plmn); // Query DB for all simultaneously

    
    // Reformat associative array to be keyed by cell ID
    $dbDump_dataMap = [];
    if ($arrayResults != null) {
        foreach ($arrayResults as $_unused_index => $arrayRow) {
            $dbDump_dataMap[$arrayRow['cell_id']] = $arrayRow;
        }
    }

    // Loop through each cell
    foreach ($cellList as $cellNumber) {
		
        $cellId = $base + (int)$cellNumber;

        // Check if data was returned by db
		if (array_key_exists($cellId, $dbDump_dataMap)) { 

            $tmpDb = $dbDump_dataMap[$cellId];
		
            // Assuming $tmpDb['date_of_info'] contains a datetime string from the database
            if (is_null($tmpDb['latitude']) || is_null($tmpDb['longitude'])) {
                // Parse the date_of_info into a DateTime object
                $dateOfInfo = new DateTime($tmpDb['date_of_info']);
                $currentDate = new DateTime(); // Current date and time
                
                // Subtract 90 days from the current date
                $currentDate->modify('-90 days');
                
                // Check if date_of_info is newer than 90 days
                if ($dateOfInfo > $currentDate) {
                    if (!isset($_GET['forceNewResults'])) {
                    continue; // It's newer than 90 days, skip
                    }
                }
            } else {
                $responses[$eNB][$cellNumber] = [
                    'cellId' => $tmpDb['cell_id'], // Calculate the correct cellId
                    'lat' => $tmpDb['latitude'],          // Using the latitude from the DB result
                    'lng' => $tmpDb['longitude'],         // Using the longitude from the DB result
                    'accuracyMiles' => $tmpDb['accuracyMiles'], // Convert accuracy from meters to miles
                ];
                continue;
            }
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
	$cell_identifier = get_cell($cellNumber, $eNB, $plmn, $rat);
    $eNB = $formData[$index]['eNB']; // Retrieve the eNB for the current index

    if (isset($data['location'])) {
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
    } else {
		$sqlInsert = "INSERT INTO local_poly (plmn, cell, cell_id, enb, rat, latitude, longitude, accuracyMiles) 
		VALUES ('$plmn', '$cellNumber', '$cell_identifier', '$eNB', '$rat', NULL, NULL, NULL) 
		ON DUPLICATE KEY UPDATE latitude=VALUES(latitude), longitude=VALUES(longitude), accuracyMiles=VALUES(accuracyMiles)";
		
		$conn->query($sqlInsert);
	}

    curl_multi_remove_handle($multiCurl, $ch);
    curl_close($ch);
}

if (empty($responses)) {
    // Continue with generating the map only when requests are sent via auto-submission to allow user to correct.
	if (!isset($polyFormData)) {
        error("Nothing could be found");
    } else {
        return;
    }
	}

curl_multi_close($multiCurl);

$json_response = json_encode($responses);