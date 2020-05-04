<html lang="en">
<head>
  <meta charset="utf-8">
   <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
   <?php include '../functions.php';
   if (isset($_GET['latitude'])) $latitude = $_GET['latitude'];
   if (isset($_GET['longitude'])) $longitude = $_GET['longitude'];
   ?>
</head>
<body class="body">
<div id="mapid"></div>
<script>
lat = <?php echo $latitude?>;
long = <?php echo $longitude?>;

  var mymap = L.map('mapid').setView([<?php echo $latitude;?>,<?php echo $longitude;?>], 13);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
      maxZoom: 19,
      minZoom: 3.5,
      tileSize: 512,
      zoomOffset: -1,
      accessToken: 'pk.eyJ1IjoicmFjY29vbmNhc3QiLCJhIjoiY2s3YjZ0cDViMDM3ODNncnlwdWY5M2VudCJ9.X_icvui90_cQLuP3VjG7BA'
  }).addTo(mymap);

  var icon = L.icon({
      iconUrl: '../images/red.png',
      iconSize: [20, 20], // size of the icon
      popupAnchor: [0, -15]
  });
  var marker = L.marker([lat, long]).addTo(mymap);

<?php
$conn = mysqli_connect($servername, $username, $password, $dbname);
$sql = "SELECT * FROM findlater";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {

  $colCount = 1;
    foreach ($row as $field => $value) {
      $sepCount = ($colCount++);

switch ($sepCount) {
  case 1:
      $row_id = $value;
      break;
case 2:
    $id = $value;
    break;
case 3:
    $carrier = $value;
    break;
case 4:
    $type = $value;
    break;
case 5:
    $lat = $value;
    break;
case 6:
    $long = $value; ?>
var customPopup = "<?php $url = "findlatermap-popup.php?row_id=$row_id"; echo '<iframe frameBorder=\"0\" src=\"'.$url.'\">';?>";
L.marker([<?php echo $lat;?>,<?php echo $long;?>], {icon: icon}).bindPopup(customPopup).addTo(mymap);

<?php
case 7:
    $firstseen = $value;
    break;
case 8:
    $bands = $value;
    break;
case 9:
    $city = $value;
    break;
case 10:
    $zip = $value;
    break;
case 11:
    $state = $value;
    break;
case 12:
    $address = $value;
    break;
case 13:
    $bio = $value;
    break;
            }
    }
}
?>

</script>
</body>
</html>
