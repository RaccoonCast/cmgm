<!doctype html>
<html lang="en">
<head>
  <script src="../js/redir.js"></script>
  <?php
  include '../functions.php';
  if (!isset($_GET['latitude'])) $latitude = $default_latitude;
  if (!isset($_GET['longitude'])) $longitude = $default_longitude;
  include "includes/DB-filter-post.php";
  include "../includes/link-conversion-and-handling/function_goto.php";
  ?>
</head>
<body>
<?php
if (!isset($limit)) $limit = "100";
$sql = "SELECT DISTINCT *, (3959 * ACOS(COS(RADIANS($latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($longitude)) + SIN(RADIANS($latitude)) * SIN(RADIANS(latitude)))) AS DISTANCE FROM db ".@$db_vars." ORDER BY distance LIMIT $limit";
if (isset($_GET['showsql'])) echo $sql;
$result = mysqli_query($conn,$sql);
if (mysqli_num_rows($result) == "1") {
  while($row = $result->fetch_assoc()) {
  foreach ($row as $key => $value) {
    $$key = $value;
    if ($key == "DISTANCE") { redir("Edit.php?id=$id","0"); }
  }
}
}
if (mysqli_num_rows($result) == "0") {
  echo "<br> No results found.";
  redir("Search.php?latitude=$latitude&longitude=$longitude","1");
} ?>
<table border="1">
<thead>
<tr>
  <th class="btn-holder-edit">Edit</th>
  <th class="enb">eNB</th>
  <th class="carrier">Carrier</th>
  <th class="address">Address</th>
  <th class="notes">Notes</th>
  <th class="btn-holder-evidence">Evidence</th>
</tr>
</thead>
<tbody> <?php
if (mysqli_num_rows($result) > 1) { while($row = $result->fetch_assoc()) {
    foreach ($row as $key => $value) {
      $$key = $value;
      if ($key == "DISTANCE") {
        echo "<tr>";
        $db_map_link = $domain_with_http . "database/Map.php?latitude=".$latitude."&longitude=".$longitude."&zoom=18&back=DB";
        $gmlink = function_goto($latitude,$longitude,NULL,NULL,NULL,NULL,NULL,"Maps",NULL,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,@$cm_netType);
        $cmlink = function_goto($latitude,$longitude,$carrier,NULL,NULL,NULL,NULL,"CellMapper",NULL,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,@$cm_netType);

          ?> <td><input type="button" class="w-100 btn-edit" onclick="redir('Edit.php?id=<?php echo $id;?>','0')"value="Edit"></input></td> <?php

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

          if($isMobile != "true") {
            if (!empty($notes)) echo nl2br("<td class="."notes".">".$notes."</td>");
            if (empty($notes)) echo nl2br("<td></td>");
          }

        if(substr($evidence_a, 0, 6) == "image-") $evidence_a = "uploads/$evidence_a";

        if ((substr($evidence_a, 0, 6) != "image-") OR (file_exists($evidence_a))) { ?>
          <td><div class="btn-group"><input type="button" onclick="copy('<?php echo $domain_with_http . "/database/" . $evidence_a; ?>')" class="btn-evidence" value="Copy evidence"></input> <input
         type="button" class="btn-evidence" onclick="redir('<?php echo $evidence_a; ?>','0')" value="View evidence"></input></td></div> <?php } else {
           echo "Missing file!";
         }



        echo "</tr>"."\n";

      }
    }}}
?>
</tbody>
</table>
<?php
if (mysqli_num_rows($result) > 40) {
  echo "<br>";
}
include "includes/footer.php"; ?>
</body>
</html>
