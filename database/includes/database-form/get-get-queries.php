<?php
$sub1 = ""; $sub2 = "";
if (isset($_GET['carrier'])) $carrier = $_GET['carrier'];
if (isset($_GET['id_1'])) $id_1 = $_GET['id_1'];

if (!empty($carrier)) {
  $sub1 = "WHERE carrier='$carrier'";
}

if (!empty($id_1)) {
  if (empty($sub1)) {
    $sub2 = "WHERE id_1='$id_1'";
  } else {
    $sub2 = "AND id_1='$id_1'";
  }
}
?>
