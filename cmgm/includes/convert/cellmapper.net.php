<?php
$latitude = explode('latitude=', $data, 2)[1];
$longitude = explode('longitude=', $data, 2)[1];
$mcc = explode('MCC=', $data, 2)[1];
$mcc = substr($mcc,0,3);
$mnc = explode('MNC=', $data, 2)[1];
$mnc = substr($mnc,0,3);
$network = "$mcc$mnc";
if('310260' == '' . $network . '') {$carrier = "T-Mobile";}
if('310120' == '' . $network . '') {$carrier = "Sprint";}
if('310410' == '' . $network . '') {$carrier = "ATT";}
if('311480' == '' . $network . '') {$carrier = "Verizon";}
$latitude = substr($latitude,0,10);
$longitude = substr($longitude,0,10);
$conv_type = "CellMapper";
?>
