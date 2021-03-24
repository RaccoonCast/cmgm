<?php
// SQL Database login info
$servername = 'mysql.cmgm.gq';
$db_username = 'cmgm';
$siteroot = $_SERVER['DOCUMENT_ROOT'];
$password = file_get_contents($siteroot . "\secret-sql-login.hiddenpass", true);
$dbname = 'cmgm';
$conn = mysqli_connect($servername, $db_username, $password, $dbname);
?>
