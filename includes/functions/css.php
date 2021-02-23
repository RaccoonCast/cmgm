<?php
// Get filename of current page - remove the file extension - set that as page title (THIS IS IMPORTANT FOR THE CSS CODE)
$without_extension = ucfirst(basename($_SERVER['PHP_SELF'],'.php'));
function css($type,$file) {
  // trick it into using the same CSS for multiple pages
  if ($file == "HubDatabase") $file = "Home";
  if (file_exists('styles/' . $file . '/' . $type . '.css')) {
   echo '<link rel="stylesheet" href="styles/' . $file . '/' . $type . '.css">';
 }
}

// Use Mobile CSS if on Mobile and use Desktop if on Desktop (OBVSLY)
if(isMobile()){
  css("mobile",$without_extension);
} else {
  css("desktop",$without_extension);
}
css("main",$without_extension);
?>
