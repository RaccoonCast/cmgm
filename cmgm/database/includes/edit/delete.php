<?php function delete($id,$delete_conf,$redirPage,$conn) {
if ($delete_conf == "true") {
  mysqli_query($conn, "DELETE from database_db WHERE id='$id'"); // First parameter is just return of "mysqli_connect()" function
  if ($redirPage == "Map-popup") {
    echo "Deletion successful, refresh page to show changes.";
  }
  if ($redirPage == "Edit") {
    echo "Attempting to redirect to edit page for ID $id, should fail to find.";
    redir("Edit.php?id=$id",2);
  }
  if ($redirPage != "Map-popup") redir("../",0);
}
?>
<p>Are you sure you want to delete?</p>
<form action="Edit.php" id="form1" method="post">
  <input type="hidden" name="id" value="<?php echo $id?>">
  <input type="hidden" name="delete" value="true">
  <input type="hidden" name="redirPage" value="<?php echo $redirPage?>">
  <button type="submit" form="form1" value="Submit">Delete</button>
<br>
</form>
<?php
if ($redirPage == "Map-popup") { die(); } else { echo'<br><hr><br>'; }
}
?>
