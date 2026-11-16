<?php
$allowGuests = true;
include __DIR__ . '/../functions.php';

$zoom = isset($_GET['zoom']) && is_numeric($_GET['zoom']) ? (float) $_GET['zoom'] : 14;
$latitude = isset($_GET['latitude']) && is_numeric($_GET['latitude']) ? (float) $_GET['latitude'] : (float) $default_latitude;
$longitude = isset($_GET['longitude']) && is_numeric($_GET['longitude']) ? (float) $_GET['longitude'] : (float) $default_longitude;
$limit = isset($_GET['limit']) && is_numeric($_GET['limit'])
  ? max(1, min(5000, (int) $_GET['limit']))
  : (int) (isMobile() ? $map_map_mobile_pin_limit : $map_map_pin_limit);
$viewport_batch_size = isset($_GET['limit']) && is_numeric($_GET['limit'])
  ? max(1, min(25000, (int) $_GET['limit']))
  : (isMobile() ? 250 : 400);
$pin_size = isset($_GET['pin_size']) && is_numeric($_GET['pin_size'])
  ? max(8, min(80, (int) $_GET['pin_size']))
  : 20;

$polyLinkUrl = '#';
if (isset($_GET['showPolyLink']) && strlen($_GET['showPolyLink']) > 1) {
  $decodedPolyLink = base64_decode($_GET['showPolyLink'], true);
  if ($decodedPolyLink !== false) {
    $polyLinkUrl = preg_replace('/&hidePolyForm.*$/', '', $decodedPolyLink);
  }
}

$footerThemes = [
  'black' => ['border' => '#2D2C2C', 'background' => '#111111'],
  'original' => ['border' => '#a6a6a4', 'background' => '#dededc'],
  'pink' => ['border' => '#c400c9', 'background' => '#f986fc'],
  'win32' => ['border' => '#868686', 'background' => '#bab8b8']
];
$footerTheme = $footerThemes[$theme] ?? $footerThemes['black'];
$showMapFilterUi = !isset($_GET['hideui']) || $_GET['hideui'] !== 'true';

$filename_for_css = 'Map';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <?php include __DIR__ . '/../includes/functions/headhtml.php'; ?>
  <?php if ($showMapFilterUi): ?>
    <!-- Use Poly Map's actual UI styles rather than a visual approximation. -->
    <link rel="stylesheet" href="/poly/styles/Map/<?= htmlspecialchars($theme, ENT_QUOTES) ?>.css">
    <link rel="stylesheet" href="/poly/styles/<?= htmlspecialchars($theme, ENT_QUOTES) ?>.css">
    <link rel="stylesheet" href="/poly/styles/Map/desktop.css" media="(min-width: 681px)">
    <link rel="stylesheet" href="/poly/styles/desktop.css" media="(min-width: 681px)">
    <link rel="stylesheet" href="/poly/styles/Map/mobile.css" media="(max-width: 680px)">
    <link rel="stylesheet" href="/poly/styles/mobile.css" media="(max-width: 680px)">
    <link rel="stylesheet" href="/poly/styles/Map/main.css">
    <link rel="stylesheet" href="/poly/styles/main.css">
    <link rel="stylesheet" href="styles/filter-ui.css?v=<?= filemtime(__DIR__ . '/styles/filter-ui.css') ?>">
  <?php endif; ?>
  <!-- Keep the Leaflet height chain local to this page. External theme CSS may be cached independently. -->
  <style>
    html, body, #map, #mapid { width: 100%; height: 100%; }
    html, body { margin: 0; }
    footer {
      border-top: 2px solid <?= htmlspecialchars($footerTheme['border'], ENT_QUOTES) ?>;
      background-color: <?= htmlspecialchars($footerTheme['background'], ENT_QUOTES) ?>;
    }
    .leaflet-added-button { margin-top: 5px !important; }
    /* Override the retired WIP offset even when an older map stylesheet is cached. */
    #polyMapButton { top: auto !important; }
    .leaflet-control a,
    .leaflet-control a:visited,
    .leaflet-control a:hover,
    .leaflet-control a:active {
      color: var(--accent-color) !important;
      text-decoration: none !important;
      filter: saturate(60%);
    }
    #footerContainer footer { z-index: 1000; }
  </style>
  <?php if (isset($_GET['hideui']) && !isset($_GET['marker_latitude'])): ?>
    <style>@media (min-width:1151px) and (max-width:1190px),(max-width:1010px){.leaflet-top{top:80px!important}}</style>
  <?php endif; ?>
