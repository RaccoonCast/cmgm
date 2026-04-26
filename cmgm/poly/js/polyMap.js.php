<script>
// Extract parameters from the URL
const params = new URLSearchParams(window.location.search);
const defaultLat = parseFloat(params.get('latitude')) || 34.201;
const defaultLng = parseFloat(params.get('longitude')) || -118.44;
const defaultZoom = parseInt(params.get('zoom')) || 13;
const plmn = params.get('plmn') || '0';
const rat = params.get('rat') || 'LTE';

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
  newUrl.searchParams.set('rat', rat);
  history.replaceState(null, '', newUrl);
}

mymap.on('moveend zoomend', () => {
  updateURLFromMap();
  fetchAndRenderPolygons();
});

mymap.on('tooltipopen', e => {
  const el = e.tooltip.getElement();
  el.addEventListener('click', () => {
    navigator.clipboard.writeText(e.tooltip.getContent());
  });
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
  const filterTac = '<?php echo (isset($_GET['filterTac']) && preg_match("/^([0-9]+)$/", $_GET['filterTac'])) ? $_GET['filterTac'] : 'false'; ?>';

  const rat = '<?php echo (isset($_GET['rat']) && preg_match("/[E-r]/", $_GET['rat'])) ? $_GET['rat'] : 'LTE'; ?>';

  const showsql = '<?php echo isset($_GET['showsql']) ? $_GET['showsql'] : 'false'; ?>';
  const limit = '<?php echo isset($_GET['limit']) ? $_GET['limit'] : '175'; ?>';

  const useEnbAverage = '<?= isset($_GET['average']) && $_GET['average'] != "false" ? 'true' : 'false' ?>' === 'true';
  console.log('average:', useEnbAverage);

  console.log('passing dbfc', filterCells);
  const bounds = mymap.getBounds();
  console.log(bounds);

  let apiUrl = `https://cmgm.us/api/poly/getPolyEnbs.php?boundsNELatitude=${bounds.getNorthEast().lat}&boundsNELongitude=${bounds.getNorthEast().lng}&boundsSWLatitude=${bounds.getSouthWest().lat}&boundsSWLongitude=${bounds.getSouthWest().lng}`;

  if (useEnbAverage) {
    apiUrl += '&average=true';
  }

  const response = await fetch(apiUrl);
  const data = await response.json();

  const enbGroups = {};
  
  // Flatten the data since it's grouped inside PLMN keys
  const allEnbs = Object.values(data).flat();

  for (const item of allEnbs) {
    const enbId = item.enb; 

    // Check not already drawn
    if (!drawnENBs.has(enbId)) {
      // Add to enbGroups if necessary
      if (!enbGroups[enbId]) { enbGroups[enbId] = []; }

      const lat = item.latitude;
      const lon = item.longitude;

      if (useEnbAverage) {
        // If averaging, we only need to grab the coords from the first cell we see for this eNB
        if (enbGroups[enbId].length === 0) {
          enbGroups[enbId].push({
            coords: [lat, lon],
            cellId: 0,
            sectorId: '*',
            tac: item.tac
          });
        }
      } else {
        // Since the API now gives us one row per cell, we just push it directly!
        enbGroups[enbId].push({
          coords: [lat, lon], 
          cellId: item.cell_id, 
          sectorId: item.cell,  
          tac: item.tac
        });
      }
    }
  }


for (const [enb, coords, tac] of Object.entries(enbGroups)) {

  // Filter polygons with fewer than three points
  const hideIncompletePolygons = <?php echo(isset($_GET['hideIncompletePolygons'])) ? 'true' : 'false'; ?>;
  if (hideIncompletePolygons && coords.length < 3 && !(useEnbAverage)) continue;

  // Get points with extra info
  const pointsWithIndices = coords.map((c, i) => ({
    originalIndex: i,
    coords: c.coords,
    sector: c.sectorId,
    tac: c.tac
  }));

  // Sort points
  const sortedPoints = sortPointsClockwise(
    pointsWithIndices.map(el => el.coords),
    pointsWithIndices
  );

  // Draw polygon
  const hidePolygons = <?php echo(isset($_GET['hidePolygons']) && $_GET['hidePolygons'] != 'false') ? 'true' : 'false'; ?>;
  const polygonPoints = sortedPoints.map(p => p.coords);
  if (!hidePolygons) {
    L.polygon(polygonPoints, { color: '#<?php echo $accent_color; ?>', weight: 2 }).addTo(mymap);
  }

  drawnENBs.add(parseInt(enb));

  const hideLabels = <?php echo(isset($_GET['hideLabels']) && $_GET['hideLabels'] != 'false') ? 'true' : 'false'; ?>;
  const showTacs = <?php echo(isset($_GET['showTacs']) && $_GET['showTacs'] != 'false') ? 'true' : 'false'; ?>;

  function getLabel(pt) {
    let labelText = enb;
    if (!useEnbAverage) labelText += `-${pt.sector}`;
    if (showTacs && pt.tac) labelText += `\n(${pt.tac})`;
    return labelText;
  }

  // Add markers with clickable labels
  if (!hideLabels) {
    sortedPoints.forEach(pt => {
      const labelText = getLabel(pt);

      const marker = L.marker(pt.coords, { opacity: 0 }).addTo(mymap);

      // Bind permanent tooltip
      marker.bindTooltip(labelText, {
        permanent: true,
        direction: 'center',
        className: 'label-tooltip'
      });

      const baseENB = labelText.split('-')[0];

      // Make tooltip clickable
      const tooltipEl = marker.getTooltip().getElement();
      tooltipEl.style.pointerEvents = 'auto';
      tooltipEl.style.cursor = 'pointer';
      tooltipEl.addEventListener('click', () => {
        navigator.clipboard.writeText(baseENB)
          .then(() => console.log('Copied:', baseENB))
          .catch(err => console.error('Copy failed:', err));
      });
    });
  }
}

}

fetchAndRenderPolygons();
</script>