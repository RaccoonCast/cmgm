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
        body, html { margin: 0; padding: 0; height: 100%; overflow: hidden; font-family: sans-serif; background: #1a1a1a; }
        .header {
            z-index: 1001;
        }
        select, input[type="number"] {
            padding: 6px 10px;
            border-radius: 6px;
            border: 1px solid #444;
            background: #222;
            color: white;
            outline: none;
        }
        select:focus, input:focus { border-color: #0078ff; }
        
        #map { height: 100vh; width: 100%; background: #1a1a1a; }
        
        /* Menu & Labels */
        .custom-menu {
            position: absolute; background: #fff; border: 1px solid #999; border-radius: 4px;
            padding: 4px 0; z-index: 10000; box-shadow: 0px 4px 12px rgba(0,0,0,0.4); min-width: 180px;
        }
        .menu-item { padding: 10px 15px; cursor: pointer; font-size: 13px; color: #222; }
        .menu-item:hover { background: #0078ff; color: #fff; }
        .menu-divider { border-top: 1px solid #eee; margin: 4px 0; }

        .tower-label {
            background: rgba(255, 255, 255, 0.95); border: 1px solid #333;
            padding: 4px; font-weight: bold; font-size: 11px; border-radius: 4px;
            text-align: center; line-height: 1.2; pointer-events: auto !important; cursor: pointer; color: #000;
        }
    </style>
</head>
<body>

<div class="header">
    <div class="formsContainerContainer">
        <div id="formsContainer">
        <select id="filterPlmn">
            <option value="310410">AT&T</option>
            <option value="310120">Sprint</option>
            <option value="310260">T-Mobile</option>
            <option value="311480">Verizon</option>
            <option value="313340">Dish Wireless</option>
            <option value="311580">US Cellular</option>
            <option value="" disabled>--</option>
            <option value="_custom_">Custom PLMN</option>
            <option value="0">All PLMNs</option>
        </select>

        <select id="filterRat">
            <option value="LTE">LTE</option>
            <option value="NR">NR</option>
            <option value="0">All RATs</option>
        </select>

        <span>Limit:</span>
        <input type="number" id="filterLimit" style="width: 75px;">
        <span>Size:</span>
        <input type="number" id="iconSize" style="width: 75px;">

        <label class="checkbox-group"><input type="checkbox" id="labels"> Labels</label>
        <label class="checkbox-group"><input type="checkbox" id="dontUnload"> No Unload</label>
    </div>
    </div>
</div>

<div id="map"></div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script>
    const urlParams = new URLSearchParams(window.location.search);
    
    // UI Elements
    const plmnEl = document.getElementById('filterPlmn');
    const ratEl = document.getElementById('filterRat');
    const limitEl = document.getElementById('filterLimit');
    const sizeEl = document.getElementById('iconSize');
    const labelsEl = document.getElementById('labels');
    const unloadEl = document.getElementById('dontUnload');

    // Init values
    plmnEl.value = urlParams.get('plmn') || "0";
    ratEl.value = urlParams.get('rat') || "LTE";
    limitEl.value = urlParams.get('limit') || 1000;
    sizeEl.value = urlParams.get('iconSize') || 10;
    labelsEl.checked = urlParams.get('labels') !== 'false';
    unloadEl.checked = urlParams.has('dontUnload');

    const map = L.map('map', {
        preferCanvas: true, boxZoom: true, zoomSnap: 0, zoomDelta: 0.8,
        wheelPxPerZoomLevel: 120, wheelDebounceTime: 100
    }).setView([parseFloat(urlParams.get('latitude')) || 34.1317, parseFloat(urlParams.get('longitude')) || -118.2630], parseInt(urlParams.get('zoom')) || 14);
    
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
        urlParams.set('latitude', center.lat.toFixed(6));
        urlParams.set('longitude', center.lng.toFixed(6));
        urlParams.set('zoom', map.getZoom());
        urlParams.set('plmn', plmnEl.value);
        urlParams.set('rat', ratEl.value);
        urlParams.set('limit', limitEl.value);
        urlParams.set('iconSize', sizeEl.value);
        if (labelsEl.checked) urlParams.set('labels', 'true'); else urlParams.set('labels', 'false');
        if (unloadEl.checked) urlParams.set('dontUnload', 'true'); else urlParams.delete('dontUnload');
        window.history.replaceState({}, '', `${window.location.pathname}?${urlParams.toString()}`);
    }

    async function updateData() {
        updateUrl();
        const center = map.getCenter();
        const bounds = map.getBounds();
        const zoom = map.getZoom();

        const apiUrl = `https://cmgm.us/api/cmgm/getPolyEnbs.php?latitude=${center.lat}&longitude=${center.lng}&limit=${limitEl.value}&plmn=${plmnEl.value}&rat=${ratEl.value}`;

        try {
            const res = await fetch(apiUrl);
            const data = await res.json();

            Object.keys(data).forEach(plmnKey => {
                data[plmnKey].forEach(tower => {
                    const id = `${plmnKey}-${tower.rat}-${tower.enb}`;
                    if (!markerMap[id]) {
                        const marker = L.circleMarker([tower.latitude, tower.longitude], {
                            radius: parseFloat(sizeEl.value),
                            fillColor: (plmnKey === '310260') ? (tower.rat === 'LTE' ? '#b200ae' : '#ff4dff') :
                                       (plmnKey === '310410') ? (tower.rat === 'LTE' ? '#0059b2' : '#4da2ff') :
                                       (plmnKey === '310120') ? '#FFEF87' :
                                       (plmnKey === '311580') ? '#E8B937' :
                                       (plmnKey === '311480') ? (tower.rat === 'LTE' ? '#b20000' : '#ff4a4a') : '#ffffff',
                            color: "#000", weight: 1.5, fillOpacity: 1
                        }).addTo(map);

                        const prefix = tower.rat === 'NR' ? 'gNB' : 'eNB';
                        const cellsInfo = tower.cells ? `<br>Cells: ${tower.cells}` : '';
                        marker.bindTooltip(`${prefix} ${tower.enb}${cellsInfo}`, { 
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

            
            const allVisibleMarkers = Object.values(markerMap);
            allVisibleMarkers.sort((a, b) => map.distance(center, a.getLatLng()) - map.distance(center, b.getLatLng()));

            allVisibleMarkers.forEach((m, index) => {
                const shouldBePermanent = (zoom > 10 && index < 250 && labelsEl.checked);
                const tooltip = m.getTooltip();
                if (tooltip && tooltip.options.permanent !== shouldBePermanent) {
                    const content = tooltip.getContent();
                    m.unbindTooltip().bindTooltip(content, { 
                        permanent: shouldBePermanent, direction: 'bottom', className: 'tower-label', offset: [0, 12], interactive: true 
                    });
                    if (shouldBePermanent) m.openTooltip();
                }
            });

            if (!unloadEl.checked) {
                for (let key in markerMap) {
                    if (!bounds.contains(markerMap[key].getLatLng())) {
                        map.removeLayer(markerMap[key]);
                        delete markerMap[key];
                    }
                }
            }
        } catch (err) { console.error(err); }
    }

    // --- Listeners ---
    let debounceTimer;
    const slowUpdate = () => { clearTimeout(debounceTimer); debounceTimer = setTimeout(updateData, 400); };

    [plmnEl, ratEl, labelsEl, unloadEl].forEach(el => {
        el.addEventListener('change', () => {
            if (el === plmnEl && el.value === "_custom_") {
                const custom = prompt("Enter Custom PLMN:");
                if (custom) {
                    const opt = new Option(custom, custom, true, true);
                    plmnEl.add(opt, plmnEl.options[plmnEl.options.length - 1]);
                }
            }
            if (el === plmnEl || el === ratEl) clearAllMarkers();
            updateData();
        });
    });

    [limitEl, sizeEl].forEach(el => {
        el.addEventListener('input', () => {
            if (el === sizeEl) {
                const newSize = parseFloat(sizeEl.value);
                Object.values(markerMap).forEach(m => m.setRadius(newSize));
            }
            slowUpdate();
        });
    });

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

        items.push(
            { isDivider: true },
            { label: 'View in Poly', action: () => window.open(`https://cmgm.us/poly?plmn=${tower.plmn}&zoom=17&rat=${tower.rat}&eNB=${tower.enb}&cellListDepri_1=-`, '_blank') },
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
            if (opt.isDivider) { menu.appendChild(Object.assign(document.createElement('div'), {className:'menu-divider'})); return; }
            const item = document.createElement('div'); item.className = 'menu-item'; item.innerText = opt.label;
            item.onclick = (ev) => { ev.stopPropagation(); opt.action(); menu.remove(); };
            menu.appendChild(item);
        });
        document.body.appendChild(menu);
        setTimeout(() => { window.onclick = () => { menu.remove(); window.onclick = null; }; }, 10);
    }

    map.on('moveend', slowUpdate);
    updateData();

    document.addEventListener('paste', function (e) {
    // Don't trigger if you're actually typing in one of the input boxes
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;

    const pasted = (e.clipboardData || window.clipboardData).getData('text');
    const coords = parseLatLng(pasted);
    
    if (coords) {
        map.setView(coords, map.getZoom());
        // updateData() will trigger automatically via the moveend listener
    }
});
</script>
</body>
</html>