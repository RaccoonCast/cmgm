<?php
$without_extension = ucfirst(basename($_SERVER['PHP_SELF'],'.php'));
$page_to_ignore_on = 'Verify';
$passkey = 'hPHXtAbNzgkvj45kctRCsVyu8vtUesGUHxa7BC3qF87aSq49mQwAM2cRD9vxvTC4EWbQkrubu6GZsqd4fKeJmZk8UsMhgHhYb6d9hU5t9kFZu6JxKf8r4j7X8YsSCpjh';


if (isset($_COOKIE["usercheck_cookie"])) {
  $usercheck_cookie = $_COOKIE["usercheck_cookie"];
} else {
  if ($without_extension != $page_to_ignore_on) {
    $usercheck_status = "fail";
  echo '<meta http-equiv="refresh" content="0;URL=../cookie/verify.php" /> ';
  exit();
  }
}

if ($usercheck_cookie != $passkey && $without_extension != $page_to_ignore_on) {
  $usercheck_status = "fail";
  echo '<meta http-equiv="refresh" content="0;URL=../cookie/verify.php" /> ';
  exit();
  }
 ?>
