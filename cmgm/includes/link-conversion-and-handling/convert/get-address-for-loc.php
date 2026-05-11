<?php
unset ($state,$city,$zip); 

// This file is used solely by convert.php, it expects an input of $latitude & $longitude which it'll convert to the address of the given input.
if (isset($maps_api_key) && $goto != "CellMapper" && $goto != "Google Maps" && $goto != "Street View" && $goto != "Beta" && $goto != "Edit" && $goto != "Map") {
$url_2 = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($latitude).','.trim($longitude).'&key=' . $maps_api_key . '';
$ch = curl_init(); curl_setopt($ch, CURLOPT_URL, $url_2); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); curl_setopt($ch, CURLOPT_PROXYPORT, 3128); curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); $response = curl_exec($ch);
mysqli_query($conn, "UPDATE userID SET gmaps_util = gmaps_util + 1 WHERE userID = '$userID'");

// Parse the json output
$response = json_decode($response);
// Assuming $response is your main response object
$results = $response->results;

$address = $state = $county = $city = $route =$zip = $street_number = null;

// Loop through each result until we find valid address components
foreach ($results as $result) {
    if (isset($result->address_components)) {
        $addressComponents = $result->address_components;
        
        foreach ($addressComponents as $addrComp) {
            // Guard clause: Ensure 'types' exists and is not empty to prevent warnings
            if (!isset($addrComp->types) || empty($addrComp->types)) {
                continue;
            }

            if (empty($state) && in_array('administrative_area_level_1', $addrComp->types)) $state = $addrComp->short_name;
            if (empty($county) && in_array('administrative_area_level_2', $addrComp->types)) $county = $addrComp->long_name;
            if (empty($city) && in_array('locality', $addrComp->types)) $city = $addrComp->long_name;  
            if (empty($route) && in_array('route', $addrComp->types)) $route = $addrComp->short_name;
            if (empty($zip) && in_array('postal_code', $addrComp->types)) $zip = $addrComp->long_name;
            if (empty($street_number) && in_array('street_number', $addrComp->types)) $street_number = $addrComp->short_name;

            // "Support" for Puerto Rico/US Virgin Islands
            if (isset($state) && strlen($state) > 2) {
                if (in_array('country', $addrComp->types)) {
                    $state = $addrComp->short_name;
                }
            }
        }

        // If we found all valid address components, break out of the outer loop
        if ($state !== null && $county !== null && $city !== null && $route !== null && $zip !== null && $street_number !== null) {
            break;
        }
    }
}

    if (@$city == @$county) $county = NULL; // If county and city are the same, set county to empty.
    if (!empty($route) && !empty($street_number)) $address = "$street_number $route"; // Generate $address variable for field on edit.

}
?>
