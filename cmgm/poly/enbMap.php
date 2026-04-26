<?php
$allowGuests = true;
include "../functions.php";
 
?>
<!DOCTYPE html>
<html>
<head>
    <?php
    $titleOverride = true;
    include "../includes/functions/headhtml.php"; 
    ?>
    <title>eNB Poly Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <style>
    </style>
</head>
<body>
<?php


// Get values from URL or set defaults
// $plmn = $_GET['plmn'] ?? "0";
// $rat  = $_GET['rat'] ?? "LTE";
// $limit = $_GET['requestBatchSize'] ?? 250;
// 
//  '';
// 
// $oldest_date = isset($_GET['oldest_date']) ?  $_GET['oldest_date'] : '>2000-01-01';
// $newest_date = isset($_GET['newest_date']) ? $_GET['newest_date'] : '>2000-01-01';
// $perfectOnly = isset($_GET['perfectOnly']) ? $_GET['perfectOnly'] : 'null';
include "../api/poly/get_param.php";

// Get date of data
$dateOfData = mysqli_fetch_assoc(mysqli_query($conn, "SELECT last_run FROM local_poly_enbs_date"))['last_run'];
$dateOfData = (new DateTime($dateOfData, new DateTimeZone('UTC')))
    ->setTimezone(new DateTimeZone('America/Los_Angeles'))
    ->format('Y-m-d H:i:s');

// Helper to check if a value is a "Standard" option or a "Custom" one
function is_custom($val, $options) {
    return !in_array((string)$val, $options);
}
?>
<div class="header">
    <div class="formsContainerContainer">
        <div id="formsContainer">   
        <?php include "includes/plmn-and-rat-selector.php"; ?>

        <!-- Batch size -->
        <select class="misc_cw" title="Set batch size" name="requestBatchSize" id="requestBatchSize">
          <option style="display:none" value="<?php if ($limit !== 0) echo $limit; ?>" selected>
            Batch size: <?php echo $limit; ?>
          </option>
          <?php if ($limit == 0) { ?>
          <option style="display:none" value="0" selected>
            Batch size: Unlimited
          </option> <?php } ?>
          <!-- Preset number options -->
          <option value="50">50</option>
          <option value="125">125</option>
          <option value="250">250</option>
          <option value="450">450</option>
          <option value="800">800</option>
          <option value="1500">1500</option>
          <option value="3000">3000</option>
          <option value="7500">7500</option>
          <option value="15000">15000</option>
          <option value="40000">40000</option>
          <option value="0">Unlimited (Slow)</option>
          <option value="" disabled>--</option>
          <option value="_custom_">Custom batch size</option>
        </select>

        <!-- Icon size -->
        <select class="misc_cw" title="Set icon size" name="iconSize" id="iconSize">
          <option style="display:none" value="<?php echo $iconSize; ?>" selected>
            Icon size: <?php echo $iconSize; ?>
          </option>
          <option value="3">3</option>
          <option value="5">5</option>
          <option value="8">8</option>
          <option value="10">10</option>
          <option value="15">15</option>
          <option value="20">20</option>
          <option value="25">30</option>
          <option value="50">50</option>
          <option value="100">100</option>
          <option value="" disabled>--</option>
          <option value="_custom_">Custom...</option>
        </select>
        <button class="poly-btn" id="hamburger-menu">▼</button>
        <div id="hamburger-area" hidden>
            <?php include "includes/advanced-selectors.php"; ?>
        </div>
    </div>
    </div>
</div>

