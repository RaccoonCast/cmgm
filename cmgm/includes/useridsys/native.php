<?php
$curr_userIP = $_SERVER["REMOTE_ADDR"];
include SITE_ROOT . "/includes/functions/sqlpw.php";

// Check to see if browser has a USER ID cookie and if it does create a variable called "cookie_userID" with that value.
if (isset($_COOKIE['userID'])) {
  $cookie_userID = $_COOKIE['userID'];
} else {
  $cookie_userID = null;
}

// Get userID data SQL for user with the browser's IP or the browser's userID cookie.
$sql = "SELECT * FROM userID WHERE userIP = '$curr_userIP' OR userID='$cookie_userID'";
$result = mysqli_query($conn,$sql);
while($row = $result->fetch_assoc()) {
    foreach ($row as $key => $value) {
      if ($key != "id") {
        $$key = $value;
        if (@$debug_flag == "3") {
          echo basename(__FILE__) . ": " . "Setting $" . $key . " to have value '" . $value . "'<br>";
        }
      }
    }
  }

// If the above code failed, $userIP variable would NOT be set, this means no entry... New IP.php we go!
if (!isset($userIP)) {
  include "newIP.php";
}

// If the IP of the current browser is not the same as the IP listed in the database update the IP in the databse with the IP of the current browser.
if ($curr_userIP != $userIP) {
  mysqli_query($conn,"UPDATE userID SET userIP = '$curr_userIP' WHERE userID = '$cookie_userID'");
}

// Renew the cookie.
?> <script src="/js/setCookie.js"></script><script>setCookie("userID", "<?php echo $userID ?>", "1"); </script> <?php
$result->close();

if (isset($gmaps_api_key_access)) if ($gmaps_api_key_access == 'true') $maps_api_key = file_get_contents($siteroot . "/secret_maps_api_key.hiddenpass", true);
?>
