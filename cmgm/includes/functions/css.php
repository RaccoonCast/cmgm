<?php
// Auto-include CSS
// Get filename of current page - remove the file extension - set that as page title (THIS IS IMPORTANT FOR THE CSS CODE)
$filename = basename($_SERVER['PHP_SELF'],'.php');
function css($type,$file) {
  if (file_exists('styles/' . $file . '/' . $type . '.css')) echo '<link rel="stylesheet" href="styles/' . $file . '/' . $type . '.css">' . PHP_EOL;
  if (file_exists('styles/' . $type . '.css')) echo '<link rel="stylesheet" href="styles/' . $type . '.css">' . PHP_EOL;
}

// Use Mobile CSS if on Mobile and use Desktop if on Desktop (OBVSLY)
if(isMobile()){
  css("mobile-".$theme,$filename);
  css($theme,$filename);
  css("mobile",$filename);
} else {
  css("desktop-".$theme,$filename);
  css($theme,$filename);
  css("desktop",$filename);
}
css("main",$filename);
?>
