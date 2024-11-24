<!DOCTYPE HTML>
<head>
<meta name="viewport" 
      content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
<?php
$poly = true;
$titleOverride = true;
include "../functions.php";

// Initialize variables from the URL parameters
isset($_GET['carrier']) && $carrier = $_GET['carrier']; 
isset($_GET['rat']) && $rat = $_GET['rat']; 
isset($_GET['eNB']) && $eNB = $_GET['eNB']; 
isset($_GET['cellList']) && $cellList = $_GET['cellList'];

// If the form is submitted via POST, handle the data
if (isset($carrier, $rat, $eNB, $cellList)) {

    // Prepare POST data
    $postData = [
        'plmn' => $carrier,
        'rat' => $rat,
        'eNB' => $eNB,
        'cellList' => html_entity_decode($cellList)
    ];

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
?>
<title>eNB Polygon Generator</title>
</head>
<body>
  <div class="header">
    <h1>eNB Polygon Generator</h1> <span 
	id="formsContainer">
    <form id="carrierForm">
        <select id="plmn" name="plmn" required>
            <option value="310410"<?php if ($carrier == '310410') echo ' selected'; ?>>AT&T</option>
            <option value="310260"<?php if ($carrier == '310260') echo ' selected'; ?>>T-Mobile</option>
            <option value="311480"<?php if ($carrier == '311480') echo ' selected'; ?>>Verizon</option>
        </select>
        <select id="rat" name="rat" required>
            <option value="LTE"<?php if ($rat == 'LTE') echo ' selected'; ?>>LTE</option>
            <option value="NR"<?php if ($rat == 'NR') echo ' selected'; ?>>NR</option>
        </select>
        <input type="number" id="eNB" name="eNB" maxlength="10" required placeholder="eNB" value="<?php echo htmlspecialchars($eNB); ?>" />
        <input type="text" id="cellList" name="cellList" pattern="^[0-9,]+$" required placeholder="1,2,3 (Cells)" value="<?php echo htmlspecialchars($cellList); ?>" />
    </form>
</span>

<div id="buttonContainer">
    <!-- Add form button -->
    <button id="addFormButton" type="button">+</button>

    <!-- Single submit button -->
    <button id="submitButton" type="submit">Submit</button>
</div>
  </div>
  <iframe id="iframe" <?php if (isset($data->URL)) echo 'src="' . $data->URL . '&hideui=true"'; ?> allow="clipboard-write"></iframe>
  <script src="js/poly.js"></script>
</body>
</html>