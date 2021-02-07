<?php
// Warnings if cookies not set
if (isset($_COOKIE["debug"])) $debug = "true";

if (!empty($_COOKIE["api_key"])) {
  $api_key = $_COOKIE["api_key"];
} else {
  echo "WARNING: <a href="."/cookie/google-maps.php".">Google Maps API</a> key is NOT defined<br>";
}

if (!empty($_COOKIE["latitude"]) | !empty($_COOKIE["longitude"])) {
  $cookie_latitude = $_COOKIE["latitude"];
  $cookie_longitude = $_COOKIE["longitude"];
} else {
  echo "WARNING: <a href="."/cookie/latlong.php".">Latitude/Longitude</a> key is NOT defined<br>";
}

/*
Debug code for the cookie values
echo "API KEY: $api_key (Cookie)<br>";
echo "Latitude: $cookie_latitude (Cookie)<br>";
echo "Longitude: $cookie_longitude (Cookie)<br>";
*/
?>
