<!doctype html>
<html lang="en">
<head>
  <?php
  $allowGuests = "true";
  include '../functions.php';
  include "../includes/functions/calculateEV.php";
  $id = $_GET['mp-id'];
  ?>
  <script src="../js/redirAtPos.js"></script>
  <script>
         function openEditLink() {  window.open("<?php echo $domain_with_http; ?>/database/Edit.php?id=<?php echo $id; ?>", "_blank"); }
         function openDeleteLink() {  window.open("<?php echo $domain_with_http; ?>/database/Edit.php?id=<?php echo $id; ?>&delete=false&redirPage=Map-popup", "_self"); }
     </script>
</head>
<body class="body">
<?php


$database_get_list = "id,date_added,LTE_1,LTE_2,LTE_3,LTE_4,LTE_5,LTE_6,carrier,latitude,longitude,city,zip,state,address,notes,evidence_score,evidence_a,sv_a,sv_b,sv_c,sv_d,sv_e,sv_f,cellsite_type,concealed";

$sql = "SELECT $database_get_list FROM db WHERE id = $id;";
$result = mysqli_query($conn, $sql);

$sql_read_result = mysqli_query($conn,$sql);
while($row = $sql_read_result->fetch_assoc()) foreach ($row as $key => $value) $$key = $value;

                    if(!empty($LTE_1)) { $lte_list = $LTE_1; }
                    if(!empty($LTE_2)) { $lte_list = $lte_list . ", " . $LTE_2; }
                    if(!empty($LTE_3)) { $lte_list = $lte_list . ", " . $LTE_3; }
                    if(!empty($LTE_4)) { $lte_list = $lte_list . ", " . $LTE_4; }
                    if(!empty($LTE_5)) { $lte_list = $lte_list . ", " . $LTE_5; }
                    if(!empty($LTE_6)) { $lte_list = $lte_list . ", " . $LTE_6; }
                    if(!empty($NR_1)) { $ltenr_list = ", " . $ltenr_list . ", " . $NR_1; }
                    if(!empty($NR_2)) { $ltenr_list = ", " . $ltenr_list . ", " . $NR_2; }

                    if(!empty($sv_a)) { $sv_list = '<a href="https://'.$sv_a.'">SV_A</a>'; }
                    if(!empty($sv_b)) { $sv_list = $sv_list . " | " . '<a target="_blank" href="https://'.$sv_b.'">SV_B</a>'; }
                    if(!empty($sv_c)) { $sv_list = $sv_list . " | " . '<a target="_blank" href="https://'.$sv_c.'">SV_C</a>'; }
                    if(!empty($sv_d)) if (isMobile()) $sv_list = $sv_list . '<br><a target="_blank" href="https://'.$sv_d.'">SV_D</a>';
                    if(!empty($sv_d)) if (!isMobile()) { $sv_list = $sv_list . " | " . '<a target="_blank" href="https://'.$sv_d.'">SV_D</a>'; }
                    if(!empty($sv_e)) { $sv_list = $sv_list . " | " . '<a target="_blank" href="https://'.$sv_e.'">SV_E</a>'; }
                    if(!empty($sv_f)) { $sv_list = $sv_list . " | " . '<a target="_blank" href="https://'.$sv_f.'">SV_F</a>'; }
                    if (empty($sv_a)) $sv_list = "Street View";

                    $concealed_status = ($concealed == "true") ? "Concealed" : "Unconcealed";
                    ?>

                    <table>
                    <thead>
                    <tr id="tr-even">
                    <td colspan="2"><?php echo $concealed_status . " " . $carrier  . " " .  ucfirst($cellsite_type) . " #" . $id ?></td>
                    </tr>
                    </thead>

                    <tr class="tr-odd">
                    <td>eNBs</td>
                    <td><?php echo $lte_list ?></td>
                    </tr>

                    <tr class="tr-even">
                    <td>Actions</td>
                    <td><input onclick="openDeleteLink()" type="button" value="Delete"><input type="button" value="View Notes"><input onclick="openEditLink()" type="button" value="Edit"></td>
                    </tr>

                    <tr class="tr-odd">
                    <td rowspan="2">Address</td>
                    <td><?php echo $address ?></td>
                    </tr>

                    <tr class="tr-odd">
                    <td><?php echo $city . " " . $state ?>, <?php echo $zip ?></td>
                    </tr>

                    <tr class="tr-even">
                    <td>Street View</td>
                    <td><?php echo $sv_list ?></td>
                    </tr>
</tbody>
</table>
</body>
</html>
