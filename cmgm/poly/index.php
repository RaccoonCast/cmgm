<!DOCTYPE HTML>
<head>
<?php
$titleOverride = true;
include "../functions.php";
?>
<title>eNB Polygon Generator</title>
</head>
</body>
  <div class="header">
    <h1>eNB Polygon Generator</h1>
    <form id="carrierForm">
    <a id="mapLink" href="#" style="display: none;">Map URL</a>
      <select id="carrier" name="carrier" required>
        <option value="310410">AT&T</option>
        <option value="310260">T-Mobile</option>
        <option value="311480">Verizon</option>
      </select>
      <input type="number" id="eNB" name="eNB" maxlength="10" required placeholder="eNB" />
      <input type="text" id="cellList" name="cellList" pattern="^[0-9,]+$" required placeholder="1,2,3 (Cells)" />
      <button type="submit">Submit</button>
    </form>
  </div>
  <iframe id="iframe" allow="clipboard-write" style="display: none;"></iframe>
  <script src="js/poly.js"></script>
</body>
</html>