<?php
unset ($state,$city,$zip); 

// This file is used solely by convert.php, it expects an input of $latitude & $longitude which it'll convert to the address of the given input.
if (isset($maps_api_key) && $goto != "CellMapper" && $goto != "Google Maps" && $goto != "Street View" && $goto != "Beta" && $goto != "Edit" && $goto != "Map") {
$url_2 = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($latitude).','.trim($longitude).'&key=' . $maps_api_key . '';
$ch = curl_init(); curl_setopt($ch, CURLOPT_URL, $url_2); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); curl_setopt($ch, CURLOPT_PROXYPORT, 3128); curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); $response = curl_exec($ch); curl_close($ch);
mysqli_query($conn, "UPDATE userID SET gmaps_util = gmaps_util + 1 WHERE userID = '$userID'");

// Parse the json output
$response = json_decode($response);

 // Retrieve important information from JSON and set the data to a corresponding variable
 $addressComponents = $response->results[0]->address_components;
 foreach ($addressComponents as $addrComp) {
     if ($addrComp->types[0] == 'postal_code') $zip = $addrComp->long_name;
     if ($addrComp->types[0] == 'street_number') $street_number = $addrComp->short_name;
     if ($addrComp->types[0] == 'route') $route = $addrComp->short_name;
     if ($addrComp->types[0] == 'locality') $city = $addrComp->long_name;
     if (!isset($city)) if (in_array("sublocality", $addrComp->types)) { $city = $addrComp->long_name; }
     if ($addrComp->types[0] == 'administrative_area_level_1') $state = $addrComp->short_name;
     
     if (isset($state) && strlen($state) > 2) {
      if ($addrComp->types[0] == 'country') @$state = $addrComp->short_name;
    }

     if ($addrComp->types[0] == 'administrative_area_level_2') $county = $addrComp->long_name;
     if (@$city == @$county) $county = NULL;
     }

    if (!empty($route) && !empty($street_number)){
      $address = "$street_number $route";
    } else {
      $address = "";
    }


    }
?>
