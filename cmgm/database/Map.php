<html lang="en">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <meta charset="utf-8">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <?php
  function like_match($pattern, $subject)
  {
    $pattern = str_replace('%', '.*', preg_quote($pattern, '/'));
    return (bool) preg_match("/^{$pattern}$/i", $subject);
  }
  $zoom = 14;
  $allowGuests = "true";
  include '../functions.php';
  if (!isset($_GET['latitude']))
    $latitude = $default_latitude;
  if (!isset($_GET['longitude']))
    $longitude = $default_longitude;

  if (isMobile()) {
    $limit = $map_map_mobile_pin_limit;
  } else {
    $limit = $map_map_pin_limit;
  }
  include 'includes/DB-filter-get.php';
  ?>
</head>

<body class="body">
  <div id="sidebar">
    <?php // include "includes/Map/sidebar.php" ?>
  </div>
  <div id="map">
    <div id="mapid"></div>
    <?php if (@$_GET['hideui'] !== "true") { ?>
      <button class="special_button" id="backButton">
        <div class="buttonContainer">
          <?php echo isMobile() ? "⬅" : "🔙"; ?>
        </div>
      </button>
      <button class="special_button" id="refreshButton">
        <div class="buttonContainer">🔃</div>
      </button> 
    <?php } ?>
    <?php if (isset($_GET['showPolyLink'])) { ?>
      <button class="special_button" id="openPolyButton" style="top: 79px;">
        <div class="buttonContainer">↗️</div>
      </button>
    <?php } ?>

    <!-- Query db for pins stuff -->
    <?php
    $database_only_load_nearby = ", (3959 * ACOS(COS(RADIANS($latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($longitude)) + SIN(RADIANS($latitude)) * SIN(RADIANS(latitude)))) AS DISTANCE";

    $database_get_list = "id,carrier,latitude,longitude,cellsite_type,concealed,status,tags";

    $sql = "SELECT DISTINCT $database_get_list $database_only_load_nearby FROM db WHERE 1=1 $db_vars ORDER BY distance LIMIT $limit";
    if (isset($_GET['showsql'])) echo "//" . $sql . PHP_EOL; // show SQL select query in Source Code (hackers only!!)
    $result = mysqli_query($conn, $sql);

    $resultArray = mysqli_fetch_all($result);
    ?>

    <!-- Start JS -->
    <script>
      /**
       * @param latLngInDeg array of arrays with latitude and longtitude
       *   pairs in degrees. e.g. [[latitude1, longtitude1], [latitude2
       *   [longtitude2] ...]
       *
       * @return array with the center latitude longtitude pairs in
       *   degrees.
       */
      function getLatLngCenter(t) { function n(t) { return 180 * t / Math.PI } function a(t) { return t * Math.PI / 180 } for (var h = 0, r = 0, e = 0, M = 0; M < t.length; M++) { var o = a(t[M][0]), c = a(t[M][1]); h += Math.cos(o) * Math.cos(c), r += Math.cos(o) * Math.sin(c), e += Math.sin(o) } var g = h / t.length, s = r / t.length, u = e / t.length, i = (c = Math.atan2(s, g), Math.sqrt(g * g + s * s)); return [n(o = Math.atan2(u, i)), n(c)] }


      // Define lat/lng
      lat = <?php echo $latitude ?>;
      long = <?php echo $longitude ?>;

      // Cast add event listeners for buttons
      <?php if ($_GET['hideui'] !== "true") { ?>
        document.getElementById('refreshButton').addEventListener('click', () => location.reload());
        document.getElementById('backButton').addEventListener('click', () => history.back());
      <?php } ?>

      // Create list of markers
      let markerList = [];
      // Cast function add markers 
      function marker(latitude, longitude, status, id) {
        var customPopup = '<iframe frameBorder=\"0\" src=\"Map-popup.php?id=' + id + '\">';
        const marker = L.marker([latitude, longitude], { icon: status }).bindPopup(customPopup, customOptions).addTo(mymap).on('click', function (e) {
          console.log(e.latlng.lat);
        });

        markerList.push(marker);

      }

      // Create list of coords
      let recordCoordList = [];


      // Get PHP 2D array of records
      const records = <?php echo json_encode($resultArray); ?>;

      // Sort records into JS 2D array
      for (let record of records) {
        const latitude = record[2];
        const longitude = record[3];

        recordCoordList.push([latitude, longitude]);
      }

      // Should we use coord cetner, or passed lat/lng?
      const useCoordCenter = <?php echo (isset($_GET['useCoordCenter']) or empty($_GET['latitude'])) ? 'true' : 'false'; ?>;

      // Get polygon center


      // const usePolygonCenter = <?php echo (isset($_GET['polygon']) or empty($_GET['latitude'])) ? 'true' : 'false'; ?>;

      // Define new center coords
      const centerCoords = useCoordCenter ? getLatLngCenter(recordCoordList) : [lat, long];


      var mymap = L.map('mapid', {
        center: centerCoords,
        zoom: <?php echo $zoom; ?>, //Default to passed in zoom
        zoomDelta: 0.888888, // Custom zoomDelta value
        zoomSnap: 0.00000000001,
        //wheelPxPerZoomLevel: 143,
      });

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
        history.replaceState("obj", "", URI);
        //location.reload(true);
      }

      function myTimer() {
        mymap.on('moveend', updateURL); {
          var bounds = mymap.getBounds();
        };
      }

      // Copy coords on right-click.
      mymap.on('contextmenu', (event) => {
            const { lat, lng } = event.latlng;
            const coordinates = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;

            // Copy coordinates to clipboard
            navigator.clipboard.writeText(coordinates);
        });

      // Create a custom keyboard shortcut for refreshing the map
      document.addEventListener('keydown', function (event) {
        if ((event.shiftKey || event.metaKey) && event.key.toLowerCase() === 'y') { // Use Ctrl/Cmd + Y
          updateURL();
          location.reload(); // Refresh the page
          event.preventDefault(); // Prevent the browser from performing the default action for the key combination
        }
      });

      var myVar = setInterval(myTimer, 1);
      // var myVar2 = setInterval(refresher, 5000);

      function refresher() {
        location.reload(true);
      }

      // specify popup options
      var customOptions =
      {
        'className': 'custom'
      }

      /* BEGIN CAST JS */
      // Function to extract query parameters from the URL
      function getQueryParams() {
          const queryString = window.location.search.slice(1);
          const params = {};
          queryString.split('&').forEach(pair => {
              const [key, value] = pair.split('=');
              params[decodeURIComponent(key)] = decodeURIComponent(value || '');
          });
          return params;
      }

      // Function to sort points in counterclockwise order around their centroid
      function sortPointsClockwise(coordinatePairArray, pointsWithIndices) {
          // Calculate the centroid
          const centroid = coordinatePairArray.reduce(
              (acc, point) => [acc[0] + point[0], acc[1] + point[1]],
              [0, 0]
          ).map(coord => coord / coordinatePairArray.length);
          
          // Sort points based on angle relative to the centroid
          return pointsWithIndices.sort((a, b) => {
              const angleA = Math.atan2(a.coords[1] - centroid[1], a.coords[0] - centroid[0]);
              const angleB = Math.atan2(b.coords[1] - centroid[1], b.coords[0] - centroid[0]);
              return angleA - angleB;
          });
      }

      // Extract parameters from the URL
      const params = getQueryParams();

      // Parse polygon points from the URL
      let polygonPoints = [];
      if (params.polygon) {
          try {
              polygonPoints = params.polygon.split(',').map(Number).reduce((acc, value, index, array) => {
                  if (index % 2 === 0) {
                      acc.push([value, array[index + 1]]);
                  }
                  return acc;
              }, []).filter(point => point.length === 2);


              // Store points with original indices
              let polygonPointsWithIndices = [];
              for (let coordPair of polygonPoints) {
                polygonPointsWithIndices.push({
                  originalIndex: polygonPoints.indexOf(coordPair),
                  coords: coordPair
                });
              }

              // Sort the points to ensure they form a proper polygon
              polygonPointsWithIndicesSorted = sortPointsClockwise(polygonPoints, polygonPointsWithIndices);

              // re-remove indices
              polygonPoints = [];
              for (let i of polygonPointsWithIndicesSorted) {
                polygonPoints.push(i.coords);
              }
              

              console.log(polygonPoints, polygonPointsWithIndices)
          } catch (error) { 
              console.error('Error parsing polygon points:', error);
              polygonPoints = [];
          }
      }

      // Parse labels for the polygon vertices
      const polygonLabels = params.polygonlabels ? params.polygonlabels.split(',') : [];

      // Initialize polygon vertex list
      let polygonVertexMarkerList = [];

      // Check if there are enough valid points to draw a polygon
      if (polygonPoints.length >= 1) {
          // Add the polygon to the map
          L.polygon(polygonPoints, { color: '#<?php echo $accent_color; ?>', weight: 2 }).addTo(mymap);
      
          // Add numbered markers at each vertex with optional labels
          // Use sorted array with original indices, so that the labels match
          polygonPointsWithIndicesSorted.forEach((point) => {
              const label = polygonLabels[point.originalIndex] // Use original index
              const marker = L.marker(point.coords, { opacity: 0 })
                  .bindTooltip(`${label}`, { permanent: true, direction: 'center', className: 'label-tooltip' })
                  .addTo(mymap);
              // add to list
              polygonVertexMarkerList.push(marker);
          });
      } else {
          console.log('No valid polygon points provided in URL parameters.');
      }


      /* BEGIN PHP */
      <?php

      include 'includes/map/iconsize.php';

      foreach ($resultArray as $row) {

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

            // Carrier pin styles
            if (@$pin_style == "carrier" or !isset($carrier)) {
                $status = NULL;
                if ($pin_carrier == "T-Mobile") $status = "tmobile";
                if ($pin_carrier == "ATT") $status = "att";
                if ($pin_carrier == "Sprint") $status = "sprint";
                if ($pin_carrier == "Verizon") $status = "verizon";
                if ($pin_carrier == "Dish") $status = "dish";
                if (like_match('sprint_keep,%',$tags) == "TRUE" OR like_match('%,sprint_keep',$tags) == "TRUE" OR like_match('%,sprint_keep,%',$tags) == "TRUE" OR $tags == "sprint_keep") $status = "sprint_keep";
                if (empty($status)) $status = "unknown";
           }
              if (@$pin_style != "basic") {
                if (like_match('decom,%',$tags) == "TRUE" OR like_match('%,decom',$tags) == "TRUE" OR like_match('%,decom,%',$tags) == "TRUE" OR $tags == "decom") $status = "decom";
                if (like_match('unmapped,%',$tags) == "TRUE" OR like_match('%,unmapped',$tags) == "TRUE" OR like_match('%,unmapped,%',$tags) == "TRUE" OR $tags == "unmapped") $status = "unmapped";
                if (like_match('weird,%',$tags) == "TRUE" OR like_match('%,weird',$tags) == "TRUE" OR like_match('%,weird,%',$tags) == "TRUE" OR $tags == "weird") $status = "weird";
                if (like_match('wip,%',$tags) == "TRUE" OR like_match('%,wip',$tags) == "TRUE" OR like_match('%,wip,%',$tags) == "TRUE" OR $tags == "wip") $status = "wip";
                if (like_match('special,%',$tags) == "TRUE" OR like_match('%,special',$tags) == "TRUE" OR like_match('%,special,%',$tags) == "TRUE" OR $tags == "special") $status = "special";
           }

              // End of PHP, generate marker and add to map.
              ?>
              marker(<?php echo $lat ?>, <?php echo $long ?>, <?php echo $status ?>, <?php echo $id ?>);
              <?php
              break;
          }
        }
      }

      if (isset($marker_latitude))
        echo "L.marker([$marker_latitude,$marker_longitude]).addTo(mymap);";
      ?>

      const skipPolyZoom = "<?php echo json_encode(isset($_GET['skipPolyZoom'])); ?>" === 'true';

      // Zoom to fit all records (if applicable)
      if (useCoordCenter) {
        const markerGroup = new L.featureGroup(markerList);
        mymap.fitBounds(markerGroup.getBounds().pad(0.5));
      // Zoom to fit all vertices of polygon (if applicable)
      } else if (polygonVertexMarkerList && !skipPolyZoom) {
        const markerGroup = new L.featureGroup(polygonVertexMarkerList);
        mymap.fitBounds(markerGroup.getBounds().pad(0.5));
      }

    </script>
    <?php if ($_GET['hideui'] !== "true")
      include "includes/footer.php"; ?>
  </div>
</body>

</html>