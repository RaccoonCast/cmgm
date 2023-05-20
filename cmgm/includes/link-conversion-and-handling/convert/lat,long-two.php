<?php
// FAA
$data = str_replace('Latitude (NAD 83): ', '', $data);
$data = str_replace(' Longitude (NAD 83): ', ',', $data);
$data = str_replace('LAT: ', '', $data);
$data = str_replace(' LONG: ', ',', $data);
// AntennaSearch side note: order matters (think about it)
$data = str_replace('Latitude ', '', $data);
$data = str_replace(' Longitude ', ',', $data);

?>
