<?php
// SQL Database login info
$servername = 'mysql.cmgm.us';
$db_username = 'cmgm';
$siteroot = $_SERVER['DOCUMENT_ROOT'];
if ($siteroot == "/home/spane2003/cmgm.us") {
  $password = file_get_contents($siteroot . "/secret_sql_login.hiddenpass", true);
} else {
  $password = file_get_contents($siteroot . "\secret_sql_login.hiddenpass", true);
}
$dbname = 'cmgm';
$conn = mysqli_connect($servername, $db_username, $password, $dbname);
?>
