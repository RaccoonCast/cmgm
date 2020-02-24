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

$servername = '127.0.0.1';
$username = 'rooter';
$password = 'My$QLP@$$w0rd';
$dbname = 'cmgm';

?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">
<link rel="icon" type="image/png" href="/logo.png">
