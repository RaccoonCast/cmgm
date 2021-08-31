<html lang="en">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="includes/Map/ol.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="crossorigin=""></script>
  <?php
   $zoom = 14;
   include '../functions.php';

   if(isMobile()){
     $limit = "355";
   } else {
     $limit = "650";
   }
   include 'includes/DB-filter.php';
   if (@substr($back, 0, 4) == "Edit") {
     $back_url = $back;
   } elseif (@substr($back, 0, 4) == "Home") {
     $back_url = "../Home.php";
   } elseif (@substr($back, 0, 2) == "DB") {
     $back_url = "DB.php?latitude=" . $latitude . "&longitude=" . $longitude . "&carrier=" . $carrier;
   }
   ?>
</head>
<body class="body">
<div id="sidebar"><?php include "includes/Map/sidebar.php"?></div>
<div id="map"><div id="mapid"></div>
<?php if (!isset($marker_latitude)) { ?>
<button class="special_button" id="backButton"><div class="buttonContainer"><?php if(isMobile()) { echo "⬅"; } else {echo "🔙";} ?></div></button>
<button class="special_button" id="refreshButton"><div class="buttonContainer">🔃</div></button>
<?php } ?>
<script>
lat = <?php echo $latitude?>;
long = <?php echo $longitude?>;

<?php if (!isset($marker_latitude)) { ?>
  document.getElementById('refreshButton').addEventListener('click', () => location.reload());
  document.getElementById('backButton').addEventListener('click', () => location.replace("<?php echo @$back_url; ?>"));
<?php } ?>
function marker(latitude,longitude,status,id,url_suffix) {
  var customPopup = '<iframe frameBorder=\"0\" src=\"Map-popup.php?mp-id=' + id + '&url_suffix=' + url_suffix + '\">';
  L.marker([latitude,longitude], {icon: status }).bindPopup(customPopup,customOptions).addTo(mymap).on('click', function(e) {
    console.log(e.latlng.lat);
});
}

  var mymap = L.map('mapid').setView([<?php echo $latitude;?>,<?php echo $longitude;?>], <?php echo $zoom;?>);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
      maxZoom: 18,
      minZoom: 5,
      tileSize: 256,
      zoomOffset: 0,
      accessToken: 'pk.eyJ1IjoicmFjY29vbmNhc3QiLCJhIjoiY2s3YjZ0cDViMDM3ODNncnlwdWY5M2VudCJ9.X_icvui90_cQLuP3VjG7BA'
  }).addTo(mymap);

  function updateURL() {
      newLat = mymap.getCenter().lat;
      newLong = mymap.getCenter().lng;
      newZoom = mymap.getZoom()
      tags = "<?php echo @$url_suffix; ?>";

      var URI = "Map.php?latitude=" + newLat + "&longitude=" + newLong + "&zoom=" + newZoom + tags;
      console.log(URI);
      history.pushState("obj", "", URI);
      //location.reload(true);
  }

  function myTimer() {
    mymap.on('moveend', updateURL); {
       var bounds = mymap.getBounds();
    };
  }


  var myVar = setInterval(myTimer, 1);
  // var myVar2 = setInterval(refresher, 5000);

  function refresher() {
    location.reload(true);
  }

  // specify popup options
  var customOptions =
      {
      'className' : 'custom'
      }

 <?php
  if(isMobile()){
    include 'includes/map/iconsize-mobile.php';
  } else {
    include 'includes/map/iconsize-desktop.php';
  }


$database_only_load_nearby = ", (3959 * ACOS(COS(RADIANS($latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($longitude)) + SIN(RADIANS($latitude)) * SIN(RADIANS(latitude)))) AS DISTANCE";

$database_get_list = "id,latitude,longitude,status";

$sql = "SELECT DISTINCT $database_get_list $database_only_load_nearby FROM database_db $db_vars ORDER BY distance LIMIT $limit";
if (isset($_GET['showsql'])) echo $sql;
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

if (!empty($lat) && !empty($long) && !empty($status)) {?>
marker(<?php echo $lat?>,<?php echo $long?>,<?php echo $status?>,<?php echo $id?>);
<?php }
break;
            }
    }
}
?>
<?php
// Code for the marker on Form.
if (isset($marker_latitude)) echo "L.marker([$marker_latitude,$marker_longitude]).addTo(mymap);"; ?>
</script>
<?php if (!isset($marker_latitude))include "includes/footer.php"; ?>
</div>
</body>
</html>
