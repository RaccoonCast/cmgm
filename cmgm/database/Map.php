<html lang="en">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="crossorigin=""></script>
  <?php
    function like_match($pattern, $subject)
    {
        $pattern = str_replace('%', '.*', preg_quote($pattern,'/'));
        return (bool) preg_match("/^{$pattern}$/i", $subject);
    }
   $zoom = 14;
     $allowGuests = "true";
   include '../functions.php';
   if (!isset($_GET['latitude'])) $latitude = $default_latitude;
   if (!isset($_GET['longitude'])) $longitude = $default_longitude;

   if(isMobile()){
     $limit = "300";
   } else {
     $limit = "530";
   }
   include 'includes/DB-filter-get.php';
   if (@substr($back, 0, 4) == "Edit") {
     $back_url = $back;
   } elseif (@substr($back, 0, 4) == "Home") {
     $back_url = "../Home.php";
   } elseif (@substr($back, 0, 2) == "DB") {
     $back_url = "DB.php?latitude=" . $latitude . "&longitude=" . $longitude . "&carrier=" . $carrier;
   } elseif (@substr($back, 0, 4) == "Search") {
     $back_url = "../Search.php";
   }
   ?>
</head>
<body class="body">
<div id="sidebar"><?php // include "includes/Map/sidebar.php"?></div>
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
      maxZoom: 19,
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
include 'includes/map/iconsize.php';

$database_only_load_nearby = ", (3959 * ACOS(COS(RADIANS($latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($longitude)) + SIN(RADIANS($latitude)) * SIN(RADIANS(latitude)))) AS DISTANCE";

$database_get_list = "id,carrier,latitude,longitude,cellsite_type,concealed,status,tags";

$sql = "SELECT DISTINCT $database_get_list $database_only_load_nearby FROM db $db_vars ORDER BY distance LIMIT $limit";
if (isset($_GET['showsql'])) echo "//" . $sql . PHP_EOL; // show SQL select query in Source Code (hackers only!!)
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {

  $colCount = 1;
    foreach ($row as $field => $value) {
      $sepCount = ($colCount++);

switch ($sepCount) {
  case 1:  $id = $value; break;
  case 2:  $pin_carrier = $value; break;
  case 3:  $lat = $value; break;
  case 4:  $long = $value; break;
  case 5:  $cellsite_type = $value; break;
  case 6:  $concealed = $value; break;
  case 7:  $status = $value; break;
  case 8:  $tags = $value;

if (@$pin_style == "celltype") {
  $status = NULL;
  if ($cellsite_type == "rooftop" && $concealed == "false") $status = "lightgray";
  if ($cellsite_type == "rooftop" && $concealed == "true") $status = "darkgray";
  if ($cellsite_type == "monopalm") $status = "lightgreen";
  if ($cellsite_type == "monopine") $status = "darkgreen";
  if ($cellsite_type == "misc-tree") $status = "darkgreen";
  if ($cellsite_type == "tower") $status = "tower";
  if (empty($status)) $status = "unknown";
  }
if (@$pin_style == "carrier" or !isset($carrier)) {
  $status = NULL;
  if ($pin_carrier == "T-Mobile") $status = "tmobile";
  if ($pin_carrier == "ATT") $status = "att";
  if ($pin_carrier == "Sprint") $status = "sprint";
  if ($pin_carrier == "Verizon") $status = "verizon";
  if (like_match('sprint_keep,%',$tags) == "TRUE" OR like_match('%,sprint_keep',$tags) == "TRUE" OR like_match('%,sprint_keep,%',$tags) == "TRUE" OR $tags == "sprint_keep") $status = "sprint_keep";
  if (empty($status)) $status = "unknown";
  }
if (@$pin_style != "basic") {
  if (like_match('unmapped,%',$tags) == "TRUE" OR like_match('%,unmapped',$tags) == "TRUE" OR like_match('%,unmapped,%',$tags) == "TRUE" OR $tags == "unmapped") $status = "unmapped";
  if (like_match('weird,%',$tags) == "TRUE" OR like_match('%,weird',$tags) == "TRUE" OR like_match('%,weird,%',$tags) == "TRUE" OR $tags == "weird") $status = "weird";
  if (like_match('wip,%',$tags) == "TRUE" OR like_match('%,wip',$tags) == "TRUE" OR like_match('%,wip,%',$tags) == "TRUE" OR $tags == "wip") $status = "wip";
  if (like_match('special,%',$tags) == "TRUE" OR like_match('%,special',$tags) == "TRUE" OR like_match('%,special,%',$tags) == "TRUE" OR $tags == "special") $status = "special";
}


?>marker(<?php echo $lat?>,<?php echo $long?>,<?php echo $status?>,<?php echo $id?>);
<?php
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
