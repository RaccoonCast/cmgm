<?php
// SQL Database login info
$servername = 'mysql.cmgm.gq';
$username = 'cmgm';
$siteroot = $_SERVER['DOCUMENT_ROOT'];
$password = file_get_contents($siteroot . "/secret-sql-login.hiddenpass", true);
$dbname = 'cmgm';
$conn = mysqli_connect($servername, $username, $password, $dbname);
?>
