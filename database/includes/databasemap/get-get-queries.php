<?php
$sub1 = ""; $sub2 = "";
if (isset($_GET['carrier'])) $carrier = $_GET['carrier'];
if (isset($_GET['LTE_1'])) $LTE_1 = $_GET['LTE_1'];
if (isset($_GET['limit'])) $limit = $_GET['limit'];

if (!empty($carrier)) {
  $sub1 = "WHERE carrier='$carrier'";
}

if (!empty($LTE_1)) {
  if (empty($sub1)) {
    $sub2 = "WHERE LTE_1='$LTE_1'";
  } else {
    $sub2 = "AND LTE_1='$LTE_1'";
  }
}
?>
