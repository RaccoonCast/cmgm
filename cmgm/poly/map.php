<?php
include "../functions.php";
?>
<!DOCTYPE html>
<html>
<head>
  <title>eNB Polygon Map</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <style>
    #map { height: 100vh; }
    body {
      margin: 0px;
      padding: 0px;
    }
  </style>
</head>
<body>
<div id="map"></div>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<?php include dirname(__FILE__) . '/js/polyMap.js.php' ?>
</body>
</html>
