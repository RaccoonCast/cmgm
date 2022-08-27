<?php

$sql = "SELECT DISTINCT id,LTE_1,carrier,latitude,longitude,address,city,state,zip,notes,evidence_a, (3959 * ACOS(COS(RADIANS($latitude)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS($longitude)) + SIN(RADIANS($latitude)) * SIN(RADIANS(latitude)))) AS DISTANCE FROM db ".@$db_vars." ".@$locsearch." ORDER BY distance LIMIT 75";
$result = mysqli_query($conn,$sql);

if (mysqli_num_rows($result) == "1") {
  while($row = $result->fetch_assoc()) {
  foreach ($row as $key => $value) {
    $$key = $value;
    if ($key == "DISTANCE") { redir("Edit.php?id=$id","0"); }
  }
}
}
if  ((mysqli_num_rows($result) == "0")) {
  echo "<br> No results found.";
  redir("Search.php?latitude=$latitude&longitude=$longitude","1");
}
?>
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

        include_once $SITE_ROOT . "/includes/link-conversion-and-handling/function_goto.php";
        $gmlink = function_goto($latitude,$longitude,NULL,NULL,NULL,NULL,NULL,"Maps",NULL,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,@$cm_netType);
        $cmlink = function_goto($latitude,$longitude,$carrier,NULL,NULL,NULL,NULL,"CellMapper",NULL,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom,@$cm_netType);

          ?> <td><input type="button" class="w-100 btn-edit" onclick="redir('Edit.php?id=<?php echo $id;?>','0')"value="Edit"></input></td> <?php

        echo '<td class="lte" id="'.$id.'"><a href="'.$cmlink.'">'.$LTE_1.'</a></td>';
        echo '<td class="carrier">'.$carrier.'</td>';
        echo '<td class="address"><div class="addr-box"><a href="'.$gmlink.'">'.$address.' <br>'.$city.', '.$state.' '.$zip.'</a></td></div>';
        echo '<td class="notes">' . $notes . '</td>';

          ?> <td> <?php
          if (!empty($evidence_a)) {
        if(substr($evidence_a, 0, 6) == "image-") $evidence_a = "uploads/$evidence_a";

          ?> <div class="btn-group"> <?php
        if ((substr($evidence_a, 0, 8) == "uploads/") && (file_exists($evidence_a))) { ?>
            <input type="button" onclick="copyToClipboard('<?php echo $domain_with_http . "/database/" . $evidence_a; ?>')" class="btn-evidence" value="Copy evidence"></input>
            <input type="button" class="btn-evidence" onclick="redir('<?php echo $evidence_a; ?>','0')" value="View evidence"></input></td></div> <?php } else { ?>
            <input type="button" onclick="copyToClipboard('<?php echo $evidence_a; ?>')" class="btn-evidence" value="Copy evidence"></input>
            <input type="button" class="btn-evidence" onclick="redir('<?php echo $evidence_a; ?>','0')" value="View evidence"></input></div>
         <?php }}
         ?> </td> <?php
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
