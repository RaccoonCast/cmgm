<?php
include "../includes/functions/tower_types.php";
if (!isset($_GET['limit'])) $limit = 75;
$latitude_for_search = !empty($latitude) ? $latitude : $default_latitude;
$longitude_for_search = !empty($longitude) ? $longitude : $default_longitude;
$sql = "SELECT DISTINCT id,LTE_1,carrier,latitude,longitude,address,city,state,zip,notes,evidence_a,cellsite_type,old_cellsite_type,region_lte, (3959 * ACOS(COS(RADIANS(".@$latitude_for_search.")) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS(".@$longitude_for_search.")) + SIN(RADIANS(".@$latitude_for_search.")) * SIN(RADIANS(latitude)))) AS DISTANCE FROM db WHERE 1=1 ".@$db_vars." ".@$locsearch." ORDER BY distance LIMIT $limit";

$result = mysqli_query($conn,$sql);
$numRows = mysqli_num_rows($result);
if ($numRows == 0) {
  echo '<h4 style="padding-left: 1.2em">No results found, <a href="javascript:history.back()">go back</a>.</h4>';
  die();
}


if(empty($result)) {

}

if (mysqli_num_rows($result) == "1") {
  while($row = $result->fetch_assoc()) {
  foreach ($row as $key => $value) {
    $$key = $value;
    if ($key == "DISTANCE") { redir("Edit.php?id=$id","0"); }
  }
}
}

?>
<table border="1">
<thead>
<tr>
  <th class="btn-holder-edit">Edit</th>
  <th class="enb">eNB</th>
  <th class="carrier">Carrier</th>
  <th class="address">Address</th>
  <th class="cellsite_type">Cellsite Type</th>
  <th class="notes">Notes</th>
  <th class="link">Link</th>
  <th class="btn-holder-evidence">Evidence</th>
</tr>
</thead>
<tbody> <?php
if (mysqli_num_rows($result) > 1) { while($row = $result->fetch_assoc()) {
    foreach ($row as $key => $value) {
      $$key = $value;
      if ($key == "DISTANCE") {
        echo "<tr>";
        if ($cellsite_type == "") {
          $cellsite_type_normalized = "<i>" . ucfirst($old_cellsite_type) . "</i>";
        } else {
          $category = ucfirst(explode('_', $cellsite_type)[0]);
          $cellsite_type_normalized = $options[$category][$cellsite_type];
        }
        include_once $SITE_ROOT . "/includes/link-conversion-and-handling/function_goto.php";
        include_once $SITE_ROOT . "/includes/misc-functions/cm_linkgen.php";
        $user_data = ['latitude' => $latitude, 'longitude' => $longitude];                                                                
        $gmlink = function_goto($user_data, "Google Maps");
        $cmlink = cellmapperLink($latitude,$longitude,$cm_zoom,$carrier,"LTE",$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$LTE_1,$region_lte);

          ?> <td><input type="button" class="w-100 btn-edit" onclick="redir('Edit.php?id=<?php echo $id;?>','0')"value="Edit"></input></td> <?php

        echo '<td class="lte" id="'.$id.'"><a href="'.$cmlink.'">'.$LTE_1.'</a></td>';
        echo '<td class="carrier">'.$carrier.'</td>';
        echo '<td class="address"><div class="addr-box"><a href="'.$gmlink.'">'.$address.' <br>'.$city.', '.$state.' '.$zip.'</a></td></div>';
        echo '<td title="'.$cellsite_type.'" "class="cellsite_type">' . $cellsite_type_normalized . '</td>';
        echo '<td class="notes">' . $notes . '</td>';
        echo '<td class="link"><a href="Edit.php?id='.$id.'">#'.str_pad($id, 4, '0', STR_PAD_LEFT);'</a></td>';

          ?> <td> <?php
          if (!empty($evidence_a)) {

          ?> <div class="btn-group"> <?php
          if (preg_match('/^(?:image|canon|misc|photo)/', $evidence_a)) { ?>
            <input type="button" onclick="copyToClipboard('<?php echo $domain_with_http . "/database/" . $evidence_a; ?>')" class="btn-evidence" value="Copy"></input>
            <input type="button" class="btn-evidence" onclick="redir('https://files.cmgm.us/<?php echo $evidence_a; ?>','0')" value="View"></input></td></div> <?php } else { ?>
            <input type="button" onclick="copyToClipboard('<?php echo $evidence_a; ?>')" class="btn-evidence" value="Copy"></input>
            <input type="button" class="btn-evidence" onclick="redir('<?php echo $evidence_a; ?>','0')" value="View"></input></div>
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
