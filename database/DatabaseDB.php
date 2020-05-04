<!doctype html>
<html lang="en">
<head>
  <?php include '../functions.php';?>
</head>
<body>
<?php

$conn = mysqli_connect($servername, $username, $password, $dbname);

$limit = 10000;
$sub1 = "";
$sub2 = "";
$sub3 = "";
if (isset($_GET['limit'])) $limit = $_GET['limit'];
if (isset($_GET['debug'])) $debug = $_GET['debug'];

if (isset($_GET['carrier'])) $carrier = $_GET['carrier'];
if (isset($_GET['type'])) $type = $_GET['type'];
if (isset($_GET['id'])) $id = $_GET['id'];

if (empty($carrier)) { } else {
  $sub1 = "WHERE carrier='$carrier'";
}

if (empty($type)) { } else {
  if (empty($sub1)) {
    $sub2 = "WHERE type='$type'";
  } else {
      $sub2 = "AND type='$type'";
  }
}

if (empty($id)) { } else {
  if (empty($sub2) && (empty($sub1)) ) {
    $sub3 = "WHERE id='$id'";
  } else {
      $sub3 = "AND id='$id'";
  }
}

if (isset($_GET['latitude'])) $latitude = $_GET['latitude'];
if (isset($_GET['longitude'])) $longitude = $_GET['longitude'];
if (empty($_GET['latitude'])) $latitude = "0.00";
if (empty($_GET['longitude'])) $longitude = "0.00";

$sql = "SELECT DISTINCT *, (3959 * ACOS(COS(RADIANS($latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($longitude)) + SIN(RADIANS($latitude)) * SIN(RADIANS(latitude))))
AS DISTANCE FROM database_db
$sub1 $sub2 $sub3
ORDER BY distance
LIMIT $limit";
$result = mysqli_query($conn, $sql); // First parameter is just return of "mysqli_connect()" function

?>
<table border="1">
<thead>
<tr>
  <?php if (isset($debug)) echo '<th>row_id</ht>'; ?>
  <?php if (isset($debug)) echo '<th>id</ht>'; ?>
  <?php if (isset($debug)) echo '<th>carrier</ht>'; ?>
  <?php if (isset($debug)) echo '<th>type</ht>'; ?>
  <?php if (isset($debug)) echo '<th>latitude</ht>'; ?>
  <?php if (isset($debug)) echo '<th>longitude</ht>'; ?>
  <?php if (isset($debug)) echo '<th>firstseen</ht>'; ?>
  <?php if (isset($debug)) echo '<th>bands</ht>'; ?>
  <?php if (isset($debug)) echo '<th>city</ht>'; ?>
  <?php if (isset($debug)) echo '<th>zip</ht>'; ?>
  <?php if (isset($debug)) echo '<th>state</ht>'; ?>
  <?php if (isset($debug)) echo '<th>❌</ht>'; ?>
  <?php if (isset($debug)) echo '<th>address</ht>'; ?>
  <?php if (isset($debug)) {
    echo '<th>bio</ht>';
  }
  else { ?>
    <th>eNB ID</th>
    <th>Carrier</th>
    <th>Type</th>
    <th>First Seen</th>
    <th>Band(s)</th>
    <th>Address</th>
    <th>Delete</th>
    <th>Bio</th>
    <?php
  } ?>
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
                    case 1:
                      $row_id = $value;
                      if (isset($debug)) echo nl2br("<td>" . $value . "</td>");
                      break;
                    case 2:
                      $id = $value;
                      echo nl2br("<td>" . $value . "</td>");
                      break;
                    case 3:
                      $carrier = $value;
                      echo nl2br("<td>" . $value . "</td>");
                      break;
                    case 4:
                      $type = $value;
                      echo nl2br("<td>" . $value . "</td>");
                      break;
                    case 5:
                      $lat = $value;
                      if (isset($debug)) echo nl2br("<td>" . $value . "</td>");
                      break;
                    case 6:
                      $long = $value;
                      if (isset($debug)) echo nl2br("<td>" . $value . "</td>");
                      break;
                    case 7:
                      $firstseen = $value;
                      echo '<td class="firstseen">' . $value . '</td>';
                      break;
                    case 8:
                      $bands = $value;
                      echo nl2br("<td>" . $value . "</td>");
                      break;
                    case 9:
                      $city = $value;
                      if (isset($debug)) echo nl2br("<td>" . $value . "</td>");
                      break;
                    case 10:
                      $zip = $value;
                      if (isset($debug)) echo nl2br("<td>" . $value . "</td>");
                      break;
                    case 11:
                      $state = $value;
                      if (isset($debug)) echo nl2br("<td>" . $value . "</td>");
                      break;
                    case 12:
                      $address = $value;
                      echo nl2br('<td class="address"><a href="/Hub.php?latitude='.$lat.'&longitude='.$long.'">' . $address . ' <br>' . $city . ', ' . $state . ' ' . $zip . '</a></td>');
                      echo nl2br('<td><center><a href="Delete.php?databaseID='.$row_id.'">❌</a></center></td>');
                      break;
                    case 13:
                      $bio = $value;
                      echo nl2br("<td>" . $value . "</td>");
                      break;
                  }
            }
echo "</tr>";
/*
UPDATE `cmgm`.`database` SET `type`='Rooftop' WHERE  `row_id`=2;
SELECT `row_id`, `id`, `carrier`, `type`, `latitude`, `longitude`, `firstseen`, `bands`, `city`, `zip`, `state`, `address`, `bio`
FROM `cmgm`.`database` WHERE  `row_id`=2;
*/
    }
?>
</tbody>
</table>
</body>
</html>
