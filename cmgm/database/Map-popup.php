<!doctype html>
<html lang="en">
<head>
<script src="/js/copyToClipboard.js"></script>
  <?php
  $allowGuests = "true";
  include '../functions.php';
  include "../includes/functions/calculateEV.php";
  $id = preg_replace("/[^0-9]/", '', $_GET['id']);
  ?>
  <script>
         function openEditLink() {  window.open("<?php echo $domain_with_http; ?>/database/Edit.php?id=<?php echo $id; ?>", "_blank"); }
         function openDeleteLink() {  window.open("<?php echo $domain_with_http; ?>/database/Edit.php?id=<?php echo $id; ?>&delete=false&redirPage=Map-popup", "_self"); }
         function openNotesLink() {  window.open("<?php echo $domain_with_http; ?>/database/Map-popup-viewnotes.php?mp-id=<?php echo $id; ?>&delete=false&redirPage=Map-popup", "_self"); }
     </script>
</head>
<body class="body">
<?php

$cmgm_uploads_page = "https://files.cmgm.us/";
$database_get_list = "id,date_added,LTE_1,LTE_2,LTE_3,LTE_4,LTE_5,LTE_6,LTE_7,LTE_8,LTE_9,NR_1,NR_2,NR_3,carrier,latitude,longitude,city,zip,state,address,notes,evidence_a,evidence_b,evidence_c,photo_a,photo_b,photo_c,photo_d,photo_e,photo_f,extra_a,extra_b,extra_c,extra_d,extra_e,extra_f,sv_a,sv_b,sv_c,sv_d,sv_e,sv_f,sv_a_date,sv_b_date,sv_c_date,sv_d_date,sv_e_date,sv_f_date,bingmaps_a,cellsite_type,old_cellsite_type,concealed,region_lte,tags,status";

$sql = "SELECT $database_get_list FROM db WHERE id = $id;";
$result = mysqli_query($conn, $sql);

$sql_read_result = mysqli_query($conn,$sql);
while($row = $sql_read_result->fetch_assoc()) foreach ($row as $key => $value) $$key = $value;

if (isset($bingmaps_a)) $bmlink = '<a target="_blank" style="font-size: 10px; vertical-align: super" href="'.$bingmaps_a.'">BM</a>';

