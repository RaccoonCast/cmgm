<?php
if (strpos($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 'upload.cmgm.us') !== false && basename($_SERVER['PHP_SELF']) !== 'Upload.php') {
    redir("https://upload.cmgm.us/database/", 0);
  }
?>