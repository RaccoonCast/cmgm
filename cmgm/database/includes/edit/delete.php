<?php function delete($id,$delete_conf,$redirPage,$conn,$userID) {
if ($delete_conf == "true" && $userID != "guest") {
  mysqli_query($conn, "DELETE from db WHERE id='$id'"); // First parameter is just return of "mysqli_connect()" function
  if ($redirPage == "Map-popup") {
    echo "Deletion successful, refresh Map to update.";
    die();
  }
  if ($redirPage == "Edit") {
    echo "Redirecting to home page.";
    redir("../",1);
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
</form><br>
<button onclick="history.back()">Cancel</button>
<?php
if ($redirPage == "Map-popup") { die(); } else { echo'<br><hr><br>'; }
}
?>
