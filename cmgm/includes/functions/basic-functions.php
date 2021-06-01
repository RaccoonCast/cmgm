<?php
// basic basic functions
function redir($page,$time) {
echo '<meta http-equiv="Refresh" content="' . $time . '; url=' . $page . '">';
die();
}

// The mobile detection function
function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}
if(isMobile()) {
  $isMobile = "true";
} else {
  $isMobile = "false";
}

$curr_userIP = $_SERVER["REMOTE_ADDR"];

// Auto-include CSS
// Get filename of current page - remove the file extension - set that as page title (THIS IS IMPORTANT FOR THE CSS CODE)
$without_extension = ucfirst(basename($_SERVER['PHP_SELF'],'.php'));
function css($type,$file) {
  if (file_exists('styles/' . $file . '/' . $type . '.css')) {
   echo '<link rel="stylesheet" href="styles/' . $file . '/' . $type . '.css">';
  }
  if (file_exists('styles/' . $type . '.css')) {
     echo '<link rel="stylesheet" href="styles/' . $type . '.css">';
  }
}

// Use Mobile CSS if on Mobile and use Desktop if on Desktop (OBVSLY)
if(isMobile()){
  css("mobile",$without_extension);
} else {
  css("desktop",$without_extension);
}
css("main",$without_extension);


echo '<link rel="stylesheet" href="/styles/font.css">';
?>
