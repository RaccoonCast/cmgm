<?php
// FAA
$data = str_replace('Latitude (NAD 83): ', '', $data);
$data = str_replace(' Longitude (NAD 83): ', ',', $data);
$data = str_replace('LAT: ', '', $data);
$data = str_replace(' LONG: ', ',', $data);


?>
