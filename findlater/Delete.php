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
  $sql = "DELETE FROM `cmgm`.`findlater` WHERE  `row_id`=" . $row_id . ";";
  mysqli_query($conn, $sql); // First parameter is just return of "mysqli_connect()" function
  echo '<meta http-equiv="refresh" content="0;URL=../" /> ';
}

$sql = "SELECT * FROM findlater WHERE row_id = $row_id;";
$result = mysqli_query($conn, $sql);
?>
<form action="Delete.php" id="form1" method="get">
<input type="hidden" name="row_id" value="<?php echo $row_id?>">
<input type="hidden" name="delete_confirmed" value="true">
<table border="0">
<thead>
<tr>
    <th>eNB ID</th>
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
<button type="submit" form="form1" value="Submit">Submit</button>
</form>
</body>
</html>
