<?php
date_default_timezone_set('America/Los_Angeles');
include 'includes/functions/getGetVars.php';
include 'includes/functions/basic-functions.php';
if (!isset($doNotConnectToMySql)) {
    include 'includes/useridsys/native.php';
    include_once 'includes/functions/sqlpw.php';
}
include 'includes/functions/headhtml.php';
include 'includes/functions/common-debug-info.php';
?>