</head>
<body class="body">
  <?php if ($showMapFilterUi) include __DIR__ . '/includes/filter-ui.php'; ?>
  <div id="map">
    <div id="mapid"></div>
    <div class="dropdown-content" id="polyInfoButton_content"></div>
  </div>

  <script>
    const initialCenter = [<?= json_encode($latitude) ?>, <?= json_encode($longitude) ?>];
    const initialZoom = <?= json_encode($zoom) ?>;
    let requestLimit = <?= json_encode($limit) ?>;
    let viewportBatchSize = <?= json_encode($viewport_batch_size) ?>;
    let pinRadius = Math.max(3, (<?= json_encode($pin_size) ?> / 2 - 1));
    const defaultRequestLimit = requestLimit;
    const defaultViewportBatchSize = viewportBatchSize;
    const useCoordCenter = <?= (isset($_GET['useCoordCenter']) || empty($_GET['latitude'])) ? 'true' : 'false' ?>;
    let pinStyle = <?= json_encode($_GET['pin_style'] ?? 'carrier') ?>;
    let useCarrierIcons = pinStyle === 'carrier';
    let useConcealedIcons = pinStyle === 'concealed';
    let requestedTags = <?= json_encode($_GET['tags'] ?? null) ?>;
    const polyLinkUrl = <?= json_encode($polyLinkUrl) ?>;
    const skipPolyZoom = <?= isset($_GET['skipPolyZoom']) ? 'true' : 'false' ?>;

    const solidPin = color => Object.freeze({ type: 'solid', color });
    const splitPin = (left, right) => Object.freeze({ type: 'split', left, right });
    const snowballPin = Object.freeze({ type: 'snowball' });
    const pinAppearances = {
      unverified: solidPin('#FF0000'),
      verified: solidPin('#4ABE00'),
      weird: solidPin('#0FA7FF'),
      unmapped: snowballPin,
      decom: solidPin('#696969'),
      wip: solidPin('#38FF43'),
      tmobile: solidPin('#FF00FF'),
      verizon: solidPin('#FF0000'),
      dish: solidPin('#FF9707'),
      att: solidPin('#0FA7FF'),
      sprint: solidPin('#FFFB00'),
      unknown: snowballPin,
      tmobile_spk: splitPin('#FF00FF', '#FFFB00'),
      att_spk: splitPin('#0FA7FF', '#FFFB00'),
      verizon_spk: splitPin('#FF0000', '#FFFB00'),
      dish_spk: splitPin('#FF9707', '#FFFB00'),
      sprint_spk: solidPin('#FFFB00'),
      concealed_true: solidPin('#4ABE00'),
      concealed_false: solidPin('#FF0000')
    };

    const map = L.map('mapid', {
      zoomControl: false,
      preferCanvas: true,
      center: initialCenter,
      zoom: initialZoom,
      zoomDelta: 0.888888,
      zoomSnap: 0.00000000001
    });
    // Some older parent scripts refer to the legacy global name.
    const mymap = map;

    // All database pins share one canvas. This avoids thousands of image-backed
    // DOM markers while retaining per-pin hit testing and popup behavior.
    L.Canvas.include({
      _updateCmgmPin(layer) {
        if (!this._drawing || layer._empty()) return;

        const context = this._ctx;
        const point = layer._point;
        const radius = layer._radius;
        const appearance = layer._pinAppearance || snowballPin;

        context.save();
        // A cheap offset silhouette approximates the soft shadow from the old
        // PNG pins without invoking canvas shadowBlur for every live marker.
        context.beginPath();
        context.arc(point.x + 1.25, point.y + 1.5, radius + 0.75, 0, Math.PI * 2);
        context.fillStyle = 'rgba(0, 0, 0, 0.35)';
        context.fill();

        context.beginPath();
        context.arc(point.x, point.y, radius, 0, Math.PI * 2);

        if (appearance.type === 'split') {
          context.save();
          context.clip();
          context.fillStyle = appearance.left;
          context.fillRect(point.x - radius, point.y - radius, radius, radius * 2);
          context.fillStyle = appearance.right;
          context.fillRect(point.x, point.y - radius, radius, radius * 2);
          context.restore();
        } else if (appearance.type === 'snowball') {
          context.fillStyle = '#A8A8A8';
          context.fill();
          context.beginPath();
          context.arc(point.x, point.y, radius * 0.72, 0, Math.PI * 2);
          context.fillStyle = '#FFFFFF';
          context.fill();
        } else {
          context.fillStyle = appearance.color;
          context.fill();
        }

        context.beginPath();
        context.arc(point.x, point.y, radius, 0, Math.PI * 2);
        context.lineWidth = 2;
        context.strokeStyle = '#111111';
        context.stroke();
        context.restore();
      }
    });

    const CmgmCanvasPin = L.CircleMarker.extend({
      initialize(latlng, options = {}) {
        this._pinAppearance = options.pinAppearance || snowballPin;
        L.CircleMarker.prototype.initialize.call(this, latlng, options);
      },

      setPinAppearance(appearance) {
        if (this._pinAppearance !== appearance) {
          this._pinAppearance = appearance;
          this.redraw();
        }
        return this;
      },

      _updatePath() {
        this._renderer._updateCmgmPin(this);
      },

      _getPopupAnchor() {
        return [0, -(this._radius + 2)];
      }
    });

    const pinRenderer = L.canvas({ padding: 0.5, pane: 'markerPane' });

    L.control.zoom({ position: 'topleft' }).addTo(map);
    const baseMapLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
      maxNativeZoom: 19,
      maxZoom: 19,
      minZoom: 3,
      tileSize: 256,
      zoomOffset: 0
    });

    const pinLocations = new Map();
    const persistentInitialPinIds = new Set();
    const outOfBoundsCleanupThreshold = 2000;
    let initialPinBatchCaptured = false;
    let activePinRequest = null;
    let requestSequence = 0;
    let viewportTimer = null;
    let lastCompleteViewportBounds = null;
    let initialLoadComplete = false;

    function normalizeLongitude(value) {
      let longitude = Number(value) % 360;
      if (longitude < -180) longitude += 360;
      if (longitude > 180) longitude -= 360;
      return longitude;
    }

    function pinAppearanceForRecord(record) {
      let iconName = String(record.status || 'unknown').toLowerCase().replace(/[^a-z0-9_]/g, '');

      if (useCarrierIcons) {
        iconName = String(record.carrier || 'unknown').toLowerCase().replace(/-/g, '').replace(/[^a-z0-9_]/g, '');
        const tags = String(record.tags || '').split(',').map(tag => tag.trim()).filter(Boolean);
        if (requestedTags !== 'unmapped') {
          if (tags.includes('sprint_keep')) iconName += '_spk';
          if (tags.includes('decom')) iconName = 'decom';
          else if (tags.includes('unmapped')) iconName = 'unmapped';
        }
      } else if (useConcealedIcons) {
        iconName = 'concealed_' + String(record.concealed);
        const tags = String(record.tags || '').split(',').map(tag => tag.trim()).filter(Boolean);
        if (requestedTags !== 'unmapped') {
          if (tags.includes('unmapped')) iconName = 'unmapped';
        }
      }

      return pinAppearances[iconName] || snowballPin;
    }

    function addOrUpdatePin(record) {
      const id = String(record.id);
      if (record.latitude === null || record.longitude === null || record.latitude === '' || record.longitude === '') return null;
      const latitude = Number(record.latitude);
      const longitude = Number(record.longitude);
      if (!Number.isFinite(latitude) || !Number.isFinite(longitude)) return null;

      const existing = pinLocations.get(id);
      const appearance = pinAppearanceForRecord(record);
      if (existing) {
        const current = existing.getLatLng();
        if (current.lat !== latitude || current.lng !== longitude) existing.setLatLng([latitude, longitude]);
        existing.setPinAppearance(appearance);
        if (!map.hasLayer(existing)) existing.addTo(map);
        return existing;
      }

      const popup = `<iframe class="iframe_box" title="CMGM record ${id}" frameborder="0" src="Map-popup.php?id=${encodeURIComponent(id)}"></iframe>`;
      const marker = new CmgmCanvasPin([latitude, longitude], {
        renderer: pinRenderer,
        pane: 'markerPane',
        radius: pinRadius,
        pinAppearance: appearance,
        interactive: true,
        bubblingMouseEvents: false
      }).bindPopup(popup, { className: 'custom' }).addTo(map);
      pinLocations.set(id, marker);
      return marker;
    }

    function unloadOutOfBoundsPins(invalidateCoverageIfRemoved = false) {
      if (pinLocations.size <= outOfBoundsCleanupThreshold) return;

      const retainedBounds = map.getBounds().pad(0.15);
      const center = map.getCenter();
      const removalLimit = pinLocations.size - outOfBoundsCleanupThreshold;
      const candidates = [];

      for (const [id, marker] of pinLocations) {
        if (persistentInitialPinIds.has(id)) continue;
        if (!retainedBounds.contains(marker.getLatLng())) {
          candidates.push({
            id,
            marker,
            distance: map.distance(center, marker.getLatLng())
          });
        }
      }

      candidates.sort((a, b) => b.distance - a.distance);
      let removedPin = false;
      for (const { id, marker } of candidates.slice(0, removalLimit)) {
        map.removeLayer(marker);
        pinLocations.delete(id);
        removedPin = true;
      }
      if (invalidateCoverageIfRemoved && removedPin) lastCompleteViewportBounds = null;
    }

    function buildPinRequest(mode, requestBounds = null) {
      const url = new URL('pins.php', window.location.href);
      const currentParams = new URLSearchParams(window.location.search);
      currentParams.forEach((value, key) => url.searchParams.append(key, value));
      url.searchParams.set('mode', mode);
      url.searchParams.set('limit', mode === 'initial' ? requestLimit : viewportBatchSize);

      const center = map.getCenter();
      url.searchParams.set('centerLat', center.lat);
      url.searchParams.set('centerLon', normalizeLongitude(center.lng));

      if (mode === 'viewport') {
        const bounds = requestBounds || map.getBounds().pad(0.1);
        url.searchParams.set('boundsNELat', Math.min(90, bounds.getNorth()));
        url.searchParams.set('boundsNELon', bounds.getEast());
        url.searchParams.set('boundsSWLat', Math.max(-90, bounds.getSouth()));
        url.searchParams.set('boundsSWLon', bounds.getWest());
      }
      return url;
    }

    function viewportCoveredByCompleteRequest() {
      if (!lastCompleteViewportBounds) return false;
      const viewport = map.getBounds();
      return lastCompleteViewportBounds.contains(viewport.getNorthWest())
        && lastCompleteViewportBounds.contains(viewport.getSouthEast());
    }

    function nextAnimationFrame() {
      return new Promise(resolve => requestAnimationFrame(resolve));
    }

    async function renderPinBatch(records, sequence) {
      const loadedMarkers = [];
      const chunkSize = 60;
      for (let offset = 0; offset < records.length; offset += chunkSize) {
        if (sequence !== requestSequence) return [];
        for (const record of records.slice(offset, offset + chunkSize)) {
          const marker = addOrUpdatePin(record);
          if (marker) loadedMarkers.push(marker);
        }
        if (offset + chunkSize < records.length) await nextAnimationFrame();
      }
      return loadedMarkers;
    }

    async function loadPins(mode = 'viewport') {
      if (mode === 'viewport' && viewportCoveredByCompleteRequest()) {
        if (activePinRequest) {
          activePinRequest.abort();
          activePinRequest = null;
          requestSequence++;
        }
        unloadOutOfBoundsPins(true);
        return [];
      }

      if (activePinRequest) activePinRequest.abort();
      const controller = new AbortController();
      const sequence = ++requestSequence;
      const preserveLoadedBatch = !initialPinBatchCaptured;
      activePinRequest = controller;
      const requestBounds = mode === 'viewport' ? map.getBounds().pad(0.1) : null;

      try {
        const response = await fetch(buildPinRequest(mode, requestBounds), {
          signal: controller.signal,
          headers: { Accept: 'application/json' }
        });
        if (!response.ok) throw new Error(`Pin request failed with HTTP ${response.status}`);
        const payload = await response.json();
        if (sequence !== requestSequence) return [];

        const loadedMarkers = await renderPinBatch(payload.records || [], sequence);
        if (sequence !== requestSequence) return [];
        if (preserveLoadedBatch) {
          // Include any chunks drawn by an immediately superseded startup
          // request as well as the first request that completes successfully.
          for (const id of pinLocations.keys()) persistentInitialPinIds.add(id);
          initialPinBatchCaptured = true;
        }
        if (mode === 'viewport') {
          lastCompleteViewportBounds = payload.capped !== true && payload.count < payload.limit
            ? requestBounds
            : null;
          unloadOutOfBoundsPins();
        }
        return loadedMarkers;
      } catch (error) {
        if (error.name !== 'AbortError') console.error('Unable to load map pins:', error);
        return [];
      } finally {
        if (activePinRequest === controller) activePinRequest = null;
      }
    }

    window.refreshMapFilters = async parameters => {
      const params = parameters instanceof URLSearchParams
        ? new URLSearchParams(parameters)
        : new URLSearchParams(parameters || window.location.search);

      const numericLimit = Number(params.get('limit'));
      requestLimit = Number.isFinite(numericLimit) && numericLimit > 0
        ? Math.max(1, Math.min(5000, Math.trunc(numericLimit)))
        : defaultRequestLimit;
      viewportBatchSize = Number.isFinite(numericLimit) && numericLimit > 0
        ? Math.max(1, Math.min(25000, Math.trunc(numericLimit)))
        : defaultViewportBatchSize;

      const numericPinSize = Number(params.get('pin_size'));
      const nextPinSize = Number.isFinite(numericPinSize) && numericPinSize > 0
        ? Math.max(8, Math.min(80, numericPinSize))
        : 20;
      pinRadius = Math.max(3, (nextPinSize / 2 - 1));
      pinStyle = params.get('pin_style') || 'carrier';
      useCarrierIcons = pinStyle === 'carrier';
      useConcealedIcons = pinStyle === 'concealed';
      requestedTags = params.get('tags');

      history.replaceState({}, '', `${window.location.pathname}?${params.toString()}${window.location.hash}`);
      clearTimeout(viewportTimer);
      if (activePinRequest) {
        activePinRequest.abort();
        activePinRequest = null;
      }
      requestSequence++;
      map.closePopup();
      for (const marker of pinLocations.values()) map.removeLayer(marker);
      pinLocations.clear();
      persistentInitialPinIds.clear();
      initialPinBatchCaptured = false;
      lastCompleteViewportBounds = null;

      return loadPins('viewport');
    };

    function updateURL() {
      const center = map.getCenter();
      const params = new URLSearchParams(window.location.search);
      params.set('latitude', center.lat);
      params.set('longitude', normalizeLongitude(center.lng));
      params.set('zoom', map.getZoom());
      params.delete('useCoordCenter');
      history.replaceState(null, '', `${window.location.pathname}?${params.toString()}${window.location.hash}`);
    }

    function scheduleViewportLoad() {
      clearTimeout(viewportTimer);
      viewportTimer = setTimeout(() => loadPins('viewport'), 120);
    }

    map.on('moveend', () => {
      if (!initialLoadComplete) return;
      updateURL();
      scheduleViewportLoad();
    });

    map.on('contextmenu', event => {
      const coordinates = `${event.latlng.lat.toFixed(6)}, ${event.latlng.lng.toFixed(6)}`;
      navigator.clipboard?.writeText(coordinates).catch(() => {});
    });

    document.addEventListener('keydown', event => {
      if ((event.shiftKey || event.metaKey) && event.key.toLowerCase() === 'y') {
        updateURL();
        location.reload();
        event.preventDefault();
      }
    });

    function makeControl(id, html, title, clickHandler) {
      return L.Control.extend({
        onAdd() {
          const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-added-button');
          container.id = id;
          if (id === 'polyInfoButton') container.classList.add('poly-info-control');
          container.innerHTML = `<a href="#" title="${title}">${html}</a>`;
          L.DomEvent.disableClickPropagation(container);
          if (clickHandler) {
            L.DomEvent.on(container, 'click', event => {
              L.DomEvent.preventDefault(event);
              clickHandler(this.options);
            });
          }
          return container;
        }
      });
    }

    const PolyInfoButton = makeControl('polyInfoButton', '<span style="font-size:22px">&#9650;</span>', 'Polygon cell info');
    const PolyMapButton = makeControl('polyMapButton', '<span style="font-size:22px">&#11201;</span>', 'Poly Map', options => navigateToPolyMap(Boolean(options.mini)));
    const OpenPolyButton = L.Control.extend({
      onAdd() {
        const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-added-button');
        const target = polyLinkUrl !== '#' ? ' target="_blank" rel="noopener"' : '';
        container.id = 'openPolyButton';
        container.innerHTML = `<a href="${escapeAttribute(polyLinkUrl)}"${target} title="Open Poly page"><span style="font-size:27px">&#8599;</span></a>`;
        L.DomEvent.disableClickPropagation(container);
        return container;
      }
    });

    function escapeAttribute(value) {
      return String(value).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    const urlParams = new URLSearchParams(location.search);
    if (urlParams.has('hideui') && !urlParams.has('showPolyLink') && !urlParams.has('marker_latitude') && urlParams.has('polygon')) {
      map.addControl(new PolyInfoButton({ position: 'topleft' }));
    }
    if (!urlParams.has('marker_latitude')) map.addControl(new PolyMapButton({ position: 'topleft', mini: false }));
    if (urlParams.has('marker_latitude')) {
      map.addControl(new PolyMapButton({ position: 'topleft', mini: true }));
      map.addControl(new OpenPolyButton({ position: 'topleft' }));
    }

    function navigateToPolyMap(mini) {
      const center = map.getCenter();
      const parentParams = new URLSearchParams(window.parent.location.search);
      let carrier = sessionStorage.getItem('carrier') || parentParams.get('carrier') || '';
      const excluded = carrier.startsWith('!');
      if (excluded) carrier = carrier.slice(1);

      const plmnMap = {
        'T-Mobile': '310260', ATT: '310410', Verizon: '311480',
        Sprint: '310120', Dish: '313340'
      };
      let plmn = plmnMap[carrier];
      if (!plmn) {
        plmn = [...new Set([...parentParams.entries()]
          .filter(([key, value]) => /^plmn_\d+$/.test(key) && value)
          .map(([, value]) => value))].join(',');
      }

      const target = new URL('/poly/Map.php', window.location.origin);
      target.searchParams.set('latitude', center.lat);
      target.searchParams.set('longitude', normalizeLongitude(center.lng));
      target.searchParams.set('zoom', map.getZoom());
      if (plmn) target.searchParams.set('plmn', `${excluded ? '!' : ''}${plmn}`);
      if (mini) {
        const storedEnb = sessionStorage.getItem('enb');
        target.searchParams.set('mini', storedEnb || '');
      }
      sessionStorage.setItem('polyMapReturnUrl', window.location.href);
      (mini ? window : window.parent).location.href = target.href;
    }

    function parsePolygon() {
      const raw = urlParams.get('polygon');
      if (!raw) return { points: [], markers: [] };

      const values = raw.split(',').map(Number);
      const indexed = [];
      for (let index = 0; index + 1 < values.length; index += 2) {
        if (Number.isFinite(values[index]) && Number.isFinite(values[index + 1])) {
          indexed.push({ originalIndex: index / 2, coords: [values[index], values[index + 1]] });
        }
      }
      if (!indexed.length) return { points: [], markers: [] };

      const centroid = indexed.reduce((sum, point) => [sum[0] + point.coords[0], sum[1] + point.coords[1]], [0, 0])
        .map(value => value / indexed.length);
      indexed.sort((a, b) => Math.atan2(a.coords[1] - centroid[1], a.coords[0] - centroid[0])
        - Math.atan2(b.coords[1] - centroid[1], b.coords[0] - centroid[0]));

      const points = indexed.map(point => point.coords);
      L.polygon(points, { color: '#<?= htmlspecialchars($accent_color, ENT_QUOTES) ?>', weight: 2 }).addTo(map);

      const labels = (urlParams.get('polygonlabels') || '').split(',');
      const uniquePoints = new Map();
      for (const point of indexed) {
        const key = JSON.stringify(point.coords);
        if (!uniquePoints.has(key)) uniquePoints.set(key, { coords: point.coords, labels: [] });
        uniquePoints.get(key).labels.push(labels[point.originalIndex] || '');
      }

      const markers = [];
      for (const point of uniquePoints.values()) {
        const label = point.labels.filter(Boolean).join(', ');
        const marker = L.marker(point.coords, { opacity: 0, interactive: false })
          .bindTooltip(escapeAttribute(label), { permanent: true, direction: 'center', className: 'label-tooltip' })
          .addTo(map);
        markers.push(marker);
      }
      return { points, markers };
    }

    const polygon = parsePolygon();

    <?php if (isset($_GET['marker_latitude'], $_GET['marker_longitude']) && is_numeric($_GET['marker_latitude']) && is_numeric($_GET['marker_longitude'])): ?>
      L.marker([<?= json_encode((float) $_GET['marker_latitude']) ?>, <?= json_encode((float) $_GET['marker_longitude']) ?>]).addTo(map);
    <?php endif; ?>

    async function initializePins() {
      if (useCoordCenter) {
        const markers = await loadPins('initial');
        if (markers.length) {
          const markerBounds = L.featureGroup(markers).getBounds().pad(0.2);
          const reduceMotion = window.matchMedia?.('(prefers-reduced-motion: reduce)').matches;
          map.flyToBounds(markerBounds, {
            animate: !reduceMotion,
            duration: 0.20,
            easeLinearity: 0.25
          });
          return;
        }
      } else if (polygon.markers.length && !skipPolyZoom) {
        map.fitBounds(L.featureGroup(polygon.markers).getBounds().pad(0.5), { animate: false });
        await loadPins('viewport');
        return;
      }
      await loadPins('viewport');
    }

    async function initializeMap() {
      try {
        await initializePins();
      } catch (error) {
        console.error('Unable to initialize map pins:', error);
      } finally {
        initialLoadComplete = true;
        // Give the shared marker canvas one paint before tile requests begin.
        await nextAnimationFrame();
        baseMapLayer.addTo(map);
      }
    }

    initializeMap();

    // Called by the Poly iframe after a successful cell deletion.
    function deleteCell(eNB, rat, plmn, cellId) {
      if (!confirm(`Are you sure you want to delete ${eNB}:${cellId}?`)) return;
      const params = new URLSearchParams({ cells: cellId, enb: eNB, rat, plmn, permanentlyDelete: false });
      fetch(`https://cmgm.us/api/poly/purgeApi.php?${params}`)
        .then(response => {
          if (!response.ok) throw new Error(`Delete failed with HTTP ${response.status}`);
          return response.text();
        })
        .then(() => {
          if (typeof window.parent.refreshPolyFromIframe === 'function') window.parent.refreshPolyFromIframe();
        })
        .catch(error => {
          console.error(error);
          alert('Failed to delete cell.');
        });
    }
  </script>

  <?php if ($showMapFilterUi): ?><script src="js/filter-ui.js?v=<?= filemtime(__DIR__ . '/js/filter-ui.js') ?>"></script><?php endif; ?>

  <?php if (!isset($_GET['hideui']) || $_GET['hideui'] !== 'true') include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
