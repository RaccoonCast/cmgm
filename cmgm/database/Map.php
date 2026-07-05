<?php
$allowGuests = true;
include '../functions.php';
?>
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
  include '../includes/functions/headhtml.php';
  if (!isset($_GET['latitude']))
    $latitude = $default_latitude;
  if (!isset($_GET['longitude']))
    $longitude = $default_longitude;

  if (isMobile()) {
    $limit = $map_map_mobile_pin_limit;
  } else {
    $limit = $map_map_pin_limit;
  }
  if (isset($_GET['hideui']) && !isset($_GET['marker_latitude'])) {
    echo "<style>@media (min-width: 1151px) and (max-width: 1190px), (max-width: 1010px) {.leaflet-top{top:80px!important;}</style>"; // Move controls down if on Poly form within 1195px rule. 
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
    <div class="dropdown-content" id="polyInfoButton_content"></div>

    <!-- Query db for pins stuff -->
    <?php
    $database_only_load_nearby = ", (3959 * ACOS(COS(RADIANS($latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($longitude)) + SIN(RADIANS($latitude)) * SIN(RADIANS(latitude)))) AS DISTANCE";

    $database_get_list = "id,carrier,latitude,longitude,status,tags";

    $sql = "SELECT DISTINCT $database_get_list $database_only_load_nearby FROM db WHERE 1=1 $db_vars ORDER BY distance LIMIT $limit";
    if (isset($_GET['showsql']))
      echo "//" . $sql . PHP_EOL; // show SQL select query in Source Code (hackers only!!)
    $result = mysqli_query($conn, $sql);

    $resultArray = mysqli_fetch_all($result, MYSQLI_ASSOC);
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

      // Create list of markers
      let markerList = [];
      // Cast function add markers 
      function marker(latitude, longitude, status, id) {
        var customPopup = '<iframe class=\"iframe_box\" frameBorder=\"0\" src=\"Map-popup.php?id=' + id + '\">';
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
        const latitude = record.latitude;
        const longitude = record.longitude;

        recordCoordList.push([latitude, longitude]);
      }

      // Should we use coord cetner, or passed lat/lng?
      const useCoordCenter = <?php echo (isset($_GET['useCoordCenter']) or empty($_GET['latitude'])) ? 'true' : 'false'; ?>;

      // Get polygon center


      // const usePolygonCenter = <?php echo (isset($_GET['polygon']) or empty($_GET['latitude'])) ? 'true' : 'false'; ?>;

      // Define new center coords
      const centerCoords = useCoordCenter ? getLatLngCenter(recordCoordList) : [lat, long];


      var mymap = L.map('mapid', {
        zoomControl: false,
        center: centerCoords,
        zoom: <?php echo $zoom; ?>, //Default to passed in zoom
        zoomDelta: 0.888888, // Custom zoomDelta value
        zoomSnap: 0.00000000001,
        //wheelPxPerZoomLevel: 143,
      });

      // Add original zoom control
      L.control.zoom({ position: 'topleft' }).addTo(mymap);
      <?php
          // Set a default fallback URL
          $polyLinkUrl = '#'; 
          
          // Process the URL if the parameter exists and is valid
          if (isset($_GET['showPolyLink']) && strlen($_GET['showPolyLink']) > 1) {
              $decoded = base64_decode($_GET['showPolyLink']);
              $polyLinkUrl = preg_replace('/&hidePolyForm.*$/', '', $decoded);
          }
      ?>
      // json_encode safely formats the string for JavaScript
      // Output will look like: const POLY_LINK_URL = "http://example.com/map";
      const POLY_LINK_URL = <?php echo json_encode($polyLinkUrl); ?>;
    
		  // Create custom control
        const polyInfoButton = L.Control.extend({
          onAdd(map) {
            const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-added-button poly-info-control');

            container.innerHTML = '<a href="#" class=""> <span style="font-size: 22px;">▲</span></a>'; // Or any HTML content
            container.id = 'polyInfoButton';

            // Prevent map interaction when clicking the control
            L.DomEvent.disableClickPropagation(container);

            return container;
          }
        });

        const polyMapButton = L.Control.extend({
          onAdd(map) {
            const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-added-button');
            container.innerHTML = '<a href="#"><span style="font-size:22px;">⯁</span></a>';
            container.id = 'polyMapButton';
            L.DomEvent.disableClickPropagation(container);
            return container;
          }
        });

        const refreshButton = L.Control.extend({
          onAdd(map) {
            const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-added-button');
            container.innerHTML = '<a href="#"><span style="font-size:22px;">🔃</span></a>';
            container.id = 'refreshButton';
            L.DomEvent.disableClickPropagation(container);
            return container;
          }
        });

        const backButton = L.Control.extend({
          onAdd(map) {
            const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-added-button');
            container.innerHTML = '<a href="#"><span style="font-size:22px;"><?= isMobile() ? "⬅" : "🔙" ?></span></a>';
            container.id = 'backButton';
            L.DomEvent.disableClickPropagation(container);
            return container;
          }
        });

        const openPolyButton = L.Control.extend({
          onAdd(map) {
            const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-added-button');
            
            // Check if we have a real URL to determine the target attribute
            const targetAttr = POLY_LINK_URL !== '#' ? 'target="_blank"' : '';
            
            container.innerHTML = `<a href="${POLY_LINK_URL}" ${targetAttr}><span style="font-size:27px;">↗</span></a>`;
            container.id = 'openPolyButton';
            L.DomEvent.disableClickPropagation(container);
            
            return container;
          }
        });
        const url_params = new URLSearchParams(location.search);

        if (!url_params.has('hideui') && !url_params.has('polygon')) { // Only show refresh button on regular Map.php
            mymap.addControl(new refreshButton({ position: 'topleft' }));
            // mymap.addControl(new BackControl({ position: 'topleft' })); // Deprecated : Back button is generally useless.
        }

        if (url_params.has('hideui') && !url_params.has('showPolyLink') && !url_params.has('marker_latitude') && url_params.has('polygon')) { // Only show poly's cell info button on Poly when a polygon loaded.
          mymap.addControl(new polyInfoButton({ position: 'topleft' })); // Poly Cells Info Button
        }
        
        if (!url_params.has('marker_latitude')) { // Hide open Poly Map button solely on Edit's iframe.
          mymap.addControl(new polyMapButton({ position: 'topleft' }));
        }

        if (url_params.has('polygon') && url_params.has('marker_latitude')) { // Only show open poly button on edit iframe and only *after* polygon has loaded.
          mymap.addControl(new openPolyButton({ position: 'topleft' }));
        }

      // Cast add event listeners for buttons
      document.getElementById('refreshButton')?.addEventListener('click', () => location.reload());
      document.getElementById('backButton')?.addEventListener('click', () => history.back());
      document.getElementById('polyMapButton')?.addEventListener('click', () => {
          const center = mymap.getCenter();
          const zoom = mymap.getZoom();

          const params = new URLSearchParams(window.parent.location.search);

          let carrier = params.get('carrier') || '';

          const hasBang = carrier.startsWith('!');
          if (hasBang) {
              carrier = carrier.substring(1);
          }

          const plmnMap = {
              'T-Mobile': '310260',
              'AT&T': '310410',
              'Verizon': '311480',
              'Dish': '313340'
          };

          let plmn = plmnMap[carrier];

          // Fall back to plmn_1, plmn_2, ... if no carrier mapping exists
          if (!plmn) {
              const plmns = [...params.entries()]
            .filter(([key, value]) => /^plmn_\d+$/.test(key) && value)
            .map(([, value]) => value);

              plmn = [...new Set(plmns)].join(',');
          }

          let url =
              `/poly/Map.php?latitude=${center.lat}` +
              `&longitude=${center.lng}` +
              `&zoom=${zoom}`;

          if (plmn) {
              url += `&plmn=${hasBang ? '!' : ''}${plmn}`;
          }

          window.parent.location.href = url;
      });


      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
        maxZoom: 19,
        minZoom: 3,
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
        // console.log(URI);
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

          console.log('pp:', polygonPoints);

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

        // Merge duplicates and assign labels
        function combineDuplicatesAndLabel(arr) {
          const map = new Map();
          for (const item of arr) {
            let itemCoords = JSON.stringify(item.coords);
            if (!map.has(itemCoords)) {
              map.set(itemCoords, { coords: itemCoords, label: [polygonLabels[item.originalIndex]] });
            } else {
              map.get(itemCoords).label.push(polygonLabels[item.originalIndex]);
            }
          }
          return Array.from(map.values());
        }
        let polygonPointsWithLabels = combineDuplicatesAndLabel(polygonPointsWithIndicesSorted)
          .map(p => { p.coords = JSON.parse(p.coords); return p });

        // Add numbered markers at each vertex with optional labels
        // Use sorted array with original indices, so that the labels match
        polygonPointsWithLabels.forEach((point) => {
          const label = point.label//polygonLabels[point.originalIndex] // Use original index
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
              // Set $status (icon type) to default CMGM-db status to allow for Green/Red icons incase pin_style isn't per $carrier
              $status = $row['status'];
              if (@$pin_style == "carrier" or !isset($carrier)) {

                // Set $status (icon type) to be based on the carrier, remove - for t-mobile and strtolower to address case sensitivity.
                $status = strtolower(str_replace('-', '', $row['carrier']));
                
                // Address icon being set by tags (like decom or sprkeep) 
                if (@$pin_style != "basic" && @$_GET['tags'] !== "unmapped") {
                   $tags = array_map('trim', explode(',', $row['tags']));
                   if (in_array('sprint_keep', $tags)) $status .= "_spk"; // Amend sprkeep marker incase of a Sprint R&R w/ another carrier
                   $status = array_values(array_intersect(['decom','unmapped'], $tags))[0] ?? $status; // Overwrite previous status with decom/unmapped if either.
                }
              }
            
              // End of PHP, generate marker and add to map.
              ?>
              marker(<?= $row['latitude']?>,<?= $row['longitude']?>,<?= $status ?>,<?= $row['id']?>);
              <?php
          }
      
          // Add default leaflet pin marker where prescribed, primarily/solely for CMGM Edit.
          if (isset($marker_latitude) && isset($marker_longitude)) echo "L.marker([$marker_latitude,$marker_longitude]).addTo(mymap);";
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
    <?php if (!isset($_GET['hideui']) || $_GET['hideui'] !== 'true')
      include "includes/footer.php"; ?>
  </div>
</body>
</html>