<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>

	<style>
		html, body {
			height: 100%;
			margin: 0;
		}
    #map {
      padding: 0em;
      margin: 0em;
    }
    #side_menu {
      padding: 0em;
      margin: 0em;
    }
	</style>


</head>
<body> <?php include "../functions.php";  ?>
<div id="map" style="display: inline-block; width: 100%; max-width: 100%; height: 100%;"></div>
<script>
<?php include '../database/includes/map/iconsize-v2.php'; ?>

// Create the Leaflet map
var map = L.map('map');
map.on('load', addPins);
map.setView([34.12468878369871, -118.25637949350477], 19);

// Add a base layer to the map
L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
	maxNativeZoom: 19,
	maxZoom: 25,
  attribution:
    '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

// Create an object to store the map pins
var pinLocations = {};




// Utility function to add a map pin to the map, if it does not already exist
function addPin(lat, lng, name, carrier_name) {
	// Check if the pin already exists on the map
		// If the pin does not already exist, create a new map pin
		var customPopup = '<iframe frameBorder=\"0\" src=\"../database/Map-popup.php?id=' + name + '\">';
		var customOptions = { 'className' : 'custom' }
		let status;
		let carrier = carrier_name;
		let tags;
		pinStyle = "carrier";

			if (carrier === "T-Mobile") status = tmobileIcon;
			if (carrier === "ATT") status = attIcon;
			if (carrier === "Sprint") status = sprintIcon;
			if (carrier === "Verizon") status = verizonIcon;
			if (carrier === "Dish") status = dishIcon;
			// if (tags.includes("sprint_keep")) status = "sprint_keep";
			if (!status) status = "unknown";

		var newPin = L.marker(L.latLng(lat, lng), { title: name, icon: tmobileIcon }).addTo(map).bindPopup(customPopup,customOptions);
	}

	function addPins() {
		location.latitude = 34.12468878369871;
		location.longitude = -118.25637949350477;

	addPin(location.latitude, location.longitude, "1", "T-Mobile");
	addPin(location.latitude + 0.00006, location.longitude + 0.00006, "2", "T-Mobile");
	addPin(location.latitude + 0.00006, location.longitude - 0.00006, "3", "T-Mobile");
	addPin(location.latitude + 0.00006, location.longitude, "4", "T-Mobile");
	}


</script>
</body>
</html>
