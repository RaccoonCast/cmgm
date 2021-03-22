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
  if ($key == "latitude" OR $key == "longitude" OR $key == "limit") {
    ${$key} = $value;
  } elseif ($key == "id") {
    if (!empty($value)) {
    ${$key} = $value;
    $id = str_replace(' ', '', $id);
    $db_variables = "LTE_1='$id' OR LTE_2='$id' OR LTE_3='$id' OR LTE_4='$id' OR LTE_5='$id' OR LTE_5='$id' OR LTE_6='$id' OR NR_1='$id' OR NR_2='$id' AND " . $db_variables;
  }
  } else {
    if (!$key == 'carrier') if (empty($value)) {
      $db_variables = $key . ' = "'.$value.'" AND ' . $db_variables;
    }
  }
}


?>
<table  style="margin-bottom: 1cm" border="1">
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
$sql = "SELECT DISTINCT *,
(3959 * ACOS(COS(RADIANS($latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($longitude)) + SIN(RADIANS($latitude)) * SIN(RADIANS(latitude)))) AS DISTANCE
FROM database_db WHERE $db_variables ORDER BY distance LIMIT $limit";
$result = mysqli_query($conn,$sql);
while($row = $result->fetch_assoc()) {
    foreach ($row as $key => $value) {
      $$key = $value;
      if ($key == "alt_carriers_here") {
        echo "<tr>";
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

          if($isMobile == "true") if (!empty($bio)) echo nl2br("<td class=" . "bio" . ">" . $bio . "<br><br>");
          if($isMobile == "true") if (empty($bio)) echo nl2br("<td class=" . "bio" . ">");
          if($isMobile != "true") echo nl2br("<td class=" . "widget-td" . " style=" . "text-align: center;" . ">");
          echo '<a class="widget" href="SupplID.php?id='.$id.'"><abbr title="Add extra IDs">➕</abbr></a>';
          echo '<a class="widget" href="' . $db_map_link . '"><abbr title="View on Database Map">🌎</abbr></a>';
          echo '<a class="widget" href="Edit.php?id='.$id.'"><abbr title="Edit">🔧</abbr></a>';
          echo '<a class="widget" href="Delete.php?id='.$id.'"><abbr title="Delete">✂️</abbr></a>';
          echo '<a class="widget" href="Reader.php?back_url=DB&id='.$id.'"><abbr title="View all info">🔍</abbr></a></td>';

          if($isMobile != "true") {
            if (!empty($bio)) echo nl2br("<td class=" . "bio" . ">" . $bio . "</td>");
            if (empty($bio)) echo nl2br("<td></td>");
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
        include '../includes/functions/EV-math.php';
        if($isMobile == "true") echo "<br>Score: " . $ev . "</td>";
        if($isMobile != "true") echo "<td>" . $ev . "</td>";
        echo "</tr>";
      }
      }
      }
?>
</tbody>
</table>
<?php include "includes/footer.php"; ?>
</body>
</html>
