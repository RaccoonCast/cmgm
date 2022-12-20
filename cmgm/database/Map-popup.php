<!doctype html>
<html lang="en">
<head>
  <?php
  $allowGuests = "true";
  include '../functions.php';
  include "../includes/functions/calculateEV.php";
  $id = $_GET['mp-id'];
  ?>
  <script>
         function openEditLink() {  window.open("<?php echo $domain_with_http; ?>/database/Edit.php?id=<?php echo $id; ?>", "_blank"); }
         function openDeleteLink() {  window.open("<?php echo $domain_with_http; ?>/database/Edit.php?id=<?php echo $id; ?>&delete=false&redirPage=Map-popup", "_self"); }
         function openNotesLink() {  window.open("<?php echo $domain_with_http; ?>/database/Map-popup-viewnotes.php?mp-id=<?php echo $id; ?>&delete=false&redirPage=Map-popup", "_self"); }
     </script>
</head>
<body class="body">
<?php


$database_get_list = "id,date_added,LTE_1,LTE_2,LTE_3,LTE_4,LTE_5,LTE_6,carrier,latitude,longitude,city,zip,state,address,notes,evidence_score,evidence_a,evidence_b,evidence_c,photo_a,photo_b,photo_c,photo_d,photo_e,photo_f,sv_a,sv_b,sv_c,sv_d,sv_e,sv_f,cellsite_type,concealed,region_lte,tags";

$sql = "SELECT $database_get_list FROM db WHERE id = $id;";
$result = mysqli_query($conn, $sql);

$sql_read_result = mysqli_query($conn,$sql);
while($row = $sql_read_result->fetch_assoc()) foreach ($row as $key => $value) $$key = $value;

