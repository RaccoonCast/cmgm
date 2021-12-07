<!doctype html>
<html lang="en">
<head>
  <?php
  include '../functions.php';
  include "../includes/functions/calculateEV.php";
  ?>
  <script src="../js/redirAtPos.js"></script>
</head>
<body class="body">
<?php
$id = $_GET['mp-id'];

$database_get_list = "id,date_added,LTE_1,LTE_2,LTE_3,LTE_4,LTE_5,LTE_6,carrier,latitude,longitude,city,zip,state,address,notes,evidence_score,evidence_a,sv_a";

$sql = "SELECT $database_get_list FROM database_db WHERE id = $id;";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) { // Important line !!! Check summary get row on array ..
  $colCount = 1;

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
                    case 8:  $LTE_6 = $value; break;
                    case 9:  $carrier = $value; break;
                    case 10:  $latitude = $value; break;
                    case 11:  $longitude = $value; break;
                    case 12:  $city = $value; break;
                    case 13:  $zip = $value; break;
                    case 14:  $state = $value; break;
                    case 15:  $address = $value; break;
                    case 16:  $notes = $value; break;
                    case 17:  $evidence_score = $value; break;
                    case 18:  $evidence_a = $value; break;
                    case 19:  $sv_a = $value;

                    echo nl2br("<p class=" . "widget" . ">" . $carrier . " <a href=" . "Reader.php?back_url=Map-popup&mp-id=" . $id . ">#" . $id . "</a> </p>");
                    $no_reader = "true";
                    $no_map = "true";
                    $redirPage = "Map-popup";
                    include "../includes/widgets/widgets.php";
                    ?> <br> <?php

                    if (empty($LTE_1)) $LTE_1 = "CellMapper";

                      $cellmapper_net_url = cellmapperLink($latitude,$longitude,$cm_zoom,$carrier,"LTE",$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc);
                        if(!empty($LTE_1)) ?> <a class="widget" target="_blank" href="<?php echo $cellmapper_net_url ?>"><?php echo $LTE_1?><?php
                        if(!empty($LTE_2)) { echo " & " . $LTE_2; }
                        if(!empty($LTE_3)) { echo " & " . $LTE_3; }
                        if(!empty($LTE_4)) { echo " & " . $LTE_4; }
                        if(!empty($LTE_5)) { echo " & " . $LTE_5; }
                        if(!empty($LTE_6)) { echo " & " . $LTE_6; }
                        if(!empty($NR_1)) { echo " & " . $NR_1; }
                        if(!empty($NR_2)) { echo " & " . $NR_2; } ?> </a> <?php
                        echo "<br>";

                      echo nl2br('<a target="_blank" href="https://maps.google.com/maps?f=q&source=s_q&hl=en&q=' .$latitude . ',' .$longitude . '">' . $address . ' <br>' . $city . ', ' . $state . ' ' . $zip . '</a>');
                      $recalcEV = calculateEV($id,$carrier);
                      if (!empty($recalcEV)) {
                      echo "<p>Evidence Score: " . $recalcEV . "";
                      }

                      if (!empty($evidence_score)) {
                      echo "( Old: " . $evidence_score . " )</p>";
                      } else {
                      echo "</p>";
                      }

                      if(substr($evidence_a, 0, 14) == "image-evidence") {
                         $evidence_a = "uploads/$evidence_a";
                      }

                      if (!empty($evidence_a)) {
                        echo ("<a target=" . "_blank" . " href=" . "$evidence_a" . ">Evidence</a><br>");
                      }
                      // todo:// find some to auto populate fields like carrier, status similiar to how we do for api/getTowers.php
                      echo nl2br(@$notes);
                      break;

                }
          }
        }
?>
</tbody>
</table>
</body>
</html>
