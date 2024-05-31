<?php

$domain = $_SERVER['SERVER_NAME'];
$port = $_SERVER['SERVER_PORT'] ? ':'.$_SERVER['SERVER_PORT'] : '';
$http = (in_array("HTTPS", $_SERVER) && $_SERVER['HTTPS'] == "on") ? "https://" : "http://";
$domain_with_http = $http . $domain . $port;

// SQL Database login info
$servername = 'mysql.' . 'cmgm.us';
$db_username = 'cmgm';
$siteroot = $_SERVER['DOCUMENT_ROOT'];

if (strtoupper(substr(PHP_OS, 0, 3))) {
  $password = file_get_contents($siteroot . "/secret_sql_login.hiddenpass", true);
} else {
  $password = file_get_contents($siteroot . "\secret_sql_login.hiddenpass", true);
}
$dbname = 'cmgm';
$conn = mysqli_connect($servername, $db_username, $password, $dbname);
?>
