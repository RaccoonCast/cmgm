<?php
function getUsername($userID,$conn) {
  echo mysqli_fetch_array(mysqli_query($conn, "SELECT username FROM userID WHERE userID='$userID'"))['username'];
} ?>