function cellmapperLink2 ($cm_latitude,$cm_longitude,$cm_zoom,$cm_carrier,$cm_netType,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$ppT,$ppL) {
  if ("$cm_carrier" == "T-Mobile") $beginning = "MCC=310&MNC=260&";
  if ("$cm_carrier" == "Sprint") $beginning = "MCC=310&MNC=120&";
  if ("$cm_carrier" == "ATT") $beginning = "MCC=310&MNC=410&";
  if ("$cm_carrier" == "Verizon") $beginning = "MCC=311&MNC=480&";
  if ("$cm_carrier" == "Dish") $beginning = "MCC=313&MNC=340&";
  if ("$cm_carrier" == "Unknown") return $ppT;
  if (empty($cm_netType)) $cm_netType = "LTE";
  return '<a target="_blank" href="https://www.cellmapper.net/map?'.$beginning.'type='.$cm_netType.'&latitude='.$cm_latitude.'&longitude='.$cm_longitude.'&zoom='.$cm_zoom.'&clusterEnabled='.$cm_groupTowers.'&showTowerLabels='.$cm_showLabels.'&showOrphans='.$cm_showLowAcc.'&ppT='.$ppT.'&ppL='.$ppL.'">'.$ppT.'</a>';
}

                    $lte_list = null;
                    if(!empty($LTE_1)) { $lte_list = $lte_list . "" . cellmapperLink2($latitude,$longitude,$cm_zoom,$carrier,"LTE",$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$LTE_1,$region_lte); }
                    if(!empty($LTE_2)) { $lte_list = $lte_list . " | " . cellmapperLink2($latitude,$longitude,$cm_zoom,$carrier,"LTE",$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$LTE_2,$region_lte); }
                    if(!empty($LTE_3)) { $lte_list = $lte_list . " | " . cellmapperLink2($latitude,$longitude,$cm_zoom,$carrier,"LTE",$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$LTE_3,$region_lte); }
                    if(!empty($LTE_4)) { $lte_list = $lte_list . " <br> " . cellmapperLink2($latitude,$longitude,$cm_zoom,$carrier,"LTE",$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$LTE_4,$region_lte); }
                    if(!empty($LTE_5)) { $lte_list = $lte_list . " | " . cellmapperLink2($latitude,$longitude,$cm_zoom,$carrier,"LTE",$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$LTE_5,$region_lte); }
                    if(!empty($LTE_6)) { $lte_list = $lte_list . " | " . cellmapperLink2($latitude,$longitude,$cm_zoom,$carrier,"LTE",$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$LTE_6,$region_lte); }
                    if(!empty($LTE_7)) { $lte_list = $lte_list . " | " . cellmapperLink2($latitude,$longitude,$cm_zoom,$carrier,"LTE",$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$LTE_7,$region_lte); }
                    if(!empty($LTE_8)) { $lte_list = $lte_list . " | " . cellmapperLink2($latitude,$longitude,$cm_zoom,$carrier,"LTE",$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$LTE_8,$region_lte); }
                    if(!empty($LTE_9)) { $lte_list = $lte_list . " | " . cellmapperLink2($latitude,$longitude,$cm_zoom,$carrier,"LTE",$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$LTE_9,$region_lte); }

                    if(empty($lte_list)) { $lte_list = cellmapperLink2($latitude,$longitude,$cm_zoom,$carrier,"LTE",$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$LTE_1,$region_lte); }
                    if(!empty($NR_1) && $carrier == "Dish") { $nr_list = $nr_list . "" . cellmapperLink2($latitude,$longitude,$cm_zoom,$carrier,"NR",$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$NR_1,$region_nr); }
                    if(!empty($NR_2) && $carrier == "Dish") { $nr_list = $nr_list . " | " . cellmapperLink2($latitude,$longitude,$cm_zoom,$carrier,"NR",$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$NR_2,$region_nr); }
                    if(!empty($NR_3) && $carrier == "Dish") { $nr_list = $nr_list . " | " . cellmapperLink2($latitude,$longitude,$cm_zoom,$carrier,"NR",$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$NR_3,$region_nr); }


                    // Create links for sv_a/b/c/d, evidence_a/b/c, photo_a/b/c/d/e/f, misc a/b/c
                    $map_popup_flag = "true";
                    include "includes/edit/file_attach_link_gen.php";

                    $concealed_status = ($concealed == "true") ? "Concealed " : "Unconcealed ";
                    $status_icon = ($status == "verified") ? "🟢" : "🔴";
                    if (!empty($cellsite_type)) {
                      include "$SITE_ROOT/includes/functions/tower_types.php";
                      $cellsite_type = !empty($cellsite_type) ? $cellsite_type : $old_cellsite_type;
                      $category = ucfirst(explode('_', $cellsite_type)[0]);
                      $cellsite_type = $options[$category][$cellsite_type];
                    } else {
                      $cellsite_type = $old_cellsite_type;
                    }

                    $url_for_cmgm = "https://cmgm.us/$id";

                    ?>

                    <table>
                    <thead>
                    <tr>

                    <td colspan="2" class="label title" style="line-height: 30px;">
                    <?php echo $status_icon . $concealed_status .  ucfirst($cellsite_type); ?>
                    <div onclick="copyToClipboard('<?php echo $url_for_cmgm; ?>')" style="font-size: 1.2em; cursor: pointer; position: absolute; top: 5px; right: 6px;">📋</div>
                    </td>
                    </tr>
                    </thead>

                    <tr>
                      <td colspan="2" class="label">
                        <input style="float: left" onclick="navigator.clipboard.writeText('<?php echo $latitude . "," . $longitude;?>')" type="button" value="Copy location">
                        <input style="float: left" onclick="openDeleteLink()" type="button" value="Delete #<?php echo $id ?>">
                        <?php if (!empty($notes)) { ?><input style="float: left" onclick="openNotesLink()" type="button" value="View Notes"> <?php } ?>
                        <input style="float: left" onclick="openEditLink()" type="button" value="Edit">
                      </td>
                    </tr>

                    <tr>
                    <td class="label"><?php echo $carrier; echo $carrier === "Dish" ? "<br>gNBs" : "<br>eNBs";?></td>
                    <td><?php echo @$lte_list; echo @$nr_list; ?></td>
                    </tr>


                    <tr rowspan="2">
                    <td class="label">Address</td>
                    <td>
                    <?php echo nl2br('<a target="_blank" href="https://maps.google.com/maps?f=q&source=s_q&hl=en&q=' .$latitude . ',' .$longitude . '">' . $address . ' <br>' . $city . ', ' . $state . ' ' . $zip . '</a>');?>
                    </td>
                    </tr>


                    <tr>
                    <td class="label">Street View<?php echo @$bmlink; ?></td>
                    <td>
                    <?php
                    $variableValues = array($sv_a, $sv_b, $sv_c, $sv_d, $sv_e, $sv_f);
                    $secondaryVariableValues = array($sv_a_date, $sv_b_date, $sv_c_date, $sv_d_date, $sv_e_date, $sv_f_date);
                    $variableNames = array('SV_A', 'SV_B', 'SV_C', 'SV_D', 'SV_E', 'SV_F');

                    $nonEmptyLinks = array();
                    for ($i = 0; $i < count($variableNames); $i++) {
                        $name = $variableNames[$i];
                        $value = $variableValues[$i];
                        $secondaryValue = $secondaryVariableValues[$i];

                        if (!empty($value) && !empty($secondaryValue)) {
                            $nonEmptyLinks[$name] = array(
                                'url' => $value,
                                'label' => $secondaryValue
                            );
                        }
                    }

                    $links = array_map(function ($linkData, $name) {
                        return '<a target="_blank" title="' . $linkData['label'] . '" href="' . $linkData['url'] . '">' . $name . '</a>';
                    }, $nonEmptyLinks, array_keys($nonEmptyLinks));

                    if (!empty($links)) {
                        echo implode(' | ', $links);
                    } else {
                        echo '<a class="error" target="_blank" href="https://www.google.com/maps?layer=c&cbll='.$latitude.','.$longitude.'">Street View</a>';
                    }

                    ?>
                    </td>
                    </tr>
                    <?php
                    $foreachList = array('photo_a', 'photo_b', 'photo_c', 'photo_d', 'photo_e', 'photo_f', 'extra_a', 'extra_b', 'extra_c', 'extra_d', 'extra_e', 'extra_f', 'evidence_a', 'evidence_b', 'evidence_c');
                    foreach ($foreachList as &$value) {
                        if (substr($$value, 0, 1) === '#' && !empty($$value)) $$value = $domain_with_http . '/database/Edit.php?id=' . substr($$value, 1);
                        if (substr($$value, 0, 1) === '@') {
                          $$value = 'https://canon.cmgm.us/' . str_replace("#", "%23", substr($$value, 1));
                        }
                      }
                    ?>
                    <tr>
                    <td class="label">Evidence</td>
                    <td>
                    <?php
                    if(!empty($evidence_a)) echo (substr($evidence_a,0,4)=="http") ? '<a target="_blank" href="' . $evidence_a . '">EV_A</a>' : '<a target="_blank" href="' .$cmgm_uploads_page . $evidence_a . '">EV_A</a>';
                    if(!empty($evidence_b)) echo (substr($evidence_b,0,4)=="http") ? ' | <a target="_blank" href="' . $evidence_b . '">EV_B</a>' : ' | <a target="_blank" href="' .$cmgm_uploads_page . $evidence_b . '">EV_B</a>';
                    if(!empty($evidence_c)) echo (substr($evidence_c,0,4)=="http") ? ' | <a target="_blank" href="' . $evidence_c . '">EV_C</a>' : ' | <a target="_blank" href="' .$cmgm_uploads_page . $evidence_c . '">EV_C</a>';
                    ?>
                    </td>
                    </tr>

                    <tr>
                    <td class="label">Photos</td>
                    <td>
                    <?php
                    if(!empty($photo_a)) echo (substr($photo_a,0,4)=="http") ? '<a target="_blank" href="' . $photo_a . '">PH_A</a>' : '<a target="_blank" href="' .$cmgm_uploads_page . $photo_a . '">PH_A</a>';
                    if(!empty($photo_b)) echo (substr($photo_b,0,4)=="http") ? ' | <a target="_blank" href="' . $photo_b . '">PH_B</a>' : ' | <a target="_blank" href="' .$cmgm_uploads_page . $photo_b . '">PH_B</a>';
                    if(!empty($photo_c)) echo (substr($photo_c,0,4)=="http") ? ' | <a target="_blank" href="' . $photo_c . '">PH_C</a>' : ' | <a target="_blank" href="' .$cmgm_uploads_page . $photo_c . '">PH_C</a>';

                    if(!empty($photo_d)) echo isMobile() ? "<br>" : " | ";

                    if(!empty($photo_d)) echo (substr($photo_d,0,4)=="http") ? '<a target="_blank" href="' . $photo_a . '">PH_D</a>' : '<a target="_blank" href="' .$cmgm_uploads_page . $photo_d . '">PH_D</a>';
                    if(!empty($photo_e)) echo (substr($photo_e,0,4)=="http") ? ' | <a target="_blank" href="' . $photo_e . '">PH_E</a>' : ' | <a target="_blank" href="' .$cmgm_uploads_page . $photo_e . '">PH_E</a>';
                    if(!empty($photo_f)) echo (substr($photo_f,0,4)=="http") ? ' | <a target="_blank" href="' . $photo_c . '">PH_F</a>' : ' | <a target="_blank" href="' .$cmgm_uploads_page . $photo_c . '">PH_F</a>';
                    ?>
                    </tr></td>
                    <tr>
                    <td class="label">Extras</td>
                    <td>
                    <?php
                    if(!empty($extra_a)) echo (substr($extra_a,0,4)=="http") ? '<a target="_blank" href="' . $extra_a . '">EX_A</a>' : '<a target="_blank" href="' .$cmgm_uploads_page . $extra_a . '">EX_A</a>';
                    if(!empty($extra_b)) echo (substr($extra_b,0,4)=="http") ? ' | <a target="_blank" href="' . $extra_b . '">EX_B</a>' : ' | <a target="_blank" href="' .$cmgm_uploads_page . $extra_b . '">EX_B</a>';
                    if(!empty($extra_c)) echo (substr($extra_c,0,4)=="http") ? ' | <a target="_blank" href="' . $extra_c . '">EX_C</a>' : ' | <a target="_blank" href="' .$cmgm_uploads_page . $extra_c . '">EX_C</a>';

                    if(!empty($extra_d)) echo isMobile() ? "<br>" : " | ";

                    if(!empty($extra_d)) echo (substr($extra_d,0,4)=="http") ? '<a target="_blank" href="' . $extra_a . '">EX_D</a>' : '<a target="_blank" href="uploads/' . $extra_d . '">EX_D</a>';
                    if(!empty($extra_e)) echo (substr($extra_e,0,4)=="http") ? ' | <a target="_blank" href="' . $extra_e . '">EX_E</a>' : ' | <a target="_blank" href="' .$cmgm_uploads_page . $extra_e . '">EX_E</a>';
                    if(!empty($extra_f)) echo (substr($extra_f,0,4)=="http") ? ' | <a target="_blank" href="' . $extra_c . '">EX_F</a>' : ' | <a target="_blank" href="' .$cmgm_uploads_page . $extra_c . '">EX_F</a>';
                    ?>
                    </td>
                    </tr>

                    <tr>
                    <td class="label">Tags</td>
                    <td><?php echo @$tags?></td>
                    </tr>

</tbody>
</table>
</body>
</html>
