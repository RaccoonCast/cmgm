<?php
include '../includes/functions/mapWithPin.php';

echo '<div id="map" position="relative;">';

if(isMobile()){
  mapWithPin($latitude,$longitude,"17","380px","150px","25");
} else {
  mapWithPin($latitude,$longitude,"17","720px","460px","80");
}

echo '</div>';
?>
