<?php
// Get filename of current page - remove the file extension - set that as page title (THIS IS IMPORTANT FOR THE CSS CODE)
$without_extension = ucfirst(basename($_SERVER['PHP_SELF'],'.php'));

// Use Mobile CSS if on Mobile and use Desktop if on Desktop (OBVSLY)
if(isMobile()){
  echo '<link rel="stylesheet" href="styles/' . $without_extension . '/mobile.css">';
} else {
  echo '<link rel="stylesheet" href="styles/' . $without_extension . '/desktop.css">';
}
echo '<link rel="stylesheet" href="styles/' . $without_extension . '/main.css">';
?>
