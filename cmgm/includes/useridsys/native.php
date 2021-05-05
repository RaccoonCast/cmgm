<?php
$ip = $_SERVER["REMOTE_ADDR"];
$sql = "SELECT * FROM userID WHERE userIP = '$ip'";
$result = mysqli_query($conn,$sql);
while($row = $result->fetch_assoc()) {
    foreach ($row as $key => $value) {
        $$key = $value;
        }
      }
$result->close();

if (isset($gmaps_api_key_access)) if ($gmaps_api_key_access == 'true') $maps_api_key = file_get_contents($siteroot . "/maps_api_key.hiddenpass", true);

if (!isset($userIP)) {

  if (isset($_COOKIE['userID'])) {
    $cookie_userID = $_COOKIE['userID'];
    $check_userID = @mysqli_fetch_array(mysqli_query($conn, "SELECT userID FROM userID WHERE userID='$cookie_userID'"))['userID'];
      if ($check_userID == $cookie_userID) {
        mysqli_query($conn,"UPDATE userID SET userIP = '$curr_userIP' WHERE userID = '$cookie_userID'");
        // Renew cookie.
        ?> <script src="/js/setCookie.js"></script><script>setCookie("userID", "<?php echo $userID ?>", "1"); </script> <?php
      } else {
        include "newIP.php";
      }

  } else {
    include "newIP.php";
  }

}
?>
