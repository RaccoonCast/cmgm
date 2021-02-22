<?php
// Warnings if cookies not set
if (empty($maps_api_key)) {
} else {
  if (!isset($maps_api_key)) $maps_api_key = $_COOKIE["api_key"];
}

if (!empty($_COOKIE["latitude"]) | !empty($_COOKIE["longitude"])) {
  $cookie_latitude = $_COOKIE["latitude"];
  $cookie_longitude = $_COOKIE["longitude"];
} else {
  $warningEcholatlong = 'true';
}

if ($without_extension == "Home" & isset($conv_type)) {
  if (isset($warningEchoAPIkey)) echo "WARNING: <a href="."/cookie/google-maps.php".">Google Maps API</a> key is NOT defined<br>";
  if (isset($warningEcholatlong)) echo "WARNING: <a href="."/cookie/latlong.php".">Latitude/Longitude</a> key is NOT defined<br>";
}
?>
