<?php
// basic basic functions
function redir($page,$time) {
echo '<meta http-equiv="Refresh" content="' . $time . '; url=' . $page . '"';
}

// The mobile detection function
function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

// SQL Database login info
$servername = 'mysql.cmgm.gq';
$username = 'cmgm';
$siteroot = $_SERVER['DOCUMENT_ROOT'];
$password = file_get_contents($siteroot . "/secret-sql-login.hiddenpass", true);
$dbname = 'cmgm';
$conn = mysqli_connect($servername, $username, $password, $dbname);
?>
