<!doctype html>
<html lang="en">
<head>
  <?php include '../functions.php';?>
  <link rel="stylesheet" href="styles/Edit/desktop.css">
</head>
<body>
<?php
$id = $_GET['id'];

// THIS CODE ONLY GETS RUN AFTER CONFIRMATION

if (isset($_GET['delete_confirmed'])) {
  $sql = "DELETE FROM `cmgm`.`database_db` WHERE  `id`=" . $id . ";";
  mysqli_query($conn, $sql); // First parameter is just return of "mysqli_connect()" function
  echo '<meta http-equiv="refresh" content="0;URL=../" /> ';
}
$database_get_list = "id,date_added,LTE_1,LTE_2,LTE_3,LTE_4,LTE_5,carrier,latitude,longitude,city,zip,state,address,bio,evidence_score,
evidence_link,photo_link,attached_file_link,permit_cellsite,permit_suspected_carrier,trails_match,other_carriers_dont, antennas_match_carrier,
cellmapper_triangulation,image_evidence,verified_by_visit,sector_split_match,
contact_permit_carrier,archival_antenna_addition,only_reasonable_location,carrier_multiple";

$sql = "SELECT $database_get_list FROM database_db WHERE id = $id;";
$result = mysqli_query($conn, $sql);
?>

<form action="Delete.php" id="form1" method="get">
  <input type="hidden" name="id" value="<?php echo $id?>">
  <input type="hidden" name="delete_confirmed" value="true">
  <table border="0">
<table border="0">
<thead>
<tr>
  <th>RowID</th>
  <th>Date Added</th>
  <th>ID</th>
  <th>LTE_2</th>
  <th>LTE_3</th>
  <th>LTE_4</th>
  <th>LTE_5</th>
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
  <th>Photo</th>
  <th>AttachedDoc</th>
  <th title="Permit says cellsite?">EV1</th>
  <th title="Permit matches suspected carrier">EV2</th>
  <th title="Trails match suspected address with the suspected carrier">EV3</th>
  <th title="Trails rule out other carriers">EV4</th>
  <th title="Antennas look like suspected carrier">EV5</th>
  <th title="CellMapper triangulates very close to the suspected location">EV6</th>
  <th title="Identifier at the site with carrier name">EV7</th>
  <th title="On-site verification">EV8</th>
  <th title="Sector split match location">EV9</th>
  <th title="Contact permit carrier match">EV10</th>
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
                    case 1:  $id = $value; break;
                    case 2:  $date_added = $value; break;
                    case 3:  $LTE_1 = $value; break;
                    case 4:  $LTE_2 = $value; break;
                    case 5:  $LTE_3 = $value; break;
                    case 6:  $LTE_4 = $value; break;
                    case 7:  $LTE_5 = $value; break;
                    case 8:  $carrier = $value; break;
                    case 9:  $latitude = $value; break;
                    case 10:  $longitude = $value; break;
                    case 11:  $city = $value; break;
                    case 12:  $zip = $value; break;
                    case 13:  $state = $value; break;
                    case 14:  $address = $value; break;
                    case 15:  $bio = $value; break;
                    case 16:  $evidence_score = $value; break;
                    case 17:  $evidence_link = $value; break;
                    case 18:  $photo_link = $value; break;
                    case 19:  $attached_file_link = $value; break;
                    case 20:  $permit_cellsite = $value; break;
                    case 21:  $permit_suspected_carrier = $value; break;
                    case 22:  $trails_match = $value; break;
                    case 23:  $other_carriers_dont = $value; break;
                    case 24:  $antennas_match_carrier = $value; break;
                    case 25:  $cellmapper_triangulation = $value; break;
                    case 26:  $image_evidence = $value; break;
                    case 27:  $verified_by_visit = $value; break;
                    case 28:  $sector_split_match = $value; break;
                    case 29:  $contact_permit_carrier = $value; break;
                    case 30:  $archival_antenna_addition = $value; break;
                    case 31:  $only_reasonable_location = $value; break;
                    case 32:  $carrier_multiple = $value;
?>
<tr>
  <td><input type="text" class="id" name="id" value="<?php echo $id?>"></td>
  <td><input type="text" class="date_added" name="date_added" value="<?php echo $date_added?>"></td>
  <td><input type="text" class="LTE_1" name="LTE_1" value="<?php echo $LTE_1?>"></td>
  <td><input type="text" class="LTE_2" name="LTE_2" value="<?php echo $LTE_2?>"></td>
  <td><input type="text" class="LTE_3" name="LTE_3" value="<?php echo $LTE_3?>"></td>
  <td><input type="text" class="LTE_4" name="LTE_4" value="<?php echo $LTE_4?>"></td>
  <td><input type="text" class="LTE_5" name="LTE_5" value="<?php echo $LTE_5?>"></td>
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
  <td><input type="text" class="photo_link" name="photo_link" value="<?php echo $photo_link?>"></td>
  <td><input type="text" class="attached_file_link" name="attached_file_link" value="<?php echo $attached_file_link?>"></td>
  <td><input type="text" class="permit_cellsite" name="permit_cellsite" value="<?php echo $permit_cellsite?>"></td>
  <td><input type="text" class="permit_suspected_carrier" name="permit_suspected_carrier" value="<?php echo $permit_suspected_carrier?>"></td>
  <td><input type="text" class="trails_match" name="trails_match" value="<?php echo $trails_match?>"></td>
  <td><input type="text" class="other_carriers_dont" name="other_carriers_dont" value="<?php echo $other_carriers_dont?>"></td>
  <td><input type="text" class="antennas_match_carrier" name="antennas_match_carrier" value="<?php echo $antennas_match_carrier?>"></td>
  <td><input type="text" class="cellmapper_triangulation" name="cellmapper_triangulation" value="<?php echo $cellmapper_triangulation?>"></td>
  <td><input type="text" class="image_evidence" name="image_evidence" value="<?php echo $image_evidence?>"></td>
  <td><input type="text" class="verified_by_visit" name="verified_by_visit" value="<?php echo $verified_by_visit?>"></td>
  <td><input type="text" class="sector_split_match" name="sector_split_match" value="<?php echo $sector_split_match?>"></td>
  <td><input type="text" class="contact_permit_carrier" name="contact_permit_carrier" value="<?php echo $contact_permit_carrier?>"></td>
  <td><input type="text" class="archival_antenna_addition" name="archival_antenna_addition" value="<?php echo $archival_antenna_addition?>"></td>
  <td><input type="text" class="only_reasonable_location" name="only_reasonable_location" value="<?php echo $only_reasonable_location?>"></td>
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
<button type="submit" form="form1" value="Submit">Delete</button>
</form>
</body>
</html>
