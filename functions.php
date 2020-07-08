<?php
// The mobile detection function
function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

// Get filename of current page - remove the file extension - set that as page title (THIS IS IMPORTANT FOR THE CSS CODE)
$without_extension = ucfirst(basename($_SERVER['PHP_SELF'],'.php'));
echo '<title>EvilCM - '. $without_extension . '</title>';

// Use Mobile CSS if on Mobile and use Desktop if on Desktop (OBVSLY)
if(isMobile()){
  echo '<link rel="stylesheet" href="styles/' . $without_extension . '/mobile.css">';
} else {
  echo '<link rel="stylesheet" href="styles/' . $without_extension . '/desktop.css">';
}


// if latitude & longitude & carrier are set in URL bar create PHP variable with data
if (isset($_GET['latitude'])) $latitude = $_GET['latitude'];
if (isset($_GET['longitude'])) $longitude = $_GET['longitude'];
if (isset($_GET['carrier'])) $carrier = $_GET['carrier'];

// Warnings if cookies not set
if (!empty($_COOKIE["api_key"])) { $api_key = $_COOKIE["api_key"]; } else {echo "WARNING: <a href="."/gm-cookie.php".">Google Maps API</a> key is NOT defined<br>";}
if (!empty($_COOKIE["latitude"]) | !empty($_COOKIE["longitude"])) {
  $cookie_latitude = $_COOKIE["latitude"];
  $cookie_longitude = $_COOKIE["longitude"];
} else {echo "WARNING: <a href="."/latlong-cookie.php".">Latitude/Longitude</a> key is NOT defined<br>";}

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

// the button code used in Hub*.php
function hubLatLong($file,$color,$text,$target) {
  if (!empty($_GET['latitude'])) { $latitude = $_GET['latitude']; } else {$latitude = $_COOKIE["latitude"];}
  if (!empty($_GET['longitude'])) { $longitude = $_GET['longitude']; } else {$longitude = $_COOKIE["longitude"];}
  if (!empty($_GET['carrier'])) { $carrier = $_GET['carrier']; } else {$carrier = $_COOKIE["carrier"];}
  echo "<form target=" . $target . " action=" . $file . " " . "method=" . "get" . ">
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
<script type="module">
import 'https://cdn.jsdelivr.net/npm/@pwabuilder/pwaupdate';

const el = document.createElement('pwa-update');
document.body.appendChild(el);
</script>
<link rel='manifest' href='/manifest.json'>
<link rel="apple-touch-icon" href="images/icons-192.png">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<meta charset="utf-8">
<meta name="theme-color" content="#fff"/>
