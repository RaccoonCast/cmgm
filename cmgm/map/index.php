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

	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
</head>
<body>
	<?php
	include "../functions.php";
	 ?>
<div id="side_menu" style="display: inline-block; width: 20%; max-width: 100%;">

Neque vitae tempus quam pellentesque nec nam aliquam. Volutpat consequat mauris nunc congue nisi vitae suscipit tellus. Eu consequat ac felis donec et. Id consectetur purus ut faucibus. Urna cursus eget nunc scelerisque viverra mauris. Id leo in vitae turpis massa sed. Adipiscing at in tellus integer. Id volutpat lacus laoreet non curabitur gravida arcu. Nisl tincidunt eget nullam non nisi est sit amet facilisis. Purus non enim praesent elementum. Elementum nisi quis eleifend quam adipiscing. A diam maecenas sed enim ut sem viverra aliquet eget. Tempor nec feugiat nisl pretium fusce id velit ut tortor. Nulla porttitor massa id neque aliquam vestibulum morbi blandit cursus. Dui accumsan sit amet nulla facilisi morbi tempus iaculis urna. Id venenatis a condimentum vitae sapien pellentesque habitant morbi. Mauris pharetra et ultrices neque ornare aenean. Elit eget gravida cum sociis natoque.

Netus et malesuada fames ac turpis egestas sed. Felis imperdiet proin fermentum leo vel. Quam quisque id diam vel quam. Malesuada fames ac turpis egestas sed tempus. Fames ac turpis egestas maecenas pharetra convallis posuere morbi leo. Amet massa vitae tortor condimentum lacinia quis vel. Ut sem nulla pharetra diam sit amet. Eget gravida cum sociis natoque penatibus et magnis dis. Justo nec ultrices dui sapien. Amet consectetur adipiscing elit pellentesque habitant morbi tristique. Ac odio tempor orci dapibus ultrices in. Morbi leo urna molestie at elementum eu.

Laoreet sit amet cursus sit amet. Turpis nunc eget lorem dolor. Ipsum dolor sit amet consectetur adipiscing elit ut aliquam. Lacinia quis vel eros donec ac odio tempor orci dapibus. Sed vulputate mi sit amet mauris commodo quis imperdiet. Et ultrices neque ornare aenean euismod elementum nisi quis. Justo donec enim diam vulputate ut. Cursus eget nunc scelerisque viverra mauris in aliquam sem. Mauris pellentesque pulvinar pellentesque habitant. Volutpat ac tincidunt vitae semper. Lacus suspendisse faucibus interdum posuere lorem ipsum dolor sit amet. Habitant morbi tristique senectus et netus et malesuada fames. Risus feugiat in ante metus dictum at tempor commodo ullamcorper. Donec et odio pellentesque diam volutpat commodo sed. Vulputate sapien nec sagittis aliquam malesuada bibendum. Purus in massa tempor nec feugiat nisl pretium fusce. Aenean et tortor at risus. Nisl rhoncus mattis rhoncus urna neque.

</div><div id="map" style="display: inline-block; width: 80%; max-width: 100%; height: 100%;"></div>
<script>
<?php include '../database/includes/map/iconsize-v2.php'; ?>
const apiUrl = "https://cmgm.us/api/cmgm/getTowersV2.php";
const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const latitude = urlParams.get('latitude');
const longitude = correctLongitude(urlParams.get('longitude'));
const zoom = urlParams.get('zoom');

// Create the Leaflet map
var map = L.map('map');
map.on('load', work);
map.setView([latitude, longitude], zoom);
map.on('moveend', () => {
  work();
  updateURL();
});

// Add a base layer to the map
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
		maxNativeZoom: 19,
		maxZoom: 30,
		minZoom: 5,
		tileSize: 256,
		zoomOffset: 0,
		accessToken: 'pk.eyJ1IjoicmFjY29vbmNhc3QiLCJhIjoiY2s3YjZ0cDViMDM3ODNncnlwdWY5M2VudCJ9.X_icvui90_cQLuP3VjG7BA'
}).addTo(map);

