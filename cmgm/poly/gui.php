<?php
ini_set('memory_limit','512M');
$titleOverride = true;
if (isset($_GET['download']))  $silent = true;
include "../functions.php";

// --- GET FILTERS ---
$plmn            = !empty($_GET['plmn']) ? (int)$_GET['plmn'] : null;
$cellsAllowlist  = !empty($_GET['cells_allow']) ? $_GET['cells_allow'] : null;
$cellsBlocklist  = !empty($_GET['cells_block']) ? $_GET['cells_block'] : null;
$tacsAllowlist  = !empty($_GET['tacs_allow']) ? $_GET['tacs_allow'] : null;
$tacsBlocklist  = !empty($_GET['tacs_block']) ? $_GET['tacs_block'] : null;
$enbAllowList = !empty($_GET['enb_allowList']) ? $_GET['enb_allowList'] : null;
$enbBlockList = !empty($_GET['enb_blockList']) ? $_GET['enb_blockList'] : null;
$rat             = !empty($_GET['rat']) ? $_GET['rat'] : null;
$lat             = !empty($_GET['lat']) ? $_GET['lat'] : null;
$lon             = !empty($_GET['lon']) ? $_GET['lon'] : null;
$radius          = !empty($_GET['radius']) ? $_GET['radius'] : null;
$trueLimit           = !empty($_GET['limit']) ? $_GET['limit'] : null;

// --- BUILD SQL ---
$distanceExpr = ""; // will hold Haversine if geographic filter is applied

$sql = "
    SELECT 
        enb, cell, cell_id, plmn, rat, tac, latitude, longitude
";

// Add distance column if geo filter exists
if (!is_null($lat) && !is_null($lon)) {
    $lat = floatval($lat);
    $lon = floatval($lon);
    if (is_null($radius)) $radius = 12500;
    $radius = floatval($radius);

    $distanceExpr = "(3959 * 2 * ASIN(SQRT(
        POWER(SIN(RADIANS(latitude - $lat) / 2), 2) +
        COS(RADIANS($lat)) * COS(RADIANS(latitude)) *
        POWER(SIN(RADIANS(longitude - $lon) / 2), 2)
    )))";

    $sql .= ", $distanceExpr AS distance";
}

$sql .= "
    FROM local_poly
    WHERE
          latitude IS NOT NULL
      AND longitude IS NOT NULL
";

// PLMN filter
if (!is_null($plmn)) {
    $plmnValue = $plmn;
    $sql .= $plmnFilter = " AND plmn = '" . mysqli_real_escape_string($conn, $plmnValue) . "'";
    $limit = 2500;
} elseif (!isset($plmn) && empty($default_carrier)) {
    if ($default_carrier == "T-Mobile") $plmnValue = 310260;
    if ($default_carrier == "ATT") $plmnValue = 310410;
    if ($default_carrier == "Verizon") $plmnValue = 311480;
    if ($default_carrier == "Sprint") $plmnValue = 310120;

    $sql .= $plmnFilter = " AND plmn = '" . mysqli_real_escape_string($conn, $plmnValue) . "'";
    $limit = 5000;
} else {
    $limit = 25000;
}

if (isset($_GET['download'])) { $limit = 250000; }
if (isset($trueLimit)) { $limit = $trueLimit; }

// RAT filter
if (!is_null($rat)) {
    $sql .= $ratFilter = " AND rat = '" . mysqli_real_escape_string($conn, $rat) . "'";
}

// eNB allowlist ranges
if (!is_null($enbAllowList)) {
    $enbAllowArray = explode(',', $enbAllowList);
    $enbConditions = [];

    foreach ($enbAllowArray as $range) {
        if (strpos($range, '-') !== false) {
            $bounds = explode('-', $range);
            if (count($bounds) == 2) {
            $start = (int)$bounds[0];
            $end   = (int)$bounds[1];
            $enbConditions[] = "enb BETWEEN $start AND $end";
            }
        } else {
            $enbConditions[] = "enb = $range";
        }
        }
        $bounds = explode('-', $range);
    }
    
