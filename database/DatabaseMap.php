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
  case 1:  $row_id = $value; break;
  case 2:  $date_added = $value; break;
  case 3:  $id_1 = $value; break;
  case 4:  $id_2 = $value; break;
  case 5:  $id_3 = $value; break;
  case 6:  $id_4 = $value; break;
  case 7:  $id_5 = $value; break;
  case 8:  $carrier = $value; break;
  case 9:  $lat = $value; break;
  case 10:  $long = $value; break;
  case 11:  $city = $value; break;
  case 12:  $zip = $value; break;
  case 13:  $state = $value; break;
  case 14:  $address = $value; break;
  case 15:  $bio = $value; break;
  case 16:  $evidence_score = $value; break;
  case 17:  $evidence_link = $value; break;
  case 18:  $photo_link = $value; break;
  case 19:  $attached_file_link = $value; break;
  case 20:  $permit_cellsite = $value; break;
  case 21:  $permit_suspected_carrier = $value; break;
  case 22:  $trails_match = $value; break;
  case 23:  $other_carriers_dont = $value; break;
  case 24:  $antennas_match_carrier = $value; break;
  case 25:  $cellmapper_triangulation = $value; break;
  case 26:  $image_evidence = $value; break;
  case 27:  $verified_by_visit = $value; break;
  case 28:  $sector_split_match = $value; break;
  case 29:  $contact_permit_carrier = $value; break;
  case 30:  $archival_antenna_addition = $value; break;
  case 31:  $only_reasonable_location = $value; break;
  case 32:  $carrier_multiple = $value;
?>
var customPopup = "<?php $url = "databasemap-popup.php?row_id=$row_id&limit=17&sql=$sql"; echo '<iframe frameBorder=\"0\" src=\"'.$url.'\">';?>";
L.marker([<?php echo $lat;?>,<?php echo $long;?>], {icon: icon}).bindPopup(customPopup).addTo(mymap); <?php
break;
            }
    }
}
?>
</script>
</body>
</html>
