<?php
if(!isset($address)) $address = null;
if(!isset($city)) $city = null;
if(!isset($state)) $state = null;
if(!isset($zip)) $zip = null;
echo ' Latitude: ' . $latitude;
echo '<br> Longitude: ' . $longitude;
echo '<br> Address: '. $address . ', ' . $city . ', ' . $state . ' ' . $zip;
?>
