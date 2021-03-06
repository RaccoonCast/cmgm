<!doctype html>
<html lang="en">
<head>
  <?php include '../functions.php';?>
</head>
<body>
<?php
$limit = 600;

$db_variables = "id > 0";

foreach($_GET as $key => $value){
  if ($key == "latitude" OR $key == "longitude") {
    ${$key} = $value;
  } elseif ($key == "id") {
    ${$key} = $value;
    $id = str_replace(' ', '', $id);
    $db_variables = "LTE_1='$id' OR LTE_2='$id' OR LTE_3='$id' OR LTE_4='$id' OR LTE_5='$id' OR LTE_5='$id' OR LTE_6='$id' OR NR_1='$id' OR NR_2='$id' AND " . $db_variables;
  } else {
    $db_variables = $key . ' = "'.$value.'" AND ' . $db_variables;
  }
}
$database_get_list = "id,LTE_1,carrier,latitude,longitude,city,zip,state,address,bio,evidence_score,evidence_link";

$sql = "SELECT DISTINCT $database_get_list, (3959 * ACOS(COS(RADIANS($latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($longitude)) + SIN(RADIANS($latitude)) * SIN(RADIANS(latitude)))) AS DISTANCE FROM database_db WHERE $db_variables ORDER BY distance LIMIT $limit";
$result = mysqli_query($conn, $sql); // First parameter is just return of "mysqli_connect()" function

?>
<table border="1">
<thead>
<tr>
    <th>LTE #</th>
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
                    case 1:  $id = $value; break;
                    case 2:  $LTE_1 = $value; break;
                    case 3:  $carrier = $value; break;
                    case 4:  $latitude = $value; break;
                    case 5:  $longitude = $value; break;
                    case 6:  $city = $value; break;
                    case 7:  $zip = $value; break;
                    case 8:  $state = $value; break;
                    case 9:  $address = $value; break;
                    case 10:  $bio = $value; break;
                    case 11:  $evidence_score = $value; break;
                    case 12:  $evidence_link = $value;
                    echo "<td>" . $LTE_1 . "</td>";
                    echo "<td>" . $carrier . "</td>";
                    echo '<td class="address"><a href="/Home.php?latitude='.$latitude.'&longitude='.$longitude.'">' . $address . ' <br>' . $city . ', ' . $state . ' ' . $zip . '</a></td>';
                    echo '<td style="text-align: center;"><a class="hide-underline" href="Edit.php?id='.$id.'">🔧</a><br>';
                    echo '<a class="hide-underline" href="Delete.php?id='.$id.'">✂️</a></td>';
                    echo nl2br("<td class=" . "bio" . ">" . $bio . "</td>");
                    if(substr($evidence_link, 0, 14) == "image-evidence") {
                       $evidence_link = "uploads/$evidence_link";
                    }
                    echo "<td>" . $evidence_score . "</td>";
                    if (!empty($evidence_link)) {
                      echo "<td><a target=" . "_blank" . " href=" . "$evidence_link" . ">Evidence</p></td>";
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
