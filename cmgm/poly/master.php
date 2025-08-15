<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$silent = true;
if (!isset($polyFormData)) {
    include '../functions.php';
}
include 'functions.php';

$formData = [];

// Group incoming POST data dynamically based on indices
$dataSource = isset($polyFormData) ? $polyFormData : $_POST;

foreach ($dataSource as $key => $value) {
    if (preg_match('/^(eNB|cellList|cellListDepri|plmn|rat|tac)(_(\d+))?$/', $key, $matches)) {
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

$curlHandles_goog = [];
$curlHandles_appl = [];
$responses = [];

function logWarning($warnText)
{
    error_log($warnText);
}

// If using DB TAC, the tac will be located here (keyed by normal key, with dbTac as tertiary parameter)
$dbTacValues = [];

// Process each group in $formData
foreach ($formData as $index => $data) {
    $rat = in_array($data['rat'], ['LTE', 'NR']) ? $data['rat'] : error('Invalid RAT.');
    $eNB = preg_match('/^\d{1,10}$/', $data['eNB']) ? $data['eNB'] : error('Invalid eNB/gNB.');
    $cellList = isset($data['cellList']) && preg_match('/^\d+(,\d+)*$/', $data['cellList']) ? explode(',', $data['cellList']) : null;
    $plmn = preg_match('/^\d{6}$/', $data['plmn']) ? $data['plmn'] : error('Invalid PLMN.');
    $tac = isset($data['tac']) && preg_match('/^\d{1,10}$/', $data['tac']) ? $data['tac'] : null; 
    $cellList_depri = isset($data['cellListDepri']) && preg_match('/^\d+(,\d+)*$/', $data['cellListDepri']) ? explode(',', $data['cellListDepri']) : null;
    if (isset($data['cellListDepri']) && $data['cellListDepri'] == '*') {
        $cellList_depri = range(1, 255);
    }
    if (isset($data['cellListDepri']) && $data['cellListDepri'] == '-') {
       if ($plmn == 310260) $cellList_depri = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,31,32,33,34,35,36,41,42,43,44,45,46,47,48,49,50,51,52,53,54,61,62,63,64,65,66,104,105,106,107,111,112,113,114,131,132,133,134,135,136,141,142,143,144,145,146,171,172,173,174,241,242,243,244];
       if ($plmn == 310410 || $plmn == 313100) $cellList_depri = [7,8,9,10,11,12,13,15,16,17,18,19,20,22,23,24,25,26,27,185,186,187,188,189,190,191,192,193,194,195,196,197,198,35,63,99,28,56,91,182,183,181,42,70,107,49,63,115,179,171,186,202,203,201,92,93,94,100,101,102,108,109,110,149,150,151,152,153,154,155,171,172,173,174,217,218,219,220];
       if ($plmn == 311480) $cellList_depri = [1,2,3,4,5,6,7,11,12,13,14,15,16,17,18,19,21,22,23,24,25,26,27,28,29,31,32,33,34,35,36,37,38,39,41,42,43,44,45,46,47,48,49,52,54,62,64,72,74,76,82,84,86,92,94,96,102,104,106,112,114,116,122,124,126];
       if ($plmn == 310120) error('Fuck Sprint');
    }
 
    // Set base calculation for cellId
    $base = 0;
    if ($rat == 'NR') {
        $cellIdLabel = 'newRadioCellId';
        $signalLabel = 'signalStrength';
        $base = match ($plmn) {
            '310260' => $eNB * 4096,
            '310410' => $eNB * 1024,
            '311480' => $eNB * 16384,
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
            // If forceNewResults / "Ignore Cache" is set, don't check the db
            if (!isset($_GET['forceNewResults'])) {
                $tmpDb = $dbDump_dataMap[$cellId];

                if (is_null($tmpDb['latitude']) || is_null($tmpDb['longitude'])) {
                    // If DB already null, continue without generating handles
                    continue;
                } else {
                    // Otherwise, get cache data as expected
                    $cacheProvider = $tmpDb['provider_source'];
                    $dateOfInfo = $tmpDb['date_of_info'];

                    // Convert remaining items to float
                    $tmpDb = array_map('floatval', $tmpDb);

                    $responses[$eNB][$cellNumber] = [
                        'provider' => 'Cache - ' . $cacheProvider,
                        'cellId' => $tmpDb['cell_id'],
                        'date' => $dateOfInfo,
                        'lat' => $tmpDb['latitude'],
                        'lng' => $tmpDb['longitude'],
                        'accuracyMiles' => $tmpDb['accuracyMiles'],
                    ];
                    if ($tmpDb['tac'] > 0) {
                         $responses[$eNB][$cellNumber]['tac'] = $tmpDb['tac'];
                    }
                    // Skip adding curl handles, since data is already covered by db
                    continue;
                }
            } else {
                logWarning("'Ignore Cache' option is set - searching primary sources for {$cellId}");
            }
        }

        // Skip Google if PCI+ passes request for eNB already in the DB, by Surro, with correct TAC
        // NOTE: Potential Caveat - this may not work if Surro TAC changes over time, but if that happens it'll pass to Google
        $pciPlus_skip_google = false;
        if (isset($_POST['polyUserId']) && isset($responses[$eNB])) {
            $existingDbRecord = array_slice($responses[$eNB], 0, 1)[0];
        }

        // Build curl handle for Apple, if compatible
        if (isset($tac)) {
            $appleHandle = genAppleHandle($plmn, $cellId, $tac, $rat);
            $curlHandles_appl["$index-$eNB-$cellNumber-primary"] = $appleHandle;

            // Generate secondary Apple handle for other TAC
            if (isset($existingDbRecord) && isset($existingDbRecord['tac']) && $existingDbRecord['tac'] != $tac) {
                // Get TAC
                $existingTac = $existingDbRecord['tac'];
                // Set key
                $key = "$index-$eNB-$cellNumber-dbTac";
                $dbTacValues[$key] = $existingTac;

                $appleHandle = genAppleHandle($plmn, $cellId, $existingTac, $rat);
                $curlHandles_appl[$key] = $appleHandle;
            }
        } else {
            logWarning('Surro skipped for TAC ' . $tac);
        }

        // Skip Google if cell is deprioritized (surro-only) 
        if (!in_array($cellId, $cellIdList_depri) && !isset($existingDbRecord)) {
            // Build curl handle for Google
            $googleHandle = genGoogleHandle($plmn, $cellId, $rat, $cellIdLabel, $signalLabel, $maps_api_key);
            // Add to list
            $curlHandles_goog["$index-$eNB-$cellNumber"] = $googleHandle;
        }
    }
}

// Check that any valid requests were created
if (isset($curlHandles_goog) || isset($curlHandles_appl)) {

    // Setup eNB blacklist in case needed
    $eNB_blacklist = [];

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
        [$index, $eNB, $cellNumber, $tacType] = explode('-', $key);

        // Regular key, no tac added (for other providers)
        $noTacKey = "$index-$eNB-$cellNumber";

        $jsonResponse = json_decode($response, true);

        // Check if Surro wrapper API returned nothing
        if (isset($jsonResponse['error']) || empty($jsonResponse)) {
            logWarning('Surro returned no response for ' . $cellNumber . ' on ' . $key);
            continue;
        }

        // If there's a secondary handler (for DB tac), remove that
        $dbTacKey = "{$index}-{$eNB}-{$cellNumber}-dbTac";
        if (!$tacType == "dbTac" && isset($curlHandles_appl[$dbTacKey])) {
            // logWarning("Removing db tac from consideration");
            unset($curlHandles_appl[$dbTacKey]);
        }

        // Response is clean, so we should remove from the Google handler
        // logWarning("Key: {$eNB}");
        if (isset($curlHandles_goog[$noTacKey])) {
            unset($curlHandles_goog[$noTacKey]);
        }

        // If request is from PCI+, remove all with the same eNB
        if (isset($_POST['polyUserId'])) {
            if (!in_array($eNB, $eNB_blacklist)) {
                // Loop through & remove matches
                foreach ($curlHandles_goog as $key => $_handler) {
                    if (str_contains($key, $eNB)) {
                        // logWarning("Removing Google Handle: {$key}");
                        unset($curlHandles_goog[$key]);
                    }
                // Add to blacklist
                array_push($eNB_blacklist, $eNB);
                }
            }
        }

        // Get pertinent information
        $eNB = $formData[$index]['eNB'];
        $tac = $formData[$index]['tac'];
        $plmn = $formData[$index]['plmn'];
        $rat = $formData[$index]['rat'];


        // If using DB TAC, display that instead
        if ($tacType == "dbTac") {
            $tac = $dbTacValues[$dbTacKey];
        }

        // Calculate cell ID
        $cellGid = get_cell($cellNumber, $eNB, $plmn, $rat);

        // Calculate other information
        $lat = $jsonResponse['location']['lat'];
        $lng = $jsonResponse['location']['lng'];
        $accuracyMiles = ((int) $jsonResponse['accuracy']) / 1609;

        // Generate date of successful request
        $reqDate = date('Y-m-d H:i:s');

        // Add to responses
        $responses[$eNB][$cellNumber] = [
            'provider' => 'Surro',
            'cellId' => $cellGid,
            'tac' => (int) $tac,
            'date' => $reqDate,
            'lat' => $lat,
            'lng' => $lng,
            'accuracyMiles' => $accuracyMiles,
        ];

        // Update database
        $sqlInsert = "INSERT INTO local_poly (plmn, cell, cell_id, enb, tac, rat, latitude, longitude, accuracyMiles, provider_source, date_of_info)
                      VALUES ('$plmn', '$cellNumber', '$cellGid', '$eNB', '$tac', '$rat', '$lat', '$lng', '$accuracyMiles', 'Surro', '$reqDate')
                      ON DUPLICATE KEY UPDATE tac = '$tac', latitude = '$lat', longitude = '$lng', accuracyMiles = '$accuracyMiles', provider_source = 'Surro', date_of_info = '$reqDate'";
        $conn->query($sqlInsert);
    }

    // Execute remaining Google lookups
    if (!isset($_GET['blockGoogle'])) {
        $google_multiCurl = curl_multi_init();

        // Iterate through remaining Google responses
        foreach ($curlHandles_goog as $key => $handler) {
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
            [$index, $eNB, $cellNumber] = explode('-', $key);

            // logWarning("Google request called for: {$eNB}-{$cellNumber}");

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
                $accuracyMiles = ((int) $jsonResponse['accuracy']) / 1609;

                // Generate date of successful request
                $reqDate = date('Y-m-d H:i:s');

                // Add to responses
                $responses[$eNB][$cellNumber] = [
                    'provider' => 'Google',
                    'cellId' => $cellGid,
                    'date' => $reqDate,
                    'lat' => $lat,
                    'lng' => $lng,
                    'accuracyMiles' => $accuracyMiles,
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
                $reqDate = date('Y-m-d H:i:s');

                // tell database that it was not found
                $sqlInsert = "INSERT INTO local_poly (plmn, cell, cell_id, enb, rat, latitude, longitude, accuracyMiles, date_of_info) 
	         	VALUES ('$plmn', '$cellNumber', '$cellGid', '$eNB', '$rat', NULL, NULL, NULL, '$reqDate') 
	 	        ON DUPLICATE KEY UPDATE latitude=VALUES(latitude), longitude=VALUES(longitude), accuracyMiles=VALUES(accuracyMiles), date_of_info = '$reqDate'";
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
        error('Nothing could be found');
    } else {
        return;
    }
}

$json_response = json_encode($responses);