// Create an object to store the map pins
var pinLocations = {};

// Add an event listener to the map to update the pins when the map is moved
function work() {
  // Get the current zoom level of the map
  var zoom = map.getZoom();
  // Only make the API request if the zoom level is greater than 8
  if (zoom > 1) {
    // Get the current latitude and longitude of the map center
    var lat = map.getCenter().lat;
    var lng = map.getCenter().lng;

    // Download map pin locations from the API
	fetch(`${apiUrl}?latitude=${lat}&longitude=${lng}`)
    .then(response => response.json())
    .then(data => {
      // Loop through the map pin locations
      data.forEach(location => {
        // Add the map pin to the map, if it does not already exist
        addPin(location.latitude, location.longitude, location.name, location.carrier);
      });
      // Remove map pins that are not in the current map view
      unloadOutOfBoundsPins();
		});
    }
}

function updateURL() {
		newLat = map.getCenter().lat;
		newLong = correctLongitude(map.getCenter().lng);
		newZoom = map.getZoom()
		// tags = "<?php echo @$url_suffix; ?>";

		var URI = "?latitude=" + newLat + "&longitude=" + newLong + "&zoom=" + newZoom;
		history.pushState("obj", "", URI);
		//location.reload(true);
}

// Utility function to add a map pin to the map, if it does not already exist
function addPin(lat, lng, name, carrier_name) {
  // Check if the pin already exists on the map
  if (!map.hasLayer(pinLocations[name])) {
    // If the pin does not already exist, create a new map pin
		var customPopup = '<iframe frameBorder=\"0\" src=\"../database/Map-popup.php?mp-id=' + name + '\">';
		var customOptions = { 'className' : 'custom' }
		let status;
		let carrier = carrier_name;
		let tags;
		pinStyle = "carrier";
			if (pinStyle === "celltype") {
  		status = null;
  		if (cellsiteType === "rooftop" && concealed === "false") status = lightgrayIcon;
  		if (cellsiteType === "rooftop" && concealed === "true") status = darkgrayIcon;
  		if (cellsiteType === "monopalm") status = lightgreenIcon;
  		if (cellsiteType === "monopine") status = darkgreenIcon;
  		if (cellsiteType === "misc-tree") status = darkgreenIcon;
  		if (cellsiteType === "tower") status = towerIcon;
  		if (!status) status = unknownIcon;
		}

		if (pinStyle === "carrier" || !carrier) {
  		status = null;
  		if (carrier === "T-Mobile") status = tmobileIcon;
  		if (carrier === "ATT") status = attIcon;
  		if (carrier === "Sprint") status = sprintIcon;
  		if (carrier === "Verizon") status = verizonIcon;
  		if (carrier === "Dish") status = dishIcon;
  		// if (tags.includes("sprint_keep")) status = "sprint_keep";
  		if (!status) status = unknownIcon;
		}

		// if (pinStyle !== "basic") {
  		// if (tags.includes("unmapped")) status = "unmapped";
  		// if (tags.includes("weird")) status = "weird";
  		// if (tags.includes("wip")) status = "wip";
  		// if (tags.includes("special")) status = "special";
		// }
    var newPin = L.marker(L.latLng(lat, lng), { title: name, icon: status }).addTo(map).bindPopup(customPopup,customOptions);
    // Add the new map pin to the pinLocations object
    pinLocations[name] = newPin;
  }
}

// Utility function to unload map pins that are not in the current map view
function unloadOutOfBoundsPins() {
  // Get the bounds of the current map view
  var bounds = map.getBounds().pad(0.5);

  // Loop through all of the map pins in the pinLocations object
  for (var name in pinLocations) {
    // Check if the map pin is within the bounds of the current map view
    if (!bounds.contains(pinLocations[name].getLatLng())) {
      // If the map pin is not within the bounds, remove it from the map
      map.removeLayer(pinLocations[name]);
    }
	}
}

function correctLongitude(value) {
	value = value % 360;
	if (value < -180) {
			value += 360;
	}
	if (value > 180) {
			value -= 360;
	}
	return value;
}

</script>



</body>
</html>
