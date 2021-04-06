<?php
if(!isset($address)) $address = null;
if(!isset($city)) $city = null;
if(!isset($state)) $state = null;
if(!isset($zip)) $zip = null;
echo ' Latitude: ' . $latitude;
echo '<br> Longitude: ' . $longitude;
if (isset($data)) echo '<br> Search Query: ' . $data;
if (isset($city)) echo '<br> Address: '. $address . ', ' . $city . ', ' . $state . ' ' . $zip;
?>
