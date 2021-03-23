<?php
echo '<div id="map" position="relative;">';

if(isMobile()){
  mapWithPin($latitude,$longitude,"20","380px","150px");
} else {
  mapWithPin($latitude,$longitude,"18","720px","460px");
}

echo '</div>';
?>
