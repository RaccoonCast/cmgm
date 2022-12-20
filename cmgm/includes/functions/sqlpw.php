<?php
$domain = $_SERVER['SERVER_NAME'];
$http = ($_SERVER['HTTPS'] == "on") ? "https://" : "http://";
$domain_with_http = $http . $domain;

// SQL Database login info
$servername = 'mysql.' . 'cmgm.us';
$db_username = 'cmgm';
$siteroot = $_SERVER['DOCUMENT_ROOT'];
if ($siteroot == "/home/spane2003/" . $domain) {
  $password = file_get_contents($siteroot . "/secret_sql_login.hiddenpass", true);
} else {
  $password = file_get_contents($siteroot . "\secret_sql_login.hiddenpass", true);
}
$dbname = 'cmgm';
$conn = mysqli_connect($servername, $db_username, $password, $dbname);
?>
