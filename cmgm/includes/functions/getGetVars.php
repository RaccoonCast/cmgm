<?php
// if latitude & longitude & carrier are set in URL bar create PHP variable with data
if (isset($_GET['refresh'])) { echo '<meta http-equiv="refresh" content="0;URL=../" /> '; }

// get latitude from URL
if (!empty($_GET['latitude'])) { $latitude = $_GET['latitude']; }
if (!empty($_GET['longitude'])) { $longitude = $_GET['longitude']; }
if (!empty($_GET['carrier'])) { $carrier = $_GET['carrier']; }

if (!empty($_GET['zip'])) { $zip = $_GET['zip']; }
if (!empty($_GET['state'])) { $state = $_GET['state']; }
if (!empty($_GET['city'])) { $city = $_GET['city']; }
if (!empty($_GET['address'])) { $address = $_GET['address']; }
if (!empty($_GET['data'])) { $data = $_GET['data']; }
if (!empty($_GET['conv_type'])) { $conv_type = $_GET['conv_type']; }
if (!isset($carrier)) if (isset($default_carrier)) { $carrier = $default_carrier; } else { $default_carrier = "ATT"; $carrier = $default_carrier; }

// get latitude from POST
if (!empty($_POST['latitude'])) { $latitude = $_POST['latitude']; }
if (!empty($_POST['longitude'])) { $longitude = $_POST['longitude']; }
if (!empty($_POST['carrier'])) { $carrier = $_POST['carrier']; }

if (!empty($_POST['zip'])) { $zip = $_POST['zip']; }
if (!empty($_POST['state'])) { $state = $_POST['state']; }
if (!empty($_POST['city'])) { $city = $_POST['city']; }
if (!empty($_POST['address'])) { $address = $_POST['address']; }
if (!empty($_POST['data'])) { $data = $_POST['data']; }
if (!empty($_POST['conv_type'])) { $conv_type = $_POST['conv_type']; }

@define ('SITE_ROOT', $_SERVER['DOCUMENT_ROOT']);
$SITE_ROOT = $_SERVER['DOCUMENT_ROOT'];
if (isset($_GET['goto'])) $goto = @$_GET['goto'];
if (isset($_POST['goto'])) $goto = @$_POST['goto'];
if (isset($_GET['data'])) $data = @$_GET['data'];
if (isset($_POST['data'])) $data = @$_POST['data'];
?>
