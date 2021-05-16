<?php
include 'functions.php';
$data = $_GET['data'];
$data = str_replace("\xc2\xa0", ' ', $data);

if (isset($_GET['goto_page'])) $goto_page = $_GET['goto_page'];
if (!isset($_GET['goto_page'])) {
  $goto_page = "Home";
}

// If not set get location from cookies
if (empty($data)) { include "includes/convert/cookie-location.php"; }
// CellMapper URL Conversion
elseif(substr("$data", 0, 26) === 'https://www.cellmapper.net'){ include "includes/convert/cellmapper.net.php"; }
// Google Maps URL Conversion
elseif(substr("$data", 0, 28) === 'https://www.google.com/maps/') { include "includes/convert/google-maps-url-conversion.php"; }
// Comma Seperator
elseif(strpos($data, ',') !== false ) { include "includes/convert/lat,long.php"; }
// NOTHING? Google Maps search for the entered data
include 'includes/convert/google-maps-conversion.php';

include 'goto.php';