// eNB blocklist ranges
if (!is_null($enbBlockList)) {
    $enbBlockArray = explode(',', $enbBlockList);
    $enbConditions = [];

    foreach ($enbBlockArray as $range) {
        if (strpos($range, '-') !== false) {
            $bounds = explode('-', $range);
            if (count($bounds) == 2) {
            $start = (int)$bounds[0];
            $end   = (int)$bounds[1];
            $enbConditions[] = "enb NOT BETWEEN $start AND $end";
            }
        } else {
            $enbConditions[] = "enb != $range";
        }
        }
        $bounds = explode('-', $range);
    }

if (!empty($enbConditions)) {
    $sql .= " AND (" . implode(' AND ', $enbConditions) . ")";
}

// Cell allowlist
if (!is_null($cellsAllowlist)) {
    $cellList = implode(',', array_map('intval', explode(',', $_GET['cells_allow'])));
    $sql .= " AND enb IN (SELECT enb FROM local_poly WHERE cell IN ($cellList) " . @$plmnFilter . " " . @$ratFilter . " AND latitude IS NOT NULL AND longitude IS NOT NULL GROUP BY enb)";
}

// Cell blocklist
if (!is_null($cellsBlocklist)) {
    $cellBlockList = implode(',', array_map('intval', explode(',', $_GET['cells_block'])));
    $sql .= " AND enb NOT IN (SELECT enb FROM local_poly WHERE cell IN ($cellBlockList) " . @$plmnFilter . " " . @$ratFilter . " AND latitude IS NOT NULL AND longitude IS NOT NULL GROUP BY enb)";
}

// TAC allowlist
if (!is_null($tacsAllowlist)) {
    $tacList = implode(',', array_map('intval', explode(',', $_GET['tacs_allow'])));
    $sql .= " AND enb IN (SELECT enb FROM local_poly WHERE tac IN ($tacList) " . @$plmnFilter . " " . @$ratFilter . " AND latitude IS NOT NULL AND longitude IS NOT NULL GROUP BY enb)";
}

// TAC blocklist
if (!is_null($tacsBlocklist)) {
    $tacBlockList = implode(',', array_map('intval', explode(',', $_GET['tacs_block'])));
    $sql .= " AND enb NOT IN (SELECT enb FROM local_poly WHERE tac IN ($tacBlockList) " . @$plmnFilter . " " . @$ratFilter . " AND latitude IS NOT NULL AND longitude IS NOT NULL GROUP BY enb)";
}


// Geographic filter (reuse distanceExpr so no duplicate formula)
if ($distanceExpr !== "") {
    $sql .= " AND $distanceExpr <= $radius";
}

// ---------------- ORDER BY ----------------
if ($distanceExpr !== "") {
    // Sort by nearest → farthest
    $sql .= " ORDER BY distance ASC";
} else {
    // Default behavior
    $sql .= " ORDER BY enb, cell";
}

$sql .= " LIMIT $limit";

if (isset($_GET['showsql'])) echo $sql;
$result = $conn->query($sql);
if (!$result) die("Query error: " . $conn->error);

// --- BUILD STRUCTURE ---
$enbs = [];
while ($row = $result->fetch_assoc()) {
    $enb  = (int)$row['enb'];
    $cell = (int)$row['cell'];
    $cell_id = (int)$row['cell_id'];
    $plmn = (int)$row['plmn'];
    $rat  = $row['rat'];
    $tac  = (int)$row['tac'];
    $lat  = (float)$row['latitude'];
    $lng  = (float)$row['longitude'];

    // Initialize eNB entry if not exists
    if (!isset($enbs[$enb])) {
        $enbs[$enb] = [
            'cells'     => [],
            'cell_ids'     => [],
            'rat'       => $rat,
            'plmn'      => $plmn,
            'tac'       => $tac,
            'lat_sum'   => 0,
            'lng_sum'   => 0,
            'count'     => 0
        ];
    }

    // Add unique cell
    if (!in_array($cell, $enbs[$enb]['cells'], true)) {
        $enbs[$enb]['cells'][] = $cell;
    }    
	
	// Add unique cell
    if (!in_array($cell_id, $enbs[$enb]['cell_ids'], true)) {
        $enbs[$enb]['cell_ids'][] = $cell_id;
    }

    // Sum lat/lng and increment count
    $enbs[$enb]['lat_sum'] += $lat;
    $enbs[$enb]['lng_sum'] += $lng;
    $enbs[$enb]['count']++;
}

