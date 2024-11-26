<!DOCTYPE HTML>
<head>
<meta name="viewport" 
      content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
<?php
$poly = true;
$titleOverride = true;
include "../functions.php";
?>
<title>eNB Polygon Generator</title>
</head>
<body>
  <div class="header">
    <h1>eNB Polygon Generator</h1> <div id="formsContainer">
    <form id="carrierForm">
        <select id="plmn" name="plmn" required>
            <option value="310410">AT&T</option>
            <option value="310260">T-Mobile</option>
            <option value="311480">Verizon</option>
        </select>
        <select id="rat" name="rat" required>
            <option value="LTE">LTE</option>
            <option value="NR">NR</option>
        </select>
        <input type="number" id="eNB" name="eNB" maxlength="10" required placeholder="eNB" value="" />
        <input type="text" id="cellList" name="cellList" pattern="^[0-9,]+$" required placeholder="1,2,3 (Cells)" value="<?php echo htmlspecialchars($cellList); ?>" />
    </form>
</div>

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