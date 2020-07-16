<?php
// The mobile detection function
function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

// Get filename of current page - remove the file extension - set that as page title (THIS IS IMPORTANT FOR THE CSS CODE)
$without_extension = ucfirst(basename($_SERVER['PHP_SELF'],'.php'));

// Use Mobile CSS if on Mobile and use Desktop if on Desktop (OBVSLY)
if(isMobile()){
  echo '<link rel="stylesheet" href="styles/' . $without_extension . '/mobile.css">';
} else {
  echo '<link rel="stylesheet" href="styles/' . $without_extension . '/desktop.css">';
}

include 'includes/functions/headhtml.php';
include 'includes/usercheck.php';

// if latitude & longitude & carrier are set in URL bar create PHP variable with data
if (!empty($_GET['latitude'])) { $latitude = $_GET['latitude']; }
if (!empty($_GET['longitude'])) { $longitude = $_GET['longitude']; }
if (!empty($_GET['carrier'])) { $carrier = $_GET['carrier']; }
if (!empty($_GET['zip'])) { $zip = $_GET['zip']; }
if (!empty($_GET['state'])) { $state = $_GET['state']; }
if (!empty($_GET['city'])) { $city = $_GET['city']; }
if (!empty($_GET['address'])) { $address = $_GET['address']; }

// Warnings if cookies not set
if (!empty($_COOKIE["api_key"])) { $api_key = $_COOKIE["api_key"]; } else {echo "WARNING: <a href="."/cookie/google-maps.php".">Google Maps API</a> key is NOT defined<br>";}
if (!empty($_COOKIE["latitude"]) | !empty($_COOKIE["longitude"])) {
  $cookie_latitude = $_COOKIE["latitude"];
  $cookie_longitude = $_COOKIE["longitude"];
} else {echo "WARNING: <a href="."/cookie/latlong.php".">Latitude/Longitude</a> key is NOT defined<br>";}

/*
Debug code for the cookie values
echo "API KEY: $api_key (Cookie)<br>";
echo "Latitude: $cookie_latitude (Cookie)<br>";
echo "Longitude: $cookie_longitude (Cookie)<br>";
*/

// SQL Database login info
$servername = 'mysql.cmgm.gq';
$username = 'cmgm';
$password = 'My$QLP@$$w0rd';
$dbname = 'cmgm';
$conn = mysqli_connect($servername, $username, $password, $dbname);

// the button code used in Hub*.php
include 'includes/functions/hubLatLong.php'
?>
