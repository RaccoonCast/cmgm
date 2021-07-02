<?php function lockorunlock($id,$lock_status,$redirPage,$conn,$userID) {
if ($lock_status == "lock_conf") {
  mysqli_query($conn, "UPDATE database_db SET edit_lock = '$userID' WHERE id = '$id'"); // First parameter is just return of "mysqli_connect()" function
  if ($redirPage == "Edit") { echo "Locked."; redir("Edit.php?id=$id",2);}
  if ($redirPage == "Map-popup") echo "Locked";
  if ($redirPage != "Map-popup") redir("../",0);
}
if ($lock_status == "unlock_conf") {
  mysqli_query($conn, "UPDATE database_db SET edit_lock = '' WHERE id = '$id'"); // First parameter is just return of "mysqli_connect()" function
  if ($redirPage == "Edit") { echo "Unlocked."; redir("Edit.php?id=$id",2);}
  if ($redirPage == "Map-popup") echo "Unlocked";
  if ($redirPage != "Map-popup") redir("../",0);
}

// lock - unverified
if ($lock_status == "lock") { ?>
  <p>Are you sure you want to lock?</p>
  <form action="Edit.php" id="form1" method="post">
    <input type="hidden" name="id" value="<?php echo $id?>">
    <input type="hidden" name="lock_status" value="lock_conf">
    <input type="hidden" name="redirPage" value="<?php echo $redirPage?>">
    <button type="submit" form="form1" value="Submit">Lock</button><br></form> <?php }

//unlock - unverified
if ($lock_status == "unlock") { ?>
  <p>Are you sure you want to unlock?</p>
  <form action="Edit.php" id="form1" method="post">
    <input type="hidden" name="id" value="<?php echo $id?>">
    <input type="hidden" name="lock_status" value="unlock_conf">
    <input type="hidden" name="redirPage" value="<?php echo $redirPage?>">
    <button type="submit" form="form1" value="Submit">Unlock</button><br></form> <?php
}

if ($redirPage == "Map-popup") { die(); } else { echo'<br><hr><br>'; }
}
?>
