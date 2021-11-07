<?php
include '../includes/functions/mapWithPin.php';

echo '<div class="map_holder">';

if(isMobile()){
  mapWithPin($latitude,$longitude,"17","100%","250","25");
} else {
  mapWithPin($latitude,$longitude,"18","827","530","95");
}

echo '</div>';
?>
