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
if (isset($_GET['id_1'])) $id_1 = $_GET['id_1'];

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

if (!empty($id_1)) {
  if (empty($sub2) && (empty($sub1)) ) {
    $sub3 = "WHERE id_1='$id_1'";
  } else {
    $sub3 = "AND id_1='$id_1'";
  }
}

if (isset($_GET['latitude'])) $latitude = $_GET['latitude'];
if (isset($_GET['longitude'])) $longitude = $_GET['longitude'];

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
    <th>EV</th>
    <th>Score</th>
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
                    case 1:  $row_id = $value; break;
                    case 2:  $date_added = $value; break;
                    case 3:  $id_1 = $value; break;
                    case 4:  $id_2 = $value; break;
                    case 5:  $id_3 = $value; break;
                    case 6:  $id_4 = $value; break;
                    case 7:  $id_5 = $value; break;
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
                    echo nl2br("<td>" . $id_1. "</td>");
                    echo nl2br("<td>" . $carrier . "</td>");
                    echo nl2br('<td class="address"><a href="/Hub.php?latitude='.$latitude.'&longitude='.$longitude.'">' . $address . ' <br>' . $city . ', ' . $state . ' ' . $zip . '</a></td>');
                    echo nl2br('<td><center><a class="hide-underline" href="Edit.php?row_id='.$row_id.'">🔧</a></center>');
                    echo nl2br('<center><a class="hide-underline" href="Delete.php?row_id='.$row_id.'">❌</a></center></td>');
                    echo nl2br("<td class=" . "bio" . ">" . $bio . "</td>");
                    if(substr($evidence_link, 0, 14) == "image-evidence") {
                       $evidence_link = "uploads/$evidence_link";
                    }
                    echo nl2br("<td>" . $evidence_score . "</td>");
                    if (!empty($evidence_link)) {
                      echo nl2br("<td><a target=" . "_blank" . " href=" . "$evidence_link" . ">Evidence</p></td>");
                    }
                  }
            }
echo "</tr>";

    }
?>
</tbody>
</table>
</body>
</html>