<div id="map"></div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script>
    // Hamburger Menu toggler
    document.addEventListener('DOMContentLoaded', () => {
      const button = document.getElementById('hamburger-menu');
      const area = document.getElementById('hamburger-area');

      button.addEventListener('click', () => {
        area.hidden = !area.hidden;

        // Update button icon based on hidden state
        button.textContent = area.hidden ? '▼' : '▲';
      });
    });

        // something gemini said is needed for ios
    document.addEventListener("touchstart", function() {}, true);
    // Manage form
    const plmn = document.getElementById('filterPlmn');
    const rat = document.getElementById('filterRat');
    const requestBatchSize = document.getElementById('requestBatchSize');
    const iconSize = document.getElementById('iconSize');
    const labels = document.getElementById('labels');
    const forceLabelVisibility = document.getElementById('forceLabelVisibility');
    const unload = document.getElementById('dontUnload');
    const oldest_date = document.getElementById('oldest_date');
    const newest_date = document.getElementById('newest_date');
    const perfectSurroOnly = document.getElementById('perfectSurroOnly');
    let currentRequestId = 0; // Track the latest request

    // Mapping for prefixes
    const labelPrefixes = {
        requestBatchSize: "Batch size",
        iconSize: "Icon size"
    };

    const customPrompts = {
        filterPlmn: "Enter Custom PLMN:", 
        requestBatchSize: "Enter Custom Batch Size:",
        iconSize: "Enter Custom Icon Size:"
    };

    // Helper: Update the hidden label and FORCE selection to index 0
    const updateSelectLabel = (el) => {
        const prefix = labelPrefixes[el.id];
        if (!prefix) return;

        const labelOption = el.options[0];
        if (!labelOption) return;

        // Special case: unlimited flag
        if (el.id === 'requestBatchSize' && el.value === '0') {
            labelOption.text = 'Batch size: Unlimited';
            labelOption.value = el.value;
            el.selectedIndex = 0;
            return;
        }

        // Default behavior
        labelOption.text = `${prefix}: ${el.value}`;
        labelOption.value = el.value;

        // Force UI to show the first option
        el.selectedIndex = 0;
    };

    // Init values from URL
    // Helper: Add custom value to dropdown if it doesn't exist
    const addCustomOption = (el, value) => {
        if (![...el.options].some(opt => opt.value === String(value))) {
            const opt = new Option(value, value, true, true);
            el.add(opt, el.options[el.options.length - 1]);
        }
        el.value = value;
    };

    // Initialize fields from URL (Bootstrapping custom values)
    const urlParams = new URLSearchParams(window.location.search);
    labels.checked = urlParams.get('labels') !== 'false';
    unload.checked = urlParams.has('dontUnload');
    forceLabelVisibility.checked = urlParams.has('forceLabelVisibility');

    // Apply initial prefixes
    updateSelectLabel(requestBatchSize);
    updateSelectLabel(iconSize);

    // Elements that require a full map reset/clear
    const resetTriggers = [
        plmn, rat, oldest_date, newest_date, 
        cellsAllowList, cellsBlockList, enbAllowList, 
        enbBlockList, tacsAllowList, tacsBlockList, perfectSurroOnly
    ];

    // Elements that update UI or visuals without clearing data
    const visualTriggers = [iconSize, labels, requestBatchSize, unload, forceLabelVisibility];

    [...resetTriggers, ...visualTriggers].forEach(el => {
        el.addEventListener('change', () => {

            // 1. Handle Custom Option Prompts
            if (customPrompts[el.id] && (el.value === "_custom_" || el.value === "custom")) {
                const custom = prompt(customPrompts[el.id]);
                if (custom) {
                    addCustomOption(el, custom);
                } else {
                    el.selectedIndex = 0;
                    return; 
                }
            }

            // 2. Handle Label UI updates
            if (labelPrefixes[el.id]) {
                updateSelectLabel(el);
            }

            // 3. Update Marker Dimensions (IconSize)
            if (el === iconSize) {
                const newSize = parseFloat(el.value);
                Object.values(markerMap).forEach(marker => {
                    if (typeof marker.setRadius === 'function') {
                        marker.setRadius(newSize);
                    }
                });
            }

            // 4. Reset Map if a data-critical field changed
            if (resetTriggers.includes(el)) {
                clearAllMarkers();
            }
            
            // 5. Trigger Data Update/Fetch
            if (el.id === 'labels') {
                updateLabelsOnly();   // no network, no fetch, no marker rebuild
            } else if (visualTriggers.includes(el)) {
                updateData(false);    // no fetch, just UI refresh
            } else {
                updateData(true);     // full refresh
            }
        });
    });

    const map = L.map('map', {
        preferCanvas: true, boxZoom: true, zoomSnap: 0, zoomDelta: 0.8,
        wheelPxPerZoomLevel: 120, wheelDebounceTime: 100
    }).setView([parseFloat(urlParams.get('latitude')) || 34.1317, parseFloat(urlParams.get('longitude')) || -118.2630], parseInt(urlParams.get('zoom')) || 14);
    map.attributionControl.setPrefix('<?php echo "Last updated: " . $dateOfData ?> <a href="https://cmgm.us/api/poly/updatePolyEnbs.php">⟳</a>');

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    let markerMap = {};

    function clearAllMarkers() {
        Object.values(markerMap).forEach(m => map.removeLayer(m));
        markerMap = {};
    }
    function parseLatLng(str) {
        const regex = /(-?\d+\.\d+)\s*,\s*(-?\d+\.\d+)/;
        const match = str.match(regex);
        if (match) return [parseFloat(match[1]), parseFloat(match[2])];
        return null;
    }
    function updateUrl() {
    const center = map.getCenter();

    const setOrDeleteParam = (key, value) => {
        if (value != null && value !== '') {
            urlParams.set(key, value);
        } else {
            urlParams.delete(key);
        }
    };

    if (center.lat != null) urlParams.set('latitude', center.lat.toFixed(6));
    if (center.lng != null) urlParams.set('longitude', center.lng.toFixed(6));
    if (map.getZoom() != null) urlParams.set('zoom', map.getZoom());

    setOrDeleteParam('plmn', plmn.value);
    setOrDeleteParam('rat', rat.value);
    setOrDeleteParam('limit', requestBatchSize.value);
    setOrDeleteParam('iconSize', iconSize.value);
    setOrDeleteParam('oldest_date', oldest_date.value);
    setOrDeleteParam('newest_date', newest_date.value);
    setOrDeleteParam('cellsAllowList', cellsAllowList.value);
    setOrDeleteParam('cellsBlockList', cellsBlockList.value);
    setOrDeleteParam('enbAllowList', enbAllowList.value);
    setOrDeleteParam('enbBlockList', enbBlockList.value);
    setOrDeleteParam('tacsAllowList', tacsAllowList.value);
    setOrDeleteParam('tacsBlockList', tacsBlockList.value);

    if (labels.checked) {
        urlParams.set('labels', 'true');
    } else {
        urlParams.set('labels', 'false'); 
    }

    if (forceLabelVisibility.checked) {
        urlParams.set('forceLabelVisibility', 'true');
    } else {
        urlParams.delete('forceLabelVisibility');
    }

    if (unload.checked) {
        urlParams.set('dontUnload', 'true');
    } else {
        urlParams.delete('dontUnload');
    }
    
    if (perfectSurroOnly.checked) {
        urlParams.set('perfectSurroOnly', 'true');
    } else {
        urlParams.delete('perfectSurroOnly');
    }

    window.history.replaceState(
        {},
        '',
        `${window.location.pathname}?${urlParams.toString()}`
    );
}
    function updateLabelsOnly() {
        const allVisibleMarkers = Object.values(markerMap);
        const center = map.getCenter();
  
        // Sort markers so those closest to the center get priority for labels
        allVisibleMarkers.sort((a, b) =>
            map.distance(center, a.getLatLng()) - map.distance(center, b.getLatLng())
        );

        allVisibleMarkers.forEach((m, index) => {
            // Logic: Show labels only if zoomed in, within the first 250 closest markers, and enabled by user
            const shouldBePermanent = ((map.getZoom() > 10 && index < 250 && labels.checked) || forceLabelVisibility.checked);

            const tooltip = m.getTooltip();
            if (!tooltip) return;

            // Only update if the state has changed to avoid performance hits
            if (tooltip.options.permanent !== shouldBePermanent) {
                const content = tooltip.getContent();

                m.unbindTooltip().bindTooltip(content, {
                    permanent: shouldBePermanent,
                    direction: 'bottom',
                    className: 'tower-label',
                    offset: [0, 12],
                    interactive: true
                });

                if (shouldBePermanent) m.openTooltip();
            }
        });
    }
    async function fetchData(bounds, requestId) {
        const apiUrl = `https://cmgm.us/api/poly/getPolyEnbs.php?boundsNELatitude=${bounds.neLat}&boundsNELongitude=${bounds.neLng}&boundsSWLatitude=${bounds.swLat}&boundsSWLongitude=${bounds.swLng}&limit=${requestBatchSize.value}&useAggregateTable&plmn=${plmn.value}&rat=${rat.value}&oldest_date=${oldest_date.value}&newest_date=${newest_date.value}&cellsAllowList=${cellsAllowList.value}&cellsBlockList=${cellsBlockList.value}&enbAllowList=${enbAllowList.value}&enbBlockList=${enbBlockList.value}&tacsAllowList=${tacsAllowList.value}&tacsBlockList=${tacsBlockList.value}&perfectSurroOnly=${perfectSurroOnly.checked ? 'true' : ''}&locationType=2`;

        try {
            const res = await fetch(apiUrl);
            const data = await res.json();

            // Check if the API returned an error object
            if (data && data.error) {
                alert(data.error);
                return null;
            }

            // VALIDATION: If a newer request has started, ignore this "old" data
            if (requestId !== currentRequestId) return null;

            return data;
        } catch (e) {
            // This catches network failures or non-JSON responses
            console.error("Fetch failed or returned invalid JSON:", e);
            return null;
        }
    }
    async function updateData(shouldFetch = true) {
        updateUrl();

        const bounds = map.getBounds();
        const requestId = ++currentRequestId;

        if (shouldFetch) {
            try {
                const data = await fetchData({
                    neLat: bounds.getNorthEast().lat,
                    neLng: bounds.getNorthEast().lng,
                    swLat: bounds.getSouthWest().lat,
                    swLng: bounds.getSouthWest().lng,
                }, requestId);

                if (!data) return;

                Object.keys(data).forEach(plmnKey => {
                    data[plmnKey].forEach(tower => {
                        const id = `${plmnKey}-${tower.rat}-${tower.enb}`;

                        // Only create the marker if it doesn't already exist
                        if (!markerMap[id]) {
                            const marker = L.circleMarker([tower.latitude, tower.longitude], {
                                radius: parseFloat(iconSize.value),
                                fillColor: (plmnKey === '310260') ? (tower.rat === 'LTE' ? '#b200ae' : '#ff4dff') :
                                           (plmnKey === '310410') ? (tower.rat === 'LTE' ? '#0059b2' : '#4da2ff') :
                                           (plmnKey === '310120') ? '#FFEF87' :
                                           (plmnKey === '311580') ? '#E8B937' :
                                           (plmnKey === '311480') ? (tower.rat === 'LTE' ? '#b20000' : '#ff4a4a') : '#ffffff',
                                color: "#000", weight: 1.5, fillOpacity: 1
                            }).addTo(map);

                            const prefix = tower.rat === 'NR' ? 'gNB' : 'eNB';
                            const suffix = tower.is_exact_location === '1' ? '★' : '';
                            const cellsInfo = tower.cells ? `<br>Cells: ${tower.cells}` : '';

                            marker.bindTooltip(`${prefix} ${tower.enb}${suffix} ${cellsInfo}`, { 
                                permanent: false, direction: 'bottom', className: 'tower-label', offset: [0, 12], interactive: true 
                            });

                            const handleTrigger = (e) => {
                                if (e.originalEvent.button !== 0 && e.originalEvent.button !== 2) return;
                                L.DomEvent.stopPropagation(e);
                                if (e.originalEvent.preventDefault) e.originalEvent.preventDefault();
                                createMenu(e, { ...tower, plmn: plmnKey });
                            };

                            marker.on('click', handleTrigger).on('contextmenu', handleTrigger);
                            markerMap[id] = marker;
                        }
                    });
                });
            } catch (err) {
                console.error("Fetch error:", err);
            }
        }

        // CLEANUP: Remove markers that are off-screen (unless "Don't Unload" is checked)
        if (!unload.checked) {
            for (let key in markerMap) {
                if (!bounds.contains(markerMap[key].getLatLng())) {
                    map.removeLayer(markerMap[key]);
                    delete markerMap[key];
                }
            }
        }

        updateLabelsOnly();
    }

    // --- Listeners ---
    let debounceTimer;
    const slowUpdate = () => { clearTimeout(debounceTimer); debounceTimer = setTimeout(updateData, 400); };

    function silentCopy(text) {
        const el = document.createElement('textarea'); el.value = text; document.body.appendChild(el); el.select(); document.execCommand('copy'); document.body.removeChild(el);
    }
    
    function createMenu(e, tower) {
        const oldMenu = document.getElementById('active-menu'); if (oldMenu) oldMenu.remove();
        const menu = document.createElement('div'); menu.id = 'active-menu'; menu.className = 'custom-menu';
        menu.style.left = e.originalEvent.pageX + 'px'; menu.style.top = e.originalEvent.pageY + 'px';

        const prefix = tower.rat === 'NR' ? 'gNB' : 'eNB';
        const items = [{ label: `Copy ${prefix} (${tower.enb})`, action: () => silentCopy(tower.enb) }];

        // RESTORED: TAC copy option
        if (tower.tac) {
            items.push({ label: `Copy TAC (${tower.tac})`, action: () => silentCopy(tower.tac) });
        }
        items.push({ label: `Copy location`, action: () => silentCopy(tower.latitude + ',' + tower.longitude) });

        items.push(
            { isDivider: true },
            { label: 'View in Poly', action: () => window.open(`https://cmgm.us/poly/?plmn_1=${tower.plmn}&rat_1=${tower.rat}&eNB_1=${tower.enb}&tac_1=${tower.tac}&cellListDepri_1=-`, '_blank') },
            { label: 'View in CellMapper', action: () => {
                let mnc = tower.plmn.slice(3);
                let mcc = tower.plmn.slice(0, 3);
                window.open(`https://www.cellmapper.net/map?MCC=${mcc}&MNC=${mnc}&type=${tower.rat}&latitude=${tower.latitude}&longitude=${tower.longitude}&zoom=15&ppT=${tower.enb}&ppL=${tower.tac}`, '_blank');
            }}
        );

        // determine carrier
        const carrierMap = {
            310260: "T-Mobile",
            310410: "ATT",
            311480: "Verizon",
            310120: "Sprint"
        };

        let cmgm_carrier = carrierMap[tower.plmn];

        if (cmgm_carrier) {
            items.push({
                label: 'View in CMGM',
                action: () => {
                    let mnc = tower.plmn.slice(3);
                    let mcc = tower.plmn.slice(0, 3);
                    window.open(`https://cmgm.us/database/Edit.php?q=${tower.enb}&carrier=${cmgm_carrier}`, '_blank');
                }
            });
        }

    items.forEach(opt => {
                if (opt.isDivider) { 
                    menu.appendChild(Object.assign(document.createElement('div'), {className:'menu-divider'})); 
                    return; 
                }
                const item = document.createElement('div'); 
                item.className = 'menu-item'; 
                item.innerText = opt.label;

                // Wakes up :active state on mobile devices
                item.ontouchstart = (e) => { e.stopPropagation(); }; 

                item.onclick = (ev) => { 
                    ev.stopPropagation(); 
                    opt.action(); 

                    // Delay removal so the blue highlight is visible
                    setTimeout(() => {
                        if (menu && menu.parentNode) menu.remove();
                    }, 100);
                };
                menu.appendChild(item);
            });

            document.body.appendChild(menu);

            // Global click listener to close menu when clicking away
            setTimeout(() => {
                window.onclick = () => {
                    if (menu && menu.parentNode) {
                        menu.remove();
                        window.onclick = null;
                    }
                };
            }, 50);
    }

    map.on('moveend', slowUpdate);
    updateData();


    // Paste to Jump 
    document.addEventListener('paste', function (e) {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;

        const pasted = (e.clipboardData || window.clipboardData).getData('text');
        const coords = parseLatLng(pasted);
                
        if (coords) {
            map.setView(coords, map.getZoom());
        }
    });

    // 2. Copy coordinates on right click
    map.on('contextmenu', function (e) {
        // Prevent the browser's default context menu
        if (e.originalEvent.preventDefault) e.originalEvent.preventDefault();

        navigator.clipboard.writeText(e.latlng.lat.toFixed(6) + ',' + e.latlng.lng.toFixed(6)).then(() => {
            console.log("Copied Map Coords:", coords);
        }).catch(err => {
            console.error("Copy failed:", err);
        });
    });
</script>
</body>
</html>