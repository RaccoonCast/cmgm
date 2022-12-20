<?php header('Location: ../database/Map.php'); ?>
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
<body>
<div id="side_menu" style="display: inline-block; width: 20%; max-width: 100%;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Eget mauris pharetra et ultrices neque ornare aenean euismod elementum. Nisl nunc mi ipsum faucibus vitae aliquet nec. Pharetra sit amet aliquam id diam maecenas ultricies mi eget. Venenatis tellus in metus vulputate eu scelerisque. Sed lectus vestibulum mattis ullamcorper velit sed ullamcorper morbi. Sodales ut etiam sit amet nisl purus in. Sem et tortor consequat id porta nibh venenatis cras sed. Nullam vehicula ipsum a arcu cursus vitae congue mauris. Mi quis hendrerit dolor magna eget est lorem ipsum dolor.

Neque vitae tempus quam pellentesque nec nam aliquam. Volutpat consequat mauris nunc congue nisi vitae suscipit tellus. Eu consequat ac felis donec et. Id consectetur purus ut faucibus. Urna cursus eget nunc scelerisque viverra mauris. Id leo in vitae turpis massa sed. Adipiscing at in tellus integer. Id volutpat lacus laoreet non curabitur gravida arcu. Nisl tincidunt eget nullam non nisi est sit amet facilisis. Purus non enim praesent elementum. Elementum nisi quis eleifend quam adipiscing. A diam maecenas sed enim ut sem viverra aliquet eget. Tempor nec feugiat nisl pretium fusce id velit ut tortor. Nulla porttitor massa id neque aliquam vestibulum morbi blandit cursus. Dui accumsan sit amet nulla facilisi morbi tempus iaculis urna. Id venenatis a condimentum vitae sapien pellentesque habitant morbi. Mauris pharetra et ultrices neque ornare aenean. Elit eget gravida cum sociis natoque.

Netus et malesuada fames ac turpis egestas sed. Felis imperdiet proin fermentum leo vel. Quam quisque id diam vel quam. Malesuada fames ac turpis egestas sed tempus. Fames ac turpis egestas maecenas pharetra convallis posuere morbi leo. Amet massa vitae tortor condimentum lacinia quis vel. Ut sem nulla pharetra diam sit amet. Eget gravida cum sociis natoque penatibus et magnis dis. Justo nec ultrices dui sapien. Amet consectetur adipiscing elit pellentesque habitant morbi tristique. Ac odio tempor orci dapibus ultrices in. Morbi leo urna molestie at elementum eu.

Laoreet sit amet cursus sit amet. Turpis nunc eget lorem dolor. Ipsum dolor sit amet consectetur adipiscing elit ut aliquam. Lacinia quis vel eros donec ac odio tempor orci dapibus. Sed vulputate mi sit amet mauris commodo quis imperdiet. Et ultrices neque ornare aenean euismod elementum nisi quis. Justo donec enim diam vulputate ut. Cursus eget nunc scelerisque viverra mauris in aliquam sem. Mauris pellentesque pulvinar pellentesque habitant. Volutpat ac tincidunt vitae semper. Lacus suspendisse faucibus interdum posuere lorem ipsum dolor sit amet. Habitant morbi tristique senectus et netus et malesuada fames. Risus feugiat in ante metus dictum at tempor commodo ullamcorper. Donec et odio pellentesque diam volutpat commodo sed. Vulputate sapien nec sagittis aliquam malesuada bibendum. Purus in massa tempor nec feugiat nisl pretium fusce. Aenean et tortor at risus. Nisl rhoncus mattis rhoncus urna neque.

Mauris vitae ultricies leo integer malesuada. A diam sollicitudin tempor id eu nisl nunc mi. Ultricies integer quis auctor elit. Arcu non odio euismod lacinia at quis risus. Sed viverra tellus in hac habitasse. Ut morbi tincidunt augue interdum velit euismod in pellentesque massa. Odio facilisis mauris sit amet massa vitae tortor condimentum lacinia. Pharetra massa massa ultricies mi quis hendrerit dolor. In hendrerit gravida rutrum quisque non tellus orci. Nisi vitae suscipit tellus mauris a diam. Aliquam id diam maecenas ultricies mi. Ullamcorper velit sed ullamcorper morbi tincidunt ornare massa. Tellus mauris a diam maecenas sed enim. Ultrices in iaculis nunc sed. Eleifend donec pretium vulputate sapien nec sagittis aliquam malesuada bibendum. Vel pretium lectus quam id leo in. Enim nulla aliquet porttitor lacus luctus accumsan tortor. Id volutpat lacus laoreet non curabitur gravida arcu ac tortor. Fringilla ut morbi tincidunt augue interdum velit euismod in. Risus in hendrerit gravida rutrum quisque non tellus orci.</div><div id="map" style="display: inline-block; width: 80%; max-width: 100%; height: 100%;"></div>
<script>
const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const latitude = urlParams.get('latitude');
const longitude = correctLongitude(urlParams.get('longitude'));
const zoom = urlParams.get('zoom');


	var map = L.map('map').setView([latitude, longitude], zoom);

	var tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
    maxZoom: 19,
    minZoom: 5,
    tileSize: 256,
    zoomOffset: 0,
    accessToken: 'pk.eyJ1IjoicmFjY29vbmNhc3QiLCJhIjoiY2s3YjZ0cDViMDM3ODNncnlwdWY5M2VudCJ9.X_icvui90_cQLuP3VjG7BA'
	}).addTo(map);

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

  function updateURL() {
      newLat = map.getCenter().lat;
      newLong = correctLongitude(map.getCenter().lng);
      newZoom = map.getZoom()
      tags = "<?php echo @$url_suffix; ?>";

      var URI = "?latitude=" + newLat + "&longitude=" + newLong + "&zoom=" + newZoom + tags;
      history.pushState("obj", "", URI);
      //location.reload(true);
  }

    function myTimer() {

      console.log(latitude);
      console.log(longitude);

      map.on('moveend', updateURL); {
        var bounds = map.getBounds();
      };
    }

    var myVar = setInterval(myTimer, 1000);
</script>



</body>
</html>
