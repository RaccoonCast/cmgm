<?php
$without_extension = ucfirst(basename($_SERVER['PHP_SELF'],'.php'));
$passkey = 'hPHXtAbNzgkvj45kctRCsVyu8vtUesGUHxa7BC3qF87aSq49mQwAM2cRD9vxvTC4EWbQkrubu6GZsqd4fKeJmZk8UsMhgHhYb6d9hU5t9kFZu6JxKf8r4j7X8YsSCpjh';

if ($_COOKIE["usercheck_cookie"] = $passkey) {
  $usercheck_status = "pass";
} elseif ($_GET["usercheck_cookie"] = $passkey) {
  $usercheck_status = "pass";
  ?><script>document.cookie = "usercheck_cookie=<?php echo $usercheck_cookie;?>; expires=Thu, 18 Dec 2030 12:00:00 UTC; path=/";</script><?php
} else {
$usercheck_status = "fail";
}

if($usercheck_status = 'fail') {
  ?>
  <form action="" method="get" autocomplete="off">
     <p>Passkey? </p>
     <input type="text" name="usercheck_cookie" id="txtresult" required>
     <input type="submit" class="submitbutton" style="color: #00000;"  value="Submit">
  </form>
  <?php
   exit();
}
?>
