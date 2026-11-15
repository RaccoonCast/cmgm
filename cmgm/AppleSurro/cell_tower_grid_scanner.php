<?php include '../functions.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cell Tower Grid Scanner</title>
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Leaflet Draw CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body, html { margin: 0; padding: 0; width: 100%; height: 100%; font-family: 'Inter', sans-serif; }
        #map { width: 100%; height: 100vh; z-index: 10; }
        #ui-panel {
            position: absolute; top: 20px; right: 20px; width: 340px; z-index: 1000;
            background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(5px);
            border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            max-height: 90vh; overflow-y: auto;
        }
        .leaflet-draw-tooltip { z-index: 1000 !important; }
    </style>
</head>
<body class="overflow-hidden">

    <div id="map"></div>

    <div id="ui-panel" class="p-5 flex flex-col gap-4">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Grid Scanner</h2>
            <p class="text-xs text-gray-500 mt-1">Draw a rectangle on the map to define the scan bounds.</p>
        </div>

        <!-- Filters & Settings -->
        <div class="flex flex-col gap-3 bg-gray-50 p-3 rounded border border-gray-200">
            <div class="flex flex-col gap-1">
                <div class="flex justify-between items-center">
                    <label class="text-xs font-bold text-gray-700">Grid Box Size</label>
                    <span id="gridSizeLabel" class="text-xs font-bold text-blue-600">4.0 mi</span>
                </div>
                <input type="range" id="gridSizeSlider" min="3.5" max="8" step="0.1" value="4.0" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
            </div>

            <div class="flex flex-col gap-1 mt-1">
                <label class="text-xs font-bold text-gray-700">RAT Type (Requires Scan)</label>
                <select id="ratSelect" class="text-sm p-1.5 border border-gray-300 rounded">
                    <option value="LTE">LTE</option>
                    <option value="NR">NR (5G)</option>
                </select>
            </div>
            
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-gray-700">PLMN Filter (Instant)</label>
                <input type="text" id="plmnFilter" placeholder="e.g. 310260" class="text-sm p-1.5 border border-gray-300 rounded">
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-gray-700">TAC Filter (Instant)</label>
                <input type="text" id="tacFilter" placeholder="e.g. 219" class="text-sm p-1.5 border border-gray-300 rounded">
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-gray-700">eNB Filter Ranges (Instant)</label>
                <input type="text" id="enbFilter" placeholder="e.g. 30000-309999, 1-5" class="text-sm p-1.5 border border-gray-300 rounded">
            </div>
        </div>

        <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
            <div id="progress-bar" class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
        </div>

        <div class="text-xs text-gray-600 bg-gray-100 p-2 rounded border border-gray-200 min-h-[40px]" id="status-text">
            Waiting for area selection...
        </div>

        <div class="flex flex-col gap-2 mt-2">
            <button id="startBtn" disabled class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white font-bold py-2 px-4 rounded transition-colors shadow-sm">
                Start Scan
            </button>
            <div class="flex gap-2">
                <button id="downloadBtn" class="hidden flex-1 bg-green-600 hover:bg-green-700 text-white text-sm font-bold py-2 px-2 rounded transition-colors shadow-sm">
                    JSON Data
                </button>
                <button id="downloadTacBtn" class="hidden flex-1 bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold py-2 px-2 rounded transition-colors shadow-sm">
                    TAC/eNB CSV
                </button>
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

    <script>
        let map, drawnItems, scanBounds = null, scanLayerGroup, markerLayerGroup, isScanning = false;
        
        let rawResults = [];
        let filteredResults = [];
        
        const MILES_PER_LAT_DEGREE = 69;
        const BATCH_SIZE = 20;

        const startBtn = document.getElementById('startBtn');
        const downloadBtn = document.getElementById('downloadBtn');
        const downloadTacBtn = document.getElementById('downloadTacBtn');
        const statusText = document.getElementById('status-text');
        const progressBar = document.getElementById('progress-bar');
        
        const ratSelect = document.getElementById('ratSelect');
        const plmnFilterInput = document.getElementById('plmnFilter');
        const tacFilterInput = document.getElementById('tacFilter');
        const enbFilterInput = document.getElementById('enbFilter');
        const gridSizeSlider = document.getElementById('gridSizeSlider');
        const gridSizeLabel = document.getElementById('gridSizeLabel');

        function initMap() {
            map = L.map('map').setView([34.1351, -118.2428], 11);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
				maxZoom: 19,
				attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
			}).addTo(map);

            drawnItems = new L.FeatureGroup();
            scanLayerGroup = new L.FeatureGroup();
            markerLayerGroup = new L.FeatureGroup();
            
            map.addLayer(drawnItems);
            map.addLayer(scanLayerGroup);
            map.addLayer(markerLayerGroup);

            const drawControl = new L.Control.Draw({
                draw: { polygon: false, polyline: false, circle: false, marker: false, circlemarker: false, rectangle: { shapeOptions: { color: '#3b82f6', weight: 2 } } },
                edit: { featureGroup: drawnItems, edit: false }
            });
            map.addControl(drawControl);

            const rectangleDrawer = new L.Draw.Rectangle(map, drawControl.options.draw.rectangle);
            rectangleDrawer.enable();

            map.on(L.Draw.Event.CREATED, function (event) {
                drawnItems.clearLayers();
                scanLayerGroup.clearLayers();
                markerLayerGroup.clearLayers();
                drawnItems.addLayer(event.layer);
                scanBounds = event.layer.getBounds();
                
                startBtn.disabled = false;
                downloadBtn.classList.add('hidden');
                downloadTacBtn.classList.add('hidden');
                statusText.innerText = "Area selected. Ready to scan.";
                progressBar.style.width = '0%';
                
                rawResults = []; 
                filteredResults = [];
            });
            
            startBtn.addEventListener('click', startScan);
            downloadBtn.addEventListener('click', downloadJSON);
            downloadTacBtn.addEventListener('click', downloadTACsCSV);

            plmnFilterInput.addEventListener('input', applyFilters);
            tacFilterInput.addEventListener('input', applyFilters);
            enbFilterInput.addEventListener('input', applyFilters);
            
            gridSizeSlider.addEventListener('input', (e) => {
                gridSizeLabel.innerText = `${e.target.value} mi`;
            });
        }

        async function fetchCellsData(lat, lng, rat) {
            try {
                const response = await fetch(`./api.php?lat=${lat}&lng=${lng}&rat=${rat}`);
                const data = await response.json();
                if (data.status === 'success' && data.data && data.data.surrounding) {
                    return data.data.surrounding;
                }
                return []; 
            } catch (e) {
                console.error("API Fetch Error:", e);
                return [];
            }
        }

        function calculateEnb(tower, rat) {
            if (rat === 'LTE') {
                return Math.floor(tower.cellId / 256);
            }
            
            const plmn = String(tower.mcc) + String(tower.mnc);
            switch (plmn) {
                case '310260': return Math.floor(tower.cellId / 4096);
                case '310410': return Math.floor(tower.cellId / 1024);
                case '313100': return Math.floor(tower.cellId / 1024);
                case '311480': return Math.floor(tower.cellId / 16384);
                case '313340': return Math.floor(tower.cellId / 16);
                default: return Math.floor(tower.cellId / 4096);
            }
        }

        function passesFilters(tower, plmnFilter, tacFilter, enbFilter) {
            if (plmnFilter) {
                const towerPlmn = "" + tower.mcc + tower.mnc;
                if (towerPlmn !== plmnFilter) return false;
            }

            if (tacFilter) {
                if (String(tower.tacId) !== String(tacFilter)) return false;
            }

            if (enbFilter) {
                if (tower.enb === null || tower.enb === undefined) return false;
                const ranges = enbFilter.split(',').map(s => s.trim());
                let matched = false;
                
                for (let r of ranges) {
                    if (r.includes('-')) {
                        const [min, max] = r.split('-').map(Number);
                        if (tower.enb >= min && tower.enb <= max) { matched = true; break; }
                    } else {
                        if (tower.enb === Number(r)) { matched = true; break; }
                    }
                }
                if (!matched) return false;
            }
            return true;
        }

        function renderMarkers(towers) {
            markerLayerGroup.clearLayers();
            
            towers.forEach(tower => {
                if (tower.latitude && tower.longitude) {
                    const blackDotIcon = L.divIcon({
                        className: 'custom-div-icon',
                        html: "<div style='background-color: #000000; width: 8px; height: 8px; border-radius: 50%; border: 1px solid white;'></div>",
                        iconSize: [8, 8],
                        iconAnchor: [4, 4]
                    });

                    const marker = L.marker([tower.latitude, tower.longitude], { icon: blackDotIcon });
                    
                    marker.bindPopup(`
                        <strong>Cell ID:</strong> ${tower.cellId}<br>
                        <strong>TAC:</strong> ${tower.tacId}<br>
                        <strong>eNB:</strong> ${tower.enb}<br>
                        <strong>PLMN:</strong> ${tower.mcc}${tower.mnc}<br>
                        <strong>PCI:</strong> ${tower.pci ?? 'N/A'}
                    `);

                    markerLayerGroup.addLayer(marker);
                }
            });
        }

        function applyFilters() {
            if (isScanning || rawResults.length === 0) return;
            
            const plmnFilter = plmnFilterInput.value.trim();
            const tacFilter = tacFilterInput.value.trim();
            const enbFilter = enbFilterInput.value.trim();

            filteredResults = rawResults.filter(tower => passesFilters(tower, plmnFilter, tacFilter, enbFilter));
            
            renderMarkers(filteredResults);
            
            statusText.innerHTML = `<span class="text-green-600 font-bold">Scan Complete!</span><br>Found ${filteredResults.length} filtered towers (out of ${rawResults.length} total).`;
            
            if (filteredResults.length > 0) {
                downloadBtn.classList.remove('hidden');
                downloadTacBtn.classList.remove('hidden');
            } else {
                downloadBtn.classList.add('hidden');
                downloadTacBtn.classList.add('hidden');
            }
        }

        async function startScan() {
            if (!scanBounds || isScanning) return;
            isScanning = true;
            startBtn.disabled = true;
            downloadBtn.classList.add('hidden');
            downloadTacBtn.classList.add('hidden');
            rawResults = [];
            filteredResults = [];
            scanLayerGroup.clearLayers();
            markerLayerGroup.clearLayers();
            
            const rat = ratSelect.value;
            const gridSizeMiles = parseFloat(gridSizeSlider.value);
            const north = scanBounds.getNorth();
            const south = scanBounds.getSouth();
            const west = scanBounds.getWest();
            const east = scanBounds.getEast();

            const latStep = gridSizeMiles / MILES_PER_LAT_DEGREE;
            
            const scanPoints = [];
            let currentLat = north - (latStep / 2);
            
            while (currentLat >= south) {
                const milesPerLngDegree = 69.1 * Math.cos(currentLat * Math.PI / 180);
                const lngStep = gridSizeMiles / milesPerLngDegree;
                let currentLngIndex = 0;

                while (true) {
                    let currentLng = west + (lngStep / 2) + (currentLngIndex * lngStep);
                    if (currentLng > east) break;
                    
                    scanPoints.push({ lat: currentLat, lng: currentLng, latStep, lngStep });
                    currentLngIndex++;
                }
                currentLat -= latStep;
            }

            const totalSteps = scanPoints.length;
            let currentStepCount = 0;

            for (let i = 0; i < totalSteps; i += BATCH_SIZE) {
                const batch = scanPoints.slice(i, i + BATCH_SIZE);
                
                statusText.innerHTML = `Scanning batch ${Math.floor(i/BATCH_SIZE) + 1} of ${Math.ceil(totalSteps/BATCH_SIZE)}...<br>Processed: ${currentStepCount} / ${totalSteps}`;

                const batchPromises = batch.map(async (point) => {
                    const cellData = await fetchCellsData(point.lat, point.lng, rat);
                    return { point, cellData };
                });

                const batchResults = await Promise.all(batchPromises);

                for (const result of batchResults) {
                    drawScanBox(result.point.lat, result.point.lng, result.point.latStep, result.point.lngStep);
                    
                    result.cellData.forEach(tower => {
                        tower.enb = calculateEnb(tower, rat);
                        rawResults.push(tower);
                    });
                    
                    currentStepCount++;
                }

                let pct = Math.min(100, Math.round((currentStepCount / totalSteps) * 100));
                progressBar.style.width = `${pct}%`;
            }

            isScanning = false;
            startBtn.disabled = false;
            
            rawResults = Array.from(new Map(rawResults.map(item => [item.cellId + '-' + item.pci, item])).values());
            
            progressBar.style.width = '100%';
            applyFilters();
        }

        function drawScanBox(centerLat, centerLng, latStep, lngStep) {
            const bounds = [[centerLat - latStep/2, centerLng - lngStep/2], [centerLat + latStep/2, centerLng + lngStep/2]];
            const boxColor = '#22c55e';
            
            const rect = L.rectangle(bounds, { color: boxColor, weight: 1, fillColor: boxColor, fillOpacity: 0.3 });
            rect.bindPopup(`<strong>Area Center:</strong><br>${centerLat.toFixed(4)}, ${centerLng.toFixed(4)}<br><strong>Status:</strong> Processed`);
            scanLayerGroup.addLayer(rect);
        }

        function triggerDownload(content, filename, type) {
            const blob = new Blob([content], { type: type });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }

        function downloadJSON() {
            triggerDownload(JSON.stringify(filteredResults, null, 4), `cell_scan_results_${new Date().getTime()}.json`, 'application/json');
        }

        function downloadTACsCSV() {
            const tacCounts = {};
            filteredResults.forEach(t => {
                if (t.tacId != null) {
                    tacCounts[t.tacId] = (tacCounts[t.tacId] || 0) + 1;
                }
            });
        
            const sortedTacs = Object.entries(tacCounts)
                .sort((a, b) => b[1] - a[1]);
		
            const uniqueEnbs = [...new Set(filteredResults.map(t => t.enb).filter(t => t != null))].sort((a, b) => a - b);
		
            let csvContent = "occurences,tacId\n";
            csvContent += sortedTacs.map(([tac, count]) => {
                const paddedCount = String(count).padStart(4, '0'); // Pads to ensure 3 leading zeros (e.g., 5 -> 0005)
                return `${paddedCount},${tac}`;
            }).join("\n");
            
            csvContent += "\n\nenbId\n";
            csvContent += uniqueEnbs.join("\n");
		
            triggerDownload(csvContent, `unique_tacs_enbs_${new Date().getTime()}.csv`, 'text/csv');
        }

        window.onload = initMap;
    </script>
</body>
</html>