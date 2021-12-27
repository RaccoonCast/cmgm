<!doctype html>
<html lang="en">
<head>
  <?php
  include '../functions.php';
  if (!isset($_GET['latitude'])) $latitude = $default_latitude;
  if (!isset($_GET['longitude'])) $longitude = $default_longitude;
  include "includes/DB-filter.php";
  include "../includes/link-conversion-and-handling/goto.php";
  ?>
</head>
<body>
<?php
$sql = "SELECT DISTINCT *, (3959 * ACOS(COS(RADIANS($latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($longitude)) + SIN(RADIANS($latitude)) * SIN(RADIANS(latitude)))) AS DISTANCE FROM db ".@$db_vars." ORDER BY distance LIMIT $limit";
if (isset($_GET['showsql'])) echo $sql;
$result = mysqli_query($conn,$sql);
$counter=0;
while($row = $result->fetch_assoc()) {
    foreach ($row as $key => $value) {
      $counter++;
      if ($counter==1) { ?>
        <table border="1">
        <thead>
        <tr>
          <th>LTE #</th>
          <?php if($isMobile != "true") {?><th>Carrier</th> <?php } ?>
          <th>Address</th>
          <?php if($isMobile != "true") {?><th>Widgets</th> <?php } ?>
          <th>Notes</th>
          <th>EV</th>
          <?php if($isMobile != "true") {?><th>Score</th> <?php } ?>
        </tr>
        </thead>
        <tbody> <?php
      }
      $$key = $value;
      if ($key == "DISTANCE") {
        echo "<tr>";
        $db_map_link = "https://cmgm.ml/database/Map.php?latitude=".$latitude."&longitude=".$longitude."&zoom=18&back=DB";
        $gmlink = function_goto($latitude,$longitude,NULL,NULL,NULL,NULL,NULL,"Google Maps",NULL,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,@$cm_netType);
        $cmlink = function_goto($latitude,$longitude,$carrier,NULL,NULL,NULL,NULL,"CellMapper",NULL,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,@$cm_netType);

        if($isMobile == "true") {
        echo '<td class="lte" id="'.$id.'">'.$carrier.'<br><a href="'.$cmlink.'">'.$LTE_1.'</a></td>';
        } else {
        echo '<td class="lte" id="'.$id.'"><a href="'.$cmlink.'">'.$LTE_1.'</a></td>';
        echo '<td class="carrier">'.$carrier.'</td>';
        }

        if($isMobile == "true"){
          echo '<td class="address"><div class="addr-box"><a href="'.$gmlink.'">'.$address.'</a></div></td>';
        } else {
          echo '<td class="address"><div class="addr-box"><a href="'.$gmlink.'">'.$address.' <br>'.$city.', '.$state.' '.$zip.'</a></td></div>';
        }

          if($isMobile == "true") if (!empty($notes)) echo nl2br("<td class="."notes"."><div class="."notes-text".">".$notes."</div>");
          if($isMobile == "true") if (empty($notes)) echo nl2br("<td class="."notes"."><div class="."notes-text"."></div>");
          if($isMobile != "true") echo nl2br("<td class="."widget-td"." style="."text-align: center;".">");

          ?><div class="widget-box"><?php
          include SITE_ROOT."/includes/widgets/widgets.php";
          ?></td></div><?php

          if($isMobile != "true") {
            if (!empty($notes)) echo nl2br("<td class="."notes".">".$notes."</td>");
            if (empty($notes)) echo nl2br("<td></td>");
          }

        if(substr($evidence_a, 0, 14) == "image-evidence") $evidence_a = "uploads/$evidence_a";

        if (!empty($evidence_a)) {
          if(substr($evidence_a, 0, 14) == "image-evidence") {
            if (file_exists($evidence_a)) {
            if($isMobile == "true") echo "<td class="."ev"."><a target="."_blank"." href="."$evidence_a".">Evidence</a>";
            if($isMobile != "true") echo "<td class="."ev"."><a target="."_blank"." href="."$evidence_a".">Evidence</a></td>";
          } else {
            if($isMobile == "true") echo "<td class="."ev".">Evidence is missing";
            if($isMobile != "true") echo "<td class="."ev".">Evidence is missing</td>";
          }
        } else {
          if($isMobile == "true") echo "<td class="."ev"."><a target="."_blank"." href="."$evidence_a".">Evidence</a>";
          if($isMobile != "true") echo "<td class="."ev"."><a target="."_blank"." href="."$evidence_a".">Evidence</a></td>";
        }
        } else {
        if($isMobile != "true") echo "<td class="."ev"."></td>";
        }
        include '../includes/functions/calculateEV-math.php';
        if($isMobile != "true") echo "<td>".$ev."</td>";
        echo "</tr>"."\n";
      }
      }
      }
      if($counter==0){
      ?> <br> <?php
      echo " No results found.";
      redir("Search.php?latitude=$latitude&longitude=$longitude","1");
    } else {}
?>
</tbody>
</table>
<?php include "includes/footer.php"; ?>
</body>
</html>
