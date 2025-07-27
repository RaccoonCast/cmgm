<!DOCTYPE HTML>
<head>
<meta name="viewport" 
      content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
<?php
$poly = true;
$titleOverride = true;
include "../functions.php";

$firstRun = true;

// Extract eNBs and TACs
$carrierList = [];
$ratList = [];
$enbList = [];
$cellListList = [];
$cellListDepriList = [];
$tacList = [];  // <-- New TAC array

foreach($_GET as $key => $value) {
    if (str_starts_with($key, 'plmn')) {
        array_push($carrierList, $value);
    } else if (str_starts_with($key, 'rat')) {
        array_push($ratList, $value);
    } else if (str_starts_with($key, 'eNB')) {
        array_push($enbList, $value);
    } else if (str_starts_with($key, 'cellList') && !str_starts_with($key, 'cellListDepri')) {
        array_push($cellListList, $value);
    } else if (str_starts_with($key, 'cellListDepri')) {
        array_push($cellListDepriList, $value);
    } else if (str_starts_with($key, 'tac')) {  // <-- Collect TAC values
        array_push($tacList, $value);
    }
}

// Check that the length of all lists is the same
foreach([$ratList, $enbList] as $value ) {
    if (count($carrierList) != count($value)) {
        echo 'bad parameters: ' . json_encode($carrierList) . " vs " . json_encode($value) . " for " . $key;
        return false;
    }
}

// If all lists are blank, add template carrier (and TAC)
if (count($carrierList) === 0) {
    array_push($carrierList, '310410');
    array_push($ratList, 'LTE');
    array_push($enbList, '');
    array_push($cellListList, '');
    array_push($cellListDepriList, '');
    array_push($tacList, ''); // <-- Default tac value
}

$responses = null;
// If URL parameters are prefilled, handle the data
if (
    array_filter($carrierList, 'is_numeric') === $carrierList &&
    array_filter($ratList, fn($val) => $val === 'LTE' || $val === 'NR') === $ratList &&
    array_filter($enbList, 'is_numeric') === $enbList &&
    array_filter($cellListList, fn($val) => preg_match('/^\d+(,\d+)*$/', $val)) === $cellListList &&
    array_filter($cellListDepriList, fn($val) => preg_match('/^\d+(,\d+)*$/', $val)) === $cellListDepriList
) {
    $postData = [];

    // Same code as HTML generation, just without the HTML
    foreach ($carrierList as $index => $value) {
        // Get prefix
        $namedIndex = ($index === 0) ? '' : '_' . $index;

        // Add carriers to array
        $polyFormData['plmn' . $namedIndex] = $value;

        // Add others to array
        $polyFormData['rat' . $namedIndex] = $ratList[$index];
        $polyFormData['eNB' . $namedIndex] = $enbList[$index];
        $polyFormData['cellList' . $namedIndex] = $cellListList[$index];
        $polyFormData['cellListDepri' . $namedIndex] = $cellListDepriList[$index];
        $polyFormData['tac' . $namedIndex] = $tacList[$index] ?? '';  // <-- Added TAC

    }
    // Create polygon and poly url.
    include "../poly/master.php";
    if (!empty($responses)) include "../poly/poly.php";
}

// Make sure URL is returned, otherwise return regular map
$iframe_url = isset($url) ? $url . '&hideui=true' : 'https://cmgm.us/database/Map.php?&hideui=true';

if (isset($_GET['marker_latitude']) && isset($_GET['marker_longitude']) && isset($_GET['hidePolyForm'])) {
    $iframe_url .= '&marker_latitude=' . $_GET['marker_latitude'] . '&marker_longitude=' . $_GET['marker_longitude'] . '&showPolyLink';
}

?>
<title>eNB Polygon Generator</title>
 <script src="js/polyInfoButton.js"></script>
