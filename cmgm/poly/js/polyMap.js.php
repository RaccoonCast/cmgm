<script>
// Extract parameters from the URL
const params = new URLSearchParams(window.location.search);
const defaultLat = parseFloat(params.get('latitude')) || 34.201;
const defaultLng = parseFloat(params.get('longitude')) || -118.44;
const defaultZoom = parseInt(params.get('zoom')) || 13;
const plmn = params.get('plmn') || '0';

const mymap = L.map('map').setView([defaultLat, defaultLng], defaultZoom);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 19
}).addTo(mymap);



// Update the URL with map center and zoom
function updateURLFromMap() {
  const center = mymap.getCenter();
  const zoom = mymap.getZoom();
  const newUrl = new URL(window.location.href);
  newUrl.searchParams.set('latitude', center.lat.toFixed(6));
  newUrl.searchParams.set('longitude', center.lng.toFixed(6));
  newUrl.searchParams.set('zoom', zoom);
  newUrl.searchParams.set('plmn', plmn);
  history.replaceState(null, '', newUrl);
}

mymap.on('moveend zoomend', () => {
  updateURLFromMap();
  fetchAndRenderPolygons();
});

mymap.on('contextmenu', (event) => {
  const { lat, lng } = event.latlng;
  const coordinates = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;

  // Copy coordinates to clipboard
  navigator.clipboard.writeText(coordinates);
});

let drawnENBs = new Set();

function sortPointsClockwise(coordinatePairArray, pointsWithIndices) {
  const centroid = coordinatePairArray.reduce(
    (acc, point) => [acc[0] + point[0], acc[1] + point[1]],
    [0, 0]
  ).map(coord => coord / coordinatePairArray.length);

  return pointsWithIndices.sort((a, b) => {
    const angleA = Math.atan2(a.coords[1] - centroid[1], a.coords[0] - centroid[0]);
    const angleB = Math.atan2(b.coords[1] - centroid[1], b.coords[0] - centroid[0]);
    return angleA - angleB;
  });
}

async function fetchAndRenderPolygons() {
  const center = mymap.getCenter();
  const filterCells = '<?php echo (isset($_GET['filterCells']) && preg_match("/^[0-9]+(,[0-9]+)*$/", $_GET['filterCells'])) ? $_GET['filterCells'] : 'false'; ?>';
  const filterEnbs = '<?php echo (isset($_GET['filterEnbs']) && preg_match("/^([0-9]+)-([0-9]+)$/", $_GET['filterEnbs'])) ? $_GET['filterEnbs'] : 'false'; ?>';

  const showsql = '<?php echo isset($_GET['showsql']) ? $_GET['showsql'] : 'false'; ?>';
  const limit = '<?php echo isset($_GET['limit']) ? $_GET['limit'] : '175'; ?>';

  const useEnbAverage = '<?= isset($_GET['average']) && $_GET['average'] != "false" ? 'true' : 'false' ?>' === 'true';
  console.log('average:', useEnbAverage);

  console.log('passing dbfc', filterCells);

  let newApiUrl = `https://cmgm.us/api/cmgm/getPolies.php?latitude=${center.lat}&longitude=${center.lng}&plmn=${plmn}&filterCells=${filterCells}&filterEnbs=${filterEnbs}&showsql=${showsql}&limit=${limit}`

  if (useEnbAverage) {
    newApiUrl += '&average=true';
  }

  // try {
  const response = await fetch(newApiUrl);
  const data = await response.json();

  // Check for potential ?filterCells arg

  const enbGroups = {};


  for (const item of Object.values(data)) {
    // Check not already drawn
    if (!drawnENBs.has(item.enb)) {
      // Add to enbGroups if necessary
      if (!enbGroups[item.enb]) { enbGroups[item.enb] = []; }
      // Push coords to array
      if (useEnbAverage) {
        // latitude, longitude are available in base
        enbGroups[item.enb].push({
          coords: [item.latitude, item.longitude],
          cellId: 0,
          sectorId: '*',
          tac: item.tac
        })
      } else {
        // Push for each cell
        for (let sectorId of Object.keys(item.cells)) {
          enbGroups[item.enb].push({
            coords: [item.cells[sectorId][0], item.cells[sectorId][1]],
            cellId: 'unknown',
            sectorId: sectorId,
            tac: item.tac
          });
        }
      }
    }
  }


  for (const [enb, coords, tac] of Object.entries(enbGroups)) {

    // Filter polygons with fewer than three points
    const showIncompletePolygons = <?php echo(isset($_GET['showIncompletePolygons']) || isset($_GET['filterCells'])) ? 'true' : 'false'; ?>;
    if (coords.length < 3 && !(showIncompletePolygons || useEnbAverage)) continue; // Need at least 3 points to form polygon

    // Get from request points with indices and information attached
    const pointsWithIndices = coords.map((c, i) => ({ originalIndex: i, coords: c.coords, sector: c.sectorId, tac: c.tac }));

    // Sort points while maintaining their indices and information
    const sortedPoints = sortPointsClockwise(pointsWithIndices.map(el => el.coords), pointsWithIndices);//.map(p => p.coords);

    // Create polygon based on points alone
    const hidePolygons = <?php echo(isset($_GET['hidePolygons']) && $_GET['hidePolygons'] != 'false') ? 'true' : 'false'; ?>;
    const polygonPoints = sortedPoints.map(p => p.coords);
    if (!hidePolygons) {
      const polygon = L.polygon(polygonPoints, { color: '#<?php echo $accent_color; ?>', weight: 2 }).addTo(mymap);
    }

    drawnENBs.add(parseInt(enb));

    const hideLabels = <?php echo(isset($_GET['hideLabels']) && $_GET['hideLabels'] != 'false') ? 'true' : 'false'; ?>;
    const showTacs = <?php echo(isset($_GET['showTacs']) && $_GET['showTacs'] != 'false') ? 'true' : 'false'; ?>;



    function getLabel(pt) {

      let labelText = enb;

      if (!useEnbAverage) {
        labelText += `-${pt.sector}`;
      }

      // Add TAC if requested
      if (showTacs && pt.tac) {
        labelText += `\n(${pt.tac})`;
      }

      return labelText
    }

    // Iterate in sorted order
    if (!hideLabels) {
      sortedPoints.forEach(pt => {
        L.marker(pt.coords, { opacity: 0 })
          // Bind label at coordinate
          .bindTooltip(getLabel(pt), {
            permanent: true,
            direction: 'center',
            className: 'label-tooltip'
          })
          .addTo(mymap);
      });
    }
  }
  // } catch (e) {
  //   console.error('Error loading polygons:', e);
  // }
}

fetchAndRenderPolygons();
</script>