// Calculate averaged lat/lng for each eNB
foreach ($enbs as $enb => $data) {
    $enbs[$enb]['latitude'] = $data['lat_sum'] / $data['count'];
    $enbs[$enb]['longitude'] = $data['lng_sum'] / $data['count'];

    sort($enbs[$enb]['cells'], SORT_NUMERIC);
    $enbs[$enb]['cell_list'] = implode(' ', $enbs[$enb]['cells']);
}

$result->free();
$conn->close();

if (isset($_GET['download'])) {
    // Send headers to force download
    header('Content-Type: text/csv');
	header('Content-Disposition: attachment; filename="enbs.csv"');
	
	$output = fopen('php://output', 'w');
	
	// Write CSV header
	fputcsv($output, ['PLMN', 'RAT', 'ID', 'TAC', 'Latitude', 'Longitude', 'Cells', 'Poly Link', 'DAS Link']);
	
	// Loop through ENBs and output CSV rows
	foreach ($enbs as $enbId => $entry) {
		$plmn = $entry['plmn'];
		$tac = $entry['tac'];
		$rat = $entry['rat'];
		$latitude = round($entry['latitude'], 5);
		$longitude = round($entry['longitude'], 5);
	
		$dasLink = "https://api.cellmapper.net/v6/overrideData?"
				. "MCC=" . substr($plmn, 0, 3)
				. "&MNC=" . substr($plmn, 3)
				. "&Region=$tac&RAT=$rat&Site=$enbId"
				. "&CellID=null&Latitude=null&Longitude=null"
				. "&action=setAttribute&attribute=TOWER_TYPE&attribute_value=DAS";
	
		$polyLink = "https://cmgm.us/poly?"
				. "plmn=" . $plmn
				. "&zoom=17"
				. "&rat=" . $rat
				. "&eNB=" . $enbId
				. "&tac=" . $tac
				. "&cellListDepri=-";
	
		$enbLink = "https://www.cellmapper.net/map?"
				. "MCC=" . substr($plmn, 0, 3)
				. "&MNC=" . substr($plmn, 3)
				. "&latitude=" . $entry['latitude']
				. "&longitude=" . $entry['longitude']
				. "&zoom=18&rat=$rat&ppT=$enbId&ppL=$tac";
	
		$locationLink = "https://maps.google.com/maps?f=q&source=s_q&hl=en&q=" . $latitude . "," . $longitude;
	
		// Write CSV row
		fputcsv($output, [
			"$plmn",
			"$rat",
			$enbId,
			$tac,
			$latitude,
			$longitude,
			implode(' ', $entry['cells']),
			$polyLink,
			$dasLink
		]);
}

fclose($output);
exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<script>
 function copyToClipboard(text) {
    var dummy = document.createElement("textarea");
    // to avoid breaking orgain page when copying more words
    // cant copy when adding below this code
    // dummy.style.display = 'none'
    document.body.appendChild(dummy);
    //Be careful if you use texarea. setAttribute('value', value), which works with "input" does not work with "textarea". – Eduard
    dummy.value = text;
    dummy.select();
    document.execCommand("copy");
    document.body.removeChild(dummy);
}
// PRETTY INFO DISPLAY
function getQueryVariable(variable)
{
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
var latlong = lat + "," + long
console.log(lat);
console.log(long);
console.log(latlong);
copyToClipboard(latlong);
 }
</script>
<meta charset="UTF-8">
<title>CMGM - Mapper</title>

</head>
<body>
<!-- FILTER FORM -->
<div class="form">
<form method="get">
    <input type="text" name="plmn" placeholder="PLMN (e.g. 310410)" value="<?= @$plmnValue ?>">
    <input type="text" name="cells_allow" placeholder="Cells List Whitelist" value="<?= @$_GET['cells_allow'] ?>">
    <input type="text" name="cells_block" placeholder="Cells List Blacklist" value="<?= @$_GET['cells_block'] ?>">
    <input type="text" name="enb_allowList" placeholder="eNB Range Whitelist" value="<?= @$_GET['enb_allowList'] ?>">
    <input type="text" name="enb_blockList" placeholder="eNB Range Blocklist" value="<?= @$_GET['enb_blockList'] ?>">
    <input type="text" name="tacs_allow" placeholder="TACs List Whitelist" value="<?= @$_GET['tacs_allow'] ?>">
    <input type="text" name="tacs_block" placeholder="TACs List Blacklist" value="<?= @$_GET['tacs_block'] ?>">
	<select name="rat" id="rat">
        <option value="" <?php if (empty($_GET['rat'])) echo 'selected';?>>LTE & NR</option>
        <option value="LTE" <?php if ($_GET['rat'] == "LTE") echo 'selected';?>>LTE</option>
	<option value="NR" <?php if ($_GET['rat'] == "NR") echo 'selected';?>>NR</option>
    </select>
    <input type="text" name="lat" placeholder="Latitude" value="<?= @$_GET['lat'] ?>">
    <input type="text" name="lon" placeholder="Longitude" value="<?= @$_GET['lon'] ?>">
    <input type="number" step="0.1" name="radius" placeholder="Radius (miles)" value="<?= @$_GET['radius'] ?>">
    <input class="poly-btn colorized" type="submit" value="View">
</form>
    <button class="poly-btn" onclick="location.href=location.href+'&download'">View as CSV</button>
</div>

<!-- TABLE -->
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
    <!-- <th>Current Type</th> -->
</tr>
</thead>
<tbody>
<?php
// Load translation.json once at the top
$plmnTranslations = json_decode(file_get_contents('translations.json'), true);
$plmnMap = [];
foreach ($plmnTranslations as $item) {
    $plmnMap[$item['plmn']] = $item['name'];
}

// Now generate table rows
foreach ($enbs as $enbId => $entry) {
    $plmn = (string)$entry['plmn'];
    $plmnName = $plmnMap[$plmn] ?? $plmn; // fallback to numeric PLMN
    $tac = $entry['tac'];
    $rat = $entry['rat'];
    $latitude = round($entry['latitude'], 5);
    $longitude = round($entry['longitude'], 5);
    $curr_type = !empty($entry['current_type']) ? $entry['current_type'] : "Unknown";

    $dasLink = "https://api.cellmapper.net/v6/overrideData?"
             . "MCC=" . substr($plmn, 0, 3)
             . "&MNC=" . substr($plmn, 3)
             . "&Region=$tac&RAT=$rat&Site=$enbId"
             . "&CellID=null&Latitude=null&Longitude=null"
             . "&action=setAttribute&attribute=TOWER_TYPE&attribute_value=DAS";

    $enbLink = "https://www.cellmapper.net/map?"
             . "MCC=" . substr($plmn, 0, 3)
             . "&MNC=" . substr($plmn, 3)
             . "&latitude=" . $entry['latitude']
             . "&longitude=" . $entry['longitude']
             . "&zoom=18&rat=$rat&ppT=$enbId&ppL=$tac";

    $polyLink = "https://cmgm.us/poly?"
             . "plmn=" . $plmn
             . "&zoom=17"
             . "&rat=" . $rat
             . "&eNB=" . $enbId
             . "&tac=" . $tac
             . "&cellListDepri=-";

    $locationLink = "https://maps.google.com/maps?f=q&source=s_q&hl=en&q=" . $latitude . "," . $longitude;

echo "<tr class=\"tr\">
        <td title='$plmn'>$plmnName - $rat</td>
        <td><a href='$enbLink' target='_blank'>$enbId</a></td>
        <td>$tac</td>
        <td><a onclick=\"copyToClipboard('$latitude,$longitude'); return false;\" href='$locationLink' target='_blank'>$latitude,$longitude</a></td>
        <td>";
            // Each cell as a link with its own hover for cell_id, separated by commas
            echo implode(', ', array_map(function($c, $id) use ($plmn, $tac, $rat) {
                $url = "https://cmgm.us/AppleSurro/?carrier=$plmn&cid=$id&tac=$tac&rat=$rat";
                return "<a href='$url' title='$id' target='_blank' style='margin-right:2px;'>$c</a>";
            }, $entry['cells'], $entry['cell_ids']));
echo "</td>
        <td><a href='$polyLink' target='_blank'>Poly Link</a></td>
        <td><a href='$dasLink' target='_blank'>Set as DAS</a></td>
      </tr>";
}


?>
</tbody>
</table>
</body>
</html>
