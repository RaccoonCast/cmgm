<?php
function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}
$without_extension = ucfirst(basename($_SERVER['PHP_SELF'],'.php'));

if(isMobile()){
  echo '<link rel="stylesheet" href="styles/' . $without_extension . '/mobile.css">';
} else {
  echo '<link rel="stylesheet" href="styles/' . $without_extension . '/desktop.css">';
}
echo '<title>EvilCM - '. $without_extension . '</title>';

if (isset($_GET['latitude'])) $latitude = $_GET['latitude'];
if (isset($_GET['longitude'])) $longitude = $_GET['longitude'];
if (isset($_GET['carrier'])) $carrier = $_GET['carrier'];
if (isset($_COOKIE["api_key"])) { $api_key = $_COOKIE["api_key"]; } else {echo "WARNING: <a href="."/gm-cookie.php".">Google Maps API</a> key is NOT defined<br>";}

if (isset($_COOKIE["latitude"]) | isset($_COOKIE["longitude"])) {
  $cookie_latitude = $_COOKIE["latitude"];
  $cookie_longitude = $_COOKIE["longitude"];
} else {echo "WARNING: <a href="."/latlong-cookie.php".">Latitude/Longitude</a> key is NOT defined<br>";}

/*
echo "API KEY: $api_key (Cookie)<br>";
echo "Latitude: $cookie_latitude (Cookie)<br>";
echo "Longitude: $cookie_longitude (Cookie)<br>";
*/

$servername = 'mysql.cmgm.gq';
$username = 'cmgm';
$password = 'My$QLP@$$w0rd';
$dbname = 'cmgm';

function hubLatLong($file,$color,$text) {
  if (!empty($_GET['latitude'])) { $latitude = $_GET['latitude']; } else {$latitude = $_COOKIE["latitude"];}
  if (!empty($_GET['longitude'])) { $longitude = $_GET['longitude']; } else {$longitude = $_COOKIE["longitude"];}
  if (!empty($_GET['carrier'])) { $carrier = $_GET['carrier']; } else {$carrier = $_COOKIE["carrier"];}
  echo "<form action=" . $file . " " . "method=" . "get" . ">
  ";
?>
<input type="hidden" name="latitude" value="<?php echo $latitude;?>">
  <input type="hidden" name="longitude" value="<?php echo $longitude;?>">
  <input type="hidden" name="carrier" value="<?php if (isset($_GET['carrier'])) echo $carrier;?>">
  <input type="submit" class="submitbutton" style="color: <?php echo $color; ?>;" value='<?php echo $text; ?>' >
</form>

<?php
}
?>
<link rel="icon" type="image/png" href="/images/logo.png">
<link rel="manifest" href="/manifest.json">
<link rel="apple-touch-icon" href="images/icons-192.png">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<meta charset="utf-8">
<meta name="theme-color" content="#fff"/>
<script src="/pwa2.js"></script>
