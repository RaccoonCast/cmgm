<?php
if (!isset($_GET['new'])) {
   if (@$back_num < 15 & isset($back_num)) redir("Edit.php?id=" . --$id . "&back=" . ++$back_num . "","0"); // back
   if (@$next_num < 15 & isset($next_num)) redir("Edit.php?id=" . ++$id . "&next=" . ++$next_num . "","0"); // next
   if (!empty($id)) $new_id = @mysqli_fetch_array(mysqli_query($conn, "SELECT id FROM database_db WHERE LTE_1='$id' OR LTE_2='$id' OR LTE_3='$id' OR LTE_4='$id' OR LTE_5='$id' OR LTE_5='$id' OR LTE_6='$id' OR NR_1='$id' OR NR_2='$id'"))['id']; // id search
   if (!empty($new_id)) redir("Edit.php?id=$new_id","0"); // redir to searched ID
   ?> <title>EvilCM - Edit</title> <?php
   include "includes/edit/id_input.php";
   include "includes/edit/prev_next.php";
   die();
} else {
   $id = mysqli_fetch_array(mysqli_query($conn, "SELECT t1.id + 1 FROM database_db t1 WHERE NOT EXISTS (SELECT * FROM database_db t2 WHERE t2.id = t1.id + 1) LIMIT 1"))['t1.id + 1'];
}
?>