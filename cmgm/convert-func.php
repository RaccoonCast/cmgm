<?php
function convert($latitude,$longitude) {
include SITE_ROOT . "/includes/useridsys/native.php";
include SITE_ROOT . "/includes/functions/getGetVars.php";
include SITE_ROOT . "/includes/functions/sqlpw.php";
include SITE_ROOT . "/includes/convert/get-address-for-loc.php";
$goto_page = "Home";

if ($latitude == 'unknown') $latitude = $default_latitude;
if ($longitude == 'unknown') $longitude = $default_longitude;

$latitude = substr($latitude,0,10);
$longitude = substr($longitude,0,10);
if(empty($carrier)) $carrier = null;
if(empty($zip)) $zip = null;
if(empty($address)) $address = null;
if(empty($city)) $city = null;
if(empty($state)) $state = null;

// URL Builder 3.0 /s
return 'Home.php?' . 'latitude=' . $latitude .'&longitude=' . $longitude .'&carrier=' . $carrier .'&address=' . $address .'&zip=' . $zip .'&city=' . $city . '&state=' . $state;
}
