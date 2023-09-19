<?php
// FAA
$data = str_replace('Latitude (NAD 83): ', '', $data);
$data = str_replace(' Longitude (NAD 83): ', ',', $data);

// AntennaSearch side note: order matters (think about it)
$data = str_replace('Latitude ', '', $data);
$data = str_replace(' Longitude ', ',', $data);

$data = str_replace('Latitude: ', '', $data);
$data = str_replace(' Longitude: ', ',', $data);

$data = str_replace('LAT: ', '', $data);
$data = str_replace(' LONG: ', ',', $data);

// Don't set conv_type since it'll still need to run through convert/lat,long.php to change "34,-118" to $latitude = 34 & $longitude = -118
?>
