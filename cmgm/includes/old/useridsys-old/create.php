<?php
$userIP = $_SERVER["REMOTE_ADDR"];


if (!isset($_COOKIE['userID'])) {
$checkIP = mysqli_fetch_array(mysqli_query($conn, "SELECT userIP FROM userID WHERE userIP = '$userIP'"))['userIP'];

if (isset($checkIP)) {
  if (isset($_POST['pin'])) {
    $userID = mysqli_fetch_array(mysqli_query($conn, "SELECT pin FROM userID WHERE userIP = '$userIP'"))['userIP'];
  } else {

if (isset($_POST['pin'])) $db_pin = mysqli_fetch_array(mysqli_query($conn, "SELECT pin FROM userID WHERE userIP = '$userIP'"))['pin'];
if ($db_pin == $pin) {
  $userID = mysqli_fetch_array(mysqli_query($conn, "SELECT userID FROM userID WHERE userIP = '$userIP'"))['userID'];
  setcookie("userID", $userID, time()+2147483647);
}
    include "submit.php"
  } else {
    include "form.php"
  }
      }
} else {
  include "get-vals.php"
                    }
              }
      }
}
