<?php
// Auto-include CSS
// Get filename of current page - remove the file extension - set that as page title (THIS IS IMPORTANT FOR THE CSS CODE)
if (!isset($filename_for_css)) $filename_for_css = basename($_SERVER['PHP_SELF'],'.php');
if (!function_exists('css')) {
 function css($type,$file) {
  if ($file !== "Upload") {
    $php_cwd = ($_SERVER['PHP_SELF'] == '/') ? $_SERVER['DOCUMENT_ROOT'] : '.';
    if (file_exists($php_cwd . "/styles/" . $file . '/' . $type . '.css')) { echo '<link rel="stylesheet" href="styles/' . $file . '/' . $type . '.css">' . PHP_EOL; }
    if (file_exists($php_cwd . "/styles/" . $type . '.css')) { echo '<link rel="stylesheet" href="styles/' . $type . '.css">' . PHP_EOL; }
  } else {
    echo '<link rel="stylesheet" href="database/styles/Upload/' . $type . '.css">' . PHP_EOL;
  }

  }
}

// Use Mobile CSS if on Mobile and use Desktop if on Desktop (OBVSLY)
if(isMobile()){
  css("mobile-".$theme,$filename_for_css);
  css($theme,$filename_for_css);
  css("mobile",$filename_for_css);
} else {
  css("desktop-".$theme,$filename_for_css);
  css($theme,$filename_for_css);
  css("desktop",$filename_for_css);
}
css("main",$filename_for_css);

// PRIMARY THEME
echo '<link rel="stylesheet" href="/styles/' . $theme . '.css">' . PHP_EOL;
?>
