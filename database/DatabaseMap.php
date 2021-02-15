<html lang="en">
<head>
  <meta charset="utf-8">
   <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin=""/>
   <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
   <?php
   $zoom = 14;
   include '../functions.php';
   include 'includes/databasemap/get-get-queries.php';
   if (empty($limit)) $limit = "250";
   // todo: sep limit for mobile/desktop
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


$database_only_load_nearby = ", (3959 * ACOS(COS(RADIANS($latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($longitude)) + SIN(RADIANS($latitude)) * SIN(RADIANS(latitude)))) AS DISTANCE";

$database_get_list = "row_id,latitude,longitude,status";

$sql = "SELECT DISTINCT $database_get_list $database_only_load_nearby FROM database_db $sub1 $sub2 ORDER BY distance LIMIT $limit";

$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {

  $colCount = 1;
    foreach ($row as $field => $value) {
      $sepCount = ($colCount++);

switch ($sepCount) {
  case 1:  $row_id = $value; break;
  case 2:  $lat = $value; break;
  case 3:  $long = $value; break;
  case 4:  $status = $value;

?>
var customPopup = "<?php echo '<iframe frameBorder=\"0\" src=\"databasemap-popup.php?row_id='.$row_id.'\">';?>";
L.marker([<?php echo $lat;?>,<?php echo $long;?>], {icon: <?php echo $verified ?>Verified}).bindPopup(customPopup).addTo(mymap); <?php
break;
            }
    }
}
?>
</script>
</body>
</html>
