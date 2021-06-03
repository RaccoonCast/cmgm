<?php
define ('SITE_ROOT', $_SERVER['DOCUMENT_ROOT']);
$SITE_ROOT = $_SERVER['DOCUMENT_ROOT'];
include 'includes/functions/basic-functions.php';
if (!isset($_GET['noCheck'])) {
  include 'includes/useridsys/native.php';
} else {
  include SITE_ROOT . "/includes/functions/sqlpw.php";
  $debug_flag = "off";
}
include 'includes/functions/getGetVars.php';
include 'includes/functions/headhtml.php';
// use \/ for testing lat,long reqs
// include 'js/consoleLogLatLong.js.php';
include 'includes/functions/common-debug-info.php';
?>
