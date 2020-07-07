<!doctype html>
<html lang="en">
<head>
  <?php include '../functions.php';?>
</head>
<body>
<?php
$conn = mysqli_connect($servername, $username, $password, $dbname);
$row_id = $_GET['row_id'];

// THIS CODE ONLY GETS RUN AFTER CONFIRMATION

if (isset($_GET['delete_confirmed'])) {
  $sql = "DELETE FROM `cmgm`.`database_db` WHERE  `row_id`=" . $row_id . ";";
  mysqli_query($conn, $sql); // First parameter is just return of "mysqli_connect()" function
  echo '<meta http-equiv="refresh" content="0;URL=../" /> ';
}

$sql = "SELECT * FROM database_db WHERE row_id = $row_id;";
$result = mysqli_query($conn, $sql);
?>

<form action="Delete.php" id="form1" method="get">
  <input type="hidden" name="row_id" value="<?php echo $row_id?>">
  <input type="hidden" name="delete_confirmed" value="true">
  <table border="0">
<table border="0">
<thead>
<tr>
    <th>RowID</th>
    <th>ID</th>
    <th>Carrier</th>
    <th>Latitude</th>
    <th>Longitude</th>
    <th>City</th>
    <th>Zip</th>
    <th>State</th>
    <th>Address</th>
    <th>Bio</th>
    <th title="Evidence score">EV</th>
    <th>Evidence</th>
    <th title="Permit says cellsite?">EV1</th>
    <th title="Permit matches suspected carrier">EV2</th>
    <th title="Trails match suspected address with the suspected carrier">EV3</th>
    <th title="Trails rule out other carriers">EV4</th>
    <th title="Antennas look like suspected carrier">EV5</th>
    <th title="CellMapper triangulates very close to the suspected location">EV6</th>
    <th title="On-site image evidence of a site identifier matching the suspected carrier">EV7</th>
    <th title="On-site verification">EV8</th>
    <th title="Multiple carriers">MC</th>
</tr>
</thead>
<tbody>
<?php
while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
  $colCount = 1;
    echo "<tr>";
    ?>

    <?php
    foreach ($row as $field => $value) {
      $sepCount = ($colCount++);
                  switch ($sepCount) {
                    case 1: $row_id = $value; break;
                    case 2: $id = $value; break;
                    case 3: $carrier = $value; break;
                    case 4: $latitude = $value;  break;
                    case 5: $longitude = $value; break;
                    case 6: $city = $value; break;
                    case 7: $zip = $value; break;
                    case 8: $state = $value; break;
                    case 9: $address = $value;break;
                    case 10: $bio = $value;break;
                    case 11: $evidence_score = $value;break;
                    case 12: $evidence_link = $value;break;
                    case 13: $permit_cellsite = $value;break;
                    case 14: $permit_suspected_carrier = $value;break;
                    case 15: $trails_match = $value;break;
                    case 16: $other_carriers_dont = $value;break;
                    case 17: $antennas_match_carrier = $value;break;
                    case 18: $cellmapper_triangulation = $value;break;
                    case 19: $image_evidence = $value;break;
                    case 20: $verified_by_visit = $value;break;
                    case 21: $carrier_multiple = $value;
?>
  <tr>
<td><input type="text" class="row_id" name="row_id" value="<?php echo $row_id?>"></td>
<td><input type="text" class="id" name="id" value="<?php echo $id?>"></td>
<td><input type="text" class="carrier" name="carrier" value="<?php echo $carrier?>"></td>
<td><input type="text" class="latitude" name="latitude" value="<?php echo $latitude?>"></td>
<td><input type="text" class="longitude" name="longitude" value="<?php echo $longitude?>"></td>
<td><input type="text" class="city" name="city" value="<?php echo $city?>"></td>
<td><input type="text" class="zip" name="zip" value="<?php echo $zip?>"></td>
<td><input type="text" class="state" name="state" value="<?php echo $state?>"></td>
<td><input type="text" class="address" name="address" value="<?php echo $address?>"></td>
<td><input type="text" class="bio" name="bio" value="<?php echo $bio?>"></td>
<td><input type="text" class="evidence_score" name="evidence_score" value="<?php echo $evidence_score?>"></td>
<td><input type="text" class="evidence_link" name="evidence_link" value="<?php echo $evidence_link?>"></td>
<td><input type="text" class="permit_cellsite" name="permit_cellsite" value="<?php echo $permit_cellsite?>"></td>
<td><input type="text" class="permit_suspected_carrier" name="permit_suspected_carrier" value="<?php echo $permit_suspected_carrier?>"></td>
<td><input type="text" class="trails_match" name="trails_match" value="<?php echo $trails_match?>"></td>
<td><input type="text" class="other_carriers_dont" name="other_carriers_dont" value="<?php echo $other_carriers_dont?>"></td>
<td><input type="text" class="antennas_match_carrier" name="antennas_match_carrier" value="<?php echo $antennas_match_carrier?>"></td>
<td><input type="text" class="cellmaper_triangulation" name="cellmaper_triangulation" value="<?php echo $cellmapper_triangulation?>"></td>
<td><input type="text" class="image_evidence" name="image_evidence" value="<?php echo $image_evidence?>"></td>
<td><input type="text" class="verified_by_visit" name="verified_by_visit" value="<?php echo $verified_by_visit?>"></td>
<td><input type="text" class="carrier_multiple" name="carrier_multiple" value="<?php echo $carrier_multiple?>"></td>
 <?php
                                break;
                          }
                      }
              echo "</tr>";

            }
?>
</tbody>
</table>
<br>
<button type="submit" form="form1" value="Submit">Submit</button>
</form>
</body>
</html>
