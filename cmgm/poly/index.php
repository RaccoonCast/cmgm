<!DOCTYPE HTML>
<head>
<meta name="viewport" 
      content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
<?php
$poly = true;
$titleOverride = true;
include "../functions.php";

// Initialize variables from the URL parameters
isset($_GET['plmn']) && $carrier = $_GET['plmn']; 
isset($_GET['rat']) && $rat = $_GET['rat']; 
isset($_GET['eNB']) && $eNB = $_GET['eNB']; 
isset($_GET['cellList']) && $cellList = $_GET['cellList'];

// Extract eNBs
$carrierList = [];
$ratList = [];
$enbList = [];
$cellListList = [];

foreach($_GET as $key => $value) {
    if (str_starts_with($key, 'plmn')) {
        array_push($carrierList, $value);
    } else if (str_starts_with($key, 'rat')) {
        array_push($ratList, $value);
    } else if (str_starts_with($key, 'eNB')) {
        array_push($enbList, $value);
    } else if (str_starts_with($key, 'cellList')) {
        array_push($cellListList, $value);
    }
 }

 // Check that the length of all lists is the same
 // (If not, the params are invalid, and we should tell the user to re-do)
 foreach([$ratList, $enbList, $cellListList] as $value ) {
    if (count($carrierList) != count($value)) {
        echo 'bad parameters!';
        return false;
    }
 }

 // If all lists are blank, add template carrier
 if (count($carrierList) === 0) {
    // echo 'adding template item';
    array_push($carrierList, '310410');
    array_push($ratList, 'LTE');
    array_push($enbList, '');
    array_push($cellListList, '');
 }

//  echo implode(' ', $carrierList), "|", implode(' ', $ratList), "|", implode(' ', $enbList), "|", implode(' ', $cellListList);


// If URL parmeters are prefilled, handle the data
if (isset($carrier, $rat, $eNB, $cellList)) {

    $postData = [];

    // Same code as HTML generation, just without the HTML
    foreach ($carrierList as $index => $value) {
        // Get prefix
        $namedIndex = ($index === 0) ? '' : '_' . $index;

        // Add carriers to array
        $postData['plmn' . $namedIndex] = $value;

        // Add others to array
        $postData['rat' . $namedIndex] = $ratList[$index];
        $postData['eNB' . $namedIndex] = $enbList[$index];
        $postData['cellList' . $namedIndex] = $cellListList[$index];

    }


    // Initialize cURL and send POST request
    $ch = curl_init('https://cmgm.us/poly/web.php');
	// Set cURL options
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,            // Return the response as a string
        CURLOPT_POST => true,                      // Use POST method
        CURLOPT_POSTFIELDS => http_build_query($postData),  // Prepare POST fields
        CURLOPT_COOKIE => 'userID=' . $userID,    // Send userID as a cookie
        CURLOPT_SSL_VERIFYHOST => 0,              // Disable SSL host verification
        CURLOPT_SSL_VERIFYPEER => 0               // Disable SSL peer verification
    ]);
	
	// Execute the cURL request
	$response = curl_exec($ch);

    // Handle response
    $data = json_decode($response);

}

$iframe_url = isset($data->URL) ? 'src="' . $data->URL . '&hideui=true"' : 'src="https://cmgm.us/database/Map.php?&hideui=true"';

?>
<title>eNB Polygon Generator</title>
</head>
<div class="header">
   <div id="formContainerContainer">
      <div id="formsContainer">
         <?php foreach($carrierList as $index => $value) { ?>
         <form class="carrierForm">
            <!-- // Set name of index -->
            <?php $namedIndex = ($index === 0) ? '' : '_' . $index; ?>
            <!-- PLMN -->
            <select class="plmn" name="<?php echo "plmn" . $namedIndex;?>" required>
               <option value="310410"<?php if ($value == '310410') echo ' selected'; ?>>AT&T</option>
               <option value="310260"<?php if ($value == '310260') echo ' selected'; ?>>T-Mobile</option>
               <option value="311480"<?php if ($value == '311480') echo ' selected'; ?>>Verizon</option>
            </select>
            <!-- RAT -->
            <select class="rat" name="<?php echo "rat" . $namedIndex;?>" required>
               <option value="LTE"<?php if ($ratList[$index] == 'LTE') echo ' selected'; ?>>LTE</option>
               <option value="NR"<?php if ($ratList[$index] == 'NR') echo ' selected'; ?>>NR</option>
            </select>
            <!-- eNB -->
            <input type="number" class="eNB" name="<?php echo "eNB" . $namedIndex;?>" maxlength="10" required placeholder="eNB" value="<?php echo $enbList[$index]; ?>" /><!-- Cells -->
            <input type="text" class="cellList" name="<?php echo "cellList" , $namedIndex;?>" pattern="^[0-9,]+$" required placeholder="1,2,3 (Cells)" value="<?php echo $cellListList[$index]; ?>" />
         </form>
         <?php } ?>
      </div>
      <button id="addFormButton" type="button">+</button>
      <button id="submitButton" type="submit">Submit</button>
   </div>
</div>
<iframe id="iframe" <?php echo $iframe_url ?> allow="clipboard-write"></iframe>
<script src="js/poly.js"></script>
</body>
</html>