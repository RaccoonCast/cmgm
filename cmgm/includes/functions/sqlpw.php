<?php
// SQL Database login info
$servername = 'mysql.cmgm.ml';
$db_username = 'cmgm';
$siteroot = $_SERVER['DOCUMENT_ROOT'];
if ($siteroot == "/home/spane2003/cmgm.ml") {
  $password = file_get_contents($siteroot . "/secret_sql_login.hiddenpass", true);
} else {
  $password = file_get_contents($siteroot . "\secret_sql_login.hiddenpass", true);
}
$dbname = 'cmgm';
$conn = mysqli_connect($servername, $db_username, $password, $dbname);
?>
