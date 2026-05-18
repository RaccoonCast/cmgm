<?php
ini_set('memory_limit','4096M');
ini_set('max_execution_time', '300');
$titleOverride = true;
$allowGuests = true;
if (isset($_GET['download'])) $silent = true;
include "../functions.php";

// --- GET FILTERS & EXECUTE QUERY ---
include "../api/poly/filterPoly.php";
$result = $conn->query($sql_query);

// --- 1. BUILD SHARED DATA ARRAY ---
$validRows = [];
$hasSearchCenter = !empty($latitude) && !empty($longitude); 
$num = 0;
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) { 
        $validRows[] = $row;
        $num++;
    }
}

// --- 2. ENHANCE DATA WITH PRE-CALCULATED LINKS & FORMATTING ---
$plmnTranslations = json_decode(file_get_contents('translations.json'), true);
$plmnMap = [];
if ($plmnTranslations) {
    foreach ($plmnTranslations as $item) {
        $plmnMap[$item['plmn']] = $item['name'];
    }
}

foreach ($validRows as &$row) {
    $item_plmn = $row['plmn'];

    $row['plmnName'] = $plmnMap[$item_plmn] ?? $item_plmn;
    $row['tac']      = $row['tac'] ?? 0;
    $row['rat']      = $row['rat'] ?? "LTE";
    $row['enbId']    = $row['enb'];

    $row['display_lat'] = round($row['latitude'], 5);
    $row['display_lon'] = round($row['longitude'], 5);

    // Pre-build shared URLs
    $row['dasLink'] = "https://api.cellmapper.net/v6/overrideData?"
        . "MCC=" . substr($item_plmn, 0, 3) . "&MNC=" . substr($item_plmn, 3)
        . "&Region={$row['tac']}&RAT={$row['rat']}&Site={$row['enbId']}"
        . "&CellID=null&Latitude=null&Longitude=null"
        . "&action=setAttribute&attribute=TOWER_TYPE&attribute_value=DAS";

    $row['polyLink'] = "https://cmgm.us/poly?"
        . "plmn={$item_plmn}&zoom=17&rat={$row['rat']}"
        . "&eNB={$row['enbId']}&tac={$row['tac']}&cellListDepri=-";

    $row['enbLink'] = "https://www.cellmapper.net/map?"
        . "MCC=" . substr($item_plmn, 0, 3) . "&MNC=" . substr($item_plmn, 3)
        . "&latitude={$row['latitude']}&longitude={$row['longitude']}"
        . "&zoom=18&rat={$row['rat']}&ppT={$row['enbId']}&ppL={$row['tac']}";

    $row['locationLink'] = "https://maps.google.com/maps?f=q&source=s_q&hl=en&q="
        . "{$row['display_lat']},{$row['display_lon']}";
}
unset($row); // Break reference