function cellmapperLink2 ($cm_latitude,$cm_longitude,$cm_zoom,$cm_carrier,$cm_netType,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$ppT,$ppL) {
  if ("$cm_carrier" == "T-Mobile") $beginning = "MCC=310&MNC=260&";
  if ("$cm_carrier" == "Sprint") $beginning = "MCC=310&MNC=120&";
  if ("$cm_carrier" == "ATT") $beginning = "MCC=310&MNC=410&";
  if ("$cm_carrier" == "Verizon") $beginning = "MCC=311&MNC=480&";
  if ("$cm_carrier" == "Unknown") return $ppT;
  if (empty($cm_netType)) $cm_netType = "LTE";
  return '<a target="_blank" href="https://www.cellmapper.net/map?'.$beginning.'type='.$cm_netType.'&latitude='.$cm_latitude.'&longitude='.$cm_longitude.'&zoom='.$cm_zoom.'&clusterEnabled='.$cm_groupTowers.'&showTowerLabels='.$cm_showLabels.'&showOrphans='.$cm_showLowAcc.'&ppT='.$ppT.'&ppL='.$ppL.'">'.$ppT.'</a>';
}

                    if(!empty($LTE_1)) { $lte_list = cellmapperLink2($latitude,$longitude,$cm_zoom,$carrier,"LTE",$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$LTE_1,$region_lte); }

                    if(!empty($LTE_2)) { $lte_list = $lte_list . ", " . cellmapperLink2($latitude,$longitude,$cm_zoom,$carrier,"LTE",$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$LTE_2,$region_lte); }
                    if(!empty($LTE_3)) { $lte_list = $lte_list . ", " . cellmapperLink2($latitude,$longitude,$cm_zoom,$carrier,"LTE",$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$LTE_3,$region_lte); }
                    if(!empty($LTE_4)) { $lte_list = $lte_list . ", " . cellmapperLink2($latitude,$longitude,$cm_zoom,$carrier,"LTE",$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$LTE_4,$region_lte); }
                    if(!empty($LTE_5)) { $lte_list = $lte_list . ", " . cellmapperLink2($latitude,$longitude,$cm_zoom,$carrier,"LTE",$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$LTE_5,$region_lte); }
                    if(!empty($LTE_6)) { $lte_list = $lte_list . ", " . cellmapperLink2($latitude,$longitude,$cm_zoom,$carrier,"LTE",$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$LTE_6,$region_lte); }
                    if(!empty($NR_1)) { $ltenr_list = ", " . $ltenr_list . ", " . cellmapperLink2($latitude,$longitude,$cm_zoom,$carrier,"NR",$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$NR_1,$region_nr); }
                    if(!empty($NR_2)) { $ltenr_list = ", " . $ltenr_list . ", " . cellmapperLink2($latitude,$longitude,$cm_zoom,$carrier,"NR",$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$NR_2,$region_nr); }


                    // Create links for sv_a/b/c/d, evidence_a/b/c, photo_a/b/c/d/e/f, misc a/b/c
                    $map_popup_flag = "true";
                    include "includes/edit/file_attach_link_gen.php";

                    $concealed_status = ($concealed == "true") ? "Concealed" : "Unconcealed";
                    ?>

                    <table>
                    <thead>
                    <tr>
                    <td colspan="2"><?php echo $concealed_status . " " . $carrier  . " " .  ucfirst($cellsite_type) . " #" . $id ?></td>
                    </tr>
                    </thead>

                    <tr>
                      <td colspan="2">
                        <input style="float: left" onclick="openDeleteLink()" type="button" value="Delete">
                        <input style="float: left" onclick="openNotesLink()" type="button" value="View Notes">
                        <input style="float: left" onclick="openEditLink()" type="button" value="Edit">
                      </td>
                    </tr>

                    <tr>
                    <td>eNBs</td>
                    <td><?php echo $lte_list ?></td>
                    </tr>


                    <tr rowspan="2">
                    <td>Address</td>
                    <td>
                    <?php echo nl2br('<a target="_blank" href="https://maps.google.com/maps?f=q&source=s_q&hl=en&q=' .$latitude . ',' .$longitude . '">' . $address . ' <br>' . $city . ', ' . $state . ' ' . $zip . '</a>');?>
                    </td>
                    </tr>


                    <tr>
                    <td>Street View</td>
                    <td>
                    <?php
                    if(!empty($sv_a)) echo '<a target="_blank" href="'.$sv_a.'">SV_A</a>';
                    if(!empty($sv_b)) echo " | " . '<a target="_blank" href="'.$sv_b.'">SV_B</a>';
                    if(!empty($sv_c)) echo " | " . '<a target="_blank" href="'.$sv_c.'">SV_C</a>';

                    if(!empty($sv_d)) echo isMobile() ? "<br>" : " | ";

                    if(!empty($sv_d)) echo '<a target="_blank" href="'.$sv_d.'">SV_D</a>';
                    if(!empty($sv_e)) echo " | " . '<a target="_blank" href="'.$sv_e.'">SV_E</a>';
                    if(!empty($sv_f)) echo " | " . '<a target="_blank" href="'.$sv_f.'">SV_F</a>';

                    if (empty($sv_a)) echo '<a class="error" target="_blank" href="https://www.google.com/maps?layer=c&cbll='.$latitude.','.$longitude.'">Street View</a>';
                    ?></td>
                    </tr>

                    <tr>
                    <td>Evidence</td>
                    <td>
                    <?php
                    if(!empty($evidence_a)) echo (substr($evidence_a,0,4)=="http") ? '<a target="_blank" href="' . $evidence_a . '">EV_A</a>' : '<a target="_blank" href="uploads/' . $evidence_a . '">EV_A</a>';
                    if(!empty($evidence_b)) echo (substr($evidence_b,0,4)=="http") ? ' | <a target="_blank" href="' . $evidence_b . '">EV_B</a>' : ' | <a target="_blank" href="uploads/' . $evidence_b . '">EV_B</a>';
                    if(!empty($evidence_c)) echo (substr($evidence_c,0,4)=="http") ? ' | <a target="_blank" href="' . $evidence_c . '">EV_C</a>' : ' | <a target="_blank" href="uploads/' . $evidence_c . '">EV_C</a>';
                    ?>
                    </td>
                    </tr>

                    <tr>
                    <td>Photos</td>
                    <td>
                    <?php
                    if(!empty($photo_a)) echo (substr($photo_a,0,4)=="http") ? '<a target="_blank" href="' . $photo_a . '">PH_A</a>' : '<a target="_blank" href="uploads/' . $photo_a . '">PH_A</a>';
                    if(!empty($photo_b)) echo (substr($photo_b,0,4)=="http") ? ' | <a target="_blank" href="' . $photo_b . '">PH_B</a>' : ' | <a target="_blank" href="uploads/' . $photo_b . '">PH_B</a>';
                    if(!empty($photo_c)) echo (substr($photo_c,0,4)=="http") ? ' | <a target="_blank" href="' . $photo_c . '">PH_C</a>' : ' | <a target="_blank" href="uploads/' . $photo_c . '">PH_C</a>';

                    if(!empty($photo_d)) echo isMobile() ? "<br>" : " | ";

                    if(!empty($photo_d)) echo (substr($photo_d,0,4)=="http") ? '<a target="_blank" href="' . $photo_a . '">PH_D</a>' : '<a target="_blank" href="uploads/' . $photo_d . '">PH_D</a>';
                    if(!empty($photo_e)) echo (substr($photo_e,0,4)=="http") ? ' | <a target="_blank" href="' . $photo_e . '">PH_E</a>' : ' | <a target="_blank" href="uploads/' . $photo_e . '">PH_E</a>';
                    if(!empty($photo_f)) echo (substr($photo_f,0,4)=="http") ? ' | <a target="_blank" href="' . $photo_c . '">PH_F</a>' : ' | <a target="_blank" href="uploads/' . $photo_c . '">PH_F</a>';
                    ?>
                    </td>
                    </tr>

                    <tr>
                    <td>Tags</td>
                    <td><?php echo @$tags?></td>
                    </tr>

</tbody>
</table>
</body>
</html>
