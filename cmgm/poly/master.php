<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$silent = true;
if (!isset($polyFormData))
    include "../functions.php";
include "functions.php";

$formData = [];

// Group incoming POST data dynamically based on indices
$dataSource = isset($polyFormData) ? $polyFormData : $_POST;

foreach ($dataSource as $key => $value) {
    if (preg_match('/^(eNB|cellList|cellListDepri|plmn|rat|tac)(_(\d+))?$/', $key, $matches)) {
        $field = $matches[1]; // eNB, cellList, plmn, or rat
        $index = $matches[3] ?? 0; // Use 0 if no index is specified

        logWarning('field:' . $field);

        // Ensure the array for the specific index exists
        if (!isset($formData[$index])) {
            $formData[$index] = [];
        }

        // Store the value in the corresponding field for that index
        $formData[$index][$field] = $value;
    }
}

$curlHandles_goog = [];
$curlHandles_appl = [];
$responses = [];

function logWarning($warnText) {
    error_log($warnText);
}

// Process each group in $formData
foreach ($formData as $index => $data) {
    $rat = in_array($data['rat'], ['LTE', 'NR']) ? $data['rat'] : error("Invalid RAT.");
    $eNB = preg_match('/^\d{1,10}$/', $data['eNB']) ? $data['eNB'] : error("Invalid eNB/gNB.");
    $cellList = isset($data['cellList']) && preg_match('/^\d+(,\d+)*$/', $data['cellList']) ? explode(',', $data['cellList']) : null;
    $cellList_depri = isset($data['cellListDepri']) && preg_match('/^\d+(,\d+)*$/', $data['cellListDepri']) ? explode(',', $data['cellListDepri']) : null;
    $plmn = preg_match('/^\d{6}$/', $data['plmn']) ? $data['plmn'] : error("Invalid PLMN.");
    $tac = isset($data['tac']) && preg_match('/^\d{1,10}$/', $data['tac']) ? $data['tac'] : null; // Don't error on invalid TAC, it will just use Google instead

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

    // Get deprioritized cells, if they exist
    $cellIdList_depri = [];
    if ($cellList_depri != null) {
        $cellList = array_merge($cellList ? $cellList : [], $cellList_depri);
        $cellIdList_depri = array_map(fn($cellNumber) => $base + (int) $cellNumber, $cellList_depri);
    }

    // Check if no cells (or depri cells) were sent - if so, error
    if (!isset($cellList) || count($cellList) == 0) {
        error('Invalid cellList');
    }
    

    // Get from DB
    $cellIdList = array_map(fn($cellNumber) => $base + (int) $cellNumber, $cellList); // Get list of ids

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

        $cellId = $base + (int) $cellNumber;

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
                // Get cache provider
                $cacheProvider = $tmpDb['provider_source'];
                $dateOfInfo = $tmpDb['date_of_info'];

                // Convert remaining items to float
                $tmpDb = array_map('floatval', $tmpDb);

                $responses[$eNB][$cellNumber] = [
                    'provider' => 'Cache - ' . $cacheProvider,
                    'cellId' => $tmpDb['cell_id'], // Calculate the correct cellId
                    'date' => $dateOfInfo,          // Using the latitude from the DB result
                    'lat' => $tmpDb['latitude'],          // Using the latitude from the DB result
                    'lng' => $tmpDb['longitude'],         // Using the longitude from the DB result
                    'accuracyMiles' => $tmpDb['accuracyMiles'], // Convert accuracy from meters to miles
                ];
                continue;
            }
        }

        // Build curl handle for Apple, if compatible
        if (isset($tac) && $rat == 'LTE') {
            $appleHandle = genAppleHandle($plmn, $cellId, $tac);
            $curlHandles_appl["$index-$cellNumber"] = $appleHandle;   
        } else {
            logWarning('Surro skipped for TAC ' . $tac);
        }

        // Skip Google if cell is deprioritized (surro-only)
        if (!in_array($cellId, $cellIdList_depri)) {
            // Build curl handle for Google
            $googleHandle = genGoogleHandle($plmn, $cellId, $rat, $cellIdLabel, $signalLabel, $maps_api_key);
            // Add to list
            $curlHandles_goog["$index-$cellNumber"] = $googleHandle;
        }
       
        
    }
}

