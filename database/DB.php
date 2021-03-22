<!doctype html>
<html lang="en">
<head>
  <?php include '../functions.php';
  if (!isset($_GET['latitude'])) $latitude = "38.89951743540001";
  if (!isset($_GET['longitude'])) $longitude = "-77.03655226691319";
  ?>
</head>
<body>
<?php
$limit = "500";

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

$sql = "SELECT DISTINCT $database_get_list,
(3959 * ACOS(COS(RADIANS($latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($longitude)) + SIN(RADIANS($latitude)) * SIN(RADIANS(latitude)))) AS DISTANCE
FROM database_db WHERE $db_variables ORDER BY distance LIMIT $limit";
$result = mysqli_query($conn, $sql); // First parameter is just return of "mysqli_connect()" function

?>
<table border="1">
<thead>
<tr>
  <th>LTE #</th>
  <?php if($isMobile != "true") {?><th>Carrier</th> <?php } ?>
  <th>Address</th>
  <?php if($isMobile != "true") {?><th>Widgets</th> <?php } ?>
  <th>Bio</th>
  <th>EV</th>
  <?php if($isMobile != "true") {?><th>Score</th> <?php } ?>
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
                    $db_map_link = "https://cmgm.gq/database/Map.php?latitude=" . $latitude . "&longitude=" . $longitude . "&zoom=18";
                    $cmlink = "../goto.php?goto_page=CellMapper&latitude=$latitude&longitude=$longitude";
                    $gmlink = "../goto.php?goto_page=Google Maps&latitude=$latitude&longitude=$longitude";
                    if($isMobile == "true") {
                    echo "<td class=" . "lte" . " id=" . $id . ">" . $carrier . "<br><a href=" . "$cmlink" .">" . $LTE_1 . "</a></td>";
                    } else {
                    echo "<td class=" . "lte" . " id=" . $id . "><a href=" . "$cmlink" .">" . $LTE_1 . "</a></td>";
                    echo "<td class=" . "carrier" . ">" . $carrier . "</td>";
                    }
                    if($isMobile == "true"){
                      echo '<td class="address"><a href="' . $gmlink . '">' . $address . '</a></td>';
                    } else {
                      echo '<td class="address"><a href="' . $gmlink . '">' . $address . ' <br>' . $city . ', ' . $state . ' ' . $zip . '</a></td>';
                    }


                    // start widget echo
                    if($isMobile == "true"){
                      echo nl2br("<td class=" . "bio" . ">" . $bio);
                      if (!empty($bio)) echo '<br>';
                      echo '<div class="widget-td">';
                      echo '<a class="widget" href="SupplID.php?id='.$id.'"><abbr title="Add extra IDs">➕</abbr></a>';
                      echo '<a class="widget" href="' . $db_map_link . '"><abbr title="View on Database Map">🌎</abbr></a>';
                      echo '<a class="widget" href="Edit.php?id='.$id.'"><abbr title="Edit">🔧</abbr></a>';
                      echo '<a class="widget" href="Delete.php?id='.$id.'"><abbr title="Delete">✂️</abbr></a>';
                      echo '<a class="widget" href="Reader.php?back_url=DB&id='.$id.'"><abbr title="View all info">🔍</abbr></a></td>';
                      } else {
                      echo '<td class="widget-td" style="text-align: center;">';
                      echo '<a class="widget" href="SupplID.php?id='.$id.'"><abbr title="Add extra IDs">➕</abbr></a>';
                      echo '<a class="widget" href="' . $db_map_link . '"><abbr title="View on Database Map">🌎</abbr></a>';
                      echo '<a class="widget" href="Edit.php?id='.$id.'"><abbr title="Edit">🔧</abbr></a>';
                      echo '<a class="widget" href="Delete.php?id='.$id.'"><abbr title="Delete">✂️</abbr></a>';
                      echo '<a class="widget" href="Reader.php?back_url=DB&id='.$id.'"><abbr title="View all info">🔍</abbr></a></td>';
                      echo nl2br("<td class=" . "bio" . ">" . $bio . "</td>");
                      }
                    if(substr($evidence_link, 0, 14) == "image-evidence") $evidence_link = "uploads/$evidence_link";

                    if (isset($evidence_link)) {
                      if(substr($evidence_link, 0, 14) == "image-evidence") {
                        if (file_exists($evidence_link)) {
                        if($isMobile == "true") echo "<td class=" . "ev" . "><a target=" . "_blank" . " href=" . "$evidence_link" . ">Evidence</a>";
                        if($isMobile != "true") echo "<td class=" . "ev" . "><a target=" . "_blank" . " href=" . "$evidence_link" . ">Evidence</a></td>";
                      } else {
                        if($isMobile == "true") echo "<td class=" . "ev" . ">Evidence is missing";
                        if($isMobile != "true") echo "<td class=" . "ev" . ">Evidence is missing</td>";
                      }
                    } else {
                      if($isMobile == "true") echo "<td class=" . "ev" . "><a target=" . "_blank" . " href=" . "$evidence_link" . ">Evidence</a>";
                      if($isMobile != "true") echo "<td class=" . "ev" . "><a target=" . "_blank" . " href=" . "$evidence_link" . ">Evidence</a></td>";
                    }
                    } else {
                    if($isMobile != "true") echo "<td class=" . "ev" . "></td>";
                    }

                    if($isMobile == "true") echo "<br>Score: " . $evidence_score . "</td>";
                    if($isMobile != "true") echo "<td>" . $evidence_score . "</td>";
                  }
            }
echo "</tr>";

    }
?>
</tbody>
</table>
</body>
</html>
