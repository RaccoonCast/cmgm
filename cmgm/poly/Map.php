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
        <title>Poly Map</title>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    </head>

    <body>
        <?php
        include "../api/poly/get_param.php";

        // Get date of data
        $dateOfData = mysqli_fetch_assoc(mysqli_query($conn, "SELECT last_run FROM poly_enbs_date"))['last_run'];
        $dateOfData = (new DateTime($dateOfData, new DateTimeZone('UTC')))
            ->setTimezone(new DateTimeZone('America/Los_Angeles'))
            ->format('Y-m-d H:i:s');
        
        include "formBuilder.php"; 
        ?>
        <div id="map"></div>


        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="js/poly-map.js"></script>
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
            document.addEventListener("touchstart", function () { }, true);
            // Manage form
            const plmn = document.getElementById('plmn');
            const rat = document.getElementById('rat');
            const request_batch_size = document.getElementById('request_batch_size');
            const icon_size = document.getElementById('icon_size');
            const label_settings = document.getElementById('label_settings');
            const unload = document.getElementById('dont_unload');
            const hide_cells = document.getElementById('hide_cells');
            const random_color = document.getElementById('random_color');
            const oldest_date = document.getElementById('oldest_date');
            const newest_date = document.getElementById('newest_date');
            const cells_allow_list = document.getElementById('cells_allow_list');
            const cells_block_list = document.getElementById('cells_block_list');
            const enb_allow_list = document.getElementById('enb_allow_list');
            const enb_block_list = document.getElementById('enb_block_list');
            const score = document.getElementById('score');
            const reach = document.getElementById('reach');
            const cells_quantity = document.getElementById('cells_quantity');
            const view_mode = document.getElementById('view_mode');
            const cm_includes = document.getElementById('cm_includes');
            const cm_excludes = document.getElementById('cm_excludes');
            const tacs_allow_list = document.getElementById('tacs_allow_list');
            const tacs_block_list = document.getElementById('tacs_block_list');
            let currentRequestId = 0; // Track the latest request

            const labelMap = {
                0: "Never",
                1: "at Very High Zoom",
                2: "at High Zoom",
                3: "Normal",
                4: "at Low Zoom",
                5: "at Very Low Zoom",
                6: "Always"
            };

            const view_modeMap = {
                "enbs": "eNB",
                "cells": "Cell",
                "cm": "CM"
            };

            // Mapping for prefixes
            const labelPrefixes = {
                request_batch_size: "Batch size",
                icon_size: "Icon size",
                label_settings: "Labels",
                view_mode: `View Mode`
            };

            const customPrompts = {
                request_batch_size: "Enter Custom Batch Size:",
                icon_size: "Enter Custom Icon Size:",
                plmn: "Enter Custom PLMN:"
            };

            // Helper: Update the hidden label and FORCE selection to index 0
            const updateSelectLabel = (el) => {
                const prefix = labelPrefixes[el.id];
                if (!prefix) return;

                const labelOption = el.options[0];
                if (!labelOption) return;

                // Special case: unlimited flag
                if (el.id === 'request_batch_size' && el.value === '0') {
                    labelOption.text = 'Batch size: Unlimited';
                    labelOption.value = el.value;
                    el.selectedIndex = 0;
                    return;
                }

                // Special case: label settings mapping
                if (el.id === 'label_settings') {
                    labelOption.text = `Labels: ${labelMap[el.value]}`;
                    labelOption.value = el.value;
                    el.selectedIndex = 0;
                    return;
                }

                // Special case: View Mode settings
                if (el.id === 'view_mode') {
                    const mode = el.value;
                    const managedIds = [
                        'newest_date', 'cells_quantity',
                        'CMGMPINNED', 'CMPINNED', 'DAS', 
                        'PICO', 'MACRO', 'MAPPED', 'PERFECTSURRO'
                    ];
                
                    managedIds.forEach(id => {
                        const box = document.getElementById(id);
                        if (!box) return;
                
                        let shouldDisable = false;
                
                        if (mode === 'cells') {
                            shouldDisable = true;
                        } else if (mode === 'enbs' || mode === 'enb') {
                            shouldDisable = (id !== 'newest_date' && id !== 'cells_quantity');
                        } else if (mode === 'cm') {
                            shouldDisable = false;
                        }
                
                        if (shouldDisable) {
                            box.setAttribute("disabled", "true");
                        } else {
                            box.removeAttribute("disabled");
                        }
                    });
                
                    const cmFilters = document.getElementById('cm_filters');
                    if (cmFilters) {
                        if (mode !== 'cm') {
                            cmFilters.style.display = 'none';
                        } else {
                            cmFilters.style.display = '';
                        }
                    }
                
                    labelOption.text = `View Mode: ${view_modeMap[mode]}`;
                    labelOption.value = mode;
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

            // Apply initial prefixes
            updateSelectLabel(request_batch_size);
            updateSelectLabel(icon_size);

            // Elements that require a full map reset/clear
            const resetTriggers = [
                plmn, rat, oldest_date, newest_date,
                cells_allow_list, cells_block_list, enb_allow_list,
                enb_block_list, tacs_allow_list, tacs_block_list,
                cm_includes, cm_excludes, view_mode, 
                random_color, score, reach, cells_quantity, hide_cells
            ];

            // Elements that update UI or visuals without clearing data
            const visualTriggers = [icon_size, label_settings, unload, request_batch_size];

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
                    // Because we separated our dictionaries, we safely only target pointMap (which holds pins)
                    if (el === icon_size) {
                        const newSize = parseFloat(el.value);
                        Object.values(pointMap).forEach(marker => {
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
                renderer: L.canvas(), boxZoom: true, zoomSnap: 0, zoomDelta: 0.8, worldCopyJump: true,
                wheelPxPerZoomLevel: 120, wheelDebounceTime: 100, maxZoom: 22
            }).setView([parseFloat(urlParams.get('latitude')) || 34.1317, parseFloat(urlParams.get('longitude')) || -118.2630], parseFloat(urlParams.get('zoom')) || 14);
            map.attributionControl.setPrefix('<?php if (!isset($_GET['mini'])) echo "Last updated: " . $dateOfData . '<a href="https://cmgm.us/api/poly/updatePolyEnbs.php">⟳</a>' ?>');

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 22,
                maxNativeZoom: 19
            }).addTo(map)

            // We split the marker map into strict dictionaries
            let pointMap = {};    // For L.CircleMarker (pins) and L.Marker (labels)
            let polygonMap = {};  // For L.Polygon (cell areas)
            let explodedMap = {}; // For exploded eNB temporary layer groups
            const mapLayerGroup = L.layerGroup().addTo(map);

            function sortPointsClockwise(points) {
                const centroid = points.reduce(
                    (acc, point) => [acc[0] + point.coords[0], acc[1] + point.coords[1]],
                    [0, 0]
                ).map(coord => coord / points.length);
                        
                return points.sort((a, b) => {
                    const angleA = Math.atan2(a.coords[1] - centroid[1], a.coords[0] - centroid[0]);
                    const angleB = Math.atan2(b.coords[1] - centroid[1], b.coords[0] - centroid[0]);
                    return angleA - angleB;
                });
            }
            function openPurgeModal(tower) {
                console.log(tower);
                const existingModal = document.getElementById('purge-modal-overlay');
                if (existingModal) existingModal.remove();

                // 1. Create Overlay background
                const overlay = document.createElement('div');
                overlay.id = 'purge-modal-overlay';

                // 2. Create Modal Box
                const modal = document.createElement('div');
                modal.id = 'purge-modal'; // Added ID for external CSS

                const cells = (tower.cells || tower.cells === 0) ? tower.cells.toString().split(' ').map(c => c.trim()) : [];

                // 3. Build HTML Structure (No inline styles)
                modal.innerHTML = `
                    
                    <p>What cell(s) would you like to delete from ${tower.enb}?</p><br><br>

                    <div id="cellCheckboxes" class="delete-checkbox-container">
                      ${cells.map((c, i) => `
                        <label>
                          <input checked type="checkbox" class="cell-checkbox" value="${c}"> ${c}
                        </label>
                        ${(i + 1) % 6 === 0 ? '<br>' : ''}
                      `).join('')}
                    </div>

                    <hr>

                    <div class="perm-delete-container" title="Sets location of specified cells as 0.0, 0.0 instead of just deleting rows." >
                        <label>
                            <input type="checkbox" id="permDeleteCheckbox"> Permanently delete
                        </label>
                    </div>
                    <br>
                    <div class="modal-actions">
                        <button id="selectAllCells" class="poly-btn">Select All Cells</button>
                        <button id="cancelPurgeBtn" class="floatright poly-btn">Cancel</button>
                        <button id="confirmPurgeBtn" class="floatright poly-btn colorized">Delete</button>
                    </div>
                `;

                overlay.appendChild(modal);
                document.body.appendChild(overlay);

                modal.addEventListener('click', (e) => e.stopPropagation());

                // --- Event Listeners ---
                if (cells.length > 0) {
                    document.getElementById('selectAllCells').addEventListener('click', (e) => {
                        e.preventDefault();
                        const checkboxes = modal.querySelectorAll('.cell-checkbox');
                        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                        checkboxes.forEach(cb => cb.checked = !allChecked);
                    });
                }

                const closeModal = () => overlay.remove();
                document.getElementById('cancelPurgeBtn').addEventListener('click', closeModal);
                overlay.addEventListener('click', closeModal); 

                // Delete logic
                document.getElementById('confirmPurgeBtn').addEventListener('click', async () => {
                    const selectedCells = Array.from(modal.querySelectorAll('.cell-checkbox:checked')).map(cb => cb.value);
                    const isPermanent = document.getElementById('permDeleteCheckbox').checked;

                    const params = new URLSearchParams({
                        cells: selectedCells.join(','),
                        enb: tower.enb,
                        rat: tower.rat,
                        plmn: tower.plmn,
                        permanentlyDelete: isPermanent ? 'true' : 'false'
                    });

                    const btn = document.getElementById('confirmPurgeBtn');
                    btn.disabled = true;
                    btn.innerText = "Deleting...";


                    try {
                        const response = await fetch('/api/poly/purgeApi.php?' + params.toString());

                        if (response.ok) {
                            closeModal();
                            clearAllMarkers(tower);
                        } else {
                            alert("Error during purge API call. Status: " + response.status);
                            btn.disabled = false;
                            btn.innerText = "Delete";
                        }
                    } catch (err) {
                        console.error("Fetch error:", err);
                        alert("Failed to reach the purge API.");
                        btn.disabled = false;
                        btn.innerText = "Delete";
                    }
                });
            }
            async function triggerForceUpdate(tower) {
                const params = new URLSearchParams({
                    enb: tower.enb,
                    rat: tower.rat,
                    plmn: tower.plmn
                });

                try {
                    const response = await fetch('/api/poly/forceUpdate.php?' + params.toString());

                    if (response.ok) {
                        // Refresh the map data seamlessly once the update finishes
                        updateData(); 
                    } else {
                        alert("Error during force update. Status: " + response.status);
                    }
                } catch (err) {
                    console.error("Fetch error:", err);
                    alert("Failed to reach the force update API.");
                }
            }           

            // --- Explode / Un-explode Logic ---
            async function explodeEnb(tower) {
                const markerId = `${tower.plmn}-${tower.rat}-${tower.enb}`;
                
                if (explodedMap[markerId]) return;
                explodedMap[markerId] = { loading: true };

                if (pointMap[markerId] && mapLayerGroup.hasLayer(pointMap[markerId])) {
                    mapLayerGroup.removeLayer(pointMap[markerId]);
                }

                const apiUrl = `https://cmgm.us/api/poly/getPoly.php?plmn=${tower.plmn}&rat=${tower.rat}&enb=${tower.enb}`;
                try {
                    const res = await fetch(apiUrl);
                    const data = await res.json();

                    if (!data || data.error || !Array.isArray(data) || data.length === 0) {
                        if (data && data.error) alert(data.error);
                        delete explodedMap[markerId];
                        if (pointMap[markerId]) mapLayerGroup.addLayer(pointMap[markerId]);
                        return;
                    }

                    const explodeGroup = L.layerGroup().addTo(mapLayerGroup);

                    const points = data.map(pt => ({
                        coords: [parseFloat(pt.latitude), parseFloat(pt.longitude)],
                        sectorId: (pt.cells || pt.cells === 0) ? pt.cells : '?',
                        plmn: pt.plmn,
                        rat: pt.rat,
                        rawTower: pt,
                        enb: pt.enb
                    }));

                    // Helper to cleanly trigger un-explosion
                    const handleUnexplode = (e) => {
                        L.DomEvent.stopPropagation(e);
                        if (e.originalEvent && e.originalEvent.preventDefault) e.originalEvent.preventDefault();
                        unexplodeEnb(markerId);
                    };

                    // Draw Polygon if at least 2 points exist
                    if (points.length >= 2) {
                        const sorted = sortPointsClockwise(points);
                        const polyColor = getColor(tower.plmn, tower.rat, random_color.checked);
                        
                        const poly = L.polygon(sorted.map(p => p.coords), {
                            color: polyColor, weight: 2, fillOpacity: 0.2, interactive: true
                        }).addTo(explodeGroup);

                        // Polygon: Left Click OR Middle Click un-explodes back to eNB marker
                        poly.on('click', handleUnexplode);
                        poly.on('mousedown', (e) => {
                            if (e.originalEvent.button === 1) handleUnexplode(e);
                        });
                    }

                    // Draw individual cell labels
                    points.forEach(pt => {
                        const labelMarker = L.marker(pt.coords, {
                            icon: L.divIcon({ className: 'tower-label', html: `${pt.enb}-${pt.sectorId}`, icon_size: [0, 0] }),
                            interactive: true
                        }).addTo(explodeGroup);

                        // Handler for opening the custom context menu for this specific sector
                        const handleSectorMenu = (e) => {
                            L.DomEvent.stopPropagation(e);
                            if (e.originalEvent.preventDefault) e.originalEvent.preventDefault();
                            createMenu(e, { ...pt.rawTower, plmn: pt.plmn });
                        };

                        // Both Left Click and Right Click open the sector menu
                        labelMarker.on('click', handleSectorMenu).on('contextmenu', handleSectorMenu);

                        // Middle Click on a cell label un-explodes the site
                        labelMarker.on('mousedown', (e) => {
                            if (e.originalEvent.button === 1) handleUnexplode(e);
                        });
                    });

                    explodedMap[markerId] = {
                        group: explodeGroup,
                        tower: tower
                    };

                } catch (err) {
                    console.error("Failed to explode eNB:", err);
                    delete explodedMap[markerId];
                    if (pointMap[markerId]) mapLayerGroup.addLayer(pointMap[markerId]);
                }
            }

            function unexplodeEnb(markerId) {
                if (!explodedMap[markerId]) return;

                if (explodedMap[markerId].group) {
                    mapLayerGroup.removeLayer(explodedMap[markerId].group);
                }
                delete explodedMap[markerId];

                // Restore standard eNB pin
                if (pointMap[markerId]) {
                    mapLayerGroup.addLayer(pointMap[markerId]);
                    updateLabelsOnly();
                } else {
                    // If the pin was cleaned up from pointMap while exploded, refresh UI
                    updateData(false);
                }
            }
            
            function syncTooltipClasses(marker, isTargetEnb, containerElement = null) {
                const isCmLoc = marker.location_type >= 30 && marker.location_type < 40;
                const isCmgmLoc = marker.location_type >= 40 && marker.location_type < 50;
                const isCmView = view_mode.value === 'cm';
                const isMini = urlParams.has('mini'); // urlParams is already globally scoped
                        
                const classRules = {
                    'target-enb-highlight': isMini && isTargetEnb,
                    'target-enb-green': isCmView && isCmgmLoc,
                    'target-enb-yellow': isCmView && isCmLoc
                };
                        
                // If an existing tooltip container is passed, update its DOM classes in place
                if (containerElement) {
                    Object.entries(classRules).forEach(([cls, apply]) => {
                        L.DomUtil[apply ? 'addClass' : 'removeClass'](containerElement, cls);
                    });
                }
                        
                // Always return the combined class string for new tooltips
                return ['tower-label', ...Object.keys(classRules).filter(k => classRules[k])].join(' ');
            }

            function clearAllMarkers(tower = null) {
                if (!tower) {
                    Object.values(pointMap).forEach(m => mapLayerGroup.removeLayer(m));
                    Object.values(polygonMap).forEach(m => mapLayerGroup.removeLayer(m));
                    Object.values(explodedMap).forEach(item => { if (item.group) mapLayerGroup.removeLayer(item.group); });
                    pointMap = {}; polygonMap = {}; explodedMap = {};
                    return;
                }

                // Use the full markerId for targeted cleanup
                const markerId = `${tower.plmn}-${tower.rat}-${tower.enb}`;

                // Remove eNB Pin marker
                if (pointMap[markerId]) { mapLayerGroup.removeLayer(pointMap[markerId]); delete pointMap[markerId]; }

                // Remove polygon (Cell Mode)
                const polyId = `poly-${markerId}`;
                if (polygonMap[polyId]) { mapLayerGroup.removeLayer(polygonMap[polyId]); delete polygonMap[polyId]; }

                // Remove cell labels (Cell Mode)
                if (explodedMap[markerId]) {
                    if (explodedMap[markerId].group) mapLayerGroup.removeLayer(explodedMap[markerId].group);
                    delete explodedMap[markerId];
                }

                Object.keys(pointMap).forEach(k => {
                    if (k.startsWith(`label-${markerId}-`)) { mapLayerGroup.removeLayer(pointMap[k]); delete pointMap[k]; }
                });
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
                setOrDeleteParam('limit', request_batch_size.value);
                setOrDeleteParam('icon_size', icon_size.value);
                setOrDeleteParam('oldest_date', oldest_date.value);
                setOrDeleteParam('newest_date', newest_date.value);
                setOrDeleteParam('cells_allow_list', cells_allow_list.value);
                setOrDeleteParam('cells_block_list', cells_block_list.value);
                setOrDeleteParam('enb_allow_list', enb_allow_list.value);
                setOrDeleteParam('enb_block_list', enb_block_list.value);
                setOrDeleteParam('tacs_allow_list', tacs_allow_list.value);
                setOrDeleteParam('tacs_block_list', tacs_block_list.value);
                setOrDeleteParam('view_mode', view_mode.value);
                setOrDeleteParam('label_settings', label_settings.value);
                setOrDeleteParam('cells_quantity', cells_quantity.value);
                setOrDeleteParam('score', score.value);
                setOrDeleteParam('reach', reach.value);
                setOrDeleteParam('cm_includes', cm_includes.value);
                setOrDeleteParam('cm_excludes', cm_excludes.value);

                if (unload.checked) {
                    urlParams.set('dont_unload', 'true');
                } else {
                    urlParams.delete('dont_unload');
                }
                if (hide_cells.checked) {
                    urlParams.set('hide_cells', 'true');
                } else {
                    urlParams.delete('hide_cells');
                }
                if (random_color.checked) {
                    urlParams.set('random_color', 'true');
                } else {
                    urlParams.delete('random_color');
                }

                window.history.replaceState(
                    {},
                    '',
                    `${window.location.pathname}?${urlParams.toString()}`
                );
            }

            function getTargetEnbs() {
                const miniParam = urlParams.get('mini');
                if (!miniParam || miniParam === "") return [];

                return miniParam.split(',').map(id => {
                    const prefix = id.charAt(0).toUpperCase();
                    const enbId = id.slice(1);

                    if (prefix === 'L') return { rat: 'LTE', enb: enbId };
                    if (prefix === 'N') return { rat: 'NR', enb: enbId };

                    // Fallback just in case a raw number is passed without L/N
                    return { rat: null, enb: id }; 
                });
            }

            function updateLabelsOnly() {
                const center = map.getCenter();
                const bounds = map.getBounds();
                const currentZoom = map.getZoom();
                const labelLevel = parseInt(label_settings.value);

                // Grab target eNB(s) (if present)
                const targetEnbs = getTargetEnbs();
                const hasTargets = targetEnbs.length > 0;

                // Separate markers into on-screen and off-screen
                let visibleOnScreen = [];
                let offScreen = [];


                Object.entries(pointMap).forEach(([key, m]) => {
                        if (m.getLatLng && bounds.contains(m.getLatLng())) {
                            visibleOnScreen.push(m);
                        } else {
                            offScreen.push(m);
                        }
                    });

                // Unbind/Hide all off-screen markers immediately (Zero DOM impact)
                offScreen.forEach(m => {
                    if (m instanceof L.Marker && m.options.icon instanceof L.DivIcon) {
                        if (mapLayerGroup.hasLayer(m)) mapLayerGroup.removeLayer(m);
                    } else if (m.getTooltip()) {
                        m.unbindTooltip();
                    }
                });

                // Pre-calculate distance ONLY for pins currently on the screen
                const centerLat = center.lat;
                const centerLng = center.lng;

                const markersWithDistance = visibleOnScreen.map(m => {
                    const latLng = m.getLatLng();
                    // Fast flat-plane distance approximation (a^2 + b^2)
                    const distanceSq = Math.pow(latLng.lat - centerLat, 2) + Math.pow(latLng.lng - centerLng, 2);
                    return { marker: m, distance: distanceSq };
                });

                // Sort the heavily reduced array
                markersWithDistance.sort((a, b) => a.distance - b.distance);
                const sortedVisibleMarkers = markersWithDistance.map(item => item.marker);

                // 4. Apply visibility logic to on-screen markers
                sortedVisibleMarkers.forEach((m, index) => {
                    const isTargetEnb = hasTargets && targetEnbs.some(t => {
                        if (!m._key) return false;
                                
                        if (t.rat) {
                            return m._key.endsWith(`-${t.rat}-${t.enb}`);
                        } else {
                            return m._key.endsWith(`-${t.enb}`); // Fallback if no L/N prefix was used
                        }
                    });
                            
                    const shouldBeVisible = isTargetEnb || ((currentZoom >= 17   && index < 150 && labelLevel >= 1) ||
                                                            (currentZoom >= 14   && index < 225 && labelLevel >= 2) ||
                                                            (currentZoom >= 12   && index < 300 && labelLevel >= 3) ||
                                                            (currentZoom >= 11   && index < 375 && labelLevel >= 4) ||
                                                            (currentZoom >= 8    && index < 500 && labelLevel >= 5) ||
                                                                                                   labelLevel == 6);
                            
                    if (m instanceof L.Marker && m.options.icon instanceof L.DivIcon) {
                        if (shouldBeVisible && !mapLayerGroup.hasLayer(m)) {
                            mapLayerGroup.addLayer(m);
                        } else if (!shouldBeVisible && mapLayerGroup.hasLayer(m)) {
                            mapLayerGroup.removeLayer(m);
                        }
                            
                        if (hasTargets && mapLayerGroup.hasLayer(m) && m._icon) {
                            if (isTargetEnb) {
                                L.DomUtil.addClass(m._icon, 'target-enb-highlight');
                            } else {
                                L.DomUtil.removeClass(m._icon, 'target-enb-highlight');
                            }
                        }
                    } else {
                        const hasTooltip = !!m.getTooltip();
                        
                        // Pass the container directly to the helper to update it in place, 
                        // while capturing the string for new tooltips
                        const dynamicClassString = syncTooltipClasses(m, isTargetEnb, m.getTooltip()?._container);
                            
                        if (shouldBeVisible) {
                            if (!hasTooltip) {
                                m.bindTooltip(m.customLabelHtml, {
                                    permanent: true, direction: 'bottom', className: dynamicClassString, offset: [0, 12], interactive: true
                                });
                                m._hoverAddedTooltip = false;
                            } else if (m._hoverAddedTooltip) {
                                m._hoverAddedTooltip = false;
                            }
                        } else {
                            if (hasTooltip && !m._hoverAddedTooltip) {
                                m.unbindTooltip();
                            }
                        }
                    }
                });
            }
            
            async function fetchData(bounds, requestId) {
                let apiUrl = `https://cmgm.us/api/poly/getPolyEnbs.php?` +
                    `boundsNELatitude=${bounds.neLat}` +
                    `&boundsNELongitude=${bounds.neLng}` +
                    `&boundsSWLatitude=${bounds.swLat}` +
                    `&boundsSWLongitude=${bounds.swLng}` +
                    `&limit=${request_batch_size.value}` +
                    `&plmn=${plmn.value}` +
                    `&rat=${rat.value}` +
                    `&view_mode=${view_mode.value}` +
                    `&oldest_date=${oldest_date.value}` +
                    `&newest_date=${newest_date.value}` +
                    `&cells_allow_list=${cells_allow_list.value}` +
                    `&cells_block_list=${cells_block_list.value}` +
                    `&enb_allow_list=${enb_allow_list.value}` +
                    `&enb_block_list=${enb_block_list.value}` +
                    `&tacs_allow_list=${tacs_allow_list.value}` +
                    `&tacs_block_list=${tacs_block_list.value}` +
                    `&cells_quantity=${cells_quantity.value}` +
                    `&score=${score.value}` +
                    `&reach=${reach.value}` +
                    // `&locationTypeFilter=${locationTypeFilter.value}` +
                    `&cm_includes=${cm_includes.value}` +
                    `&cm_excludes=${cm_excludes.value}`;
                
                if (urlParams.has('mini') && !hasAutoPannedForMini) apiUrl += '&mini';
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

            // Centralized PLMN Color Mapping
            const getColor = (plmn, rat = 'LTE', random_color = false) => {
                const getRandomHexColor = () => {
                    return '#' + Math.floor(Math.random() * 16777215)
                        .toString(16)
                        .padStart(6, '0');
                };

                if (random_color) {
                    return getRandomHexColor();
                }

                const colors = {
                    '310260': rat === 'LTE' ? '#b200ae' : '#ff4dff',
                    '310410': rat === 'LTE' ? '#0059b2' : '#4da2ff',
                    '312680': rat === 'LTE' ? '#0059b2' : '#4da2ff',
                    '313100': rat === 'LTE' ? '#0059b2' : '#4da2ff',
                    '313790': rat === 'LTE' ? '#0059b2' : '#4da2ff',
                    '311480': rat === 'LTE' ? '#b20000' : '#ff4a4a',
                    '311370': '#C16C79',
                    '310120': '#FFEF87',
                    '311580': '#E8B937',
                    '311588': '#E8B937',
                    '311589': '#E8B937'
                };

                return colors[plmn] || '#666';
            };
            // Flag to ensure we only auto-pan once on initial load
            let hasAutoPannedForMini = false;

            function panToNearestEnb(data) {
                const baseLat = parseFloat(urlParams.get('latitude'));
                const baseLng = parseFloat(urlParams.get('longitude'));

                // Guard: Only run if we have valid coordinates and haven't panned yet
                if (isNaN(baseLat) || isNaN(baseLng) || !Array.isArray(data) || data.length === 0) {
                    return;
                }

                let targetTowers = [];
                const targetEnbs = getTargetEnbs();

                // 1. Find all specific eNBs passed in &mini
                if (targetEnbs.length > 0) {
                    targetTowers = data.filter(t => 
                        targetEnbs.some(target => 
                            String(t.enb) === String(target.enb) && 
                            (!target.rat || t.rat === target.rat)
                        )
                    ).map(t => ({ lat: parseFloat(t.latitude), lng: parseFloat(t.longitude) }));
                }

                // 2. Fallback: If no exact match, find the closest distance
                if (targetTowers.length === 0) {
                    let minDistanceSq = Infinity;
                    let closestTower = null;

                    data.forEach(tower => {
                        const lat = parseFloat(tower.latitude);
                        const lng = parseFloat(tower.longitude);
                        const distSq = Math.pow(lat - baseLat, 2) + Math.pow(lng - baseLng, 2);

                        if (distSq < minDistanceSq) {
                            minDistanceSq = distSq;
                            closestTower = { lat, lng };
                        }
                    });

                    if (closestTower) targetTowers.push(closestTower);
                }

                if (targetTowers.length > 0) {
                    hasAutoPannedForMini = true;

                    // Get origin marker coordinates from sessionStorage (fallback to URL params)
                    let markerLat = baseLat;
                    let markerLng = baseLng;

                    const returnUrlString = sessionStorage.getItem('polyMapReturnUrl');
                    if (returnUrlString) {
                        try {
                            const returnUrl = new URL(returnUrlString);
                            const parsedLat = parseFloat(returnUrl.searchParams.get('marker_latitude'));
                            const parsedLng = parseFloat(returnUrl.searchParams.get('marker_longitude'));

                            if (!isNaN(parsedLat) && !isNaN(parsedLng)) {
                                markerLat = parsedLat;
                                markerLng = parsedLng;
                            }
                            L.marker([markerLat, markerLng]).addTo(map);
                        } catch (err) {
                            console.error("Could not parse polyMapReturnUrl:", err);
                        }
                    }

                    // Create bounding box containing the origin marker AND ALL target towers
                    const bounds = L.latLngBounds([[markerLat, markerLng]]);
                    targetTowers.forEach(t => bounds.extend([t.lat, t.lng]));

                    // Force Leaflet to recognize true iframe pixel dimensions before bounding
                    map.invalidateSize();

                    // Adjust camera to frame everything inside the viewport
                    map.fitBounds(bounds, {
                        padding: [150, 150], 
                        maxZoom: 17
                    });
                }
            }

            async function updateData(shouldFetch = true) {
                updateUrl();
                const bounds = map.getBounds();
                const requestId = ++currentRequestId;
                const isCellView = view_mode.value === 'cells';

                if (isCellView) {
                    map.removeControl(map.attributionControl);
                } else {
                    map.addControl(map.attributionControl);
                }

                if (shouldFetch) {
                    try {
                        const clampLng = (lng) => Math.max(-180, Math.min(180, lng));

                        const data = await fetchData({
                            neLat: bounds.getNorthEast().lat,
                            neLng: clampLng(bounds.getNorthEast().lng),
                            swLat: bounds.getSouthWest().lat,
                            swLng: clampLng(bounds.getSouthWest().lng),
                        }, requestId);

                        if (!data) return;

                        const enbGroups = {};

                        // console.log("Recv'd data:", data);
                        const excludedplmns = ['310260', '310410', '311480', '310120', '311580', plmn.value];
                        const plmnNames = (plmn.value.trim() === '' || plmn.value.includes(',')) ? { '313100': 'FirstNet', '312680': 'AT&T FWA', '313790': 'Liberty' } : {};

                        data.forEach(tower => {
                            const enbId = tower.enb;
                            const markerId = `${tower.plmn}-${tower.rat}-${enbId}`;

                            if (isCellView) {
                                if (!enbGroups[markerId]) enbGroups[markerId] = [];
                                enbGroups[markerId].push({
                                    coords: [parseFloat(tower.latitude), parseFloat(tower.longitude)],
                                    sectorId: (tower.cells || tower.cells === 0) ? tower.cells : '?',
                                    plmn: tower.plmn,
                                    rat: tower.rat,
                                    rawTower: tower,
                                    enb: enbId 
                                });
                            } else if (!pointMap[markerId]) {
                                const marker = L.circleMarker([tower.latitude, tower.longitude], {
                                    radius: parseFloat(icon_size.value),
                                    fillColor: getColor(tower.plmn, tower.rat, random_color.checked),
                                    color: "#000", weight: 1.5, fillOpacity: 1,
                                    pane: 'markerPane'
                                });

                                marker.location_type = tower.location_type;
                                marker._key = markerId;

                                // Only add pin to map if it is NOT currently exploded
                                if (!explodedMap[markerId]) {
                                    marker.addTo(mapLayerGroup);
                                }
                                
                                const label = `${excludedplmns.includes(String(tower.plmn)) ? '' : `${plmnNames[tower.plmn] ?? tower.plmn}<br>`}${tower.rat === 'NR' ? 'gNB' : 'eNB'} ${tower.enb}${tower.location_type % 10 === 1 ? '★' : ''}`;
                                
                                marker.customLabelHtml = tower.cm_status === 'unmapped'
                                ? `<span class="extra-italic">${label}${!hide_cells.checked && (tower.cells || tower.cells === 0) ? '<br>Cells: ' + tower.cells : ''}</span>`
                                : `${label}${!hide_cells.checked && (tower.cells || tower.cells === 0) ? '<br>Cells: ' + tower.cells : ''}`;
                                
                                const handleContextMenu = (e) => {
                                    L.DomEvent.stopPropagation(e);
                                    if (e.originalEvent.preventDefault) e.originalEvent.preventDefault();
                                    createMenu(e, { ...tower, plmn: tower.plmn });
                                };

                                // Remove handleLeftClick and bind both click types to handleContextMenu
                                marker._hoverAddedTooltip = false; 

                                marker.on('mouseover touchstart', function(e) {
                                    map.doubleClickZoom.disable();
                                            
                                    if (!this.getTooltip()) {
                                        const targetEnbs = getTargetEnbs();
                                        const isTargetEnb = targetEnbs.length > 0 && targetEnbs.some(t => {
                                            if (!this._key) return false;
                                            return t.rat ? this._key.endsWith(`-${t.rat}-${t.enb}`) : this._key.endsWith(`-${t.enb}`);
                                        });
                                            
                                        // Fetch the unified class string
                                        const dynamicClass = syncTooltipClasses(this, isTargetEnb);
                                            
                                        this.bindTooltip(this.customLabelHtml, { permanent: true, direction: 'bottom',  className: dynamicClass,  offset: [0, 12],  interactive: true }).openTooltip();
                                            
                                        this._hoverAddedTooltip = true; 
                                    }
                                });

                                marker.on('mouseout touchend', function(e) {
                                    map.doubleClickZoom.enable();

                                    if (this._hoverAddedTooltip) {
                                        this.unbindTooltip();
                                        this._hoverAddedTooltip = false;
                                    }
                                });

                                // 1. Both Left Click and Right Click open the context menu
                                marker.on('click', handleContextMenu).on('contextmenu', handleContextMenu);

                                // 2. Middle Click (button === 1) triggers the explosion
                                marker.on('mousedown', function(e) {
                                    if (e.originalEvent.button === 1) {
                                        L.DomEvent.stopPropagation(e); 
                                        if (e.originalEvent.preventDefault) e.originalEvent.preventDefault();
                                        explodeEnb(tower);
                                    }
                                });

                                pointMap[markerId] = marker;
                            }
                        });

                        // Handle Polygon rendering if in Cell Mode
                        if (isCellView) {
                            for (const [markerId, points] of Object.entries(enbGroups)) {
                                const rawEnb = points[0].enb; 
                                const polyId = `poly-${markerId}`;
                                
                                // Draw Polygon into polygonMap
                                if (!polygonMap[polyId]) {
                                    const polyColor = getColor(points[0].plmn, points[0].rat, random_color.checked);
                                    if (points.length >= 2) {
                                        const sorted = sortPointsClockwise(points);
                                        polygonMap[polyId] = L.polygon(sorted.map(p => p.coords), {
                                            color: polyColor, weight: 2, fillOpacity: 0.2, interactive: false
                                        }).addTo(mapLayerGroup); 
                                    }
                                }

                                points.forEach(pt => {
                                    const labelId = `label-${markerId}-${pt.sectorId}`;

                                    if (pointMap[labelId]) return;


                                    // Draw label DivIcon into pointMap
                                    const labelMarker = L.marker(pt.coords, {
                                        icon: L.divIcon({ className: 'tower-label', html: `${rawEnb}-${pt.sectorId}`, icon_size: [0, 0] }),
                                        interactive: true
                                    }).addTo(mapLayerGroup); 

                                    labelMarker._key = labelId; // Pre-assign key to avoid mutations on map pan
                                    
                                    // Add context-menu to it.
                                    const handleTrigger = (e) => {
                                        L.DomEvent.stopPropagation(e);
                                        if (e.originalEvent.preventDefault) e.originalEvent.preventDefault();
                                        // Pass the raw tower data we saved earlier into the menu
                                        createMenu(e, { ...pt.rawTower, plmn: pt.plmn });
                                    };


                                    labelMarker.on('click', handleTrigger).on('contextmenu', handleTrigger);
                                    
                                    pointMap[labelId] = labelMarker;
                                });
                            }
                        }

                        if (urlParams.has('mini') && !hasAutoPannedForMini) panToNearestEnb(data);
                    } catch (err) { console.error("Fetch error:", err); }
                }

                // Consolidated Cleanup utilizing the strict maps
                if (shouldFetch && !unload.checked) { 
                    
                    // 1. Cleanup pointMap (Handles eNB pins AND Cell Labels)
                    for (let key in pointMap) {
                        const layer = pointMap[key];

                        // Don't delete markers belonging to actively exploded sites
                        if (explodedMap[key]) continue;

                        // Only remove from memory if the coordinate is physically outside the visible map bounds
                        if (!bounds.contains(layer.getLatLng())) {
                            mapLayerGroup.removeLayer(layer);
                            delete pointMap[key];
                        }
                    }

                    // 2. Cleanup polygonMap (Only removes if out of view)
                    if (isCellView) {
                        const currentBounds = map.getBounds(); // Get the current visible rectangle

                        for (let key in polygonMap) {
                            const layer = polygonMap[key];
                            const polyBounds = layer.getBounds();

                            if (!currentBounds.intersects(polyBounds)) {
                                mapLayerGroup.removeLayer(layer);
                                delete polygonMap[key];

                                // Find and remove the associated sector labels in pointMap
                                // key format is poly-{markerId}
                                const extractedMarkerId = key.substring(5);
                                for (let pKey in pointMap) {
                                    if (pKey.startsWith(`label-${extractedMarkerId}-`)) {
                                        mapLayerGroup.removeLayer(pointMap[pKey]);
                                        delete pointMap[pKey];
                                    }
                                }
                            }
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
                const cell_list_commas = tower.cells ? String(tower.cells).replace(/ /g, ",") : '';
                
                const towerPlmnStr = String(tower.plmn);
                const mcc = towerPlmnStr.slice(0, 3);
                const mnc = towerPlmnStr.slice(3);


                if (tower.tac) {
                    items.push({ label: `Copy TAC (${tower.tac})`, action: () => silentCopy(tower.tac) });
                }
                if (!urlParams.has('mini')) items.push({ label: `Copy location`, action: () => silentCopy(tower.latitude + ',' + tower.longitude) });

                items.push(
                    { isDivider: true },
                    {
                        label: 'View in Poly',
                        action: () => window.open(`https://cmgm.us/poly/?plmn_1=${tower.plmn}&rat_1=${tower.rat}&eNB_1=${tower.enb}&tac_1=${tower.tac}&cellList_1=${cell_list_commas}&cellListDepri_1=-`, '_blank')
                    }
                );

                if (!urlParams.has('mini')) {
                    items.push({
                        label: 'View in CellMapper',
                        action: () => {
                            window.open(`https://www.cellmapper.net/map?MCC=${mcc}&MNC=${mnc}&type=${tower.rat}&latitude=${tower.latitude}&longitude=${tower.longitude}&zoom=15&ppT=${tower.enb}&ppL=${tower.tac}`, '_blank');
                        }
                    });


                    // determine carrier
                    const carrierMap = {
                        310260: "T-Mobile",
                        310410: "ATT",
                        313100: "ATT",
                        311480: "Verizon",
                        310120: "Sprint"
                    };

                    let cmgm_carrier = carrierMap[tower.plmn];

                    if (cmgm_carrier) {
                        items.push({
                            label: 'View in CMGM',
                            action: () => {
                                window.open(`https://cmgm.us/database/Edit.php?q=${tower.enb}&carrier=${cmgm_carrier}`, '_blank');
                            }
                        });

                        if (tower.location_type !== 4 || (tower.cells || tower.cells === 0)) {
                            items.push({ isDivider: true });
                        }

                        if (tower.location_type !== 4) {
                            items.push(
                                {
                                    label: 'Pin',
                                    action: () => {
                                        let location = prompt('Enter location (latitude,longitude):', '');

                                        if (!location) return;

                                        let coords = location.split(',').map(c => c.trim());

                                        if (coords.length !== 2 || isNaN(coords[0]) || isNaN(coords[1])) {
                                        alert('Invalid location format. Use: latitude,longitude');
                                        return;
                                        }

                                        let latitude = coords[0];
                                        let longitude = coords[1];

                                        window.open(`https://cmgm.us/database/Edit.php?new&pullLocation&latitude=${latitude}&longitude=${longitude}&${tower.rat}_1=${tower.enb}&region_${tower.rat.toLowerCase()}=${tower.tac}&carrier=${cmgm_carrier}`, '_blank');
                                    }
                            }
                        );
                        }
                    }
                    if ((tower.cells || tower.cells === 0)) {
                        items.push(
                            { label: `Delete`, action: () => openPurgeModal(tower) }
                        );
                    }
                }

                items.forEach(opt => {
                    if (opt.isDivider) {
                        menu.appendChild(Object.assign(document.createElement('div'), { className: 'menu-divider' }));
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
                
                // Get right click menu to fit within window bounds.
                document.body.appendChild(menu);
    
                // Get the dimensions of the menu and the window
                const menuWidth = menu.offsetWidth;
                const menuHeight = menu.offsetHeight;
                const windowWidth = window.innerWidth;
                const windowHeight = window.innerHeight;
    
                // Start with the cursor position
                let leftPos = e.originalEvent.pageX;
                let topPos = e.originalEvent.pageY;
    
                // Check horizontal bounds (if menu goes off the right edge, flip it left)
                if (leftPos + menuWidth > windowWidth + window.scrollX) {
                    leftPos = leftPos - menuWidth;
                }
    
                //  Check vertical bounds (if menu goes off the bottom edge, flip it up)
                if (topPos + menuHeight > windowHeight + window.scrollY) {
                    topPos = topPos - menuHeight;
                }
    
                // Apply the safe positions
                menu.style.left = leftPos + 'px'; 
                menu.style.top = topPos + 'px';
                // Global click listener to close menu when clicking away
                setTimeout(() => {
                    window.onclick = () => {
                        if (menu && menu.parentNode) {
                            menu.remove();
                            window.onclick = null;
                        }
                    };
                }, 50);

                // Call update tower.
                triggerForceUpdate(tower);
            }

            // Add zoomend to your listeners
            map.on('moveend', slowUpdate);
            updateData();


            // Paste to Jump 
            document.addEventListener('paste', function (e) {
                if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;

                const pasted = (e.clipboardData || window.clipboardData).getData('text');
                const coords = parseLatLng(pasted);
                const jumpZoom = map.getZoom() < 13 ? 17 : map.getZoom();
                if (coords) {
                    map.setView(coords, jumpZoom);
                }
            });

            // Copy coordinates on right click
            map.on('contextmenu', function (e) {
                // Prevent the browser's default context menu
                if (e.originalEvent.preventDefault) e.originalEvent.preventDefault();

                navigator.clipboard.writeText(e.latlng.lat.toFixed(6) + ',' + e.latlng.lng.toFixed(6)).then(() => {
                    console.log("Copied Map Coords.");
                }).catch(err => {
                    console.error("Copy failed.", err);
                });
            });

            // Middle click to open SV
            map.on('mousedown', function(e) {
                if (e.originalEvent.button === 1) {
                    

                    // Hackfix to stop browser from getting stuck in drag mode
                    // Force Leaflet to drop its current drag state
                    map.dragging.disable();

                    // Re-enable dragging after a tiny delay so it works when you come back
                    setTimeout(() => {
                        map.dragging.enable();
                    }, 1);

                    // 3. Open your active tab just like before
                    window.open(`https://www.google.com/maps/@?api=1&map_action=pano&viewpoint=${e.latlng.lat},${e.latlng.lng}`, '_blank');
                }
            });

            // Button to return to regular Map.php on edit iframe.
            const polyMapReturnButton = L.Control.extend({
              onAdd: function(map) {
                const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-added-button');
                container.id = 'polyMapReturnButton';
                container.innerHTML = '<a href="#"><span style="font-size:22px;">⯁</span></a>';

                // Prevent map clicks/drags from triggering when interacting with the container
                L.DomEvent.disableClickPropagation(container);

                // 2. Bind the click event directly inside onAdd using Leaflet's event system
                const link = container.querySelector('a');
                L.DomEvent.on(link, 'click', L.DomEvent.stop) // Prevents the default '#' page jump
                          .on(link, 'click', () => {
                              window.location.href = sessionStorage.getItem('polyMapReturnUrl');
                          });

                return container;
              }
            });

            // Check for the 'mini' URL parameter
            if (new URLSearchParams(window.location.search).has('mini')) {
              map.addControl(new polyMapReturnButton({ position: 'topleft' }));
            }
        </script>
    </body>

    </html>
