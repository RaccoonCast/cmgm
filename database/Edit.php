<!DOCTYPE html>
<head>
<?php include "../functions.php"; ?>
</head>
<?php
$conn = mysqli_connect($servername, $username, $password, $dbname);
$row_id = $_GET['row_id'];
if(isMobile()){
  header('Location: mobile-edit.php?row_id=' . $row_id . ' ');
}
if (isset($_GET['row_id'])) $row_id = $_GET['row_id'];
if (isset($_GET['id'])) $id = $_GET['id'];
if (isset($_GET['carrier'])) $carrier = $_GET['carrier'];
if (isset($_GET['latitude'])) $latitude = $_GET['latitude'];
if (isset($_GET['longitude'])) $longitude = $_GET['longitude'];
if (isset($_GET['city'])) $city = $_GET['city'];
if (isset($_GET['zip'])) $zip = $_GET['zip'];
if (isset($_GET['state'])) $state = $_GET['state'];
if (isset($_GET['address'])) $address = $_GET['address'];
if (isset($_GET['bio'])) $bio = $_GET['bio'];
if (isset($_GET['evidence_score'])) $evidence_score = $_GET['evidence_score'];
if (isset($_GET['evidence_link'])) $evidence_link = $_GET['evidence_link'];
if (isset($_GET['permit_cellsite'])) $permit_cellsite = $_GET['permit_cellsite'];
if (isset($_GET['permit_suspected_carrier'])) $permit_suspected_carrier = $_GET['permit_suspected_carrier'];
if (isset($_GET['trails_match'])) $trails_match = $_GET['trails_match'];
if (isset($_GET['other_carriers_dont'])) $other_carriers_dont = $_GET['other_carriers_dont'];
if (isset($_GET['antennas_match_carrier'])) $antennas_match_carrier = $_GET['antennas_match_carrier'];
if (isset($_GET['cellmaper_triangulation'])) $cellmapper_triangulation = $_GET['cellmaper_triangulation'];
if (isset($_GET['image_evidence'])) $image_evidence = $_GET['image_evidence'];
if (isset($_GET['verified_by_visit'])) $verified_by_visit = $_GET['verified_by_visit'];
if (isset($_GET['carrier_multiple'])) {
  $carrier_multiple = $_GET['carrier_multiple'];
  $sql_edit = "UPDATE `cmgm`.`findlater`
  SET `id` = '".mysqli_real_escape_string($conn, $id)."',
  `carrier` = '".mysqli_real_escape_string($conn, $carrier)."',
  `latitude` = '".mysqli_real_escape_string($conn, $latitude)."',
  `longitude` = '".mysqli_real_escape_string($conn, $longitude)."',
  `city` = '".mysqli_real_escape_string($conn, $city)."',
  `zip` = '".mysqli_real_escape_string($conn, $zip)."',
  `state` = '".mysqli_real_escape_string($conn, $state)."',
  `address` = '".mysqli_real_escape_string($conn, $address)."',
  `bio` = '".mysqli_real_escape_string($conn, $bio)."',
  `evidence_score` = '".mysqli_real_escape_string($conn, $evidence_score)."',
  `evidence_link` = '".mysqli_real_escape_string($conn, $evidence_link)."',
  `permit_cellsite` = '".mysqli_real_escape_string($conn, $permit_cellsite)."',
  `permit_suspected_carrier` = '".mysqli_real_escape_string($conn, $permit_suspected_carrier)."',
  `trails_match` = '".mysqli_real_escape_string($conn, $trails_match)."',
  `other_carriers_dont` = '".mysqli_real_escape_string($conn, $other_carriers_dont)."',
  `antennas_match_carrier` = '".mysqli_real_escape_string($conn, $antennas_match_carrier)."',
  `cellmapper_triangulation` = '".mysqli_real_escape_string($conn, $cellmapper_triangulation)."',
  `image_evidence` = '".mysqli_real_escape_string($conn, $image_evidence)."',
  `verified_by_visit` = '".mysqli_real_escape_string($conn, $verified_by_visit)."',
  `carrier_multiple` = '".mysqli_real_escape_string($conn, $carrier_multiple)."' WHERE row_id = $row_id";
  mysqli_query($conn, $sql_edit);
}
?> <?php


$sql = "SELECT * FROM database_db WHERE row_id = $row_id;";
$result = mysqli_query($conn, $sql);
?>
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
<form action="Edit.php" id="form<?php echo $row_id; ?>" method="get">
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
<input type="submit" class="submitbutton" value="Submit">
</form>
</body>
</html>
