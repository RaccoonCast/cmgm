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
        $dateOfData = mysqli_fetch_assoc(mysqli_query($conn, "SELECT last_run FROM local_poly_enbs_date"))['last_run'];
        $dateOfData = (new DateTime($dateOfData, new DateTimeZone('UTC')))
            ->setTimezone(new DateTimeZone('America/Los_Angeles'))
            ->format('Y-m-d H:i:s');
        ?>
        <div class="header">
            <div class="formsContainerContainer">
                <div id="formsContainer">
                    <?php include "includes/plmn-and-rat-selector.php"; ?>

                    <select class="misc_cw" title="Set batch size" name="requestBatchSize" id="requestBatchSize">
                        <option style="display:none" value="<?php if ($limit !== 0)
                            echo $limit; ?>" selected>
                            Batch size: <?php echo $limit; ?>
                        </option>
                        <?php if ($limit == 0) { ?>
                            <option style="display:none" value="0" selected>
                                Batch size: Unlimited
                            </option> <?php } ?>
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
                    <select class="misc_cw" title="Set view mode" name="viewMode" id="viewMode">
                        <?php 
                        if ($viewMode == 'enbs') $viewModeName = "View Mode: eNB"; 
                        if ($viewMode == 'cells') $viewModeName = "View Mode: Cell";
                        ?>
                        <option style="display:none" value="<?= $viewMode; ?>" selected>
                            <?= $viewModeName; ?>
                        </option>
                        <option value="enbs">eNB</option>
                        <option value="cells">Cell</option>
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

            document.addEventListener('DOMContentLoaded', () => {
                const button = document.getElementById('gui-button');

                button.addEventListener('click', () => {
                    let currentUrl = new URL(window.location.href);

                    // Replace only the filename in the path
                    currentUrl.pathname = currentUrl.pathname.replace(/[^/]+$/, 'gui.php');

                    // Remove specific query parameters
                    // currentUrl.searchParams.delete('viewMode');
                    // currentUrl.searchParams.delete('iconSize');
                    // currentUrl.searchParams.delete('labelSettings');

                    // Update the browser URL without reloading
                    window.location.href = currentUrl.toString();
                });
            });

            // something gemini said is needed for ios
            document.addEventListener("touchstart", function () { }, true);
            // Manage form
            const plmn = document.getElementById('Plmn');
            const rat = document.getElementById('Rat');
            const requestBatchSize = document.getElementById('requestBatchSize');
            const iconSize = document.getElementById('iconSize');
            const labelSettings = document.getElementById('labelSettings');
            const unload = document.getElementById('dontUnload');
            const randomColor = document.getElementById('randomColor');
            const oldest_date = document.getElementById('oldest_date');
            const newest_date = document.getElementById('newest_date');
            const perfectSurroOnly = document.getElementById('perfectSurroOnly');
            const cellsAllowList = document.getElementById('cellsAllowList');
            const cellsBlockList = document.getElementById('cellsBlockList');
            const enbAllowList = document.getElementById('enbAllowList');
            const enbBlockList = document.getElementById('enbBlockList');
            const score = document.getElementById('score');
            const cellQuantity = document.getElementById('cellQuantity');
            const viewMode = document.getElementById('viewMode');
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

            const viewModeMap = {
                "enbs": "eNB",
                "cells": "Cell"
            };

            // Mapping for prefixes
            const labelPrefixes = {
                requestBatchSize: "Batch size",
                iconSize: "Icon size",
                labelSettings: "Labels",
                viewMode: `View Mode`
            };

            const customPrompts = {
                Plmn: "Enter Custom PLMN:",
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
                
                // Special case: label settings mapping
                if (el.id === 'labelSettings') {
                    labelOption.text = `Labels: ${labelMap[el.value]}`;
                    labelOption.value = el.value;
                    el.selectedIndex = 0;
                return;
                }

                // Special case: View Mode settings
                if (el.id === 'viewMode') {
                    const lastSeenBox = document.getElementById('newest_date');
                    const cellQuantityBox = document.getElementById('cellQuantity');
                    if (el.value == 'cells') lastSeenBox.setAttribute("disabled", "true");
                    if (el.value == 'cells') cellQuantityBox.setAttribute("disabled", "true");
                    if (el.value == 'enbs') lastSeenBox.removeAttribute("disabled");
                    if (el.value == 'enbs') cellQuantityBox.removeAttribute("disabled");
                    labelOption.text = `View Mode: ${viewModeMap[el.value]}`;
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
            // labels.checked = urlParams.get('labels') !== 'false';
            unload.checked = urlParams.has('dontUnload');
            // forceLabelVisibility.checked = urlParams.has('forceLabelVisibility');

            // Apply initial prefixes
            updateSelectLabel(requestBatchSize);
            updateSelectLabel(iconSize);

            // Elements that require a full map reset/clear
            const resetTriggers = [
                plmn, rat, oldest_date, newest_date,
                cellsAllowList, cellsBlockList, enbAllowList,
                enbBlockList, tacsAllowList, tacsBlockList,
                perfectSurroOnly, viewMode, randomColor, score, cellQuantity
            ];

            // Elements that update UI or visuals without clearing data
            const visualTriggers = [iconSize, labelSettings, unload, requestBatchSize];

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
                    if (el === iconSize) {
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
                preferCanvas: true, boxZoom: true, zoomSnap: 0, zoomDelta: 0.8, worldCopyJump: true,
                wheelPxPerZoomLevel: 120, wheelDebounceTime: 100, maxZoom: 19,
            }).setView([parseFloat(urlParams.get('latitude')) || 34.1317, parseFloat(urlParams.get('longitude')) || -118.2630], parseFloat(urlParams.get('zoom')) || 14);
            map.attributionControl.setPrefix('<?php echo "Last updated: " . $dateOfData ?> <a href="https://cmgm.us/api/poly/updatePolyEnbs.php">⟳</a>');

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19
            }).addTo(map)

            // We split the markerMap into two strict dictionaries
            let pointMap = {};   // For L.CircleMarker (pins) and L.Marker (labels)
            let polygonMap = {}; // For L.Polygon (cell areas)
            const mapLayerGroup = L.layerGroup().addTo(map);

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

            function clearAllMarkers(tower = null) {
                if (!tower) {
                    Object.values(pointMap).forEach(m => mapLayerGroup.removeLayer(m));
                    Object.values(polygonMap).forEach(m => mapLayerGroup.removeLayer(m));
                    pointMap = {}; polygonMap = {};
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
                setOrDeleteParam('viewMode', viewMode.value);
                setOrDeleteParam('labelSettings', labelSettings.value);
                setOrDeleteParam('cellQuantity', cellQuantity.value);
                setOrDeleteParam('score', score.value);

                // if (labels.checked) {
                //     urlParams.set('labels', 'true');
                // } else {
                //     urlParams.set('labels', 'false');
                // }

                // if (forceLabelVisibility.checked) {
                //     urlParams.set('forceLabelVisibility', 'true');
                // } else {
                //     urlParams.delete('forceLabelVisibility');
                // }

                if (unload.checked) {
                    urlParams.set('dontUnload', 'true');
                } else {
                    urlParams.delete('dontUnload');
                }
                if (randomColor.checked) {
                    urlParams.set('randomColor', 'true');
                } else {
                    urlParams.delete('randomColor');
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
                const center = map.getCenter();
                const bounds = map.getBounds();
                const currentZoom = map.getZoom();
                const labelLevel = parseInt(labelSettings.value);

                // 1. Separate markers into on-screen and off-screen
                let visibleOnScreen = [];
                let offScreen = [];

                Object.values(pointMap).forEach(m => {
                    if (m.getLatLng && bounds.contains(m.getLatLng())) {
                        visibleOnScreen.push(m);
                    } else {
                        offScreen.push(m);
                    }
                });

                // 2. Unbind/Hide all off-screen markers immediately (Zero DOM impact)
                offScreen.forEach(m => {
                    if (m instanceof L.Marker && m.options.icon instanceof L.DivIcon) {
                        if (mapLayerGroup.hasLayer(m)) mapLayerGroup.removeLayer(m);
                    } else if (m.getTooltip()) {
                        m.unbindTooltip();
                    }
                });

                // 3. Pre-calculate distance ONLY for pins currently on the screen
                const markersWithDistance = visibleOnScreen.map(m => ({
                    marker: m,
                    distance: map.distance(center, m.getLatLng())
                }));

                // Sort the heavily reduced array
                markersWithDistance.sort((a, b) => a.distance - b.distance);
                const sortedVisibleMarkers = markersWithDistance.map(item => item.marker);

                // 4. Apply visibility logic to on-screen markers
                sortedVisibleMarkers.forEach((m, index) => {
                    const shouldBeVisible = ((currentZoom > 17   && index < 150 && labelLevel >= 1) ||
                                             (currentZoom > 14   && index < 250 && labelLevel >= 2) ||
                                             (currentZoom > 12   && index < 350 && labelLevel >= 3) ||
                                             (currentZoom > 10.5 && index < 450 && labelLevel >= 4) ||
                                             (currentZoom > 8    && index < 600 && labelLevel >= 5) ||
                                                                                    labelLevel == 6);

                    if (m instanceof L.Marker && m.options.icon instanceof L.DivIcon) {
                        // For Cell Mode labels: completely remove from map instead of visibility: hidden
                        if (shouldBeVisible && !mapLayerGroup.hasLayer(m)) {
                            mapLayerGroup.addLayer(m);
                        } else if (!shouldBeVisible && mapLayerGroup.hasLayer(m)) {
                            mapLayerGroup.removeLayer(m);
                        }
                    } else {
                        const hasTooltip = !!m.getTooltip();
                        
                        if (shouldBeVisible) {
                            if (!hasTooltip) {
                                // Bind normally because zoom/settings dictate it should be visible
                                m.bindTooltip(m.customLabelHtml, {
                                    permanent: true, direction: 'bottom', className: 'tower-label', offset: [0, 12], interactive: true
                                });
                                m._hoverAddedTooltip = false;
                            } else if (m._hoverAddedTooltip) {
                                // It's currently hovered, but zoom/settings say it should be permanent now.
                                // "Promote" it by clearing the hover flag so mouseout ignores it.
                                m._hoverAddedTooltip = false;
                            }
                        } else {
                            // Zoom/settings dictate it shouldn't be visible
                            if (hasTooltip && !m._hoverAddedTooltip) {
                                // Unbind ONLY if it wasn't triggered by an active hover
                                m.unbindTooltip();
                            }
                        }
                    }
                });
            }
            
            async function fetchData(bounds, requestId) {
                let apiUrl = `https://cmgm.us/api/poly/getPolyEnbs.php?boundsNELatitude=${bounds.neLat}&boundsNELongitude=${bounds.neLng}&boundsSWLatitude=${bounds.swLat}&boundsSWLongitude=${bounds.swLng}&limit=${requestBatchSize.value}&plmn=${plmn.value}&rat=${rat.value}&viewMode=${viewMode.value}&oldest_date=${oldest_date.value}&newest_date=${newest_date.value}&cellsAllowList=${cellsAllowList.value}&cellsBlockList=${cellsBlockList.value}&enbAllowList=${enbAllowList.value}&enbBlockList=${enbBlockList.value}&tacsAllowList=${tacsAllowList.value}&tacsBlockList=${tacsBlockList.value}&cellQuantity=${cellQuantity.value}&score=${score.value}&locationType=2&perfectSurroOnly=${perfectSurroOnly.checked ? 'true' : ''}`;
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
                const isCellView = viewMode.value === 'cells';
                let visibleEnbIds = new Set(); 

                
                if (isCellView) {
                    map.removeControl(map.attributionControl);
                } else {
                    map.addControl(map.attributionControl);
                }
                // Centralized PLMN Color Mapping
                const getColor = (plmn, rat = 'LTE', randomColor = false) => {
                    const getRandomHexColor = () => {
                        return '#' + Math.floor(Math.random() * 16777215)
                            .toString(16)
                            .padStart(6, '0');
                    };

                    if (randomColor) {
                        return getRandomHexColor();
                    }

                    const colors = {
                        '310260': rat === 'LTE' ? '#b200ae' : '#ff4dff',
                        '310410': rat === 'LTE' ? '#0059b2' : '#4da2ff',
                        '313100': rat === 'LTE' ? '#0059b2' : '#4da2ff',
                        '311480': rat === 'LTE' ? '#b20000' : '#ff4a4a',
                        '311370': '#C16C79',
                        '310120': '#FFEF87',
                        '311580': '#E8B937'
                    };

                    return colors[plmn] || '#666';
                };

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

                        console.log("Recv'd data:", data);

                        Object.keys(data).forEach(plmnKey => {
                            data[plmnKey].forEach(tower => {

                                const enbId = tower.enb;
                                const markerId = `${plmnKey}-${tower.rat}-${enbId}`;
                                
                                visibleEnbIds.add(markerId);

                                if (isCellView) {
                                    if (!enbGroups[markerId]) enbGroups[markerId] = [];
                                    enbGroups[markerId].push({
                                        coords: [parseFloat(tower.latitude), parseFloat(tower.longitude)],
                                        sectorId: (tower.cells || tower.cells === 0) ? tower.cells : '?',
                                        plmn: plmnKey,
                                        rat: tower.rat,
                                        rawTower: tower,
                                        enb: enbId // Added raw enbId back into the object just in case your polygon drawer needs it
                                    });
                                } else if (!pointMap[markerId]) {
                                    // Draw individual eNB pins into pointMap
                                    const marker = L.circleMarker([tower.latitude, tower.longitude], {
                                        radius: parseFloat(iconSize.value),
                                        fillColor: getColor(plmnKey, tower.rat, randomColor.checked),
                                        color: "#000", weight: 1.5, fillOpacity: 1
                                    }).addTo(mapLayerGroup); 
                                    
                                    const excludedPlmns = ['310260', '310410', '311480', '310120', '311580'];
                                    
                                    const label = `${ excludedPlmns.includes(String(tower.plmn)) ? '' : `${tower.plmn}<br>` }${tower.rat === 'NR' ? 'gNB' : 'eNB'} ${tower.enb}${ tower.is_exact_location === 1 ? '★' : '' }`;

                                    // Store the HTML string in a custom property on the marker, DO NOT bind it yet.
                                    marker.customLabelHtml = `${label}${(tower.cells || tower.cells === 0) ? '<br>Cells: ' + tower.cells : ''}`;

                                    const handleTrigger = (e) => {
                                        L.DomEvent.stopPropagation(e);
                                        if (e.originalEvent.preventDefault) e.originalEvent.preventDefault();
                                        createMenu(e, { ...tower, plmn: plmnKey });
                                    };

                                    // Initialize the custom hover state flag
                                    marker._hoverAddedTooltip = false; 

                                    marker.on('mouseover', function(e) {
                                        // Only bind if a tooltip doesn't already exist (from zoom settings)
                                        if (!this.getTooltip()) {
                                            this.bindTooltip(this.customLabelHtml, {
                                                permanent: true, direction: 'bottom', className: 'tower-label', offset: [0, 12], interactive: true
                                            }).openTooltip();
                                            this._hoverAddedTooltip = true; // Mark that WE added this via hover
                                        }
                                    });

                                    marker.on('mouseout', function(e) {
                                        // Only unbind if it was added by our hover event
                                        if (this._hoverAddedTooltip) {
                                            this.unbindTooltip();
                                            this._hoverAddedTooltip = false;
                                        }
                                    });

                                    marker.on('click', handleTrigger).on('contextmenu', handleTrigger);
                                    pointMap[markerId] = marker;
                                }
                            });
                        });

                        // Handle Polygon rendering if in Cell Mode
                        if (isCellView) {
                            // CHANGED: enb is now markerId because of our earlier fix
                            for (const [markerId, points] of Object.entries(enbGroups)) {
                                const rawEnb = points[0].enb; // Grab raw ENB for visual labels & copying
                                const polyId = `poly-${markerId}`;
                                
                                // Draw Polygon into polygonMap
                                if (!polygonMap[polyId]) {
                                    const polyColor = getColor(points[0].plmn, points[0].rat, randomColor.checked);
                                    if (points.length >= 3) {
                                        const sorted = sortPointsClockwise(points.map(p => p.coords), points.map(p => ({ coords: p.coords, info: p })));
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
                                        icon: L.divIcon({ className: 'tower-label', html: `${rawEnb}-${pt.sectorId}`, iconSize: [0, 0] }),
                                        interactive: true
                                    }).addTo(mapLayerGroup); 
                                    
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
                    } catch (err) { console.error("Fetch error:", err); }
                }

                // Consolidated Cleanup utilizing the strict maps
                if (shouldFetch && !unload.checked) { 
                    
                    // 1. Cleanup pointMap (Handles eNB pins AND Cell Labels)
                    for (let key in pointMap) {
                        const layer = pointMap[key];

                        if (isCellView) {
                            // In Cell mode, pointMap only contains DivIcon labels
                            if (key.startsWith('label-')) {
                                // Extract markerId. Format is label-{markerId}-{sectorId}
                                const lastDash = key.lastIndexOf('-');
                                const extractedMarkerId = key.substring(6, lastDash);
                                
                                if (!visibleEnbIds.has(extractedMarkerId)) {
                                    mapLayerGroup.removeLayer(layer);
                                    delete pointMap[key];
                                }
                            }
                        } else {
                            // In Standard Mode, pointMap only contains CircleMarkers
                            if (!bounds.contains(layer.getLatLng())) {
                                mapLayerGroup.removeLayer(layer);
                                delete pointMap[key];
                            }
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
                const cell_list_commas = String(tower.cells).replace(/ /g, ",");


                if (tower.tac) {
                    items.push({ label: `Copy TAC (${tower.tac})`, action: () => silentCopy(tower.tac) });
                }
                items.push({ label: `Copy location`, action: () => silentCopy(tower.latitude + ',' + tower.longitude) });

                items.push(
                    { isDivider: true },
                    { label: 'View in Poly', action: () => window.open(`https://cmgm.us/poly/?plmn_1=${tower.plmn}&rat_1=${tower.rat}&eNB_1=${tower.enb}&tac_1=${tower.tac}&cellList_1=${cell_list_commas}&cellListDepri_1=-`, '_blank') },
                    {
                        label: 'View in CellMapper', action: () => {
                            let mnc = tower.plmn.slice(3);
                            let mcc = tower.plmn.slice(0, 3);
                            window.open(`https://www.cellmapper.net/map?MCC=${mcc}&MNC=${mnc}&type=${tower.rat}&latitude=${tower.latitude}&longitude=${tower.longitude}&zoom=15&ppT=${tower.enb}&ppL=${tower.tac}`, '_blank');
                        }
                    }
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

                // Delete data option.
                items.push(
                    { isDivider: true },
                    { label: `Delete`, action: () => openPurgeModal(tower) }
                );

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
                triggerForceUpdate(tower)
            }

            // Add zoomend to your listeners
            map.on('moveend', slowUpdate);
            // map.on('zoomend', slowUpdate); This ensures labels re-evaluate on zoom, temporarily disabling.

            updateData();


            // Paste to Jump 
            document.addEventListener('paste', function (e) {
                if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;

                const pasted = (e.clipboardData || window.clipboardData).getData('text');
                const coords = parseLatLng(pasted);
                let jumpZoom;
                if (map.getZoom() < 13) {
                    jumpZoom = 17;
                } else {
                    jumpZoom = map.getZoom();
                }
                if (coords) {
                    map.setView(coords, jumpZoom);
                }
            });

            // 2. Copy coordinates on right click
            map.on('contextmenu', function (e) {
                // Prevent the browser's default context menu
                if (e.originalEvent.preventDefault) e.originalEvent.preventDefault();

                navigator.clipboard.writeText(e.latlng.lat.toFixed(6) + ',' + e.latlng.lng.toFixed(6)).then(() => {
                    console.log("Copied Map Coords.");
                }).catch(err => {
                    console.error("Copy failed.", err);
                });
            });
        </script>
    </body>

    </html>
