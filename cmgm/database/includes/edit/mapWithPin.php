<?php
include '../includes/functions/mapWithPin.php';

echo '<div class="map_holder" position="relative;">';

if(isMobile()){
  mapWithPin($latitude,$longitude,"17","100%","250px","25");
} else {
  mapWithPin($latitude,$longitude,"16","780px","500px","95");
}

echo '</div>';
?>
