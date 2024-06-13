<?php
unset ($state,$city,$zip); 

// This file is used solely by convert.php, it expects an input of $latitude & $longitude which it'll convert to the address of the given input.
if (isset($maps_api_key) && $goto != "CellMapper" && $goto != "Google Maps" && $goto != "Street View" && $goto != "Beta" && $goto != "Edit" && $goto != "Map") {
$url_2 = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($latitude).','.trim($longitude).'&key=' . $maps_api_key . '';
$ch = curl_init(); curl_setopt($ch, CURLOPT_URL, $url_2); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); curl_setopt($ch, CURLOPT_PROXYPORT, 3128); curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); $response = curl_exec($ch); curl_close($ch);
mysqli_query($conn, "UPDATE userID SET gmaps_util = gmaps_util + 1 WHERE userID = '$userID'");

// Parse the json output
$response = json_decode($response);
// Assuming $response is your main response object
$results = $response->results;

$state = null;
$county = null;
$city = null;
$route = null;
$zip = null;
$street_number = null;

// Loop through each result until we find valid address components
foreach ($results as $result) {
    if (isset($result->address_components)) {
        $addressComponents = $result->address_components;

        foreach ($addressComponents as $addrComp) {
            if ($addrComp->types[0] == 'administrative_area_level_1') { if (empty($state)) $state = $addrComp->short_name;}
            if ($addrComp->types[0] == 'administrative_area_level_2') { if (empty($county)) $county = $addrComp->long_name;}
            if ($addrComp->types[0] == 'locality') { if (empty($city)) $city = $addrComp->long_name;}
            if ($addrComp->types[0] == 'route') { if (empty($route)) $route = $addrComp->short_name;}
            if ($addrComp->types[0] == 'postal_code') { if (empty($zip)) $zip = $addrComp->long_name;}
            if ($addrComp->types[0] == 'street_number') { if (empty($street_number)) $street_number = $addrComp->short_name;}
        }

        // If we found valid address components, break out of the loop
        if ($state !== null && $county !== null && $city !== null && $route !== null && $zip !== null && $street_number !== null) {
            break;
        }
    }
}

    if (@$city == @$county) $county = NULL;
    if (!empty($route) && !empty($street_number)) $address = "$street_number $route";
    if (isset($state) && strlen($state) > 2) {
      if ($addrComp->types[0] == 'country') {
          $state = $addrComp->short_name;
      }
  }
}
?>
