<html lang="en">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
    integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
    crossorigin=""/>
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
    integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
    crossorigin=""></script><?php
   $zoom = 14;
   if (isset($_GET['zoom'])) $zoom = $_GET['zoom'];
   include '../functions.php';
   include 'includes/map/get-get-queries.php';
   if (empty($limit)) $limit = "250";
   // todo: sep limit for mobile/desktop
   ?>
</head>
<body class="body">
<div id="mapid"></div>
<script>
lat = <?php echo $latitude?>;
long = <?php echo $longitude?>;


function marker(latitude,longitude,status,id) {
  var customPopup = '<iframe frameBorder=\"0\" src=\"Map-popup.php?id=' + id + '\">';
  L.marker([latitude,longitude], {icon: status }).bindPopup(customPopup).addTo(mymap).on('click', function(e) {
    console.log(e.latlng.lat);
});
}

  var mymap = L.map('mapid').setView([<?php echo $latitude;?>,<?php echo $longitude;?>], <?php echo $zoom;?>);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
      maxZoom: 19,
      minZoom: 8,
      tileSize: 256,
      zoomOffset: 0,
      accessToken: 'pk.eyJ1IjoicmFjY29vbmNhc3QiLCJhIjoiY2s3YjZ0cDViMDM3ODNncnlwdWY5M2VudCJ9.X_icvui90_cQLuP3VjG7BA'
  }).addTo(mymap);

 <?php
  if(isMobile()){
    include 'includes/map/iconsize-mobile.php';
  } else {
    include 'includes/map/iconsize-desktop.php';
  }


$database_only_load_nearby = ", (3959 * ACOS(COS(RADIANS($latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($longitude)) + SIN(RADIANS($latitude)) * SIN(RADIANS(latitude)))) AS DISTANCE";

$database_get_list = "id,latitude,longitude,status";

$sql = "SELECT DISTINCT $database_get_list $database_only_load_nearby FROM database_db $sub1 $sub2 ORDER BY distance LIMIT $limit";

$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {

  $colCount = 1;
    foreach ($row as $field => $value) {
      $sepCount = ($colCount++);

switch ($sepCount) {
  case 1:  $id = $value; break;
  case 2:  $lat = $value; break;
  case 3:  $long = $value; break;
  case 4:  $status = $value;

?>
marker(<?php echo $lat?>,<?php echo $long?>,<?php echo $status?>,<?php echo $id?>);

<?php
break;
            }
    }
}
?>
</script>
</body>
</html>
