<?php
$domain = "cmgm.us";
if ($_SERVER['SERVER_NAME'] == $domain) {
  $domain_with_http = "https://" . $domain;
} else {
  $domain_with_http = "http://" . $_SERVER['SERVER_NAME'];
}

// SQL Database login info
$servername = 'mysql.' . $domain;
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
