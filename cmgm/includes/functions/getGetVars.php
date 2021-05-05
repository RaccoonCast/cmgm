<?php
// if latitude & longitude & carrier are set in URL bar create PHP variable with data
if (isset($_GET['refresh'])) { echo '<meta http-equiv="refresh" content="0;URL=../" /> '; }

// get latitude from URL
if (!empty($_GET['latitude'])) { $latitude = $_GET['latitude']; }
if (!empty($_GET['longitude'])) { $longitude = $_GET['longitude']; }
if (!empty($_GET['carrier'])) { $carrier = $_GET['carrier']; }
// didn't work? ok...
if (!isset($latitude)) if (isset($default_latitude)) { $latitude = $default_latitude; } else { $default_latitude = "38.89951535140072"; $latitude = $default_latitude; }
if (!isset($longitude)) if (isset($default_longitude)) { $longitude = $default_longitude; } else { $default_longitude = "-77.03656463746842"; $longitude = $default_longitude; }
if (!isset($carrier)) if (isset($default_carrier)) { $carrier = $default_carrier; } else { $default_carrier = "ATT"; $carrier = $default_carrier; }

if (!empty($_GET['zip'])) { $zip = $_GET['zip']; }
if (!empty($_GET['state'])) { $state = $_GET['state']; }
if (!empty($_GET['city'])) { $city = $_GET['city']; }
if (!empty($_GET['address'])) { $address = $_GET['address']; }
if (!empty($_GET['data'])) { $data = $_GET['data']; }
if (!empty($_GET['conv_type'])) { $conv_type = $_GET['conv_type']; }
?>
