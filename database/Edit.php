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
if (isset($_GET['id'])) $id = $_GET['id'];
if (isset($_GET['carrier'])) $carrier = $_GET['carrier'];
if (isset($_GET['type'])) $type = $_GET['type'];
if (isset($_GET['latitude'])) $latitude = $_GET['latitude'];
if (isset($_GET['longitude'])) $longitude = $_GET['longitude'];
if (isset($_GET['city'])) $city = $_GET['city'];
if (isset($_GET['zip'])) $zip = $_GET['zip'];
if (isset($_GET['state'])) $state = $_GET['state'];
if (isset($_GET['address'])) $address = $_GET['address'];
if (isset($_GET['bio'])) $evidence_score = $_GET['evidence_score'];
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
  `type` = '".mysqli_real_escape_string($conn, $type)."',
  `latitude` = '".mysqli_real_escape_string($conn, $latitude)."',
  `longitude` = '".mysqli_real_escape_string($conn, $longitude)."',
  `bands` = '".mysqli_real_escape_string($conn, $bands)."',
  `city` = '".mysqli_real_escape_string($conn, $city)."',
  `zip` = '".mysqli_real_escape_string($conn, $zip)."',
  `state` = '".mysqli_real_escape_string($conn, $state)."',
  `address` = '".mysqli_real_escape_string($conn, $address)."',
  `bio` = '".mysqli_real_escape_string($conn, $bio)."',
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
<table border="1">
<thead>
<tr>
    <th>ID</th>
    <th>Carrier</th>
    <th>Type</th>
    <th>Latitude</th>
    <th>Longitude</th>
    <th>Firstseen</th>
    <th>Bands</th>
    <th>City</th>
    <th>Zip</th>
    <th>State</th>
    <th>address</th>
    <th>Bio</th>
</tr>
</thead>
<tbody>
<?php
while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
  $colCount = 1;
    echo "<tr>";
    foreach ($row as $field => $value) {

      $sepCount = ($colCount++);

                  switch ($sepCount) {
                    case 1: $row_id = $value; break;
                    case 2: $id = $value; break;
                    case 3: $carrier = $value; break;
                    case 4: $type = $value; break;
                    case 5: $latitude = $value;  break;
                    case 6: $longitude = $value; break;
                    case 7: $firstseen = $value; break;
                    case 8: $bands = $value; break;
                    case 9: $city = $value; break;
                    case 10: $zip = $value; break;
                    case 11: $state = $value; break;
                    case 12: $address = $value;break;
                    case 13: $bio = $value;
?>
<form action="Edit.php" id="form<?php echo $row_id; ?>" method="get">
  <tr>
<input type="hidden" class="row_id" name="row_id" value="<?php echo $row_id?>">
<td><input type="text" class="id" name="id" value="<?php echo $id?>"></td>
<td><input type="text" class="carrier" name="carrier" value="<?php echo $carrier?>"></td>
<td><input type="text" class="type" name="type" value="<?php echo $type?>"></td>
<td><input type="text" class="latitude" name="latitude" value="<?php echo $latitude?>"></td>
<td><input type="text" class="longitude" name="longitude" value="<?php echo $longitude?>"></td>
<td><input type="text" class="firstseen" name="firstseen" value="<?php echo $firstseen?>"></td>
<td><input type="text" class="bands" name="bands" value="<?php echo $bands?>"></td>
<td><input type="text" class="city" name="city" value="<?php echo $city?>"></td>
<td><input type="text" class="zip" name="zip" value="<?php echo $zip?>"></td>
<td><input type="text" class="state" name="state" value="<?php echo $state?>"></td>
<td><input type="text" class="address" name="address" value="<?php echo $address?>"></td>
<td><input type="text" class="bio" name="bio" value="<?php echo $bio?>"></td>
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
