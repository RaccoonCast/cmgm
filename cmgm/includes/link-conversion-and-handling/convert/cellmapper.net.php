<?php
$latitude = substr(explode('latitude=', $data, 2)[1],0,12);
$longitude = substr(explode('longitude=', $data, 2)[1],0,12);
$cm_zoom = substr(explode('zoom=', $data, 2)[1],0,2);
$cm_zoom = str_replace("&","",$cm_zoom);

if (strpos($data, 'MCC=310&MNC=260') !== false) {$carrier = "T-Mobile";}
if (strpos($data, 'MCC=310&MNC=120') !== false) {$carrier = "Sprint";}
if (strpos($data, 'MCC=310&MNC=410') !== false) {$carrier = "ATT";}
if (strpos($data, 'MCC=311&MNC=480') !== false) {$carrier = "Verizon";}
if (strpos($data, 'type=LTE') !== false) {$cm_netType = "LTE";}
if (strpos($data, 'type=NR') !== false) {$cm_netType = "NR";}

$conv_type = "CellMapper";
?>