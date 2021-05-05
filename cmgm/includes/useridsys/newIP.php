<?php
header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache'); ?>
<!DOCTYPE html>
<html lang="en">
   <head>
     <script>
      function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
      }
     </script>
      <?php

      $siteroot = $_SERVER['DOCUMENT_ROOT'];
      if ($siteroot == "/home/spane2003/cmgm.gq") {
        $secret_pass = file_get_contents($siteroot . "/secret-ip-whitelist-pass.hiddenpass", true);
      } else {
        $secret_pass = file_get_contents($siteroot . "\secret-ip-whitelist-pass.hiddenpass", true);
      }

      // Check Real Quick if IP is in there to verify it's not.
      $ip = $_SERVER["REMOTE_ADDR"];
      $sql = "SELECT * FROM userID WHERE userIP = '$ip'";
      $result = mysqli_query($conn,$sql);
      while($row = $result->fetch_assoc()) { foreach ($row as $key => $value) {$$key = $value;} }
      if (isset($userIP)) { redir($_SERVER['REQUEST_URI'],"0"); die(); }


      $sql = "SELECT * FROM userID WHERE userIP = '$ip'";
      $result = mysqli_query($conn,$sql);

      while($row = $result->fetch_assoc()) {
          foreach ($row as $key => $value) {
              $$key = $value;
              }
            }
      if (isset($_POST['password']) && $secret_pass == $_POST['password']) {
        $userID = substr(str_shuffle(md5(time())),0,32);
        ?> <script> setCookie("userID", "<?php echo $userID ?>", "1"); </script> <?php

        $username = $userID;
        $userIP = $_SERVER["REMOTE_ADDR"];
        $gmaps_api_key_access = "false";
        $default_carrier = null;
        $default_latitude = "38.89951743540001";
        $default_longitude = "-77.03655226691319";
        $theme = "white";
        $gmaps_util = "0";

        $sql = "INSERT INTO userID (userID, username, userIP, gmaps_api_key_access, default_carrier, default_latitude, default_longitude, theme, gmaps_util)
                        VALUES (
                          '".mysqli_real_escape_string($conn, $userID)."',
                          '".mysqli_real_escape_string($conn, $username)."',
                          '".mysqli_real_escape_string($conn, $userIP)."',
                          '".mysqli_real_escape_string($conn, $gmaps_api_key_access)."',
                          '".mysqli_real_escape_string($conn, $default_carrier)."',
                          '".mysqli_real_escape_string($conn, $default_latitude)."',
                          '".mysqli_real_escape_string($conn, $default_longitude)."',
                          '".mysqli_real_escape_string($conn, $theme)."',
                          '".mysqli_real_escape_string($conn, $gmaps_util)."');  ";
                          mysqli_query($conn, $sql);
                          mysqli_close($conn);

                          redir($_SERVER['REQUEST_URI'],"0");
      }
       ?>
   </head>
   <body>
     <form id="form" action="<?php echo $_SERVER['REQUEST_URI'];?>" method="post" autocomplete="off">
       <p>Your IP address <?php echo $ip; ?> is not recnogized, please enter magical password.</p>
         <input type="textbox" name="password" class="textbox">
         <input type="submit" class="submitbutton" value="Submit">
         <?php die(); ?>
     </form>
   </body>
</html>
