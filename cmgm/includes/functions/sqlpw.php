<?php

// Get domain name
$domain = $_SERVER['SERVER_NAME'];

// Get port, assuming it's not 80 or 443
// $port = $_SERVER['SERVER_PORT'] ? ':'.$_SERVER['SERVER_PORT'] : '';
// if ($port === ':80' || $port === ':443') {
//   $port = '';
// }

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') { $isSecure = true; }
elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') { $isSecure = true; }
$http = isset($isSecure) ? "https://" : "http://";

$domain_with_http = $http . $domain; //. $port

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
