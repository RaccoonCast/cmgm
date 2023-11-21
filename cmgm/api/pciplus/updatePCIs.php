<?php
include "includes/functions.php";

$id = substr($_POST['id'], 0, 10);
$plmn = substr($_POST['plmn'], 0, 6);

$carriers = [
    "310260" => "T-Mobile",
    "311480" => "Verizon",
    "310120" => "Sprint",
    "310410" => "ATT",
    "313100" => "ATT",
    "313340" => "Dish"
];

if (!isset($carriers[$plmn])) {
    error("This carrier is not supported by CMGM, only the major US networks are supported.", $_GET['plmn']);
}

$carrier = $carriers[$plmn];
$pcis_in_post = @$_POST['pci'];

$sql_pcis = "SELECT PCI_1, PCI_2, PCI_3, PCI_4, PCI_5, PCI_6, PCI_7, PCI_8, PCI_9, PCI_10, PCI_11, PCI_12, PCI_13, PCI_14, PCI_15, PCI_16, PCI_17, PCI_18, PCI_19, PCI_20, PCI_21, PCI_22, PCI_23, PCI_24, PCI_25, PCI_26, PCI_27, PCI_28, PCI_29, PCI_30 FROM db WHERE (LTE_1 = '$id' OR LTE_2 = '$id' OR LTE_3 = '$id' OR LTE_4 = '$id' OR LTE_5 = '$id' OR LTE_6 = '$id' OR LTE_7 = '$id' OR LTE_8 = '$id' OR LTE_9 = '$id' OR NR_1 = '$id' OR NR_2 = '$id' OR NR_3 = '$id') AND carrier = '$carrier'";
$result = $conn->query($sql_pcis);

$pcis = [];
if ($result->num_rows > 0) { $pcis['list'] = implode(",", $result->fetch_assoc());}

$pci_string = implode(",", $pcis);
$old_pcis = explode(",", rtrim($pci_string, ','));
$new_pcis = explode(",", $pcis_in_post);

$combined_array = array_diff(array_slice(array_unique(array_merge($old_pcis, $new_pcis)), 0, 30), [""]);

if(count($combined_array) <= count($old_pcis) && !empty($old_pcis) && $pcis['list'] != ",,,,,,,,,,,,,,,,,,,,,,,,,,,,,") report("false",$id,$combined_array);

$sql = "SELECT edit_history, id FROM db WHERE (LTE_1 = '$id' OR LTE_2 = '$id' OR LTE_3 = '$id' OR LTE_4 = '$id' OR LTE_5 = '$id' OR LTE_6 = '$id' OR LTE_7 = '$id' OR LTE_8 = '$id' OR LTE_9 = '$id' OR NR_1 = '$id' OR NR_2 = '$id' OR NR_3 = '$id') AND carrier = '$carrier'";
$row = $conn->query($sql)->fetch_assoc();
$sql_update = "UPDATE db SET ";

$i = 0;
foreach ($combined_array as $value) {
    $i++;
    $sql_update .= "PCI_$i = '$value', ";
}

$edit_history .= "PCIs updated at " . date("Y-m-d H:i") . " PDT." . PHP_EOL;
$escaped_edit_history = mysqli_real_escape_string($conn, $row['edit_history']);
$sql_update .= "edit_history = '{$escaped_edit_history}' WHERE id = '{$row['id']}'";

$conn->query($sql_update);

report("true",$id,$combined_array);
?>