// Check that any valid requests were created
if (isset($curlHandles_goog) || isset($curlHandles_appl)) {
    
    // Complete all apple requests
    $apple_multiCurl = curl_multi_init();
        // Add handles to list
    foreach ($curlHandles_appl as $key => $handler) {
        curl_multi_add_handle($apple_multiCurl, $handler);
    }
    
    // Execute all Apple lookups
    do {
        $status = curl_multi_exec($apple_multiCurl, $active);
        curl_multi_select($apple_multiCurl);
    } while ($active && $status == CURLM_OK);

    // Iterate through Apple responses
    foreach ($curlHandles_appl as $key => $handle) {

        // Check for potential cURL error
        if (curl_errno($handle)) {
            $error_msg = curl_error($handle);
            logWarning('Surro API returned an error:' . $error_msg);
            continue;
        }

        // Get response
        $response = curl_multi_getcontent($handle);
        [$index, $cellNumber] = explode('-', $key);

        $jsonResponse = json_decode($response, true);

        // Check if Surro wrapper API returned nothing
        if (isset($jsonResponse['error']) || empty($jsonResponse)) {
            logWarning('Surro returned no response for ' . $cellNumber . ' on ' . $key);
            continue; 
        }

        // Response is clean, so we should remove from the Google handler
        if (isset($curlHandles_goog[$key])) {
            unset($curlHandles_goog[$key]);    
        }

        // Get pertinent information
        $eNB = $formData[$index]['eNB'];
        $plmn = $formData[$index]['plmn'];
        $rat = $formData[$index]['rat'];

        // Calculate cell ID
        $cellGid = get_cell($cellNumber, $eNB, $plmn, $rat);

        // Calculate other information
        $lat = $jsonResponse['location']['lat'];
        $lng = $jsonResponse['location']['lng'];
        $accuracyMiles = ((int)$jsonResponse['accuracy']) / 1609;

        // Generate date of successful request
        $reqDate = date("Y-m-d H:i:s");

        // Add to responses
        $responses[$eNB][$cellNumber] = [
            'provider' => 'Surro',
            'cellId' => $cellGid,
            'date' => $reqDate,
            'lat' => $lat,
            'lng' => $lng,
            'accuracyMiles' => $accuracyMiles
        ];            

        // Update database
        $sqlInsert = "INSERT INTO local_poly (plmn, cell, cell_id, enb, rat, latitude, longitude, accuracyMiles, provider_source, date_of_info)
                      VALUES ('$plmn', '$cellNumber', '$cellGid', '$eNB', '$rat', '$lat', '$lng', '$accuracyMiles', 'Surro', '$reqDate')
                      ON DUPLICATE KEY UPDATE latitude = '$lat', longitude = '$lng', accuracyMiles = '$accuracyMiles', provider_source = 'Surro', date_of_info = '$reqDate'";
        $conn->query($sqlInsert);
     
    }
    if (!isset($_GET['blockGoogle'])) {
    // Execute remaining Google lookups
    $google_multiCurl = curl_multi_init();

    // Iterate through remaining Google responses
    foreach($curlHandles_goog as $key => $handler) {
        curl_multi_add_handle($google_multiCurl, $handler);
    }

    // Execute those Google lookups
    do {
        $status = curl_multi_exec($google_multiCurl, $active);
        curl_multi_select($google_multiCurl);
    } while ($active && $status == CURLM_OK);

    // Iterate through Google responses 
    foreach ($curlHandles_goog as $key => $handle) {
        $response = curl_multi_getcontent($handle);
        [$index, $cellNumber] = explode('-', $key);

        // Decode JSON from response
        $jsonResponse = json_decode($response, true);

        // Calculate cell information
        $eNB = $formData[$index]['eNB'];
        $plmn = $formData[$index]['plmn'];
        $rat = $formData[$index]['rat'];
        $cellGid = get_cell($cellNumber, $eNB, $plmn, $rat);
        

        // If location not set, skip
        if (isset($jsonResponse['location'])) {   
    
            // Calculate location information
            $lat = $jsonResponse['location']['lat'];
            $lng = $jsonResponse['location']['lng'];
            $accuracyMiles = ((int)$jsonResponse['accuracy']) / 1609;

            // Generate date of successful request
            $reqDate = date("Y-m-d H:i:s");

            // Add to responses
            $responses[$eNB][$cellNumber] = [
                'provider' => 'Google',
                'cellId' => $cellGid,
                'date' => $reqDate,
                'lat' => $lat,
                'lng' => $lng,
                'accuracyMiles' => $accuracyMiles
            ];   
            
            // Add to DB
            // Update database
            $sqlInsert = "INSERT INTO local_poly (plmn, cell, cell_id, enb, rat, latitude, longitude, accuracyMiles, provider_source, date_of_info)
                          VALUES ('$plmn', '$cellNumber', '$cellGid', '$eNB', '$rat', '$lat', '$lng', '$accuracyMiles', 'Google', '$reqDate')
                          ON DUPLICATE KEY UPDATE latitude = '$lat', longitude = '$lng', accuracyMiles = '$accuracyMiles', provider_source = 'Google', date_of_info = '$reqDate'";
            $conn->query($sqlInsert);

            // Update UserID DB to +1 gmaps api utilization
            mysqli_query($conn, "UPDATE userID SET gmaps_util = gmaps_util + 1 WHERE userID = '$userID'");

        } else {
            
            // Generate date
            $reqDate = date("Y-m-d H:i:s");

            // tell database that it was not found
            $sqlInsert = "INSERT INTO local_poly (plmn, cell, cell_id, enb, rat, latitude, longitude, accuracyMiles, date_of_info) 
	         	VALUES ('$plmn', '$cellNumber', '$cellGid', '$eNB', '$rat', NULL, NULL, NULL, '$reqDate') 
	 	        ON DUPLICATE KEY UPDATE latitude=VALUES(latitude), longitude=VALUES(longitude), accuracyMiles=VALUES(accuracyMiles)";
            $conn->query($sqlInsert);
        }
     }

     // Close leftover cURL instances to Google
     curl_multi_close($google_multiCurl);

    }


    // Close leftover cURL instances to Surro
    curl_multi_close($apple_multiCurl);

}

if (empty($responses)) {
    // Continue with generating the map only when requests are sent via auto-submission to allow user to correct.
    if (!isset($polyFormData)) {
        error("Nothing could be found");
    } else {
        return;
    }
}

$json_response = json_encode($responses);