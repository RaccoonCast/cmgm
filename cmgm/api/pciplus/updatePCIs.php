<?php
//  cody and alps' purple iphones (CAAPI)
include "includes/functions.php"; // error msg / report function

if (is_numeric($_POST['id']) && is_numeric($_POST['plmn'])) {

  $id = substr($_POST['id'], 0, 10); // trim to 10 characters
  $plmn = substr($_POST['plmn'], 0, 6); // trim to 6 characters

  if ($plmn == "310260") $carrier = "T-Mobile";
  if ($plmn == "311480") $carrier = "Verizon";
  if ($plmn == "310120") $carrier = "Sprint";
  if ($plmn == "310410") $carrier = "ATT";
  if ($plmn == "313100") $carrier = "ATT";
  if ($plmn == "313340") $carrier = "Dish";
  if (!isset($carrier)) error("This carrier is not supported by CMGM, only the major US networks are supported.",$_GET['plmn']);
  $PCIs = @$_POST['pci'];

  $sql = "SELECT id from db WHERE (id = '$id' OR LTE_1 = '$id' OR LTE_2 = '$id' OR LTE_3 = '$id' OR LTE_4 = '$id' OR LTE_5 = '$id' OR LTE_6 = '$id' OR LTE_7 = '$id' OR LTE_8 = '$id' OR LTE_9 = '$id' OR NR_1 = '$id' OR NR_2 = '$id' OR NR_3 = '$id') AND carrier = '$carrier' ";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) { while($row = $result->fetch_assoc()) {   $id = $row['id']; } }

  $sql_pcis = "SELECT PCI_1, PCI_2, PCI_3, PCI_4, PCI_5, PCI_6, PCI_7, PCI_8, PCI_9, PCI_10, PCI_11, PCI_12, PCI_13, PCI_14, PCI_15, PCI_16, PCI_17, PCI_18, PCI_19, PCI_20, PCI_21, PCI_22, PCI_23, PCI_24, PCI_25, PCI_26, PCI_27, PCI_28, PCI_29, PCI_30 FROM db WHERE id='$id'";
  $result = $conn->query($sql_pcis);
  $pcis = array();
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
          $pcis['list'] = implode(",", $row);
        }
      }
$pci_string['list'] = implode(",", $pcis);
$var = $pci_string['list'];
$old_pcis = explode(",", rtrim($var, ','));
$new_pcis = explode(",", $PCIs);

$combined_array = array_diff(array_slice(array_unique(array_merge($old_pcis, $new_pcis)), 0, 30), array(""));
} else {
  if (!is_numeric($_POST['id'])) error("CMGM ID not set correctly.",@$_GET['id']);
  if (!is_numeric($_POST['plmn'])) error("Carrier not set correctly, accepted values: 310260, 311480, 310120, 310410, 313340",@$_GET['plmn']);
}
$i = 0;

$sql_update = "UPDATE db SET ";
foreach ($combined_array as $value) {
  $i++;
  $sql_update = $sql_update . "PCI_$i = '$value', ";
  // $edit_history_value = "$edit_history" . "————————— " . date("Y-m-d H:i") . " | PCI+ —————————" . PHP_EOL . "$vals";
  // $sql_edit .= " edit_history = 'PCIs updated to ' WHERE id = $id";ub
}
$sql_update = rtrim($sql_update, ', ') . " WHERE id = $id";
$conn->query($sql_update);
$val = $conn -> affected_rows;
if ($val == "1") report("true",$id,$combined_array);
if ($val == "0") report("false",$id,$combined_array);
?>
