<?php
// if latitude & longitude & carrier are set in URL bar create PHP variable with data
if (isset($_GET['refresh'])) { echo '<meta http-equiv="refresh" content="0;URL=../" /> '; }
if (!empty($_GET['latitude'])) { $latitude = $_GET['latitude']; }
if (!empty($_GET['longitude'])) { $longitude = $_GET['longitude']; }
if (!empty($_GET['carrier'])) { $carrier = $_GET['carrier']; }
if (!empty($_GET['zip'])) { $zip = $_GET['zip']; }
if (!empty($_GET['state'])) { $state = $_GET['state']; }
if (!empty($_GET['city'])) { $city = $_GET['city']; }
if (!empty($_GET['address'])) { $address = $_GET['address']; }
if (!empty($_GET['data'])) { $data = $_GET['data']; }
if (!empty($_GET['conv_type'])) { $conv_type = $_GET['conv_type']; }
?>