</head>
<?php if (!isset($_GET['hidePolyForm'])) {?>
<div class="header">
   <div id="formContainerContainer">
      <div id="formsContainer">
        <!-- Add carriers -->
         <?php foreach($carrierList as $index => $value) { ?>
         <form class="carrierForm">
            <!-- // Set name of index -->
            <?php $namedIndex = ($index === 0) ? '' : '_' . $index; ?>
            <!-- PLMN -->
            <select class="plmn" name="<?php echo "plmn" . $namedIndex;?>" required>
                <option value="310410"<?php if ($value == '310410') echo ' selected'; ?>>AT&T</option>
                <option value="313100"<?php if ($value == '313100') echo ' selected'; ?>>FirstNet</option>
                <option value="310120"<?php if ($value == '310120') echo ' selected'; ?>>Sprint</option>
                <option value="310260"<?php if ($value == '310260') echo ' selected'; ?>>T-Mobile</option>
                <option value="311480"<?php if ($value == '311480') echo ' selected'; ?>>Verizon</option>
            </select>
            <!-- RAT -->
            <select class="rat" name="<?php echo "rat" . $namedIndex;?>" required>
               <option value="LTE"<?php if ($ratList[$index] == 'LTE') echo ' selected'; ?>>LTE</option>
               <option value="NR"<?php if ($ratList[$index] == 'NR') echo ' selected'; ?>>NR</option>
            </select>
            <!-- eNB -->
            <input type="number" class="eNB" name="<?php echo "eNB" . $namedIndex;?>" maxlength="10" required placeholder="<?php echo $ratList[$index] == 'NR' ? 'gNB' : 'eNB'; ?>" value="<?php echo $enbList[$index]; ?>" /><!-- Cells -->
            <!-- TAC -->
            <input type="number" class="tac" name="<?php echo "tac" . $namedIndex;?>" placeholder="TAC" value="<?php echo $tacList[$index] ?? ''; ?>"></input>
            <!-- Cells -->
            <input type="text" class="cellList" name="<?php echo "cellList" . $namedIndex;?>" pattern="^[0-9,]+$" required placeholder="1,2,3 (Cells)" value="<?php echo $cellListList[$index] ?? ''; ?>" />
            <input type="text" class="cellListDepri" name="<?php echo "cellListDepri" . $namedIndex;?>" pattern="^[0-9,]+$" placeholder="7,8,9 (Extra Cells)" value="<?php echo $cellListDepriList[$index] ?? ''; ?>" />
            <!-- Delete button -->
            <input type="button" value="❌" class="closeButton" onclick="removeForm(this);"/>
         </form>
         <?php } ?>
      </div>
      <?php if (!isMobile()) { ?>
	  <div id="dontCacheCheckbox">
		<label for="forceNewResults">Ignore cache</label>
		<input id="forceNewResults" name="dontCache" type="checkbox">
	  </div>
		 <?php } ?>
      <button id="addFormButton" type="button">+</button>
      <button id="submitButton" type="submit">Submit</button>
   </div>
</div>
<?php } ?>
<script>
    const jsonResponse = `<?php echo json_encode($responses) ?>`; const responseData = jsonResponse ? btoa(jsonResponse) : null;

    const skipPolyInfoButton = '<?= isset($_GET['marker_latitude']) && isset($_GET['marker_longitude']) ? 'true' : 'false' ?>' === 'true';

    // create iframe
    const iframe = document.createElement('iframe');
    iframe.id = 'iframe';
    iframe.allow = 'clipboard-write';

      
    // add event listener for partial load (minus images)
    iframe.addEventListener('load', (_event) => {
        console.log('jsr:', jsonResponse);

        if ((jsonResponse == 'null' && window.latestData == undefined) || skipPolyInfoButton) {
            // console.log('JSON response is null, skip providing button data for now');
        } else {
            addInfoData(iframe, responseData);
        }
    });

    // add iframe to dom
    console.log('ready state:', document.readyState);
    if (document.readyState == 'interactive' || document.readyState == 'complete') {
        document.body.appendChild(iframe);
    } else {
        // add iframe to dom when ready
        let stateChanged = false;
        const listener = document.addEventListener('readystatechange', () => {
            if (stateChanged) {
                document.removeEventListener('readystatechange', listener);
                return;
            }
            stateChanged = true;
            document.body.appendChild(iframe);
        })
    }
    

    // send iframe to URL
    iframe.src = "<?= $iframe_url ?>";
    
</script>
<!-- <iframe id="iframe" src="<?php echo $iframe_url; ?>" allow="clipboard-write" onload="console.log('jsr:', jsonResponse); (jsonResponse == 'null' && window.latestData == undefined) ? console.log('JSON response = null, skip') : addInfoData(this, responseData)"></iframe> -->
<!-- <script>document.getElementById('iframe').contentWindow.postMessage('polyInfo', '');</script> -->
<?php $firstRun = false; ?>
<script src="js/poly.js"></script>
</body>
</html>
