<?php
// basic basic functions
function redir($page,$time) {
echo '<meta http-equiv="Refresh" content="' . $time . '; url=' . $page . '">';
die();
}

// The mobile detection function
function isMobile() {
    if (isset($_GET['mobile'])) return "true";
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}
if(isMobile()) {
  $isMobile = "true";
} else {
  $isMobile = "false";
}

$curr_userIP = $_SERVER["REMOTE_ADDR"];

echo '<link rel="stylesheet" href="/styles/font.css">';
?>
