<?php
function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}
$name = basename($_SERVER['PHP_SELF']);
$without_extension = basename($name, '.php');

if(isMobile()){
  echo '<link rel="stylesheet" href="styles/' . $without_extension . '/mobile.css">';
} else {
  echo '<link rel="stylesheet" href="styles/' . $without_extension . '/desktop.css">';
}

if (isset($_GET['latitude'])) $latitude = $_GET['latitude'];
if (isset($_GET['longitude'])) $longitude = $_GET['longitude'];

$servername = '127.0.0.1';
$username = 'rooter';
$password = 'My$QLP@$$w0rd';
$dbname = 'cmgm';

function hubLatLong($file,$color,$text) {
  if (isset($_GET['latitude'])) $latitude = $_GET['latitude'];
  if (isset($_GET['longitude'])) $longitude = $_GET['longitude'];
  if (isset($_GET['carrier'])) $carrier = $_GET['carrier'];
  echo "<form action=" . $file . "?latitude=" . $latitude . "&?longitude=" . $longitude . " " . "method=" . "get " . "class=" . "flex-item" . ">";
?>
  <input type="hidden" name="latitude" value="<?php echo $latitude;?>">
  <input type="hidden" name="longitude" value="<?php echo $longitude;?>">
  <input type="hidden" name="carrier" value="<?php echo $carrier;?>">
  <input type="submit" class="submitbutton" style="color: <?php echo $color; ?>;" value='<?php echo $text; ?>' >
</FORM>
<?php
}
?>
<link rel="icon" type="image/png" href="/logo.png">
<link rel="manifest" href="pwa-manifest.json">
<link rel="apple-touch-icon" href="images/icons-192.png">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<meta charset="utf-8">
<meta name="theme-color" content="#fff"/>
<script src="pwa2.js"></script>
