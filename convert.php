<?php
include 'functions.php';
$data = $_GET['data'];
if (isset($_GET['goto_page'])) $goto_page = $_GET['goto_page'];
if (!isset($_GET['goto_page'])) $goto_page = "Home.php";
if ($goto_page == "Database") $goto_page = "HubDatabase.php";
if ($goto_page == "CellMapepr") $goto_page = "cm.php";
if ($goto_page == "Google Maps") $goto_page = "gm.php";
if ($goto_page == "LA Permit Map") $goto_page = "gmaps-special.php";
if ($goto_page == "Database") $goto_page = "HubDatabase.php";

// If not set get location from cookies
if (empty($data)) { include "includes/convert/cookie-location.php"; }
// CellMapper URL Conversion
elseif (substr("$data", 0, 26) === 'https://www.cellmapper.net'){ include "includes/convert/cellmapper.net.php"; }
// Google Maps URL Conversion
elseif(substr("$data", 0, 28) === 'https://www.google.com/maps/') { include "includes/convert/google-maps-url-conversion.php"; }
// Comma Seperator
elseif( strpos($data, ',') !== false ) { include "includes/convert/lat,long.php"; }

// Google Maps search for the entered data
include 'includes/convert/google-maps-conversion.php';

// Misc code, trim lat&long, get carrier from cookie if set, prevent warnings if certain variables aren't set (They don't need to be)
if(!isset($carrier)) $carrier = $_COOKIE["carrier"];
$latitude = substr($latitude,0,10);
$longitude = substr($longitude,0,10);
if(!isset($gjson_url_1)) $gjson_url_1 = null;
if(!isset($zip)) $zip = null;
if(!isset($address)) $address = null;

// URL Builder 3.0 /s
echo '<meta http-equiv="refresh" content="0; url=' . $goto_page . '
?latitude=' . $latitude .
'&longitude=' . $longitude .
'&carrier=' . $carrier .
'&address=' . $address .
'&zip=' . $zip .
'&city=' . $city .
'&state=' . $state .
'&gjson_url_1=' . $gjson_url_1 .
'&gjson_url_2=' . $gjson_url_2 .
'&data=' . $data .
'&conv_type=' . $conv_type .
'">';
