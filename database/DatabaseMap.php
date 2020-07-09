<html lang="en">
<head>
  <meta charset="utf-8">
   <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin=""/>
   <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
   <?php include '../functions.php';
   $zoom = 14;
   if (isset($_GET['zoom'])) $zoom = $_GET['zoom'];
   ?>
</head>
<body class="body">
<div id="mapid"></div>
<script>
lat = <?php echo $latitude?>;
long = <?php echo $longitude?>;

  var mymap = L.map('mapid').setView([<?php echo $latitude;?>,<?php echo $longitude;?>], <?php echo $zoom;?>);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
      maxZoom: 19,
      minZoom: 4.5,
      tileSize: 256,
      zoomOffset: 0,
      accessToken: 'pk.eyJ1IjoicmFjY29vbmNhc3QiLCJhIjoiY2s3YjZ0cDViMDM3ODNncnlwdWY5M2VudCJ9.X_icvui90_cQLuP3VjG7BA'
  }).addTo(mymap);

 <?php
  if(isMobile()){
    include 'includes/databasemap/iconsize-mobile.php';
  } else {
    include 'includes/databasemap/iconsize-desktop.php';
  }

  include 'includes/databasemap/get-get-queries.php';

$sql = "SELECT * FROM database_db $sub1 $sub2";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {

  $colCount = 1;
    foreach ($row as $field => $value) {
      $sepCount = ($colCount++);

switch ($sepCount) {
  case 1: $row_id = $value; break;
  case 2: $id = $value; break;
  case 3: $carrier = $value; break;
  case 4: $lat = $value; break;
  case 5: $long = $value; break;
  case 6: $city = $value; break;
  case 7: $zip = $value; break;
  case 8: $state = $value; break;
  case 9: $address = $value; break;
  case 10: $bio = $value; break;
  case 11: $evidence_score = $value; break;
  case 12: $evidence_text = $value; break;
  case 13: $permit_cellsite = $value; break;
  case 14: $permit_suspected_carrier = $value; break;
  case 15: $trails_match = $value; break;
  case 16: $other_carriers_dont = $value; break;
  case 17: $antennas_match_carrier = $value; break;
  case 18: $cellmapper_triangulation = $value; break;
  case 19: $image_evidence = $value; break;
  case 20: $verified_by_visit = $value; break;
  case 21: $carrier_multiple = $value;
?>
var customPopup = "<?php $url = "databasemap-popup.php?row_id=$row_id&limit=1"; echo '<iframe frameBorder=\"0\" src=\"'.$url.'\">';?>";
L.marker([<?php echo $lat;?>,<?php echo $long;?>], {icon: icon}).bindPopup(customPopup).addTo(mymap); <?php
break;
            }
    }
}
?>
mymap.addEventListener('mousemove', function(ev) {
   lat_1 = ev.latlng.lat;
   lng_1 = ev.latlng.lng;
});

mymap.on("contextmenu", function (event) {
  console.log("Coordinates: " + event.latlng.toString());
  var url = "database-form.php?latitude=" + lat_1 + "&longitude=" + lng_1;
  var markerPopup = window.location.href = url;

  L.marker(event.latlng).addTo(mymap);
});
</script>
</body>
</html>