// --- 3. HANDLE CSV DOWNLOAD ---
if (isset($_GET['download'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="enbs.csv"');
    $output = fopen('php://output', 'w');
    
    // Header
    $csvHeader = ['PLMN', 'RAT', 'ID', 'TAC', 'Latitude', 'Longitude', 'Cells', 'First Seen Date'];
    if (!isset($_GET['slim'])) {
        $csvHeader[] = 'Poly Link';
        $csvHeader[] = 'DAS Link';
    }
    fputcsv($output, $csvHeader, ',', '"', '\\');
    
    // Rows
    foreach ($validRows as $row) {
        $csvData = [
            $row['plmn'],
            $row['rat'],
            $row['enbId'],
            $row['tac'],
            $row['display_lat'],
            $row['display_lon'],
            ($viewMode === "cells") ? trim($row['cell']) : trim($row['cells']),
            ($viewMode === "cells") ?  $row['date_of_info'] : $row['oldest_date']
        ];
        if (!isset($_GET['slim'])) {
            $csvData[] = $row['polyLink'];
            $csvData[] = $row['dasLink'];
        }
        fputcsv($output, $csvData, ',', '"', '\\');
    }
    fclose($output);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "../includes/functions/headhtml.php"; ?>
<script>
// Custom PLMN functionality
document.addEventListener('change', (e) => {
  if (e.target.id !== 'Plmn') return;

  const select = e.target;

  if (select.value !== '_custom_') return;

  let plmn = prompt('Enter custom PLMN:');

  if (!plmn) {
    select.value = '';
    return;
  }

  plmn = plmn.trim();

  // Basic validation
  if (!/^\d{6}(,\d{6})*$/.test(plmn)) {
    alert('Invalid PLMN.');
    select.value = '';
    return;
  }

  // Check if option already exists
  let option = Array.from(select.options).find(o => o.value === plmn);

  if (!option) {
    option = new Option(plmn, plmn, true, true);
    select.insertBefore(option, select.querySelector('[value=""]'));
  }

  select.value = plmn;
});

document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    form.addEventListener("submit", function () {
        const latInput = document.querySelector('input[name="latitude"]');
        const lngInput = document.querySelector('input[name="longitude"]');
        if (!latInput || !lngInput) return;
        const raw = latInput.value;
        if (raw.includes(",")) {
            const parts = raw.split(",").map(v => v.trim());
            if (parts.length === 2) {
                latInput.value = parts[0];
                lngInput.value = parts[1];
            }
        }
    });
});
function copyToClipboard(text) {
    var dummy = document.createElement("textarea");
    document.body.appendChild(dummy);
    dummy.value = text;
    dummy.select();
    document.execCommand("copy");
    document.body.removeChild(dummy);
}
function getQueryVariable(variable) {
       var query = window.location.search.substring(1);
       var vars = query.split("&");
       for (var i=0;i<vars.length;i++) {
               var pair = vars[i].split("=");
               if(pair[0] == variable){return pair[1];}
       }
       return(false);
}
function myFunction2() {
    var lat = getQueryVariable("latitude");
    var long = getQueryVariable("longitude");
    var latlong = lat + "," + long;
    copyToClipboard(latlong);
}
</script>
<meta charset="UTF-8">
<title>CMGM - Mapper (<?=$num?>)</title>
</head>
<body>
<div class="form">
<form method="get"> 
    <?php include "formBuilder.php";?>
</form>
</div>

<table>
<thead>
<tr>
    <th>Carrier</th>
    <th>eNB</th>
    <th>TAC</th>
    <th>Location</th>
    <th>Cells</th>
    <th>Poly Link</th>
    <th>DAS Link</th>
</tr>
</thead>
<tbody>
<?php
if (count($validRows) > 0) {
    foreach ($validRows as $row) {
        echo "<tr class=\"tr\">
            <td title='{$row['plmn']}'>" . htmlspecialchars($row['plmnName']) . " - {$row['rat']}</td>
            <td><a href='{$row['enbLink']}' target='_blank'>{$row['enbId']}</a></td>
            <td>{$row['tac']}</td>
            <td><a onclick=\"copyToClipboard('{$row['display_lat']},{$row['display_lon']}'); return false;\" 
                   href='{$row['locationLink']}' target='_blank'>{$row['display_lat']},{$row['display_lon']}</a></td>
            <td>";

        if (!empty(trim($row['cells']))) {
            $cellsArray = explode(' ', trim($row['cells']));
            $cellLinks = array_map(function($c) use ($row) {
                $url = "https://cmgm.us/AppleSurro/?carrier={$row['plmn']}&cid=$c&tac={$row['tac']}&rat={$row['rat']}";
                return "<a href='$url' title='$c' target='_blank' style='margin-right:2px;'>$c</a>";
            }, $cellsArray);
            echo implode(', ', $cellLinks);
        }

        echo "</td>
            <td><a href='{$row['polyLink']}' target='_blank'>Poly Link</a></td>
            <td><a href='{$row['dasLink']}' target='_blank'>Set as DAS</a></td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='7'>No results found within the specified parameters.</td></tr>";
}
?>
</tbody>
</table>
</body>
</html>