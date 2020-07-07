<!DOCTYPE html>
<head>
<?php include "../functions.php"; ?>
</head>
<?php
$row_id = $_GET['row_id'];
$conn = mysqli_connect($servername, $username, $password, $dbname);

//get all of the tabel entries
if (isset($_GET['id'])) $id = $_GET['id'];
if (isset($_GET['carrier'])) $carrier = $_GET['carrier'];
if (isset($_GET['type'])) $type = $_GET['type'];
if (isset($_GET['latitude'])) $latitude = $_GET['latitude'];
if (isset($_GET['longitude'])) $longitude = $_GET['longitude'];
if (isset($_GET['firstseen'])) $firstseen = $_GET['firstseen'];
if (isset($_GET['bands'])) $bands = $_GET['bands'];
if (isset($_GET['city'])) $city = $_GET['city'];
if (isset($_GET['zip'])) $zip = $_GET['zip'];
if (isset($_GET['state'])) $state = $_GET['state'];
if (isset($_GET['address'])) {
  $address = $_GET['address'];

  //sql update query
  $sql_edit = "UPDATE `cmgm`.`findlater`
  SET `id` = '".mysqli_real_escape_string($conn, $id)."',
  `carrier` = '".mysqli_real_escape_string($conn, $carrier)."',
  `type` = '".mysqli_real_escape_string($conn, $type)."',
  `latitude` = '".mysqli_real_escape_string($conn, $latitude)."',
  `longitude` = '".mysqli_real_escape_string($conn, $longitude)."',
  `firstseen` = '".mysqli_real_escape_string($conn, $firstseen)."',
  `bands` = '".mysqli_real_escape_string($conn, $bands)."',
  `city` = '".mysqli_real_escape_string($conn, $city)."',
  `zip` = '".mysqli_real_escape_string($conn, $zip)."',
  `state` = '".mysqli_real_escape_string($conn, $state)."',
  `address` = '".mysqli_real_escape_string($conn, $address)."' WHERE row_id = $row_id";
  mysqli_query($conn, $sql_edit);
}


$sql = "SELECT * FROM findlater WHERE row_id = $row_id;";
$result = mysqli_query($conn, $sql);
?>
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
<form action="mobile-edit.php" id="form<?php echo $row_id; ?>" method="get">
<input type="hidden" class="row_id" name="row_id" value="<?php echo $row_id?>">
<input type="text" class="id" name="id" value="<?php echo $id?>">
<input type="text" class="carrier" name="carrier" value="<?php echo $carrier?>">
<input type="text" class="type" name="type" value="<?php echo $type?>">
<input type="text" class="latitude" name="latitude" value="<?php echo $latitude?>">
<input type="text" class="longitude" name="longitude" value="<?php echo $longitude?>">
<input type="text" class="firstseen" name="firstseen" value="<?php echo $firstseen?>">
<input type="text" class="bands" name="bands" value="<?php echo $bands?>">
<input type="text" class="city" name="city" value="<?php echo $city?>">
<input type="text" class="zip" name="zip" value="<?php echo $zip?>">
<input type="text" class="state" name="state" value="<?php echo $state?>">
<input type="text" class="address" name="address" value="<?php echo $address?>">
<textarea class="bio" rows="15" name="bio" cols="45"><?php echo $bio?></textarea>
<input type="submit" class="submitbutton" value="Submit">
</form> <?php
                                break;
                          }
                      }

            }
?>
