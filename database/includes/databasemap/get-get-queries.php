<?php
$sub1 = ""; $sub2 = ""; $sub3 = "";
if (isset($_GET['carrier'])) $carrier = $_GET['carrier'];
if (isset($_GET['type'])) $type = $_GET['type'];
if (isset($_GET['id'])) $id = $_GET['id'];

if (!empty($carrier)) {
  $sub1 = "WHERE carrier='$carrier'";
}

if (!empty($id)) {
  if (empty($sub1)) {
    $sub2 = "WHERE id='$id'";
  } else {
    $sub2 = "AND id='$id'";
  }
}
?>
