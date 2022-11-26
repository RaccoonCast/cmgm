<?php
foreach($_GET as $key => $value){
  if ($key == "pciplus" OR $key == "new") {
    // do nothing
  } else {
    ${$key} = $value;
    echo $key . ": " . $value . "<br>";
  }
}
include_once SITE_ROOT . "/includes/link-conversion-and-handling/convert.php";
[$address,$city,$state,$zip] = convert("$latitude,$longitude","pciplus",$default_latitude,$default_longitude,$maps_api_key,$userID,$default_carrier,$cm_mapType,$cm_groupTowers,$cm_showLabels,$cm_showLowAcc,$cm_zoom);
// echo $address . "<br>";
// echo $city . "<br>";
// echo $state . "<br>";
// echo $zip . "<br>";


$PCIs = @$_GET['pci'];
$PCIs = explode(",", @$PCIs);
$PCI_1 = @$PCIs[0];
$PCI_2 = @$PCIs[1];
$PCI_3 = @$PCIs[2];
$PCI_4 = @$PCIs[3];
$PCI_5 = @$PCIs[4];
$PCI_6 = @$PCIs[5];
$PCI_7 = @$PCIs[6];
$PCI_8 = @$PCIs[7];
$PCI_9 = @$PCIs[8];
$PCI_10 = @$PCIs[9];
$PCI_11 = @$PCIs[10];
$PCI_12 = @$PCIs[11];
$PCI_13 = @$PCIs[12];
$PCI_14 = @$PCIs[13];
$PCI_15 = @$PCIs[14];
$PCI_16 = @$PCIs[15];
$PCI_17 = @$PCIs[16];
$PCI_18 = @$PCIs[17];
$PCI_19 = @$PCIs[18];
$PCI_20 = @$PCIs[19];
$PCI_21 = @$PCIs[20];
$PCI_22 = @$PCIs[21];
$PCI_23 = @$PCIs[22];
$PCI_24 = @$PCIs[23];
$status = "verified";
$plmn = @$_GET['plmn'];
if ($plmn == "310260") $carrier = "T-Mobile";
if ($plmn == "311480") $carrier = "Verizon";
if ($plmn == "310120") $carrier = "Sprint";
if ($plmn == "310410") $carrier = "ATT";

?>
