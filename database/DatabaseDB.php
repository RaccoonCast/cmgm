<!doctype html>
<html lang="en">
<head>
  <?php include '../functions.php';?>
</head>
<body>
<?php
$conn = mysqli_connect($servername, $username, $password, $dbname);

$limit = 10000;
$sub1 = ""; $sub2 = ""; $sub3 = "";
if (isset($_GET['limit'])) $limit = $_GET['limit'];
if (isset($_GET['carrier'])) $carrier = $_GET['carrier'];
if (isset($_GET['type'])) $type = $_GET['type'];
if (isset($_GET['id'])) $id = $_GET['id'];

if (!empty($carrier)) {
  $sub1 = "WHERE carrier='$carrier'";
}

if (!empty($type)) {
  if (empty($sub1)) {
    $sub2 = "WHERE type='$type'";
  } else {
    $sub2 = "AND type='$type'";
  }
}

if (!empty($id)) {
  if (empty($sub2) && (empty($sub1)) ) {
    $sub3 = "WHERE id='$id'";
  } else {
    $sub3 = "AND id='$id'";
  }
}

$latitude = $_GET['latitude'];
$longitude = $_GET['longitude'];

$sql = "SELECT DISTINCT *, (3959 * ACOS(COS(RADIANS($latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($longitude)) + SIN(RADIANS($latitude)) * SIN(RADIANS(latitude)))) AS DISTANCE FROM database_db $sub1 $sub2 $sub3 ORDER BY distance LIMIT $limit";
$result = mysqli_query($conn, $sql); // First parameter is just return of "mysqli_connect()" function

?>
<table border="1">
<thead>
<tr>
    <th>eNB ID</th>
    <th>Carrier</th>
    <th>Address</th>
    <th>Edit</th>
    <th>Bio</th>
    <th>Evidence Score</th>
    <th>Evidence</th>
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
                    case 4: $lat = $value; break;
                    case 5: $long = $value; break;
                    case 6: $city = $value; break;
                    case 7: $zip = $value; break;
                    case 8: $state = $value; break;
                    case 9: $address = $value; break;
                    case 10: $bio = $value; break;
                    case 11: $evidence_score = $value; break;
                    case 12: $evidence_text = $value; break;
                    case 13: $permit_cellsite = $value; break;
                    case 14: $permit_suspected_carrier = $value; break;
                    case 15: $trails_match = $value; break;
                    case 16: $other_carriers_dont = $value; break;
                    case 17: $antennas_match_carrier = $value; break;
                    case 18: $cellmapper_triangulation = $value; break;
                    case 19: $image_evidence = $value; break;
                    case 20: $verified_by_visit = $value; break;
                    case 21: $carrier_multiple = $value;
                    echo nl2br("<td>" . $id . "</td>");
                    echo nl2br("<td>" . $carrier . "</td>");
                    echo nl2br('<td class="address"><a href="/Hub.php?latitude='.$lat.'&longitude='.$long.'">' . $address . ' <br>' . $city . ', ' . $state . ' ' . $zip . '</a></td>');
                    echo nl2br('<td><center><a class="hide-underline" href="Edit.php?row_id='.$row_id.'">🔧</a></center>');
                    echo nl2br('<center><a class="hide-underline" href="Delete.php?row_id='.$row_id.'">❌</a></center></td>');
                    echo nl2br("<td>" . $bio . "</td>");
                    echo nl2br("<td>" . $evidence_score . "</td>");
                    if(substr($evidence_text, 0, 14) == "image-evidence") {
                       $evidence_text = "uploads/$evidence_text";
                    }
                    echo nl2br("<td><a target=" . "_blank" . " href=" . "$evidence_text" . ">Evidence</p></td>");
                  }
            }
echo "</tr>";

    }
?>
</tbody>
</table>
</body>
</html>
